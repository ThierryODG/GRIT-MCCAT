@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-800">Matrice des Permissions</h2>
                    <div class="flex items-center gap-4">
                        <!-- Sélecteur de rôle -->
                        <select id="roleSelector" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($roles as $r)
                                <option value="{{ route('admin.roles.matrice', $r) }}" {{ $r->id == $role->id ? 'selected' : '' }}>
                                    {{ $r->nom }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matrice des permissions -->
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('admin.roles.permissions.update', $role) }}" method="POST" id="permissions-form">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Gestion des permissions pour : <span class="text-blue-600">{{ $role->nom }}</span>
                        </h3>
                        <p class="text-sm text-gray-500">Cochez les permissions à assigner à ce rôle</p>
                    </div>

                    @if($permissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Permission</th>
                                        <th class="px-4 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">Assigner</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                {{ $permission->nom }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                       {{ $role->hasPermission($permission->nom) ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Boutons -->
                        <div class="flex justify-end gap-4 mt-6">
                            <button type="button" onclick="resetForm()" class="px-4 py-2 font-bold text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                Réinitialiser
                            </button>
                            <button type="submit" class="px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700">
                                Sauvegarder les Permissions
                            </button>
                        </div>
                    @else
                        <div class="p-8 text-center rounded-lg bg-yellow-50">
                            <svg class="w-12 h-12 mx-auto text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="mt-2 text-yellow-700">Aucune permission disponible.</p>
                            <p class="text-sm text-yellow-600">Créez des permissions avant de pouvoir les assigner aux rôles.</p>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Changement de rôle
document.getElementById('roleSelector').addEventListener('change', function() {
    window.location.href = this.value;
});

// Réinitialisation du formulaire
function resetForm() {
    const form = document.getElementById('permissions-form');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Confirmation avant sauvegarde
document.getElementById('permissions-form').addEventListener('submit', function(e) {
    const checkedCount = this.querySelectorAll('input[type="checkbox"]:checked').length;

    if (checkedCount === 0) {
        e.preventDefault();
        Swal.fire({
            title: 'Aucune permission sélectionnée',
            text: 'Voulez-vous vraiment enregistrer sans aucune permission ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, continuer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    }
});
</script>
@endpush
