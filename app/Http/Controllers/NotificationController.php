<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Afficher les notifications de l'utilisateur
     */
    public function index()
    {
        $this->pruneNotifications();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $notifications = $user->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Récupérer les notifications via AJAX (JSON)
     */
    public function list()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notifications = $user->unreadNotifications()->limit(5)->get()->map(function($n) {
            return [
                'id' => $n->id,
                'data' => $n->data,
                'created_at_human' => $n->created_at->diffForHumans(),
                'read_at' => $n->read_at,
            ];
        });

        return response()->json($notifications);
    }

    /**
     * Marquer une notification comme lue et rediriger si nécessaire
     */
    public function markAsRead(Request $request, int $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        if ($request->has('redirect') && $request->redirect) {
            return redirect($request->redirect);
        }

        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications comme lues et nettoyer les anciennes
     */
    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Marquer tout comme lu
        $user->unreadNotifications->markAsRead();

        // Nettoyage immédiat : On supprime les notifications lues depuis plus de 24h
        $user->notifications()
            ->whereNotNull('read_at')
            ->where('read_at', '<', now()->subDay())
            ->delete();

        return back()->with('success', 'Notifications traitées et nettoyées.');
    }

    /**
     * Supprimer automatiquement les très anciennes notifications
     */
    private function pruneNotifications()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Supprimer toutes les notifications (lues ou non) de plus de 30 jours pour éviter d'encombrer la base
        $user->notifications()
            ->where('created_at', '<', now()->subDays(30))
            ->delete();
            
        // Supprimer de manière préemptive les notifications déjà lues depuis plus de 7 jours
        $user->notifications()
            ->whereNotNull('read_at')
            ->where('read_at', '<', now()->subDays(7))
            ->delete();
    }
}
