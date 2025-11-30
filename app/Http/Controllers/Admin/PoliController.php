<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::all();
        return view('admin.polis.index', compact('polis'));
    }

    public function create()
    {
        $icons = $this->getAvailableIcons();
        return view('admin.polis.create', compact('icons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:polis,name',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50',
        ]);

        Poli::create([
            'name'        => $request->name,
            'description' => $request->description,
            'icon'        => $request->icon,
        ]);

        return redirect()
            ->route('admin.polis.index')
            ->with('success', 'Poli berhasil ditambahkan.');
    }

    public function edit(Poli $poli)
    {
        $icons = $this->getAvailableIcons();
        return view('admin.polis.edit', compact('poli', 'icons'));
    }

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

    public function destroy(Poli $poli)
    {
        $poli->delete();

        return redirect()
            ->route('admin.polis.index')
            ->with('success', 'Poli berhasil dihapus.');
    }

    // Daftar ikon yang disimpan dalam kolom 'icon'
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
