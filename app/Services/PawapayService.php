<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PawapayService
{
    protected string $baseUrl;

    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.pawapay.base_url');
        $this->token = config('services.pawapay.token');
    }

    public function createPaymentPage(array $data)
    {
        $response = Http::withToken($this->token)
            ->acceptJson()
            ->post("{$this->baseUrl}/v2/paymentpage", $data);

        if ($response->failed()) {
            // log l'erreur, lever une exception, etc.
            throw new \Exception('Erreur pawaPay: '.$response->body());
        }

        return $response->json();
    }
}
