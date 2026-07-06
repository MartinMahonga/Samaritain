<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitPassRequest;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\VisitPass;
use App\Services\PawapayService;
use App\Services\VisitPassService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserVisitPassController extends Controller
{
    public function __construct(
        protected VisitPassService $visitPassService,
        protected PawapayService $pawapay
    ) {}

    /**
     * Show the form to create a visit pass for a property.
     */
    public function create(Property $property)
    {
        $price = $this->visitPassService->getPassPrice();

        return view('visit-passes.create', compact('property', 'price'));
    }

    /**
     * Store the visit pass request and redirect to payment.
     */
    public function store(StoreVisitPassRequest $request)
    {
        $visitPass = $this->visitPassService->createVisitPass($request->validated());

        // Redirect to payment page
        return $this->redirectToPayment($visitPass);
    }

    /**
     * Redirect to pawaPay payment page for the given visit pass.
     */
    protected function redirectToPayment(VisitPass $visitPass)
    {
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'visit_pass_id' => $visitPass->id,
            'status' => 'pending',
            'amount' => $visitPass->amount,
        ]);

        $visitPass->update(['transaction_id' => $transaction->transaction_id]);

        try {
            $depositId = (string) Str::uuid();

            $result = $this->pawapay->createPaymentPage([
                'depositId' => $depositId,
                'returnUrl' => route('transactions.callback', $transaction),
                'customerMessage' => 'Samaritain',
                'amountDetails' => [
                    'amount' => (string) $transaction->amount,
                    'currency' => 'XAF',
                ],
                'language' => 'FR',
                'country' => 'COG',
                'reason' => 'Achat pass visite - '.$visitPass->reference,
                'metadata' => [
                    ['transactionId' => $transaction->transaction_id],
                    ['visitPassId' => (string) $visitPass->id],
                    ['userId' => (string) $transaction->user_id],
                ],
            ]);

            return redirect($result['redirectUrl']);

        } catch (\Exception $e) {
            $transaction->update(['status' => 'failed']);
            $visitPass->markAsPaymentFailed();

            return redirect()->route('my-visit-passes.show', $visitPass)
                ->with('error', 'Une erreur est survenue lors de la création du paiement: '.$e->getMessage());
        }
    }

    /**
     * List the authenticated user's visit passes.
     */
    public function index()
    {
        Gate::authorize('viewAny', VisitPass::class);

        $visitPasses = $this->visitPassService->getUserVisitPasses();

        return view('visit-passes.index', compact('visitPasses'));
    }

    /**
     * Show a single visit pass detail.
     */
    public function show(VisitPass $visitPass)
    {
        Gate::authorize('view', $visitPass);

        $visitPass->load('property.images', 'property.city', 'property.category');

        return view('visit-passes.show', compact('visitPass'));
    }

    /**
     * Download the visit pass PDF.
     */
    public function download(VisitPass $visitPass)
    {
        Gate::authorize('view', $visitPass);

        if (! $visitPass->isDownloadable()) {
            return redirect()->back()
                ->with('error', 'Le pass n\'est pas encore disponible au téléchargement. Veuillez d\'abord effectuer le paiement.');
        }

        if (! $visitPass->pdf_path || ! Storage::exists($visitPass->pdf_path)) {
            $this->visitPassService->generatePdf($visitPass);
            $visitPass->refresh();
        }

        return Storage::download(
            $visitPass->pdf_path,
            'pass-visite-'.$visitPass->reference.'.pdf'
        );
    }

    /**
     * Retry payment for a failed visit pass.
     */
    public function retryPayment(VisitPass $visitPass)
    {
        Gate::authorize('retryPayment', $visitPass);

        return $this->redirectToPayment($visitPass);
    }
}
