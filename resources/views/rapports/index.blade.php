@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Rapports & Documents</h1>
            @if(Auth::user()->isPointFocal())
                <a href="{{ route('point_focal.rapports.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Générer un rapport
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex flex-col gap-6">
            @forelse($rapportsParAnnee as $annee => $rapports)
                <!-- Folder Card for Year -->
                <div x-data="{ open: true }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div @click="open = !open"
                        class="bg-gray-50 p-4 flex justify-between items-center cursor-pointer hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-folder text-yellow-500 text-2xl"></i>
                            <h3 class="font-bold text-gray-700 text-lg">{{ $annee }}</h3>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform"
                            :class="{'transform rotate-180': !open}"></i>
                    </div>

                    <div x-show="open" class="p-4 space-y-3">
                        @foreach($rapports as $rapport)
                            <div
                                class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-transparent hover:border-gray-100 transition group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
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
                                    @if(Auth::user()->isPointFocal())
                                        <div class="flex items-center">
                                            <a href="{{ route('point_focal.rapports.show', $rapport) }}"
                                                class="text-gray-400 hover:text-blue-600 p-2" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @if($rapport->user_id === Auth::id())
                                                <form action="{{ route('point_focal.rapports.destroy', $rapport) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 p-2" title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @elseif(Auth::user()->isITS())
                                        <a href="{{ route('its.rapports.show', $rapport) }}"
                                            class="text-gray-400 hover:text-blue-600 p-2" title="Télécharger">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @elseif(Auth::user()->isInspecteurGeneral())
                                        <a href="{{ route('inspecteur_general.rapports.show', $rapport) }}"
                                            class="text-gray-400 hover:text-blue-600 p-2" title="Télécharger">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @elseif(Auth::user()->isResponsable())
                                        <a href="{{ route('responsable.rapports.show', $rapport) }}"
                                            class="text-gray-400 hover:text-blue-600 p-2" title="Télécharger">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Aucun rapport disponible</h3>
                    <p class="text-gray-500 mt-1">Les rapports générés apparaîtront ici.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection