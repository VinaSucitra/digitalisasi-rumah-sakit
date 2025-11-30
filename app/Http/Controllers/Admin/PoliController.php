<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PoliController extends Controller
{
    /**
     * Menampilkan daftar semua Poli.
     */
    public function index()
    {
        $polis = Poli::all();

        // view: resources/views/admin/polis/index.blade.php
        return view('admin.polis.index', compact('polis'));
    }

    /**
     * Menampilkan formulir tambah Poli baru.
     */
    public function create()
    {
        // Daftar ikon yang boleh dipilih (FontAwesome)
        $icons = $this->getAvailableIcons();

        // view: resources/views/admin/polis/create.blade.php
        return view('admin.polis.create', compact('icons'));
    }

    /**
     * Menyimpan Poli baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:polis,name',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50', // nama ikon, bukan file
        ]);

        Poli::create([
            'name'        => $request->name,
            'description' => $request->description,
            'icon'        => $request->icon, // contoh: 'stethoscope'
        ]);

        return redirect()
            ->route('admin.polis.index')
            ->with('success', 'Poli berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit Poli.
     */
    public function edit(Poli $poli)
    {
        $icons = $this->getAvailableIcons();

        // view: resources/views/admin/polis/edit.blade.php
        return view('admin.polis.edit', compact('poli', 'icons'));
    }

    /**
     * Memperbarui Poli yang sudah ada.
     */
    public function update(Request $request, Poli $poli)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('polis', 'name')->ignore($poli->id)],
            'description' => ['nullable', 'string'],
            'icon'        => ['nullable', 'string', 'max:50'],
        ]);

        $poli->update([
            'name'        => $request->name,
            'description' => $request->description,
            'icon'        => $request->icon,
        ]);

        return redirect()
            ->route('admin.polis.index')
            ->with('success', 'Poli berhasil diperbarui.');
    }

    /**
     * Hapus Poli dari database.
     */
    public function destroy(Poli $poli)
    {
        $poli->delete();

        return redirect()
            ->route('admin.polis.index')
            ->with('success', 'Poli berhasil dihapus.');
    }

    /**
     * Daftar ikon yang bisa dipilih admin.
     * (Nama-nama ini disimpan di kolom 'icon')
     */
    private function getAvailableIcons(): array
    {
        return [
            'stethoscope'      => 'Stetoskop (Umum)',
            'tooth'            => 'Gigi',
            'baby'             => 'Anak',
            'person-pregnant'  => 'Kandungan',
            'ear-listen'       => 'THT',
            'heart-pulse'      => 'Jantung',
            'lungs'            => 'Paru-paru',
            'brain'            => 'Saraf',
            'eye'              => 'Mata',
        ];
    }
}
