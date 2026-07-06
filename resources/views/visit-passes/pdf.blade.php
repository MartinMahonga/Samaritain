<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pass visite - {{ $visitPass->reference }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 40px;
            color: #1a1a1a;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 5px;
            color: #10b981;
        }
        .header .reference {
            font-size: 14px;
            color: #6b7280;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            font-size: 14px;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .grid {
            display: table;
            width: 100%;
        }
        .row {
            display: table-row;
        }
        .label {
            display: table-cell;
            width: 150px;
            font-size: 12px;
            color: #6b7280;
            padding: 4px 0;
        }
        .value {
            display: table-cell;
            font-size: 12px;
            font-weight: bold;
            padding: 4px 0;
        }
        .qr-container {
            text-align: center;
            margin: 20px 0;
        }
        .qr-container img {
            width: 150px;
            height: 150px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 30px;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            background: #d1fae5;
            color: #065f46;
        }
        .property-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pass Visite</h1>
        <div class="reference">Réf. {{ $visitPass->reference }}</div>
    </div>

    <div class="qr-container">
        <img src="{{ $visitPass->getQrCodeBase64() }}" alt="QR Code">
    </div>

    <div class="section">
        <h2>Propriété</h2>
        <div class="property-title">{{ $visitPass->property->title }}</div>
        <div class="grid">
            <div class="row">
                <div class="label">Ville</div>
                <div class="value">{{ $visitPass->property->city->name ?? 'Brazzaville' }}</div>
            </div>
            <div class="row">
                <div class="label">Adresse</div>
                <div class="value">{{ $visitPass->property->address ?? 'Non spécifiée' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Visiteur</h2>
        <div class="grid">
            <div class="row">
                <div class="label">Nom complet</div>
                <div class="value">{{ $visitPass->holder_name }}</div>
            </div>
            <div class="row">
                <div class="label">Téléphone</div>
                <div class="value">{{ $visitPass->phone }}</div>
            </div>
            @if($visitPass->email)
            <div class="row">
                <div class="label">Email</div>
                <div class="value">{{ $visitPass->email }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <h2>Paiement</h2>
        <div class="grid">
            <div class="row">
                <div class="label">Montant</div>
                <div class="value">{{ number_format($visitPass->amount, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="row">
                <div class="label">Statut</div>
                <div class="value"><span class="badge">Payé</span></div>
            </div>
            @if($visitPass->paid_at)
            <div class="row">
                <div class="label">Payé le</div>
                <div class="value">{{ $visitPass->paid_at->format('d/m/Y à H:i') }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        Ce pass est valable pour une visite de la propriété indiquée.<br>
        Généré le {{ now()->format('d/m/Y à H:i') }} via Samaritain
    </div>
</body>
</html>