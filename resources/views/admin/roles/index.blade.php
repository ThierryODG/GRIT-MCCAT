@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-800">Gestion des Rôles</h2>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.roles.matrice', $roles->first() ?? 1) }}"
                           class="px-4 py-2 font-bold text-white bg-purple-500 rounded hover:bg-purple-700">
                            Matrice des Permissions
                        </a>
                        <a href="{{ route('admin.roles.create') }}"
                           class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                            Nouveau Rôle
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des rôles -->
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($roles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nom du Rôle</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Utilisateurs</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Permissions</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date Création</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($roles as $role)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $role->nom }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                                {{ $role->utilisateurs_count }} utilisateur(s)
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                {{ $role->permissions_count ?? $role->permissions->count() }} permission(s)
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ optional($role->created_at)->format('d/m/Y H:i') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            <a href="{{ route('admin.roles.show', $role) }}" class="mr-3 text-blue-600 hover:text-blue-900">Voir</a>
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="mr-3 text-green-600 hover:text-green-900">Modifier</a>
                                            <a href="{{ route('admin.roles.matrice', $role) }}" class="mr-3 text-purple-600 hover:text-purple-900">Permissions</a>
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline"
                                                  id="delete-form-{{ $role->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="text-red-600 hover:text-red-900 delete-role-btn"
                                                        data-form-id="delete-form-{{ $role->id }}"
                                                        data-role-name="{{ $role->nom }}">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-8 text-center">
                        <p class="text-gray-500">Aucun rôle trouvé.</p>
                        <a href="{{ route('admin.roles.create') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-900">
                            Créer le premier rôle
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des suppressions de rôles
    document.querySelectorAll('.delete-role-btn').forEach(button => {
        button.addEventListener('click', function() {
            const formId = this.getAttribute('data-form-id');
            const roleName = this.getAttribute('data-role-name');
            const form = document.getElementById(formId);

            Swal.fire({
                title: 'Confirmation de suppression',
                html: `Voulez-vous vraiment supprimer le rôle <strong>"${roleName}"</strong> ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
