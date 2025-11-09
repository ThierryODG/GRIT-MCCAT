<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\users;

class ItsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        // Vérifie si l'utilisateur a le rôle ITS (ajuste selon ta logique)
        if (!$user || $user->role->name !== 'ITS') {
            abort(403, 'Accès réservé aux utilisateurs ITS.');
        }
        return $next($request);
    }
}
