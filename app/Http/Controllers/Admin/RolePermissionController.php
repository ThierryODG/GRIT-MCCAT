<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    /**
     * Afficher la liste des rôles
     */
    public function index()
    {
        $roles = Role::withCount('utilisateurs')->orderBy('nom')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $permissions = Permission::orderBy('nom')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Sauvegarder le nouveau rôle
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:roles,nom'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create([
                'nom' => $request->nom,
                // Use Laravel's created_at timestamp (handled automatically)
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }
        });

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Afficher les détails d'un rôle
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'utilisateurs']);
        $permissions = Permission::orderBy('nom')->get();

        return view('admin.roles.show', compact('role', 'permissions'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('nom')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Mettre à jour le rôle
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:roles,nom,' . $role->id],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        DB::transaction(function () use ($request, $role) {
            $role->update([
                'nom' => $request->nom,
            ]);

            $role->permissions()->sync($request->permissions ?? []);
        });

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle modifié avec succès.');
    }

    /**
     * Supprimer le rôle
     */
    public function destroy(Role $role)
    {
        // Empêcher la suppression si le rôle a des utilisateurs
        if ($role->utilisateurs()->exists()) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Impossible de supprimer ce rôle : il est assigné à des utilisateurs.');
        }

        DB::transaction(function () use ($role) {
            $role->permissions()->detach();
            $role->delete();
        });

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }

    /**
     * Afficher la matrice des permissions
     */
    public function matrice(Role $role)
    {
        $roles = Role::with('permissions')->orderBy('nom')->get();
        $permissions = Permission::orderBy('nom')->get();

        return view('admin.roles.matrice', compact('roles', 'permissions', 'role'));
    }

    /**
     * Mettre à jour les permissions d'un rôle (via matrice)
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.matrice', $role)
            ->with('success', 'Permissions mises à jour avec succès.');
    }
}
