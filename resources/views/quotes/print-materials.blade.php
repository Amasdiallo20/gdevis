<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matériaux - Devis {{ $quote->quote_number }}</title>
    @php
        $headerColor = isset($settings) && $settings->print_header_color ? $settings->print_header_color : '#14b8a6';
        $textColor = isset($settings) && $settings->print_text_color ? $settings->print_text_color : '#ffffff';
        $primaryColor = isset($settings) && $settings->primary_color ? $settings->primary_color : '#14b8a6';
    @endphp
    <style>
        @page {
            margin: 15mm 15mm 70mm 15mm;
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
        
        @if(!request()->has('pdf'))
        body {
            background: #f5f5f5;
            padding: 20px;
        }
        
        .document-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            min-height: 297mm;
        }
        
        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: {{ $headerColor }};
            color: {{ $textColor }};
            padding: 12px 20px;
            z-index: 10000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-toolbar h3 {
            margin: 0;
            font-size: 16px;
        }
        
        .print-toolbar-buttons {
            display: flex;
            gap: 10px;
        }
        
        .print-toolbar button {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: {{ $textColor }};
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .print-toolbar button:hover {
            background: rgba(255,255,255,0.3);
        }
        @endif
        
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
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #333;
        }
        
        .totals-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin: 15px 0;
        }
        
        .totals-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 15px 0;
        }
        
        .total-card {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
        }
        
        .total-card label {
            display: block;
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .total-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }
        
        table th {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        
        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .mb-4 {
            margin-bottom: 15px;
        }
        
        @media print {
            @if(!request()->has('pdf'))
            .print-toolbar {
                display: none;
            }
            @endif
        }
    </style>
</head>
<body>
    @if(!request()->has('pdf'))
    <div class="print-toolbar">
        <h3>📄 Aperçu - Matériaux Devis {{ $quote->quote_number }}</h3>
        <div class="print-toolbar-buttons">
            <button onclick="window.print()">🖨️ Imprimer</button>
            <button onclick="window.location.href='{{ route('quotes.print-materials', $quote) }}?pdf=1'">📄 PDF</button>
            <button onclick="window.location.href='{{ route('quotes.print-materials', $quote) }}?pdf=1&download=1'">📥 Télécharger PDF</button>
        </div>
    </div>
    
    <div class="document-container">
    @endif
    
    <!-- HEADER -->
    <div class="print-header">
        <h1>Liste des Matériaux</h1>
        <div class="info">
            <strong>Devis :</strong> {{ $quote->quote_number }}<br>
            <strong>Client :</strong> {{ $quote->client->name ?? 'N/A' }}<br>
            <strong>Date :</strong> {{ $quote->date->format('d/m/Y') }}
        </div>
    </div>
    
    <!-- Totaux Fenêtres -->
    @if(isset($materials['total_cadre']) && ($materials['total_cadre'] > 0 || $materials['total_vento'] > 0 || $materials['total_sikane'] > 0 || $materials['total_moustiquaire'] > 0))
    <div class="section-title">Totaux des Matériaux - Fenêtres</div>
    <div class="totals-grid">
        <div class="total-card">
            <label>Total CADRE</label>
            <div class="value">{{ number_format($materials['total_cadre'], 3, ',', ' ') }}</div>
            <small>barres</small>
        </div>
        <div class="total-card">
            <label>Total VENTO</label>
            <div class="value">{{ number_format($materials['total_vento'], 3, ',', ' ') }}</div>
            <small>barres</small>
        </div>
        <div class="total-card">
            <label>Total SIKANE</label>
            <div class="value">{{ number_format($materials['total_sikane'], 3, ',', ' ') }}</div>
            <small>barres</small>
        </div>
        <div class="total-card">
            <label>Total MOUSTIQUAIRE</label>
            <div class="value">{{ number_format($materials['total_moustiquaire'], 3, ',', ' ') }}</div>
            <small>barres</small>
        </div>
    </div>
    @endif
    
    <!-- Totaux Portes -->
    @if(isset($materials['total_cadre_porte']) && ($materials['total_cadre_porte'] > 0 || $materials['total_battant_porte'] > 0 || $materials['total_division'] > 0))
    <div class="section-title">Totaux des Matériaux - Portes</div>
    <div class="totals-grid-3">
        <div class="total-card">
            <label>Total CADRE PORTE</label>
            <div class="value">{{ number_format($materials['total_cadre_porte'], 3, ',', ' ') }}</div>
            <small>barres</small>
        </div>
        <div class="total-card">
            <label>Total BATTANT PORTE</label>
            <div class="value">{{ number_format($materials['total_battant_porte'], 3, ',', ' ') }}</div>
            <small>barres</small>
        </div>
        <div class="total-card">
            <label>Total DIVISION</label>
            <div class="value">{{ number_format($materials['total_division'], 3, ',', ' ') }}</div>
            <small>barres</small>
        </div>
    </div>
    @endif
    
    <!-- Détails Fenêtres -->
    @if(isset($materials['fenetres_details']) && count($materials['fenetres_details']) > 0)
    <div class="section-title">Détails par Ligne de Fenêtre</div>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Dimensions</th>
                <th class="text-center">Nb Fenêtres</th>
                <th class="text-center">CADRE</th>
                <th class="text-center">VENTO</th>
                <th class="text-center">SIKANE</th>
                <th class="text-center">MOUSTIQUAIRE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials['fenetres_details'] as $detail)
            <tr>
                <td>{{ $detail['line']->product->name ?? $detail['line']->description }}</td>
                <td>{{ number_format($detail['line']->width, 0) }} cm × {{ number_format($detail['line']->height, 0) }} cm</td>
                <td class="text-center">{{ $detail['nombre_fenetres'] }}</td>
                <td class="text-center">{{ number_format($detail['cadre'], 3, ',', ' ') }}</td>
                <td class="text-center">{{ number_format($detail['vento'], 3, ',', ' ') }}</td>
                <td class="text-center">{{ number_format($detail['sikane'], 3, ',', ' ') }}</td>
                <td class="text-center">{{ number_format($detail['moustiquaire'], 3, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Détails Portes -->
    @if(isset($materials['portes_details']) && count($materials['portes_details']) > 0)
    <div class="section-title">Détails par Ligne de Porte</div>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Dimensions</th>
                <th class="text-center">Nb Portes</th>
                <th class="text-center">Type</th>
                <th class="text-center">CADRE PORTE</th>
                <th class="text-center">BATTANT PORTE</th>
                <th class="text-center">DIVISION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials['portes_details'] as $detail)
            <tr>
                <td>{{ $detail['line']->product->name ?? $detail['line']->description }}</td>
                <td>{{ number_format($detail['line']->width, 0) }} cm × {{ number_format($detail['line']->height, 0) }} cm</td>
                <td class="text-center">{{ $detail['nombre_portes'] }}</td>
                <td class="text-center">{{ $detail['type'] === '1_battant' ? '1 BATTANT' : '2 BATTANTS' }}</td>
                <td class="text-center">{{ number_format($detail['cadre_porte'], 3, ',', ' ') }}</td>
                <td class="text-center">{{ number_format($detail['battant_porte'], 3, ',', ' ') }}</td>
                <td class="text-center">{{ number_format($detail['division'], 3, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if(!request()->has('pdf'))
    </div>
    @endif
</body>
</html>





