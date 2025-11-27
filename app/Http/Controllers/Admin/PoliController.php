<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // <<< IMPORT UNTUK PENGHAPUSAN FILE

class PoliController extends Controller
{
    /**
     * Menampilkan daftar semua Poli.
     */
    public function index()
    {
        $polis = Poli::all();
        // Catatan: Akan memanggil view('admin.polis.index')
        return view('admin.polis.index', compact('polis'));
    }

    /**
     * Menampilkan formulir tambah Poli baru.
     */
    public function create()
    {
        // Catatan: Akan memanggil view('admin.polis.create')
        return view('admin.polis.create');
    }

    /**
     * Menyimpan Poli baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:polis,name', 
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Maks 2MB
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('public/polis_icons');
        }

        Poli::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $iconPath,
        ]);

        return redirect()->route('admin.polis.index')->with('success', 'Poli berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit Poli.
     */
    public function edit(Poli $poli)
    {
        // Catatan: Akan memanggil view('admin.polis.edit')
        return view('admin.polis.edit', compact('poli'));
    }

    /**
     * Memperbarui Poli yang sudah ada.
     */
    public function update(Request $request, Poli $poli)
    {
        // Validasi, kecuali untuk nama Poli yang sedang diupdate
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('polis')->ignore($poli->id)], 
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $iconPath = $poli->icon;

        if ($request->hasFile('icon')) {
            // Hapus ikon lama jika ada
            if ($iconPath) {
                Storage::delete($iconPath);
            }
            // Simpan ikon baru
            $iconPath = $request->file('icon')->store('public/polis_icons');
        }

        $poli->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $iconPath,
        ]);

        return redirect()->route('admin.polis.index')->with('success', 'Poli berhasil diperbarui.');
    }

    /**
     * Hapus Poli dari database.
     */
    public function destroy(Poli $poli)
    {
        if ($poli->icon) {
            Storage::delete($poli->icon);
        }

        $poli->delete();

        return redirect()->route('admin.polis.index')->with('success', 'Poli berhasil dihapus.');
    }
}