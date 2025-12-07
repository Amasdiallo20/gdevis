<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif Matériaux - Devis {{ $quote->quote_number }}</title>
    @php
        $headerColor = isset($settings) && $settings->print_header_color ? $settings->print_header_color : '#14b8a6';
        $textColor = isset($settings) && $settings->print_text_color ? $settings->print_text_color : '#ffffff';
        $primaryColor = isset($settings) && $settings->primary_color ? $settings->primary_color : '#3b82f6';
    @endphp
    <style>
        @page {
            margin: 25mm 25mm 25mm 25mm;
            size: A4 portrait;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: white;
        }
        
        .print-header {
            background: {{ $headerColor }};
            color: {{ $textColor }};
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .print-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .print-header .info {
            font-size: 12px;
            margin-top: 10px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 3px solid {{ $primaryColor }};
            color: #333;
            text-align: center;
        }
        
        .table-container {
            margin: 20px 0;
            padding: 0 10mm;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 11px;
        }
        
        table th {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        
        table td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            font-size: 11px;
        }
        
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        table tbody tr:hover {
            background: #f0f0f0;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-row {
            background: {{ $primaryColor }} !important;
            color: white;
            font-weight: bold;
        }
        
        .total-row td {
            border-color: {{ $primaryColor }};
            padding: 15px 8px;
            font-size: 13px;
        }
        
        .material-name {
            font-weight: 600;
            color: #333;
        }
        
        .quantity-badge {
            display: inline-block;
            padding: 4px 8px;
            background: #e0e7ff;
            border-radius: 4px;
            font-weight: 600;
            color: #4338ca;
        }
        
        .price-value {
            font-weight: 700;
            color: #333;
        }
        
        .total-value {
            font-weight: 800;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="print-header">
        <h1>Récapitulatif des Matériaux avec Prix</h1>
        <div class="info">
            <strong>Devis :</strong> {{ $quote->quote_number }}<br>
            <strong>Client :</strong> {{ $quote->client->name ?? 'N/A' }}<br>
            <strong>Date :</strong> {{ $quote->date->format('d/m/Y') }}
        </div>
    </div>
    
    @if(isset($materials['materiaux_avec_prix']) && count($materials['materiaux_avec_prix']) > 0)
    <div class="section-title">Détail des Matériaux</div>
    <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nom Matériau</th>
                <th class="text-center">Quantité</th>
                <th class="text-center">Prix Unitaire (GNF)</th>
                <th class="text-center">Total par Ligne (GNF)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials['materiaux_avec_prix'] as $materiau)
            <tr>
                <td class="material-name">{{ $materiau['nom'] }}</td>
                <td class="text-center">
                    <span class="quantity-badge">{{ number_format($materiau['quantite'], 3, ',', ' ') }}</span>
                </td>
                <td class="text-center price-value">{{ number_format($materiau['prix_unitaire'], 0, ',', ' ') }}</td>
                <td class="text-center price-value">{{ number_format($materiau['total_ligne'], 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right" style="padding-right: 20px;">
                    <strong>Total Général des Matériaux :</strong>
                </td>
                <td class="text-center total-value">
                    {{ number_format($materials['total_general'] ?? 0, 0, ',', ' ') }} GNF
                </td>
            </tr>
        </tfoot>
    </table>
    </div>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <p style="font-size: 14px;">Aucun matériau à afficher</p>
    </div>
    @endif
</body>
</html>

