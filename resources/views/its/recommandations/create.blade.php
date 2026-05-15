@extends('layouts.app')

@section('title', 'Nouvelle Recommandation')

@section('content')
    <div class="min-h-screen py-10 bg-[#F8FAFC]">
        <div class="max-w-5xl mx-auto px-4">
            <!-- En-tête -->
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-[#1E293B] tracking-tight">Nouvelle Recommandation</h1>
                    <p class="mt-2 text-gray-500 font-medium">Formulez une recommandation stratégique pour une structure.</p>
                </div>
                <a href="{{ route('its.recommandations.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>

            <form action="{{ route('its.recommandations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Colonne Gauche : Paramètres -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-6 flex items-center">
                                <i class="fas fa-cog mr-2"></i> Paramètres
                            </h3>

                            <!-- Structure -->
                            <div class="space-y-2 mb-6">
                                <label for="structure_id" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Structure destinataire <span class="text-red-500">*</span></label>
                                <select id="structure_id" name="structure_id" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium">
                                    <option value="">Choisir une structure</option>
                                    @foreach($structures as $structure)
                                        <option value="{{ $structure->id }}" {{ old('structure_id') == $structure->id ? 'selected' : '' }}>
                                            {{ $structure->nom }} ({{ $structure->sigle }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('structure_id') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <!-- Priorité -->
                            <div class="space-y-2 mb-6">
                                <label for="priorite" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Priorité <span class="text-red-500">*</span></label>
                                <select id="priorite" name="priorite" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium">
                                    <option value="basse" {{ old('priorite') == 'basse' ? 'selected' : '' }}>🟢 Basse</option>
                                    <option value="moyenne" {{ old('priorite') == 'moyenne' ? 'selected' : '' }}>🟡 Moyenne</option>
                                    <option value="haute" {{ old('priorite', 'haute') == 'haute' ? 'selected' : '' }}>🔴 Haute</option>
                                </select>
                            </div>

                            <!-- Date Limite -->
                            <div class="space-y-2">
                                <label for="date_limite" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Date limite <span class="text-red-500">*</span></label>
                                <input type="date" id="date_limite" name="date_limite" value="{{ old('date_limite') }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium">
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                            <div class="flex space-x-3">
                                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-blue-900">Note au rédacteur</h4>
                                    <p class="text-xs text-blue-700 leading-relaxed mt-1">
                                        Cette recommandation sera enregistrée comme <strong>Brouillon</strong>. 
                                        L'Inspecteur Général devra la valider avant transmission au destinataire.
                                    </p>
                                </div>
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
                                <label for="titre" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Titre <span class="text-red-500">*</span></label>
                                <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required
                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all text-lg font-bold placeholder:text-gray-300"
                                    placeholder="Libellé court et explicite de la recommandation">
                                @error('titre') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label for="description" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Description détaillée <span class="text-red-500">*</span></label>
                                <textarea id="description" name="description" rows="10" required
                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium leading-relaxed resize-none"
                                    placeholder="Décrivez précisément le constat, les causes identifiées et l'objectif de cette recommandation...">{{ old('description') }}</textarea>
                                @error('description') <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 flex items-center">
                                    <i class="fas fa-paperclip mr-2"></i> Documents joints
                                </h3>
                                <button type="button" id="add-document-btn"
                                    class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-1"></i> Ajouter un fichier
                                </button>
                            </div>

                            <div id="documents-container" class="space-y-4">
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex flex-col md:flex-row gap-4 document-row relative animate-in fade-in zoom-in-95 duration-200">
                                    <div class="flex-grow">
                                        <input type="file" name="documents[]" 
                                            accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx"
                                            class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white file:text-gray-700 hover:file:bg-gray-100 file:shadow-sm file:transition-all">
                                        <p class="text-[10px] text-gray-400 mt-1">Formats : PDF, JPG, PNG, DOCX, XLSX (Max 10Mo)</p>
                                    </div>
                                    <div class="flex-grow">
                                        <input type="text" name="documents_descriptions[]"
                                            placeholder="Nom du document (Ex: Rapport d'audit)"
                                            class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-blue-500 transition-all">
                                    </div>
                                    <button type="button" class="remove-doc text-gray-400 hover:text-red-500 transition-colors p-2" onclick="this.closest('.document-row').remove()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-4">
                            <button type="submit"
                                class="px-10 py-4 bg-[#1E293B] hover:bg-black text-white rounded-2xl font-bold text-sm tracking-widest uppercase transition-all shadow-lg hover:shadow-xl hover:translate-y-[-2px] active:scale-95">
                                <i class="fas fa-save mr-2 text-gray-400"></i> Enregistrer
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
        document.addEventListener('change', function(e) {
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
                this.value = '';
            }
        });
    </script>
@endsection