@extends('layouts.app')

@section('title', 'Modifier la Structure')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('admin.structures.index') }}"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">Structures</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Modification</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div
                class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg leading-6 font-bold text-gray-900">
                        Modifier la Structure : {{ $structure->nom }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Mettez à jour les informations de l'entité.
                    </p>
                </div>
                <div>
                    <!-- Formulaire de suppression -->
                    <form action="{{ route('admin.structures.destroy', $structure) }}" method="POST"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette structure ? Cette action peut avoir des impacts si elle est liée à des utilisateurs ou des recommandations.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <i class="fas fa-trash-alt mr-2"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <form action="{{ route('admin.structures.update', $structure) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Code -->
                    <div class="sm:col-span-2">
                        <label for="code" class="block text-sm font-semibold text-gray-700">
                            Code Unique <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="code" id="code" value="{{ old('code', $structure->code) }}" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-colors placeholder-gray-400"
                                placeholder="ex: DSI">
                        </div>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sigle -->
                    <div class="sm:col-span-2">
                        <label for="sigle" class="block text-sm font-semibold text-gray-700">
                            Sigle
                        </label>
                        <div class="mt-1">
                            <input type="text" name="sigle" id="sigle" value="{{ old('sigle', $structure->sigle) }}"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-colors placeholder-gray-400"
                                placeholder="ex: DSI">
                        </div>
                        @error('sigle')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom -->
                    <div class="sm:col-span-6">
                        <label for="nom" class="block text-sm font-semibold text-gray-700">
                            Nom de la Structure <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $structure->nom) }}" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-colors placeholder-gray-400"
                                placeholder="ex: Direction des Systèmes d'Information">
                        </div>
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-semibold text-gray-700">
                            Description
                        </label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-lg transition-colors placeholder-gray-400">{{ old('description', $structure->description) }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Brève description des attributions de la structure.</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active -->
                    <div class="sm:col-span-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="active" name="active" type="checkbox" value="1" {{ old('active', $structure->active) ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded transition-colors">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="active" class="font-medium text-gray-700">Structure Active</label>
                                <p class="text-gray-500">Décochez cette case pour archiver la structure sans la supprimer.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('admin.structures.index') }}"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        Annuler
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all box-shadow-lg">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection