<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{

    public function index()
    {
        $medicines = Medicine::orderBy('name')->paginate(15);

        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        $types = ['Obat', 'Tindakan'];

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

    public function show(Medicine $medicine)
    {
        return view('admin.medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        $types = ['Obat', 'Tindakan'];
        $drugTypes = ['biasa', 'keras'];

        return view('admin.medicines.edit', compact('medicine', 'types', 'drugTypes'));
    }

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

    public function destroy(Medicine $medicine)
    {
        if ($medicine->image) {
            Storage::delete($medicine->image);
        }

        $medicine->delete();

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Data Obat/Tindakan berhasil dihapus.');
    }
}
