@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-800">Gestion des Utilisateurs</h2>
                    <a href="{{ route('admin.users.create') }}" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                        Nouvel Utilisateur
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtres et Recherche -->
        <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('admin.users.index') }}" method="GET" class="grid items-end grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                               placeholder="Nom, email...">
                    </div>
                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700">Rôle</label>
                        <select name="role_id" id="role_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">Tous les rôles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="structure_id" class="block text-sm font-medium text-gray-700">Structure</label>
                        <select name="structure_id" id="structure_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">Toutes les structures</option>
                            @foreach($structures as $structure)
                                <option value="{{ $structure->id }}" {{ request('structure_id') == $structure->id ? 'selected' : '' }}>
                                    {{ $structure->sigle ?? $structure->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                            Filtrer
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 font-bold text-white bg-red-500 rounded hover:bg-red-700">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nom</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Rôle</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Structure</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Téléphone</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                {{ $user->role->nom ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $user->structure->sigle ?? ($user->structure->nom ?? 'N/A') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $user->telephone ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            <a href="{{ route('admin.users.show', $user) }}" class="mr-3 text-blue-600 hover:text-blue-900">Voir</a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="mr-3 text-green-600 hover:text-green-900">Modifier</a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="py-8 text-center">
                        <p class="text-gray-500">Aucun utilisateur trouvé.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@section('scripts')
<!-- SweetAlert2 pour les pop-ups -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des confirmations de suppression
    const deleteForms = document.querySelectorAll('form[action*="destroy"]');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const userName = this.closest('tr').querySelector('td:first-child .text-gray-900').textContent;
            const userEmail = this.closest('tr').querySelector('td:nth-child(2) .text-gray-900').textContent;

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                html: `
                    <div class="text-left">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-red-100 rounded-full">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Suppression d'utilisateur</h3>
                            </div>
                        </div>
                        <p class="mb-2 text-gray-700">
                            <strong>Nom :</strong> ${userName}<br>
                            <strong>Email :</strong> ${userEmail}
                        </p>
                        <p class="mt-3 font-medium text-red-600">
                            ⚠️ Cette action est irréversible !
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler',
                customClass: {
                    popup: 'rounded-lg shadow-xl',
                    confirmButton: 'px-6 py-3 rounded-lg font-semibold',
                    cancelButton: 'px-6 py-3 rounded-lg font-semibold'
                },
                buttonsStyling: false,
                backdrop: `
                    rgba(0,0,0,0.6)
                    url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='m0 40l40-40h-40v40zm40 0v-40h-40l40 40z'/%3E%3C/g%3E%3C/svg%3E")
                    left top
                `
            }).then((result) => {
                if (result.isConfirmed) {
                    // Afficher un loader pendant la suppression
                    Swal.fire({
                        title: 'Suppression en cours...',
                        text: 'Veuillez patienter',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Soumettre le formulaire après un petit délai pour voir l'animation
                    setTimeout(() => {
                        form.submit();
                    }, 1000);
                }
            });
        });
    });

    // Messages de succès/erreur avec SweetAlert
    @if(session('success'))
        Swal.fire({
            title: 'Succès !',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#10b981',
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Erreur !',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Compris'
        });
    @endif
});
</script>

<style>
.swal2-popup {
    border-radius: 12px !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}

.swal2-confirm {
    transition: all 0.2s ease-in-out !important;
}

.swal2-confirm:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
}
</style>
@endsection
@endsection
