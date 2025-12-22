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
        // Utilisation de la relation standard : notifications() ou unreadNotifications()
        $notifications = Auth::user()->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Récupérer les notifications via AJAX (JSON)
     */
    public function list()
    {
        $notifications = Auth::user()->unreadNotifications()->limit(5)->get()->map(function($n) {
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
    public function markAsRead(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        if ($request->has('redirect') && $request->redirect) {
            return redirect($request->redirect);
        }

        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
