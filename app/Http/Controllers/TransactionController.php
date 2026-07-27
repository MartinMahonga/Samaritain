<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\PawapayService;
use App\Services\VisitPassService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function __construct(protected PawapayService $pawapay) {}

    public function depositForm() {
        $provider = $this->providerAvailable();

        return view('pages.transcation', [
            'provider' => $provider
        ]);
    }

    public function providerAvailable()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.pawapay.token'),
            'Content-Type' => 'application/json',
        ])
        ->withQueryParameters([
            'country' => 'COG',
            'operationType' => 'DEPOSIT'
        ])
        ->get(config('services.pawapay.base_url') . '/active-conf');

        if($response->successful()) {
            $data = $response->json();

            return $data;
        } else {
            return [];
        }
    }
}
