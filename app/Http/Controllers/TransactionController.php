<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\PawapayService;
use App\Services\VisitPassService;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function __construct(protected PawapayService $pawapay) {}

    public function paymentPage()
    {
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'amount' => 5000,
        ]);

        if (! $transaction) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la transaction, veuillez réessayer.');
        }

        try {
            $depositId = (string) Str::uuid();

            $result = $this->pawapay->createPaymentPage([
                'depositId' => $depositId, // uuid du modèle
                'returnUrl' => route('transactions.callback', $transaction),
                'customerMessage' => 'Samaritain',
                'amountDetails' => [
                    'amount' => (string) $transaction->amount,
                    'currency' => 'XAF',
                ],
                'language' => 'FR',
                'country' => 'COG',
                'reason' => 'Paiement du pass visite',
                'metadata' => [
                    ['transactionId' => $transaction->transaction_id],
                    ['userId' => (string) $transaction->user_id],
                ],
            ]);

            $transaction->update([
                'status' => 'pending',
            ]);

            return redirect($result['redirectUrl']);

        } catch (\Exception $e) {
            $transaction->update(['status' => 'failed']);

            return redirect()->route('transactions.callback', $transaction)
                ->with('error', 'Une erreur est survenue lors de la création de la page de paiement: '.$e->getMessage());
        }
    }

    public function callback(Transaction $transaction)
    {
        // Vérifier le statut de la transaction auprès de pawaPay
        // et mettre à jour le statut de la transaction dans la base de données.
        $transaction->update(['status' => 'completed']);

        // Si une demande de pass visite est associée, la mettre à jour
        if ($transaction->visit_pass_id) {
            $visitPass = $transaction->visitPass;

            if ($visitPass) {
                $visitPassService = app(VisitPassService::class);

                // Ici, dans une vraie intégration, on vérifierait le statut via l'API pawaPay
                // Pour l'instant, on simule la confirmation de paiement
                $visitPassService->handleSuccessfulPayment($visitPass);

                return redirect()->route('my-visit-passes.show', $visitPass)
                    ->with('success', 'Paiement confirmé avec succès ! Votre pass visite est disponible.');
            }
        }

        return view('pages.payment', ['transaction' => $transaction]);
    }
}
