<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrat de Bail</title>
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
        .clause {
FCFA
￼
￼
￼
Prix suggéré
            margin-bottom: 12px;
            padding: 8px 10px;
            background: #f9fafb;
            border-left: 3px solid #0d9488;
            border-radius: 3px;
        }
        .clause p {
            margin: 3px 0;
        }
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 45%;
        }
        .signature-box .line {
            border-top: 1px solid #374151;
            margin-top: 50px;
            padding-top: 8px;
            font-size: 10px;
            color: #6b7280;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .highlight {
            background: #f0fdf4;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #bbf7d0;
            margin: 10px 0;
        }
        .highlight strong {
            color: #059669;
        }
        table.amounts {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table.amounts th, table.amounts td {
            border: 1px solid #d1d5db;
            padding: 6px 10px;
            text-align: left;
        }
        table.amounts th {
            background: #f3f4f6;
            font-size: 10px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="brand-header">
        <div class="logo">SAMARITAIN IMMOBILIER</div>
        <div class="subtitle">Agence Immobilière — Gestion Locative — Conseil</div>
    </div>

    <div class="doc-title">Contrat de Location — Bail d'Habitation</div>

    <p style="text-align: center; color: #6b7280; font-size: 10px;">
        Fait le {{ now()->format('d/m/Y') }} à Brazzaville
    </p>

    <div class="section-title">1. Parties au Contrat</div>

    <table class="info-grid">
        <tr>
            <td class="label">Bailleur (Propriétaire) :</td>
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
        <tr><td colspan="2" style="padding: 5px 0;"></td></tr>
        <tr>
            <td class="label">Locataire :</td>
            <td class="value"><strong>{{ $contract->tenant_name }}</strong></td>
        </tr>
        @if($contract->tenant_email)
        <tr>
            <td class="label">Email :</td>
            <td class="value">{{ $contract->tenant_email }}</td>
        </tr>
        @endif
        @if($contract->tenant_phone)
        <tr>
            <td class="label">Téléphone :</td>
            <td class="value">{{ $contract->tenant_phone }}</td>
        </tr>
        @endif
    </table>

    <div class="section-title">2. Désignation du Logement</div>
    <table class="info-grid">
        <tr>
            <td class="label">Adresse :</td>
            <td class="value"><strong>{{ $property->title }}</strong></td>
        </tr>
        <tr>
            <td class="label">Situation :</td>
            <td class="value">{{ $property->address }}, {{ $property->city->name ?? 'Non spécifiée' }}</td>
        </tr>
        @if($property->surface)
        <tr>
            <td class="label">Surface :</td>
            <td class="value">{{ $property->surface }} m²</td>
        </tr>
        @endif
        @if($property->bedrooms)
        <tr>
            <td class="label">Pièces :</td>
            <td class="value">{{ $property->bedrooms }} chambre(s)</td>
        </tr>
        @endif
    </table>

    <div class="section-title">3. Durée du Bail</div>
    <div class="clause">
        <p>Le présent bail est consenti et accepté pour une durée déterminée de <strong>12 mois</strong>.</p>
        <p><strong>Date d'effet :</strong> {{ $contract->start_date->format('d/m/Y') }}</p>
        <p><strong>Date d'échéance :</strong> {{ $contract->end_date?->format('d/m/Y') ?? 'Tacite reconduction' }}</p>
        <p>À l'échéance, le bail se renouvellera par tacite reconduction, sauf dénonciation par l'une des parties avec un préavis de 3 mois.</p>
    </div>

    <div class="section-title">4. Conditions Financières</div>
    <table class="amounts">
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Loyer mensuel (hors charges)</td>
                <td><strong>{{ number_format($contract->monthly_rent, 0, ',', ' ') }}</strong></td>
            </tr>
            @if($contract->deposit)
            <tr>
                <td>Dépôt de garantie</td>
                <td>{{ number_format($contract->deposit, 0, ',', ' ') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="highlight">
        <strong>📍 Mode de paiement :</strong> Le loyer est payable mensuellement, au plus tard le 10 de chaque mois, par virement bancaire ou espèces contre reçu.
    </div>

    <div class="section-title">5. Obligations du Locataire</div>
    <div class="clause">
        <p>Le locataire s'engage à :</p>
        <p>• Payer le loyer et les charges aux échéances convenues</p>
        <p>• User paisiblement du logement</p>
        <p>• Répondre des dégradations locatives</p>
        <p>• Souscrire une assurance habitation</p>
        <p>• Autoriser les visites d'entretien et d'état des lieux</p>
    </div>

    <div class="section-title">6. Obligations du Bailleur</div>
    <div class="clause">
        <p>Le bailleur s'engage à :</p>
        <p>• Délivrer un logement décent et en bon état</p>
        <p>• Assurer la jouissance paisible du logement</p>
        <p>• Effectuer les grosses réparations</p>
        <p>• Garantir l'absence de vices cachés</p>
    </div>

    <div class="section-title">7. État des Lieux</div>
    <div class="clause">
        <p>Un état des lieux contradictoire sera établi à l'entrée et à la sortie du locataire.</p>
        <p>Il servira de référence pour la restitution du dépôt de garantie.</p>
    </div>

    <div class="signatures">
        <div class="signature-box">
            @if(isset($contract) && $contract->tenantSignature)
                <img src="{{ storage_path('app/'.$contract->tenantSignature->signature_image) }}" alt="Signature locataire" style="max-height: 80px; max-width: 100%;">
                <div class="line" style="margin-top: 10px;">
                    Signature du Locataire<br>
                    <span style="font-size: 9px; color: #9ca3af;">
                        {{ $contract->tenantSignature->user->name ?? 'Locataire' }}<br>
                        {{ $contract->tenantSignature->signed_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            @else
                <div class="line">Signature du Locataire<br><span style="font-size: 9px; color: #9ca3af;">Précédée de la mention "Lu et approuvé"</span></div>
            @endif
        </div>
        <div class="signature-box">
            @if(isset($contract) && $contract->ownerSignature)
                <img src="{{ storage_path('app/'.$contract->ownerSignature->signature_image) }}" alt="Signature propriétaire" style="max-height: 80px; max-width: 100%;">
                <div class="line" style="margin-top: 10px;">
                    Signature du Propriétaire<br>
                    <span style="font-size: 9px; color: #9ca3af;">
                        {{ $contract->ownerSignature->user->name ?? auth()->user()->name }}<br>
                        {{ $contract->ownerSignature->signed_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            @else
                <div class="line">Signature du Propriétaire<br><span style="font-size: 9px; color: #9ca3af;">{{ auth()->user()->name }}</span></div>
            @endif
        </div>
    </div>

    @if(isset($contract) && $contract->isFullySigned())
    <div style="margin-top: 30px; padding: 15px; background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 5px; text-align: center;">
        <p style="font-size: 12px; font-weight: bold; color: #059669; margin: 0;">
            Document signé électroniquement
        </p>
        <p style="font-size: 10px; color: #6b7280; margin: 5px 0 0 0;">
            ID: {{ $contract->id }} | Version: {{ $contract->contract_version }} | 
            Activé le: {{ $contract->activated_at->format('d/m/Y H:i') }}
        </p>
    </div>
    @endif

    <div class="footer">
        Document généré par Samaritain Immobilier — {{ date('d/m/Y à H:i') }}<br>
        Ce document est une version numérique du contrat de bail. Il annule et remplace toute version antérieure.
        @if(isset($contract) && $contract->isFullySigned())
        <br>Hash: {{ hash('sha256', $contract->id.'-'.$contract->contract_version.'-'.$contract->tenant_name) }}
        @endif
    </div>
</body>
</html>