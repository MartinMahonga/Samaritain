<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\PawapayService;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function __construct(protected PawapayService $pawapay)
    {
    }

    public function paymentPage()
    {
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'amount' => 2500,
        ]);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la transaction, veuillez réessayer.');
        }

        try {
            $depositId = (string) Str::uuid();

            $result = $this->pawapay->createPaymentPage([
                'depositId' => $depositId, // uuid du modèle
                'returnUrl' => route('transactions.callback', $transaction),
                'customerMessage' => 'Paiement Samaritain Immobilier',
                'amountDetails' => [
                    'amount' => (string) $transaction->amount,
                    'currency' => 'XAF', // ou ta devise
                ],
                'language' => 'FR',
                'country' => 'COG', // à vérifier selon les pays supportés par pawaPay
                'reason' => 'Paiement du pass visite',
                'metadata' => [
                    ['transactionId' => $transaction->transaction_id],
                    ['userId' => (string) $transaction->user_id],
                ],
            ]);

            dd($result);

            $transaction->update([
                'status' => 'pending',
            ]);

            return redirect($result['redirectUrl']);

        } catch (\Exception $e) {
            $transaction->update(['status' => 'failed']);

            return redirect()->route('transactions.callback', $transaction)
                ->with('error', 'Une erreur est survenue lors de la création de la page de paiement: ' . $e->getMessage());
        }
    }

    public function callback(Transaction $transaction)
    {
        // Ici, tu peux vérifier le statut de la transaction via l'API pawaPay
        // et mettre à jour le statut de la transaction dans ta base de données.
        // Par exemple :
        // $status = $this->pawapay->checkTransactionStatus($transaction->provider_reference);
        // $transaction->update(['status' => $status]);

        return view('pages.payment', ['transaction' => $transaction]);
    }
}