<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCabinetMinistre
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isCabinetMinistre()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Accès non autorisé.');
    }
}
