<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckResponsable
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isResponsable()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Accès non autorisé.');
    }
}
