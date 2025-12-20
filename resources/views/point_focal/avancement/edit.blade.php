@extends('layouts.app')

@section('title', 'Mettre à jour l\'avancement')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-3xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <h1 class="text-xl font-semibold text-gray-900 mb-4">Mettre à jour l'avancement — {{ $planAction->recommandation->reference }}</h1>

        <p class="text-sm text-gray-600 mb-4">Action : <strong class="text-gray-800">{{ $planAction->action }}</strong></p>

        <form id="avancement-form" method="POST" action="{{ route('point_focal.avancement.update', $planAction) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="pourcentage" class="block mb-2 text-sm font-medium text-gray-700">Pourcentage d'avancement: <span id="pourcentage-value" class="font-semibold">{{ $planAction->pourcentage_avancement ?? 0 }}%</span></label>
                <input type="range" name="pourcentage_avancement" id="pourcentage" min="0" max="100" value="{{ old('pourcentage_avancement', $planAction->pourcentage_avancement ?? 0) }}" class="w-full">
                @error('pourcentage_avancement')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="commentaire_avancement" class="block mb-2 text-sm font-medium text-gray-700">Commentaire d'avancement (optionnel)</label>
                <textarea name="commentaire_avancement" id="commentaire_avancement" rows="5" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('commentaire_avancement', $planAction->commentaire_avancement) }}</textarea>
                @error('commentaire_avancement')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 mt-6">
                <button id="save-btn" type="submit" class="inline-flex items-center px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">
                    <i class="mr-2 fas fa-save"></i>
                    Enregistrer
                </button>
                <a href="{{ route('point_focal.avancement.index') }}" class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded hover:bg-gray-200">Annuler</a>
                <span id="status-message" class="ml-4 text-sm text-green-600 hidden"></span>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const range = document.getElementById('pourcentage');
    const valueLabel = document.getElementById('pourcentage-value');
    const form = document.getElementById('avancement-form');
    const statusMessage = document.getElementById('status-message');

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) return meta.getAttribute('content');
        const input = form.querySelector('input[name="_token"]');
        return input ? input.value : '';
    }

    if (range) {
        range.addEventListener('input', function () {
            valueLabel.textContent = this.value + '%';
        });
    }

    // AJAX submit to update without full reload
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const url = form.getAttribute('action');
        const data = new FormData(form);
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: data
        }).then(async (resp) => {
            if (!resp.ok) {
                const text = await resp.text();
                throw new Error(text || 'Erreur serveur');
            }
            return resp.json().catch(() => ({ success: true }));
        }).then((json) => {
            statusMessage.classList.remove('hidden', 'text-red-600');
            statusMessage.classList.add('text-green-600');
            statusMessage.textContent = json.message || 'Avancement enregistré.';
            setTimeout(() => statusMessage.classList.add('hidden'), 3000);
        }).catch((err) => {
            statusMessage.classList.remove('hidden', 'text-green-600');
            statusMessage.classList.add('text-red-600');
            statusMessage.textContent = 'Erreur: ' + (err.message || 'Impossible d\'enregistrer');
        });
    });
});
</script>
@endpush

@endsection
