<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::latest()->get();
        return view('admin.equipment.index', compact('equipment'));
    }

    public function create()
    {
        return view('admin.equipment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:available,borrowed,maintenance'
        ]);

        Equipment::create($request->all());

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment berhasil ditambahkan!');
    }

    public function show($id)
    {
        $equipment = Equipment::with('loans.user')->findOrFail($id);
        return view('admin.equipment.show', compact('equipment'));
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('admin.equipment.edit', compact('equipment'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:available,borrowed,maintenance'
        ]);

        $equipment = Equipment::findOrFail($id);
        $equipment->update($request->all());

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment berhasil diupdate!');
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        
        // Cek apakah equipment sedang dipinjam
        if ($equipment->status == 'borrowed') {
            return redirect()->route('admin.equipment.index')
                ->with('error', 'Equipment sedang dipinjam, tidak bisa dihapus!');
        }
        
        $equipment->delete();
        
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment berhasil dihapus!');
    }
}