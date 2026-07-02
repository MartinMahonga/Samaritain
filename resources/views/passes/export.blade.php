<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Pass - {{ $pass->holder_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            padding: 50px;
            background: #fff;
            color: #111827;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
        }

        /* En-tête */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 24px;
            border-bottom: 2px solid #111827;
            margin-bottom: 32px;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-brand img {
            height: 28px;
            width: auto;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.2px;
        }

        .header p {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }
        
        .logo-container {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .image-logo {
            height: 78px;
            width: 78px;
        }

        .title {
            color: #111827;
        }

        .status {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 10px;
            border: 1px solid #111827;
            border-radius: 2px;
        }

        .status-actif { border-color: #111827; color: #111827; }
        .status-expiré { border-color: #9ca3af; color: #9ca3af; }
        .status-utilisé { border-color: #9ca3af; color: #9ca3af; }
        .status-suspendu { border-color: #9ca3af; color: #9ca3af; }

        /* QR Code */
        .qr-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .qr-section img {
            width: 160px;
            height: 160px;
        }

        .qr-label {
            margin-top: 10px;
            font-size: 10px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Infos */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 24px;
            padding-bottom: 24px;
            margin-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item {
            min-width: 0;
        }

        .info-label {
            font-size: 10px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 13px;
            font-weight: 500;
        }

        .info-value.mono {
            font-family: monospace;
            font-size: 12px;
        }

        .info-item.full {
            grid-column: 1 / -1;
        }

        /* Visites */
        .visits {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 8px;
        }

        .visits .label {
            font-size: 11px;
            color: #6b7280;
        }

        .visits .value {
            font-size: 13px;
            font-weight: 600;
        }

        .progress-bar {
            background: #e5e7eb;
            height: 3px;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            background: #111827;
            height: 100%;
            width: {{ ($pass->remaining_visits / $pass->allowed_visits) * 100 }}%;
        }

        /* Pied de page */
        .footer {
            margin-top: 36px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #9ca3af;
            line-height: 1.6;
        }

        @media print {
            body { padding: 0; }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-brand">
                <div class="logo-container">
                    <img class="image-logo" src="{{ public_path('light_logo.svg') }}" alt="Logo">
                </div>
                <div>
                    <h1>Pass de Visite</h1>
                    <p>Document officiel d'accès</p>
                </div>
            </div>
            <span class="status status-{{ $pass->status }}">{{ ucfirst($pass->status) }}</span>
        </div>

        <div class="qr-section">
            <img src="{{ $pass->getQrCodeBase64() }}" alt="QR Code">
            <div class="qr-label">Présentez ce QR Code à l'entrée</div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Titulaire</div>
                <div class="info-value">{{ $pass->holder_name }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $pass->phone }}</div>
            </div>

            @if ($pass->email)
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $pass->email }}</div>
            </div>
            @endif

            <div class="info-item">
                <div class="info-label">Date d'émission</div>
                <div class="info-value">{{ $pass->created_at->format('d/m/Y') }}</div>
            </div>

            <div class="info-item full">
                <div class="info-label">Période de validité</div>
                <div class="info-value">Du {{ $pass->start_date->format('d/m/Y') }} au {{ $pass->expiration_date->format('d/m/Y') }}</div>
            </div>

            <div class="info-item full">
                <div class="info-label">UUID</div>
                <div class="info-value mono">{{ $pass->uuid }}</div>
            </div>
        </div>

        <div>
            <div class="visits">
                <span class="label">Visites restantes</span>
                <span class="value">{{ $pass->remaining_visits }} / {{ $pass->allowed_visits }}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>

        <div class="footer">
            <p>Ce document est généré automatiquement. Toute falsification est interdite.</p>
            <p>Émis le {{ now()->format('d/m/Y à H:i:s') }} — Réf. {{ substr($pass->uuid, 0, 13) }}…</p>
        </div>
    </div>
</body>

</html>