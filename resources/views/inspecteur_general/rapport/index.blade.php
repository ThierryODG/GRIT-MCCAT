@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
    <div class="container p-6 mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Consulter les Rapports</h1>
        </div>

        @if(session('success'))
            <div class="p-4 mb-6 text-green-700 bg-green-100 border-l-4 border-green-500 rounded-lg">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($rapportsParAnnee as $annee => $rapports)
                <!-- Carte Dossier par Année -->
                <div x-data="{ open: true }" class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div @click="open = !open"
                        class="flex items-center justify-between p-4 transition cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center gap-3">
                            <i class="text-2xl text-yellow-500 fas fa-folder"></i>
                            <h3 class="text-lg font-bold text-gray-700">{{ $annee }}</h3>
                        </div>
                        <i class="text-gray-400 fas fa-chevron-down transition-transform"
                            :class="{'transform rotate-180': !open}"></i>
                    </div>

                    <div x-show="open" class="p-4 space-y-3">
                        @foreach($rapports as $rapport)
                            <div
                                class="flex items-center justify-between p-3 transition border border-transparent rounded-lg hover:bg-gray-50 hover:border-gray-100 group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div
                                        class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-600 bg-red-100 rounded-lg">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 truncate" title="{{ $rapport->titre }}">
                                            {{ $rapport->titre }}</h4>
                                        <p class="text-xs text-gray-500">{{ $rapport->created_at->format('d/m/Y H:i') }} •
                                            {{ $rapport->user->name }}</p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('inspecteur_general.rapports.show', $rapport) }}"
                                        class="p-2 text-gray-400 hover:text-blue-600" title="Télécharger">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="py-12 text-center bg-white border border-gray-300 border-dashed col-span-full rounded-xl">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full">
                        <i class="text-2xl text-gray-400 fas fa-folder-open"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Aucun rapport disponible</h3>
                    <p class="mt-1 text-gray-500">Les rapports générés apparaîtront ici.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
