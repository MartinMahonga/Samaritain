<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1f2937;
            line-height: 1.6;
            font-size: 11px;
            margin: 0;
            padding: 30px;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #0d9488;
        }
        .brand-header .logo {
            font-size: 22px;
            font-weight: bold;
            color: #0d9488;
            letter-spacing: 1px;
        }
        .brand-header .subtitle {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }
        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0d9488;
            margin: 20px 0;
            padding: 8px 0;
            border-bottom: 2px solid #0d9488;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0d9488;
            margin: 20px 0 10px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #d1d5db;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-grid td {
            padding: 3px 8px;
            vertical-align: top;
        }
        .info-grid .label {
            font-weight: bold;
            color: #4b5563;
            width: 140px;
        }
        .info-grid .value {
            color: #1f2937;
        }
        table.amounts {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table.amounts th, table.amounts td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
        }
        table.amounts th {
            background: #f3f4f6;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.amounts tfoot td {
            font-weight: bold;
            background: #f9fafb;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background: #d1fae5;
            color: #059669;
        }
        .status-unpaid {
            background: #fee2e2;
            color: #dc2626;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .payment-info {
            margin-top: 25px;
            padding: 12px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 4px;
        }
        .payment-info strong {
            color: #059669;
        }
    </style>
</head>
<body>
    <div class="brand-header">
        <div class="logo">SAMARITAIN IMMOBILIER</div>
        <div class="subtitle">Agence Immobilière — Gestion Locative — Conseil</div>
    </div>

    <div class="doc-title">Facture {{ $invoice->type === 'rent' ? 'de Loyer' : 'de Charges' }}</div>

    <p style="text-align: right; font-size: 10px; color: #6b7280;">
        Facture N° <strong>{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</strong><br>
        Date d'émission : {{ now()->format('d/m/Y') }}
    </p>

    <div class="section-title">Émetteur</div>
    <table class="info-grid">
        <tr>
            <td class="label">Nom :</td>
            <td class="value"><strong>{{ auth()->user()->name }}</strong></td>
        </tr>
        <tr>
            <td class="label">Email :</td>
            <td class="value">{{ auth()->user()->email }}</td>
        </tr>
        <tr>
            <td class="label">Téléphone :</td>
            <td class="value">{{ auth()->user()->phone ?? 'Non spécifié' }}</td>
        </tr>
    </table>

    <div class="section-title">Bien concerné</div>
    <table class="info-grid">
        <tr>
            <td class="label">Bien :</td>
            <td class="value"><strong>{{ $property->title }}</strong></td>
        </tr>
        <tr>
            <td class="label">Adresse :</td>
            <td class="value">{{ $property->address }}, {{ $property->city->name ?? 'Non spécifiée' }}</td>
        </tr>
    </table>

    <div class="section-title">Détail de la Facture</div>
    <table class="amounts">
        <thead>
            <tr>
                <th>Type</th>
                <th>Date d'échéance</th>
                <th>Montant (FCFA)</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ ucfirst($invoice->type) }}</td>
                <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                <td><strong>{{ number_format($invoice->amount, 0, ',', ' ') }}</strong></td>
                <td>
                    <span class="status-badge {{ $invoice->status === 'paid' ? 'status-paid' : 'status-unpaid' }}">
                        {{ $invoice->status === 'paid' ? 'Payée' : 'Impayée' }}
                    </span>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td>{{ number_format($invoice->amount, 0, ',', ' ') }} FCFA</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    @if($invoice->status === 'unpaid')
    <div class="payment-info">
        <strong>📌 Information :</strong> Cette facture est en attente de paiement. Merci de procéder au règlement avant le <strong>{{ $invoice->due_date->format('d/m/Y') }}</strong>.
    </div>
    @else
    <div class="payment-info">
        <strong>✅ Acquittée :</strong> Cette facture a été payée le {{ $invoice->paid_at?->format('d/m/Y') ?? '—' }}.
    </div>
    @endif

    <div class="footer">
        Document généré par Samaritain Immobilier — {{ date('d/m/Y à H:i') }}<br>
        Pour toute réclamation, contactez-nous à contact@samaritain.cg
    </div>
</body>
</html>