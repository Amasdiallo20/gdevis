<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis {{ $quote->quote_number }}</title>
    @php
        // Récupérer les couleurs depuis les paramètres
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
        
        /* Barre d'outils d'impression */
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
            font-weight: 600;
        }
        
        .print-toolbar-buttons {
            display: flex;
            gap: 10px;
        }
        
        .print-toolbar button {
            background: white;
            color: {{ $headerColor }};
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .print-toolbar button:hover {
            background: #f0f0f0;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .document-container {
            margin-top: 60px;
        }
        @endif
        
        /* HEADER */
        .print-header {
            position: relative;
            padding: 15px 0;
            margin-bottom: 20px;
            width: 100%;
            background: {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#f0f9ff' }};
            page-break-inside: avoid;
            border-radius: 4px;
        }
        
        .header-content {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            table-layout: fixed;
        }
        
        .logo-container {
            display: table-cell;
            vertical-align: middle;
            width: 120px;
        }
        
        .logo-container:first-child {
            text-align: left;
        }
        
        .logo-container:last-child {
            text-align: right;
        }
        
        .logo-container img {
            max-height: 60px;
            max-width: 100px;
            width: auto;
            height: auto;
            display: inline-block;
        }
        
        .header-company-name {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 20px;
            width: auto;
        }
        
        .company-name-text {
            font-size: 20px;
            font-weight: bold;
            color: {{ $headerColor }};
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
            line-height: 1.2;
        }
        
        .company-activity {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
            line-height: 1.4;
            max-height: 2.8em;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            word-wrap: break-word;
        }
        
        .header-separator {
            width: 100%;
            height: 2px;
            background-color: {{ $headerColor }};
            margin-top: 10px;
        }
        
        /* CONTENU PRINCIPAL */
        .content-wrapper {
            @if(request()->has('pdf'))
            margin-top: 0;
            padding-top: 0;
            @else
            margin-top: 0;
            @endif
            padding: 0 15px;
        }
        
        .quote-info {
            margin-bottom: 15px;
            font-size: 10px;
            page-break-after: avoid;
        }
        
        .quote-info table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .quote-info td {
            padding: 2px 0;
        }
        
        /* TABLEAUX - Gestion des sauts de page */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        
        .product-table thead {
            display: table-header-group;
        }
        
        .product-table tfoot {
            display: table-footer-group;
        }
        
        .product-table tbody {
            display: table-row-group;
        }
        
        /* Éviter de couper les en-têtes de section */
        .product-header-row {
            page-break-after: avoid;
            page-break-inside: avoid;
        }
        
        /* Éviter de couper les sous-totaux */
        .subtotal-row {
            page-break-before: avoid;
            page-break-inside: avoid;
        }
        
        /* Éviter de couper le total général */
        .total-row {
            page-break-before: avoid;
            page-break-inside: avoid;
        }
        
        /* Éviter de couper une ligne de données seule en fin de page */
        .product-table tbody tr {
            page-break-inside: avoid;
        }
        
        .product-table th {
            background-color: {{ $headerColor }};
            color: {{ $textColor }};
            padding: 6px 4px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid {{ $headerColor }};
        }
        
        .product-table td {
            padding: 4px;
            text-align: center;
            font-size: 9px;
            border: 1px solid #ddd;
        }
        
        .product-table tbody tr {
            background-color: white;
        }
        
        .product-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .product-header {
            background-color: {{ $headerColor }} !important;
            color: {{ $textColor }} !important;
            font-weight: bold;
            text-align: left !important;
            padding: 8px 10px !important;
            page-break-after: avoid;
        }
        
        .subtotal-row {
            background-color: #dbeafe !important;
            font-weight: bold;
        }
        
        .subtotal-row td {
            color: #1e40af;
        }
        
        .total-row {
            background-color: {{ $headerColor }} !important;
            color: {{ $textColor }} !important;
            font-weight: bold;
        }
        
        .total-row td {
            color: {{ $textColor }} !important;
            border-color: {{ $headerColor }} !important;
        }
        
        /* ZONE DE SIGNATURES */
        .signature-section {
            margin-top: 50px;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .signature-container {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-top: 40px;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 20px;
        }
        
        .signature-line {
            width: 100%;
            height: 1px;
            border-bottom: 2px solid #333;
            margin-bottom: 5px;
            min-height: 50px;
        }
        
        .signature-label {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* FOOTER */
        .print-footer {
            position: relative;
            margin-top: 30px;
            padding: 10px 0;
            font-size: 9px;
            border-top: 2px solid {{ $headerColor }};
            width: 100%;
        }
        
        .footer-content {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .footer-info {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 70%;
        }
        
        .footer-info p {
            margin: 0;
            color: #333;
            font-size: 12px;
            line-height: 1.6;
            display: inline-block;
        }
        
        .footer-info p:first-child::after {
            content: " | ";
            margin: 0 10px;
            font-weight: normal;
        }
        
        .footer-pagination {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            color: #333;
            font-weight: bold;
            font-size: 9px;
            width: 30%;
        }
        
        @if(request()->has('pdf'))
        .footer-pagination span {
            display: none;
        }
        @endif
        
        /* Espace pour éviter que le contenu ne chevauche le footer */
        @if(request()->has('pdf'))
        .content-wrapper {
            margin-top: 0;
            padding-top: 0;
        }
        @endif
    </style>
</head>
<body>
    @if(!request()->has('pdf'))
    <!-- BARRE D'OUTILS D'IMPRESSION -->
    <div class="print-toolbar">
        <h3>📄 Aperçu - Devis {{ $quote->quote_number }}</h3>
        <div class="print-toolbar-buttons">
            <button onclick="window.location.href='{{ route('quotes.print', $quote) }}?pdf=1'">
                🖨️ Imprimer
            </button>
            <button onclick="window.location.href='{{ route('quotes.print', $quote) }}?pdf=1&download=1'">
                📥 Télécharger PDF
            </button>
        </div>
    </div>
    
    <div class="document-container">
    @endif
    
    @php
        // Préparer le chemin du logo pour DOMPDF
        $logoUrl = '';
        if (isset($settings) && $settings->logo) {
            if (request()->has('pdf')) {
                // Pour DOMPDF, utiliser le chemin absolu du fichier avec des slashes
                $logoPath = public_path('storage/' . $settings->logo);
                if (file_exists($logoPath)) {
                    // Convertir les backslashes en slashes pour DOMPDF
                    $logoUrl = str_replace('\\', '/', $logoPath);
                }
            } else {
                // Pour l'aperçu HTML, utiliser asset()
                $logoUrl = asset('storage/' . $settings->logo);
            }
        }
    @endphp
    
    <!-- HEADER -->
    <div class="print-header">
        <div class="header-content">
            <!-- Logo à gauche -->
            <div class="logo-container">
                @if(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="Logo" style="max-height: 60px; max-width: 100px; width: auto; height: auto;" onerror="this.style.display='none';">
                @endif
            </div>
            
            <!-- Nom de l'entreprise au centre -->
            <div class="header-company-name">
                <p class="company-name-text">
                    {{ isset($settings) && $settings->company_name ? strtoupper($settings->company_name) : 'SELF BMS' }}
                </p>
                @if(isset($settings) && $settings->activity)
                <p class="company-activity">
                    {{ $settings->activity }}
                </p>
                @endif
            </div>
            
            <!-- Logo à droite -->
            <div class="logo-container">
                @if(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="Logo" style="max-height: 60px; max-width: 100px; width: auto; height: auto;" onerror="this.style.display='none';">
                @endif
            </div>
        </div>
        <div class="header-separator"></div>
    </div>
    
    <!-- INFORMATIONS DEVIS -->
    <div class="content-wrapper">
        <div class="quote-info">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 70%; vertical-align: top; padding: 0;">
                        <p style="font-size: 11px; color: #111827; margin-bottom: 2px; font-weight: bold;">
                            <strong>N°:</strong> {{ $quote->quote_number }}
                        </p>
                        @if($quote->client)
                        <p style="font-size: 11px; color: #111827; margin-bottom: 0; font-weight: bold;">
                            <strong>DEV</strong> {{ strtoupper($quote->client->name) }}
                            @if($quote->client->phone)
                             {{ $quote->client->phone }}
                            @endif
                        </p>
                        @endif
                    </td>
                    <td style="width: 30%; vertical-align: top; text-align: right; padding: 0;">
                        <p style="font-size: 11px; color: #111827; font-weight: bold; margin: 0 0 2px 0;">
                            <strong>Date:</strong> {{ $quote->date->format('d/m/Y') }}
                        </p>
                        @if($quote->valid_until)
                        <p style="font-size: 11px; color: #111827; font-weight: bold; margin: 0;">
                            <strong>Valide jusqu'au:</strong> {{ $quote->valid_until->format('d/m/Y') }}
                        </p>
                        @endif
                    </td>
                </tr>
            </table>
            
            @if($quote->notes)
            <div class="quote-notes" style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #e5e7eb; text-align: center;">
                <p style="font-size: 12px; color: #111827; margin: 0; font-weight: bold; margin-bottom: 8px;">
                    <strong>Notes:</strong>
                </p>
                <p style="font-size: 12px; color: #111827; margin: 0; line-height: 1.6; white-space: pre-wrap; text-align: center;">
                    {{ $quote->notes }}
                </p>
            </div>
            @endif
        </div>
    
        @php
            // Séparer les produits des autres types de lignes
            $productLines = $quote->lines->where('line_type', 'product');
            $otherLines = $quote->lines->whereIn('line_type', ['transport', 'labor', 'material']);
            $groupedLines = $productLines->groupBy('description');
            $grandTotalQuantity = 0;
            $grandTotalSurface = 0;
            $grandTotalAmount = 0;
        @endphp
        
        @foreach($groupedLines as $productName => $lines)
            @php
                $productTotalAmount = $lines->sum(function($line) {
                    return $line->amount ?: $line->subtotal;
                });
                $productTotalQuantity = $lines->sum('quantity');
                $productTotalSurface = $lines->sum('surface');
                $grandTotalQuantity += $productTotalQuantity;
                $grandTotalSurface += $productTotalSurface;
                $grandTotalAmount += $productTotalAmount;
            @endphp
            
            <table class="product-table">
                <thead>
                    <tr class="product-header-row">
                        <th colspan="6" class="product-header">{{ strtoupper($productName) }}</th>
                    </tr>
                    <tr>
                        <th>HAUTEUR</th>
                        <th>LARGEUR</th>
                        <th>QUANTITE</th>
                        <th>PRIX/M²</th>
                        <th>SURFACE</th>
                        <th>MONTANT TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lines as $line)
                    <tr>
                        <td>{{ $line->height ? number_format($line->height, 0, ',', ' ') : '-' }}</td>
                        <td>{{ $line->width ? number_format($line->width, 0, ',', ' ') : '-' }}</td>
                        <td>{{ number_format($line->quantity, 0, ',', ' ') }}</td>
                        <td>{{ $line->price_per_m2 ? number_format($line->price_per_m2, 0, ',', ' ') . ' GNF' : '-' }}</td>
                        <td>{{ $line->surface ? number_format($line->surface, 3, '.', ',') : '-' }}</td>
                        <td>{{ $line->amount ? number_format($line->amount, 0, ',', ' ') . ' GNF' : number_format($line->subtotal, 0, ',', ' ') . ' GNF' }}</td>
                    </tr>
                    @endforeach
                    <tr class="subtotal-row">
                        <td colspan="2"><strong>SOUS-TOTAL</strong></td>
                        <td><strong>{{ number_format($productTotalQuantity, 0, ',', ' ') }}</strong></td>
                        <td><strong>-</strong></td>
                        <td><strong>{{ number_format($productTotalSurface, 3, '.', ',') }}</strong></td>
                        <td><strong>{{ number_format($productTotalAmount, 0, ',', ' ') }} GNF</strong></td>
                    </tr>
                </tbody>
            </table>
        @endforeach
        
        <!-- AUTRES LIGNES (Transport, Main d'œuvre, Matériel) -->
        @if($otherLines->count() > 0)
        <table class="product-table">
            <thead>
                <tr class="product-header-row">
                    <th colspan="6" class="product-header">AUTRES FRAIS</th>
                </tr>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>QUANTITE</th>
                    <th>UNITÉ</th>
                    <th>PRIX UNITAIRE</th>
                    <th colspan="2">MONTANT TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($otherLines as $line)
                @php
                    $lineAmount = $line->amount ?: ($line->quantity * ($line->unit_price ?: 0));
                    $grandTotalAmount += $lineAmount;
                    // Ne pas ajouter la quantité des autres lignes, seulement les produits
                @endphp
                <tr>
                    <td>{{ $line->description }}</td>
                    <td>{{ number_format($line->quantity, 2, ',', ' ') }}</td>
                    <td>{{ $line->unit ?: 'unité' }}</td>
                    <td>{{ $line->unit_price ? number_format($line->unit_price, 0, ',', ' ') . ' GNF' : '-' }}</td>
                    <td colspan="2">{{ number_format($lineAmount, 0, ',', ' ') }} GNF</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
        <!-- TOTAL GÉNÉRAL -->
        <table class="product-table">
            <tfoot>
                @if($quote->status === 'validated' && $quote->final_amount)
                <tr class="total-row">
                    <td><strong>TOTAL CALCULÉ</strong></td>
                    <td></td>
                    <td><strong>{{ number_format($grandTotalQuantity, 0, ',', ' ') }}</strong></td>
                    <td><strong>-</strong></td>
                    <td><strong>{{ number_format($grandTotalSurface, 3, '.', ',') }}</strong></td>
                    <td><strong>{{ number_format($grandTotalAmount, 0, ',', ' ') }} GNF</strong></td>
                </tr>
                <tr class="total-row" style="background-color: #d4edda; border-top: 3px solid #28a745;">
                    <td><strong style="color: #155724;">MONTANT FINAL s'accorder</strong></td>
                    <td></td>
                    <td><strong style="color: #155724;">-</strong></td>
                    <td><strong style="color: #155724;">-</strong></td>
                    <td><strong style="color: #155724;">-</strong></td>
                    <td><strong style="color: #155724; font-size: 1.2em;">{{ number_format($quote->final_amount, 0, ',', ' ') }} GNF</strong></td>
                </tr>
                @else
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    <td></td>
                    <td><strong>{{ number_format($grandTotalQuantity, 0, ',', ' ') }}</strong></td>
                    <td><strong>-</strong></td>
                    <td><strong>{{ number_format($grandTotalSurface, 3, '.', ',') }}</strong></td>
                    <td><strong>{{ number_format($grandTotalAmount, 0, ',', ' ') }} GNF</strong></td>
                </tr>
                @endif
            </tfoot>
        </table>
    </div>
    
    <!-- ZONE DE SIGNATURES -->
    <div class="signature-section">
        <div class="signature-container">
            <div class="signature-box">
                <div class="signature-line"></div>
                <p class="signature-label">GÉRANT</p>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <p class="signature-label">CLIENT</p>
            </div>
        </div>
    </div>
    
    <!-- FOOTER -->
    <div class="print-footer">
        <div class="footer-content">
            <div class="footer-info">
                @if(isset($settings))
                    @if($settings->address)
                        <p><strong>Adresse:</strong> {{ $settings->address }}</p>
                    @endif
                    @if($settings->phone)
                        <p><strong>Téléphone:</strong> {{ $settings->phone }}</p>
                    @endif
                @endif
            </div>
            <div class="footer-pagination">
                @if(request()->has('pdf'))
                    <span id="page-number">Page 1 / 1</span>
                @else
                    <span id="page-info">Page 1</span>
                @endif
            </div>
        </div>
    </div>
    
    @if(!request()->has('pdf'))
    </div>
    @endif
    
    <!-- SCRIPT POUR PAGINATION DOMPDF -->
    @if(request()->has('pdf'))
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("DejaVu Sans", "normal");
                $size = 9;
                $pageWidth = $pdf->get_width();
                $pageHeight = $pdf->get_height();
                
                // Construire le texte avec les variables de page
                $pageNum = (int)$PAGE_NUM;
                $pageCount = (int)$PAGE_COUNT;
                $pageText = "Page " . $pageNum . " / " . $pageCount;
                
                $textWidth = $fontMetrics->get_text_width($pageText, $font, $size);
                $x = $pageWidth - 15 - $textWidth;
                $y = $pageHeight - 35;
                
                // Dessiner le texte directement
                $text = $pageText;
                $pdf->text($x, $y, $text, $font, $size);
            ');
        }
    </script>
    @endif
    
</body>
</html>
