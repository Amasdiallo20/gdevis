<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement - {{ $payment->id }}</title>
    @php
        $settings = \App\Models\Setting::getSettings();
        $headerColor = isset($settings) && $settings->print_header_color ? $settings->print_header_color : '#14b8a6';
        $textColor = isset($settings) && $settings->print_text_color ? $settings->print_text_color : '#ffffff';
        $primaryColor = isset($settings) && $settings->primary_color ? $settings->primary_color : '#14b8a6';
        
        // Logo URL
        $logoUrl = '';
        if (isset($settings) && $settings->logo) {
            if (request()->has('pdf')) {
                $logoPath = storage_path('app/public/' . $settings->logo);
                if (file_exists($logoPath)) {
                    $logoUrl = $logoPath;
                }
            } else {
                $logoUrl = asset('storage/' . $settings->logo);
            }
        }
    @endphp
    <style>
        @page {
            margin: 15mm 20mm 15mm 20mm;
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
            line-height: 1.3;
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
        
        @if(request()->has('pdf'))
        body {
            position: relative;
            min-height: 100vh;
        }
        
        .content-wrapper {
            width: 100%;
            margin: 0;
            padding: 5mm 5mm 80mm 5mm;
            position: relative;
            min-height: calc(100vh - 15mm - 15mm);
        }
        @endif
        
        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: {{ $headerColor }};
            color: {{ $textColor }};
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-toolbar h2 {
            margin: 0;
            font-size: 18px;
        }
        
        .print-toolbar button {
            background: white;
            color: {{ $headerColor }};
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .print-toolbar button:hover {
            opacity: 0.9;
        }
        @endif
        
        .print-header {
            position: relative;
            padding: 12px 10px;
            margin-bottom: 15px;
            width: 100%;
            background: {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#f0f9ff' }};
            page-break-inside: avoid;
            border-radius: 4px;
        }
        
        .header-content {
            display: table;
            width: 100%;
            margin-bottom: 5px;
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
            max-height: 50px;
            max-width: 80px;
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
            font-size: 16px;
            font-weight: bold;
            color: {{ isset($settings) && $settings->print_text_color ? $settings->print_text_color : '#ffffff' }};
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }
        
        .company-activity {
            font-size: 9px;
            color: {{ isset($settings) && $settings->print_text_color ? $settings->print_text_color : '#ffffff' }};
            margin: 3px 0 0 0;
            line-height: 1.2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .header-separator {
            height: 2px;
            background-color: {{ isset($settings) && $settings->print_header_color ? $settings->print_header_color : '#14b8a6' }};
            width: 100%;
            margin-top: 5px;
        }
        
        .receipt-title {
            font-size: 22px;
            font-weight: bold;
            color: {{ $primaryColor }};
            margin: 10px 0 15px 0;
            text-transform: uppercase;
            text-align: center;
            line-height: 1.2;
        }
        
        .receipt-info {
            display: table;
            width: 100%;
            margin: 12px 0;
            padding: 0 5px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 4px 12px 4px 0;
            width: 40%;
            color: #333;
            vertical-align: top;
            text-align: left;
            line-height: 1.3;
        }
        
        .info-value {
            display: table-cell;
            padding: 4px 0;
            color: #555;
            vertical-align: top;
            text-align: left;
            line-height: 1.3;
        }
        
        .amount-section {
            background: #f8f9fa;
            border: 2px solid {{ $primaryColor }};
            border-radius: 8px;
            padding: 15px;
            margin: 15px 5px;
            text-align: center;
        }
        
        .amount-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        
        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: {{ $primaryColor }};
            line-height: 1.2;
        }
        
        .payment-details {
            margin: 12px 0;
            padding: 0 5px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        
        .details-table td {
            padding: 6px 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            line-height: 1.3;
        }
        
        .details-table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #333;
            text-align: left;
        }
        
        .details-table td:last-child {
            color: #555;
            text-align: left;
        }
        
        .signature-section {
            @if(request()->has('pdf'))
            position: absolute;
            bottom: 55mm;
            left: 0;
            right: 0;
            margin-top: 0;
            width: 100%;
            @else
            margin-top: 25px;
            width: calc(100% - 10px);
            @endif
            display: table;
            padding: 0 5px;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 15px;
            vertical-align: top;
        }
        
        .signature-line {
            border-bottom: 2px solid {{ $primaryColor }};
            width: 180px;
            margin: 0 auto 5px;
            height: 35px;
        }
        
        .signature-label {
            font-weight: bold;
            color: #333;
            margin-top: 3px;
            font-size: 10px;
            line-height: 1.2;
        }
        
        .print-footer {
            @if(request()->has('pdf'))
            position: absolute;
            bottom: 15mm;
            left: 0;
            right: 0;
            margin-top: 0;
            @else
            margin-top: 20px;
            @endif
            padding: 10px 5px 0 5px;
            border-top: 2px solid {{ $primaryColor }};
            text-align: center;
            font-size: 9px;
            color: #666;
            line-height: 1.3;
        }
        
        .footer-info {
            margin-bottom: 5px;
        }
        
        .footer-info p {
            margin: 2px 0;
            line-height: 1.3;
        }
    </style>
</head>
<body>
    @if(!request()->has('pdf'))
    <div class="print-toolbar">
        <h2><i class="fas fa-receipt mr-2"></i>Reçu de Paiement</h2>
        <div>
            <a href="{{ route('payments.print', $payment) }}?pdf=1" target="_blank">
                <button>
                    <i class="fas fa-print mr-2"></i>Imprimer
                </button>
            </a>
            <a href="{{ route('payments.print', $payment) }}?pdf=1&download=1">
                <button>
                    <i class="fas fa-download mr-2"></i>Télécharger PDF
                </button>
            </a>
        </div>
    </div>
    <div class="document-container">
    @endif
    
    <div class="content-wrapper">
    <!-- HEADER -->
    <div class="print-header">
        <div class="header-content">
            <!-- Logo à gauche -->
            <div class="logo-container">
                @if(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="Logo" style="max-height: 50px; max-width: 80px; width: auto; height: auto;" onerror="this.style.display='none';">
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
                    <img src="{{ $logoUrl }}" alt="Logo" style="max-height: 50px; max-width: 80px; width: auto; height: auto;" onerror="this.style.display='none';">
                @endif
            </div>
        </div>
        <div class="header-separator"></div>
    </div>
    
    <!-- TITRE DU REÇU -->
    <div style="text-align: center; margin: 10px 0 15px 0; padding: 0 5px;">
        <h1 class="receipt-title">REÇU DE PAIEMENT</h1>
    </div>
    
    <!-- INFORMATIONS DU REÇU -->
    <div class="receipt-info">
        <div class="info-row">
            <div class="info-label">Numéro de reçu :</div>
            <div class="info-value"><strong>REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de paiement :</div>
            <div class="info-value">{{ $payment->payment_date->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Devis N° :</div>
            <div class="info-value"><strong>{{ $payment->quote->quote_number }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Client :</div>
            <div class="info-value"><strong>{{ $payment->quote->client->name }}</strong></div>
        </div>
        @if($payment->quote->client->phone)
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">{{ $payment->quote->client->phone }}</div>
        </div>
        @endif
    </div>
    
    <!-- MONTANT -->
    <div class="amount-section">
        <div class="amount-label">MONTANT PAYÉ</div>
        <div class="amount-value">{{ number_format($payment->amount, 0, ',', ' ') }} GNF</div>
    </div>
    
    <!-- DÉTAILS DU PAIEMENT -->
    <div class="payment-details">
        <table class="details-table">
            <tr>
                <td>Méthode de paiement :</td>
                <td><strong>{{ $payment->payment_method_label }}</strong></td>
            </tr>
            @if($payment->reference)
            <tr>
                <td>Référence :</td>
                <td>{{ $payment->reference }}</td>
            </tr>
            @endif
            <tr>
                <td>Montant total du devis :</td>
                <td><strong>{{ number_format($payment->quote->total, 0, ',', ' ') }} GNF</strong></td>
            </tr>
            <tr>
                <td>Montant déjà payé :</td>
                <td><strong>{{ number_format($payment->quote->paid_amount, 0, ',', ' ') }} GNF</strong></td>
            </tr>
            <tr>
                <td>Solde restant :</td>
                <td><strong>{{ number_format($payment->quote->remaining_amount, 0, ',', ' ') }} GNF</strong></td>
            </tr>
        </table>
    </div>
    
    @if($payment->notes)
    <div style="margin: 12px 5px; padding: 10px; background: #f8f9fa; border-left: 4px solid {{ $primaryColor }}; border-radius: 4px;">
        <strong style="line-height: 1.3;">Notes :</strong>
        <p style="margin-top: 3px; color: #555; text-align: left; line-height: 1.3;">{{ $payment->notes }}</p>
    </div>
    @endif
    
    <!-- SIGNATURES -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p class="signature-label">GÉRANT</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p class="signature-label">CLIENT</p>
        </div>
    </div>
    
    <!-- FOOTER -->
    <div class="print-footer">
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
        <p style="margin-top: 5px; font-size: 8px; color: #999; line-height: 1.3;">
            Reçu généré le {{ now()->format('d/m/Y à H:i') }}
        </p>
    </div>
    </div>
    
    @if(!request()->has('pdf'))
    </div>
    @endif
</body>
</html>

