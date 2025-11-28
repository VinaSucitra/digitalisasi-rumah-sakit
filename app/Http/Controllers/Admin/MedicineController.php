<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine; // PENTING: Import Model Medicine
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Menampilkan daftar semua obat. (READ - Index)
     */
    public function index()
    {
        // Mengambil semua data obat dengan urutan berdasarkan nama
        $medicines = Medicine::orderBy('name')->paginate(15);
        return view('admin.medicines.index', compact('medicines'));
    }

    /**
     * Menampilkan formulir untuk membuat obat baru. (CREATE - Form)
     */
    public function create()
    {
        // Menampilkan form untuk menambahkan obat baru
        return view('admin.medicines.create');
    }

    /**
     * Menyimpan obat baru ke database. (CREATE - Store)
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name',
            'type' => 'required|string|max:255', // Menambahkan jenis obat
            'price' => 'required|numeric|min:0', // Harga obat
            'description' => 'nullable|string',  // Deskripsi opsional
        ]);

        // Menyimpan data obat ke dalam database
        Medicine::create($validatedData);

        // Redirect ke halaman daftar obat dengan pesan sukses
        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Data Obat berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail obat tertentu. (READ - Show)
     */
    public function show(Medicine $medicine)
    {
        // Menampilkan detail dari obat yang dipilih
        return view('admin.medicines.show', compact('medicine'));
    }

    /**
     * Menampilkan formulir untuk mengedit obat. (UPDATE - Form)
     */
    public function edit(Medicine $medicine)
    {
        // Menampilkan form untuk mengedit data obat
        return view('admin.medicines.edit', compact('medicine'));
    }

    /**
     * Memperbarui obat di database. (UPDATE - Persist)
     */
    public function update(Request $request, Medicine $medicine)
    {
        // Validasi input dari form update
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name,' . $medicine->id, 
            'type' => 'required|string|max:255', // Jenis obat
            'price' => 'required|numeric|min:0', // Harga obat
            'description' => 'nullable|string',  // Deskripsi opsional
        ]);

        // Update data obat di database
        $medicine->update($validatedData);

        // Redirect ke halaman daftar obat dengan pesan sukses
        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Data Obat berhasil diperbarui.');
    }

    /**
     * Menghapus obat. (DELETE - Destroy)
     */
    public function destroy(Medicine $medicine)
    {
        // Menghapus data obat
        $medicine->delete();

        // Redirect ke halaman daftar obat dengan pesan sukses
        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Data Obat berhasil dihapus.');
    }
}
