<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    /**
     * Menampilkan daftar semua obat/tindakan. (READ - Index)
     */
    public function index()
    {
        // Ambil semua data, urut nama
        $medicines = Medicine::orderBy('name')->paginate(15);

        return view('admin.medicines.index', compact('medicines'));
    }

    /**
     * Menampilkan formulir untuk membuat obat baru. (CREATE - Form)
     */
    public function create()
    {
        // type: Obat / Tindakan
        $types = ['Obat', 'Tindakan'];

        // drug_type hanya untuk Obat: biasa / keras
        $drugTypes = ['biasa', 'keras'];

        return view('admin.medicines.create', compact('types', 'drugTypes'));
    }

    /**
     * Menyimpan obat baru ke database. (CREATE - Store)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255|unique:medicines,name',
            'type'        => 'required|in:Obat,Tindakan',      // Jenis item
            'drug_type'   => 'nullable|in:biasa,keras',        // Tipe obat
            'stock'       => 'required|integer|min:0',         // Stok
            'price'       => 'required|numeric|min:0',         // Harga
            'description' => 'nullable|string',                // Deskripsi opsional
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Gambar opsional
        ]);

        // Kalau jenisnya Tindakan, drug_type tidak relevan, boleh diset null
        if ($validatedData['type'] === 'Tindakan') {
            $validatedData['drug_type'] = null;
        }

        // Upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/medicines');
        }

        $validatedData['image'] = $imagePath;

        Medicine::create($validatedData);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Data Obat/Tindakan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail obat tertentu. (READ - Show)
     * (Opsional, bisa tidak dipakai)
     */
    public function show(Medicine $medicine)
    {
        return view('admin.medicines.show', compact('medicine'));
    }

    /**
     * Menampilkan formulir untuk mengedit obat. (UPDATE - Form)
     */
    public function edit(Medicine $medicine)
    {
        $types = ['Obat', 'Tindakan'];
        $drugTypes = ['biasa', 'keras'];

        return view('admin.medicines.edit', compact('medicine', 'types', 'drugTypes'));
    }

    /**
     * Memperbarui obat di database. (UPDATE - Persist)
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255|unique:medicines,name,' . $medicine->id,
            'type'        => 'required|in:Obat,Tindakan',
            'drug_type'   => 'nullable|in:biasa,keras',
            'stock'       => 'required|integer|min:0',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validatedData['type'] === 'Tindakan') {
            $validatedData['drug_type'] = null;
        }

        $imagePath = $medicine->image;

        // Jika ada file gambar baru, hapus gambar lama lalu simpan baru
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::delete($imagePath);
            }

            $imagePath = $request->file('image')->store('public/medicines');
        }

        $validatedData['image'] = $imagePath;

        $medicine->update($validatedData);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Data Obat/Tindakan berhasil diperbarui.');
    }

    /**
     * Menghapus obat/tindakan. (DELETE - Destroy)
     */
    public function destroy(Medicine $medicine)
    {
        // Hapus file gambar jika ada
        if ($medicine->image) {
            Storage::delete($medicine->image);
        }

        $medicine->delete();

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Data Obat/Tindakan berhasil dihapus.');
    }
}
