<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan de Coupe Optimisé - {{ $cutPlan->quote->quote_number }}</title>
    <style>
        @page {
            margin: 20mm;
        }
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
            border-bottom: 3px solid #3b82f6;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin: 0;
        }
        .header p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 5px;
        }
        .info-section h2 {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 5px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 40%;
            padding: 5px;
        }
        .info-value {
            display: table-cell;
            padding: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            page-break-inside: auto;
        }
        thead {
            background-color: #3b82f6;
            color: white;
        }
        thead th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tbody td {
            padding: 10px 12px;
            font-size: 11px;
        }
        .sections-cell {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .section-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            border: 1px solid #93c5fd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #eff6ff;
            border: 2px solid #3b82f6;
            border-radius: 5px;
        }
        .summary h3 {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #bfdbfe;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #3b82f6;
        }
        .summary-label {
            font-weight: bold;
        }
        .summary-value {
            font-weight: bold;
            color: #1e40af;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PLAN DE COUPE OPTIMISÉ</h1>
        <p>Optimisation des barres d'aluminium de 580 cm</p>
    </div>

    <!-- Informations du devis -->
    <div class="info-section">
        <h2>Informations du Devis</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Numéro de devis:</div>
                <div class="info-value">{{ $cutPlan->quote->quote_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Client:</div>
                <div class="info-value">{{ $cutPlan->quote->client->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date du devis:</div>
                <div class="info-value">{{ $cutPlan->quote->date->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de génération:</div>
                <div class="info-value">{{ $cutPlan->created_at->format('d/m/Y à H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Tableau des barres -->
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Barre</th>
                <th style="width: 50%;">Coupes (cm)</th>
                <th style="width: 20%;" class="text-right">Longueur Utilisée</th>
                <th style="width: 20%;" class="text-right">Chute</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cutPlan->details as $detail)
            <tr>
                <td class="text-center" style="font-weight: bold;">#{{ $detail->bar_number }}</td>
                <td>
                    <div class="sections-cell">
                        @foreach($detail->sections as $section)
                        <span class="section-badge">{{ number_format($section, 2, ',', ' ') }} cm</span>
                        @endforeach
                    </div>
                </td>
                <td class="text-right">{{ number_format($detail->used_length, 2, ',', ' ') }} cm</td>
                <td class="text-right" style="color: {{ $detail->waste > 0 ? '#ea580c' : '#16a34a' }};">
                    {{ number_format($detail->waste, 2, ',', ' ') }} cm
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Résumé -->
    <div class="summary">
        <h3>Résumé du Plan de Coupe</h3>
        <div class="summary-row">
            <span class="summary-label">Nombre total de barres utilisées:</span>
            <span class="summary-value">{{ $cutPlan->total_bars_used }} barres</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Longueur totale utilisée:</span>
            <span class="summary-value">{{ number_format($cutPlan->details->sum('used_length'), 2, ',', ' ') }} cm</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total des chutes:</span>
            <span class="summary-value" style="color: #ea580c;">{{ number_format($cutPlan->total_waste, 2, ',', ' ') }} cm</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Taux d'utilisation:</span>
            <span class="summary-value">
                {{ number_format((($cutPlan->details->sum('used_length') / ($cutPlan->total_bars_used * 580)) * 100), 2, ',', ' ') }}%
            </span>
        </div>
    </div>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }} - A2 VitraDevis</p>
    </div>
</body>
</html>



