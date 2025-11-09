@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- En-tête -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Détails du Rôle</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 text-sm font-bold text-white bg-green-500 rounded hover:bg-green-700">
                            Modifier
                        </a>
                        <a href="{{ route('admin.roles.matrice', $role) }}" class="px-4 py-2 text-sm font-bold text-white bg-purple-500 rounded hover:bg-purple-700">
                            Gérer Permissions
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-sm font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                            Retour
                        </a>
                    </div>
                </div>

                <!-- Informations du rôle -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="p-4 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Informations Générales</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom du rôle</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $role->nom }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ optional($role->created_at)->format('d/m/Y à H:i') ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ optional($role->updated_at)->format('d/m/Y à H:i') ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="p-4 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Statistiques</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre d'utilisateurs</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                        {{ $role->utilisateurs->count() }} utilisateur(s)
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre de permissions</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                        {{ $role->permissions->count() }} permission(s)
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Liste des permissions -->
                <div class="mt-6">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Permissions Associées</h3>
                    @if($role->permissions->count() > 0)
                        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                            @foreach($role->permissions as $permission)
                                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $permission->nom }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center rounded-lg bg-yellow-50">
                            <p class="text-yellow-700">Aucune permission assignée à ce rôle.</p>
                            <a href="{{ route('admin.roles.matrice', $role) }}" class="inline-block mt-2 text-yellow-800 hover:text-yellow-900">
                                Assigner des permissions
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Liste des utilisateurs (optionnel) -->
                @if($role->utilisateurs->count() > 0)
                <div class="mt-6">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Utilisateurs avec ce rôle</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nom</th>
                                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Structure</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($role->utilisateurs as $user)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $user->name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $user->email }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $user->structure->nom ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
