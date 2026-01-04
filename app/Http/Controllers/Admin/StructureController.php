<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Structure::query();

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('sigle', 'like', '%' . $request->search . '%');
        }

        $structures = $query->orderBy('nom')->paginate(15);

        return view('admin.structures.index', compact('structures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.structures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:structures,code|max:50',
            'nom' => 'required|string|max:255',
            'sigle' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        Structure::create([
            'code' => $validated['code'],
            'nom' => $validated['nom'],
            'sigle' => $validated['sigle'],
            'description' => $validated['description'],
            'active' => $request->has('active'),
        ]);

        return redirect()->route('admin.structures.index')
            ->with('success', 'Structure créée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Structure $structure)
    {
        return view('admin.structures.edit', compact('structure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:structures,code,' . $structure->id,
            'nom' => 'required|string|max:255',
            'sigle' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $structure->update([
            'code' => $validated['code'],
            'nom' => $validated['nom'],
            'sigle' => $validated['sigle'],
            'description' => $validated['description'],
            'active' => $request->has('active'),
        ]);

        return redirect()->route('admin.structures.index')
            ->with('success', 'Structure mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure)
    {
        // Check if structure has related records (users, recommendations) before deleting?
        // For now, let's assume we can delete or soft delete.
        // The migration has standard delete, foreign keys might prevent it.
        
        try {
            $structure->delete();
            return redirect()->route('admin.structures.index')
                ->with('success', 'Structure supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.structures.index')
                ->with('error', 'Impossible de supprimer cette structure car elle est liée à d\'autres éléments.');
        }
    }
}
