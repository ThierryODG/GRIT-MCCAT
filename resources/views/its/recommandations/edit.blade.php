@extends('layouts.app')

@section('title', 'Modifier la Recommandation')

@section('content')
    <div class="min-h-screen py-10 bg-[#F8FAFC]">
        <div class="max-w-5xl mx-auto px-4">
            <!-- En-tête -->
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-[#1E293B] tracking-tight">Modifier la Recommandation</h1>
                    <p class="mt-2 text-gray-500 font-medium">{{ $recommandation->reference }}</p>
                </div>
                <a href="{{ route('its.recommandations.show', $recommandation) }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>

            <form action="{{ route('its.recommandations.update', $recommandation) }}" method="POST"
                enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Bannière Statut/Rejet -->
                @if($recommandation->statut == 'rejetee_ig')
                    <div
                        class="bg-red-50 p-6 rounded-2xl border border-red-100 mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                        <div class="flex space-x-4">
                            <div class="bg-red-100 p-3 rounded-xl h-fit">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-900">Recommandation rejetée par l'IG</h3>
                                <p class="text-red-700 font-medium mt-1">
                                    {{ $recommandation->motif_rejet_ig ?? 'Veuillez prendre en compte les remarques de l\'Inspecteur Général.' }}
                                </p>
                                @if($recommandation->commentaire_ig)
                                    <div class="mt-3 p-3 bg-white/50 rounded-lg text-sm italic text-red-800 border border-red-100">
                                        "{{ $recommandation->commentaire_ig }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Colonne Gauche : Paramètres -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-6 flex items-center">
                                <i class="fas fa-cog mr-2"></i> Paramètres
                            </h3>

                            <!-- Structure -->
                            <div class="space-y-2 mb-6">
                                <label for="structure_id"
                                    class="text-xs font-bold text-gray-700 uppercase tracking-widest">Structure destinataire
                                    <span class="text-red-500">*</span></label>
                                <select id="structure_id" name="structure_id" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium">
                                    @foreach($structures as $structure)
                                        <option value="{{ $structure->id }}" {{ old('structure_id', $recommandation->structure_id) == $structure->id ? 'selected' : '' }}>
                                            {{ $structure->nom }} ({{ $structure->sigle }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Priorité -->
                            <div class="space-y-2 mb-6">
                                <label for="priorite"
                                    class="text-xs font-bold text-gray-700 uppercase tracking-widest">Priorité <span
                                        class="text-red-500">*</span></label>
                                <select id="priorite" name="priorite" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium">
                                    <option value="basse" {{ old('priorite', $recommandation->priorite) == 'basse' ? 'selected' : '' }}>🟢 Basse</option>
                                    <option value="moyenne" {{ old('priorite', $recommandation->priorite) == 'moyenne' ? 'selected' : '' }}>🟡 Moyenne</option>
                                    <option value="haute" {{ old('priorite', $recommandation->priorite) == 'haute' ? 'selected' : '' }}>🔴 Haute</option>
                                </select>
                            </div>

                            <!-- Date Limite -->
                            <div class="space-y-2">
                                <label for="date_limite"
                                    class="text-xs font-bold text-gray-700 uppercase tracking-widest">Date limite <span
                                        class="text-red-500">*</span></label>
                                <input type="date" id="date_limite" name="date_limite"
                                    value="{{ old('date_limite', $recommandation->date_limite->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium">
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                            <div class="flex items-center space-x-3 text-blue-900 mb-2">
                                <i class="fas fa-history text-sm"></i>
                                <h4 class="text-sm font-bold">Historique</h4>
                            </div>
                            <div class="text-[11px] text-blue-700 space-y-1">
                                <p>Créée le : <span
                                        class="font-bold">{{ $recommandation->created_at->format('d/m/Y') }}</span></p>
                                <p>Dernier update : <span
                                        class="font-bold">{{ $recommandation->updated_at->format('d/m/Y') }}</span></p>
                                <p>Statut : <span
                                        class="uppercase font-extrabold">{{ $recommandation->statut_label }}</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne Droite : Contenu -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-6 flex items-center">
                                <i class="fas fa-edit mr-2"></i> Détails du contenu
                            </h3>

                            <!-- Titre -->
                            <div class="space-y-2 mb-8">
                                <label for="titre" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Titre
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="titre" name="titre"
                                    value="{{ old('titre', $recommandation->titre) }}" required
                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all text-lg font-bold placeholder:text-gray-300">
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label for="description"
                                    class="text-xs font-bold text-gray-700 uppercase tracking-widest">Description détaillée
                                    <span class="text-red-500">*</span></label>
                                <textarea id="description" name="description" rows="10" required
                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium leading-relaxed resize-none">{{ old('description', $recommandation->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Documents Existants -->
                        @if($recommandation->documents->count() > 0)
                            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-6 flex items-center">
                                    <i class="fas fa-file-alt mr-2"></i> Documents actuels
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($recommandation->documents as $doc)
                                        <div
                                            class="flex items-center justify-between p-3 bg-gray-50 border border-gray-100 rounded-xl">
                                            <div class="flex items-center space-x-3 overflow-hidden">
                                                <i class="fas fa-file-pdf text-red-500"></i>
                                                <span
                                                    class="text-xs font-bold text-gray-700 truncate">{{ $doc->description ?? $doc->file_name }}</span>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                                                    class="text-gray-400 hover:text-blue-600 transition-colors">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="text-gray-400 hover:text-red-600 transition-colors">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Ajouter des Documents -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 flex items-center">
                                    <i class="fas fa-paperclip mr-2"></i> Ajouter des pièces jointes
                                </h3>
                                <button type="button" id="add-document-btn"
                                    class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-1"></i> Nouveau fichier
                                </button>
                            </div>
                            <div id="documents-container" class="space-y-4">
                                <!-- JS Row Injection -->
                            </div>
                        </div>

                        <!-- Actions de validation -->
                        <div class="flex flex-col md:flex-row gap-4 pt-6">
                            <a href="{{ route('its.recommandations.show', $recommandation) }}"
                                class="px-8 py-4 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-2xl font-bold text-sm tracking-widest uppercase transition-all text-center">
                                Annuler
                            </a>

                            @if($recommandation->statut === 'brouillon')
                                <button type="submit" name="action" value="save"
                                    class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-2xl font-bold text-sm tracking-widest uppercase transition-all shadow-sm">
                                    Sauvegarder
                                </button>
                            @endif

                            <button type="submit" name="action"
                                value="{{ $recommandation->statut === 'rejetee_ig' ? 'resoumettre' : 'soumettre' }}"
                                class="px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-sm tracking-widest uppercase transition-all shadow-lg hover:shadow-xl hover:translate-y-[-2px] active:scale-95 flex-grow">
                                <i class="fas fa-paper-plane mr-2 opacity-60"></i>
                                {{ $recommandation->statut === 'rejetee_ig' ? 'Modifier et Renvoyer' : 'Soumettre à l\'IG' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Gestion dynamique des documents
        document.getElementById('add-document-btn').addEventListener('click', function () {
            const container = document.getElementById('documents-container');
            const newRow = document.createElement('div');
            newRow.className = 'p-4 bg-gray-50 rounded-xl border border-gray-200 flex flex-col md:flex-row gap-4 document-row relative animate-in fade-in zoom-in-95 duration-200';
            newRow.innerHTML = `
                            <div class="flex-grow">
                                <input type="file" name="documents[]" 
                                    accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx"
                                    class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white file:text-gray-700 hover:file:bg-gray-100 file:shadow-sm file:transition-all">
                                <p class="text-[10px] text-gray-400 mt-1">Formats : PDF, JPG, PNG, DOCX, XLSX (Max 10Mo)</p>
                            </div>
                            <div class="flex-grow">
                                <input type="text" name="documents_descriptions[]"
                                    placeholder="Nom du document"
                                    class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-blue-500 transition-all">
                            </div>
                            <button type="button" class="remove-doc text-gray-400 hover:text-red-500 transition-colors p-2" onclick="this.closest('.document-row').remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
            container.appendChild(newRow);
        });

        // Validation des fichiers côté client
        document.addEventListener('change', function (e) {
            if (e.target && e.target.type === 'file' && e.target.name === 'documents[]') {
                const file = e.target.files[0];
                if (!file) return;

                const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'docx', 'xlsx'];
                const extension = file.name.split('.').pop().toLowerCase();
                const maxSize = 10 * 1024 * 1024; // 10Mo

                if (!allowedExtensions.includes(extension)) {
                    alert('Format non supporté ! Veuillez sélectionner un fichier PDF, Image (JPG, PNG) ou Office (DOCX, XLSX).');
                    e.target.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert('Fichier trop lourd ! La taille maximale autorisée est de 10Mo.');
                    e.target.value = '';
                    return;
                }
            }
        });

        // Validation de date
        document.getElementById('date_limite').addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (selectedDate <= today) {
                alert('La date limite doit être postérieure à aujourd\'hui.');
                this.value = '{{ $recommandation->date_limite->format('Y-m-d') }}';
            }
        });
    </script>
@endsection