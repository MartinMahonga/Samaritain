<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Models\VisitRequest;
use App\Notifications\NewVisitRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class VisitRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'          => 'required|string|max:255',
            'phone'              => 'required|string|max:20',
            'city'               => 'required|string|max:255',
            'property_category'  => 'nullable|string',
            'property_id'        => 'nullable|exists:properties,id',
            'preferred_date'     => 'required|string',
            'message'            => 'nullable|string',
        ]);

        $visitRequest = VisitRequest::create($validated);

        // Envoyer la notification à tous les administrateurs/staff
        $admins = User::where('is_staff', true)->get();
        
        // Si aucun admin n'existe, envoyez à l'utilisateur avec l'ID 1 par défaut
        if ($admins->isEmpty()) {
            $defaultAdmin = User::find(1);
            if ($defaultAdmin) {
                $defaultAdmin->notify(new NewVisitRequestNotification($visitRequest));
            }
        } else {
            Notification::send($admins, new NewVisitRequestNotification($visitRequest));
        }

        return response()->json([
            'message' => 'Demande envoyée avec succès. Nous vous contacterons sous 5 minutes.'
        ], 201);
    }
}
