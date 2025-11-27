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
        $medicines = Medicine::orderBy('name')->paginate(15);
        return view('admin.medicines.index', compact('medicines'));
    }

    /**
     * Menampilkan formulir untuk membuat obat baru. (CREATE - Form)
     */
    public function create()
    {
        return view('admin.medicines.create');
    }

    /**
     * Menyimpan obat baru ke database. (CREATE - Store)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name',
            'manufacturer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Medicine::create($validatedData);

        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Data Obat berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail obat tertentu. (READ - Show)
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
        return view('admin.medicines.edit', compact('medicine'));
    }

    /**
     * Memperbarui obat di database. (UPDATE - Persist)
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name,' . $medicine->id, 
            'manufacturer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $medicine->update($validatedData);

        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Data Obat berhasil diperbarui.');
    }

    /**
     * Menghapus obat. (DELETE - Destroy)
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('admin.medicines.index')
                         ->with('success', 'Data Obat berhasil dihapus.');
    }
}