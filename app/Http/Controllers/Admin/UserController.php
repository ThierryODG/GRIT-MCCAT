<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        $query = User::with(['role', 'structure']);

        // Recherche
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par rôle
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filtre par structure
        if ($request->filled('structure_id')) {
            $query->where('structure_id', $request->structure_id);
        }

        $users = $query->orderBy('name')->paginate(15);
        $roles = Role::all();
        $structures = Structure::active()->get();

        return view('admin.users.index', compact('users', 'roles', 'structures'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $roles = Role::all();
        $structures = Structure::active()->get();
        return view('admin.users.create', compact('roles', 'structures'));
    }

    /**
     * Sauvegarder le nouvel utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'structure_id' => ['nullable', 'exists:structures,id'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'structure_id' => $request->structure_id,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load(['role', 'structure']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $structures = Structure::active()->get();
        return view('admin.users.edit', compact('user', 'roles', 'structures'));
    }

    /**
     * Mettre à jour l'utilisateur
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'structure_id' => ['nullable', 'exists:structures,id'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'structure_id' => $request->structure_id,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Supprimer l'utilisateur
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // ✅ CORRECTION : Retirer la vérification des plans d'action pour l'instant
        // (On verra plus tard quand on aura les bonnes colonnes)

        // Vérifier seulement les recommandations (qui fonctionnent)
        $hasActiveRecommandations =
            $user->recommandationsCreees()->whereNotIn('statut', ['cloturee'])->exists() ||
            $user->recommandationsValidees()->whereNotIn('statut', ['cloturee'])->exists() ||
            $user->recommandationsResponsable()->whereNotIn('statut', ['cloturee'])->exists() ||
            $user->recommandationsAssignees()->whereNotIn('statut', ['cloturee'])->exists();

        if ($hasActiveRecommandations) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cet utilisateur a des recommandations actives. Impossible de le supprimer.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
