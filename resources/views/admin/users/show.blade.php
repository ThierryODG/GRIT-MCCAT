@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- En-tête -->
                <div class="flex items-start justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Détails de l'Utilisateur</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 text-sm font-bold text-white bg-green-500 rounded hover:bg-green-700">
                            Modifier
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                            Retour
                        </a>
                    </div>
                </div>

                <!-- Informations utilisateur -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="p-4 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Informations Personnelles</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->telephone ?? 'Non renseigné' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="p-4 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Informations Professionnelles</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Rôle</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                        {{ $user->role->nom ?? 'N/A' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Structure</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $user->structure->nom ?? 'Non renseignée' }}
                                    @if($user->structure && $user->structure->sigle)
                                        <span class="text-gray-500">({{ $user->structure->sigle }})</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
