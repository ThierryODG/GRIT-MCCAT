@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="mb-6 text-2xl font-semibold text-gray-800">Créer un Nouveau Rôle</h2>

                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Nom du rôle -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom du rôle *</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                                   class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Ex: Super Administrateur" required>
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div>
                            <label class="block mb-3 text-sm font-medium text-gray-700">Permissions</label>
                            <div class="p-4 border border-gray-300 rounded-md">
                                @if($permissions->count() > 0)
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                        @foreach($permissions as $permission)
                                            <label class="flex items-start space-x-3">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                       class="mt-1 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                <span class="text-sm text-gray-700">{{ $permission->nom }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">Aucune permission disponible.</p>
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-900">
                                        Créer des permissions d'abord
                                    </a>
                                @endif
                            </div>
                            @error('permissions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex justify-end gap-4 mt-8">
                        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                            Annuler
                        </a>
                        <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                            Créer le rôle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
