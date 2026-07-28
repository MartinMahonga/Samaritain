<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quittance de Loyer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 14px;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0d9488;
        }
        .doc-title {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: -30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .grid td {
            vertical-align: top;
            width: 50%;
        }
        .card {
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #fafafa;
        }
        .title {
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 12px;
            color: #666;
        }
        .amount-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        .amount-table th, .amount-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .amount-table th {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .signature-box {
            float: right;
            width: 250px;
            border: 1px dashed #999;
            height: 100px;
            text-align: center;
            padding-top: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <span class="logo">SAMARITAIN IMMOBILIER</span>
        <div class="doc-title">Quittance de Loyer</div>
    </div>

    <table class="grid">
        <tr>
            <td>
                <div class="title">Bailleur / Propriétaire</div>
                <strong>{{ auth()->user()->name }}</strong><br>
                Email : {{ auth()->user()->email }}<br>
                Téléphone : {{ auth()->user()->phone ?? 'Non spécifié' }}
            </td>
            <td>
                <div class="title">Locataire</div>
                <strong>{{ $contract->tenant_name }}</strong><br>
                Email : {{ $contract->tenant_email ?? 'Non spécifié' }}<br>
                Téléphone : {{ $contract->tenant_phone ?? 'Non spécifié' }}
            </td>
        </tr>
    </table>

    <div class="section card">
        <div class="title">Désignation du bien</div>
        <strong>{{ $property->title }}</strong><br>
        Adresse : {{ $property->address }}, {{ $property->city->name ?? 'Non spécifiée' }}
    </div>

    <p>Je soussigné <strong>{{ auth()->user()->name }}</strong>, propriétaire du logement désigné ci-dessus, déclare avoir reçu de la part du locataire <strong>{{ $contract->tenant_name }}</strong> la somme indiquée ci-dessous au titre du paiement du loyer.</p>

    <table class="amount-table">
        <thead>
            <tr>
                <th>Période</th>
                <th>Loyer Hors Charges</th>
                <th>Montant Payé</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Mois de {{ date('F', mktime(0, 0, 0, $rentPayment->month, 10)) }} {{ $rentPayment->year }}</td>
                <td>{{ number_format($rentPayment->amount_due, 0, ',', ' ') }} FCFA</td>
                <td><strong>{{ number_format($rentPayment->amount_paid, 0, ',', ' ') }} FCFA</strong></td>
                <td style="color: green; font-weight: bold;">PAYÉ</td>
            </tr>
        </tbody>
    </table>

    <p>Cette quittance annule tout reçu ou paiement antérieur concernant la même période. Elle est délivrée pour valoir ce que de droit.</p>

    <div class="footer">
        Fait le {{ now()->format('d/m/Y') }}<br>
        
        <div class="signature-box">
            <div class="title" style="margin-bottom: 40px;">Signature du Propriétaire</div>
            <strong>{{ auth()->user()->name }}</strong>
        </div>
    </div>

</body>
</html>
