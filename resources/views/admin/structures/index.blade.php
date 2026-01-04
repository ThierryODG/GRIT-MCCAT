@extends('layouts.app')

@section('title', 'Gestion des Structures')

@section('content')
    <div class="space-y-6">
        <!-- Actions Toolbar (Search & Create) -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Filter -->
            <form action="{{ route('admin.structures.index') }}" method="GET" class="relative flex-grow max-w-lg">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 sm:text-sm transition duration-150 ease-in-out"
                        placeholder="Filtrer par code, nom, sigle...">
                </div>
            </form>

            <!-- Create Button -->
            <a href="{{ route('admin.structures.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle Structure
            </a>
        </div>

        <!-- Content Table -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Identité
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($structures as $structure)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <!-- Code Bubble: Auto-width/padding to prevent overflow -->
                                        <div class="flex-shrink-0 min-w-[2.5rem] h-10 px-2 flex items-center justify-center rounded-lg bg-blue-50 text-blue-700 font-bold border border-blue-100 text-sm">
                                            {{ $structure->code }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">
                                                {{ $structure->nom }}
                                            </div>
                                            @if($structure->sigle)
                                                <div class="text-xs text-gray-500 font-medium mt-0.5">
                                                    {{ $structure->sigle }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 line-clamp-2 max-w-sm">
                                        {{ $structure->description ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($structure->active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                                            Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                                            Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.structures.edit', $structure) }}" 
                                       class="text-gray-400 hover:text-blue-600 transition-colors p-2 rounded-full hover:bg-blue-50">
                                        <i class="fas fa-edit text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                            <i class="fas fa-building text-2xl"></i>
                                        </div>
                                        <h3 class="text-sm font-medium text-gray-900">Aucune structure</h3>
                                        <p class="mt-1 text-sm text-gray-500 mb-4">Commencez par ajouter une nouvelle structure.</p>
                                        <a href="{{ route('admin.structures.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                                            <i class="fas fa-plus mr-2"></i>
                                            Ajouter une structure
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($structures->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $structures->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
