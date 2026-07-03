<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgencyContactRequest;
use App\Models\AgencyContact;
use App\Models\Parcelle;
use App\Models\Property;
use App\Models\User;
use App\Notifications\AgencyContactNotification;
use Illuminate\Support\Facades\Notification;

class AgencyContactController extends Controller
{
    public function propertyCreate(Property $property)
    {
        return view('pages.agency-contact.create', [
            'contactable' => $property,
            'type' => 'property',
        ]);
    }

    public function propertyStore(StoreAgencyContactRequest $request, Property $property)
    {
        return $this->processContact($request, $property, 'property.show');
    }

    public function parcelleCreate(Parcelle $parcelle)
    {
        return view('pages.agency-contact.create', [
            'contactable' => $parcelle,
            'type' => 'parcelle',
        ]);
    }

    public function parcelleStore(StoreAgencyContactRequest $request, Parcelle $parcelle)
    {
        return $this->processContact($request, $parcelle, 'parcelles.show');
    }

    protected function processContact(StoreAgencyContactRequest $request, Property|Parcelle $contactable, string $routeName)
    {
        $contact = AgencyContact::create([
            'contactable_id' => $contactable->id,
            'contactable_type' => $contactable::class,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $users = User::permission('manage-properties')->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new AgencyContactNotification($contact));
        }

        return redirect()->route($routeName, $contactable)
            ->with('success', 'Votre message a été envoyé à l\'agence. Nous vous répondrons dans les plus brefs délais.');
    }
}
