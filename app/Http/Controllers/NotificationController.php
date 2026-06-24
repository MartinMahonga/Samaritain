<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Récupérer les 5 dernières notifications et le compte des non lues
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->take(5)->get();
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    // Marquer une notification comme lue
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    // Tout marquer comme lu
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    // Supprimer une notification
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    // Supprimer toutes les notifications
    public function destroyAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();

        return response()->json(['success' => true]);
    }

    // Supprimer toutes les notifications lues
    public function destroyRead()
    {
        $user = Auth::user();
        $user->notifications()->whereNotNull('read_at')->delete();

        return response()->json(['success' => true]);
    }

    // Page dédiée avec pagination
    public function showAll()
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        return view('notifications.index', ['notifications' => $notifications]);
    }
}
