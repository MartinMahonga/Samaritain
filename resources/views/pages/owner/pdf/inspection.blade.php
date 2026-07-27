<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>État des lieux</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1a56db;
        }
        .header h1 {
            font-size: 22px;
            color: #1a56db;
            margin: 0 0 5px 0;
        }
        .header p {
            color: #666;
            margin: 0;
            font-size: 11px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-grid td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-grid .label {
            font-weight: bold;
            color: #555;
            width: 120px;
        }
        .info-grid .value {
            color: #333;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1a56db;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        table.rooms {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.rooms th {
            background: #1a56db;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }
        table.rooms td {
            padding: 6px 10px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        table.rooms tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status-clean { color: #059669; font-weight: bold; }
        .status-good { color: #2563eb; font-weight: bold; }
        .status-fair { color: #d97706; font-weight: bold; }
        .status-damaged { color: #dc2626; font-weight: bold; }
        .notes {
            background: #f3f4f6;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 11px;
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
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 8px;
            font-size: 11px;
            color: #666;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ÉTAT DES LIEUX</h1>
        <p>Samaritain — Gestion Immobilière</p>
        <p>{{ $inspection->type === 'check_in' ? "État d'entrée" : 'État de sortie' }}</p>
    </div>

    <table class="info-grid">
        <tr>
            <td class="label">Bien :</td>
            <td class="value">{{ $inspection->property->title }}</td>
        </tr>
        <tr>
            <td class="label">Adresse :</td>
            <td class="value">{{ $inspection->property->address }}</td>
        </tr>
        <tr>
            <td class="label">Date :</td>
            <td class="value">{{ $inspection->date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Inspecteur :</td>
            <td class="value">{{ $inspection->inspector_name }}</td>
        </tr>
        @if($inspection->contract)
        <tr>
            <td class="label">Locataire :</td>
            <td class="value">{{ $inspection->contract->tenant_name }}</td>
        </tr>
        @endif
    </table>

    <div class="section-title">État des pièces</div>

    @php
        $roomsData = $inspection->rooms_data ?? [];
        $statusLabels = [
            'clean' => 'Propre',
            'good' => 'Bon état',
            'fair' => 'Correct',
            'damaged' => 'Détérioré',
        ];
        $statusClasses = [
            'clean' => 'status-clean',
            'good' => 'status-good',
            'fair' => 'status-fair',
            'damaged' => 'status-damaged',
        ];
    @endphp

    @forelse($roomsData as $roomName => $elements)
        <table class="rooms">
            <thead>
                <tr>
                    <th colspan="2">{{ $roomName }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($elements as $elementName => $status)
                    <tr>
                        <td style="width: 50%;">{{ $elementName }}</td>
                        <td class="{{ $statusClasses[$status] ?? '' }}">
                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p style="color: #999; font-style: italic;">Aucune pièce renseignée.</p>
    @endforelse

    @if($inspection->notes)
        <div class="section-title">Observations</div>
        <div class="notes">
            {{ $inspection->notes }}
        </div>
    @endif

    <div class="signatures">
        <div class="signature-box">
            @if($inspection->tenant_signature)
                <p style="font-size: 20px; font-family: 'DejaVu Sans', cursive;">{{ $inspection->tenant_signature }}</p>
            @endif
            <div class="line">Signature du locataire</div>
        </div>
        <div class="signature-box">
            @if($inspection->owner_signature)
                <p style="font-size: 20px; font-family: 'DejaVu Sans', cursive;">{{ $inspection->owner_signature }}</p>
            @endif
            <div class="line">Signature du propriétaire</div>
        </div>
    </div>

    <div class="footer">
        Document généré par Samaritain — {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>