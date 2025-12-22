<?php

namespace App\Http\Controllers\InspecteurGeneral;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    /**
     * Liste des rapports (Vue dossier/année) accessibles par l'IG
     */
    public function index()
    {
        // L'IG peut consulter tous les rapports du système
        $rapportsParAnnee = Rapport::with('user')
            ->orderBy('annee', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('annee');

        return view('inspecteur_general.rapport.index', compact('rapportsParAnnee'));
    }

    /**
     * Télécharger le rapport
     */
    public function show(Rapport $rapport)
    {
        if (!Storage::exists($rapport->path)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::download($rapport->path, $rapport->titre . '.pdf');
    }
}
