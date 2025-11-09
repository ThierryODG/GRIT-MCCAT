<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Version simplifiée pour faire fonctionner l'application
        $user = Auth::user();

        $statistiques = [
            'total_recommandations' => 0,
            'recommandations_en_cours' => 0,
            'plans_en_attente_validation' => 0,
            'recommandations_cloturees' => 0,
        ];

        return view('responsable.dashboard', compact('statistiques'));
    }
}
