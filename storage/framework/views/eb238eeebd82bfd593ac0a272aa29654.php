<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement - <?php echo e($payment->id); ?></title>
    <?php
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
    ?>
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
            line-height: 1.3;
            color: #333;
            background: white;
        }
        
        <?php if(!request()->has('pdf')): ?>
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
        
        <?php if(request()->has('pdf')): ?>
        .content-wrapper {
            margin-top: 0;
            padding-top: 0;
        }
        <?php endif; ?>
        
        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: <?php echo e($headerColor); ?>;
            color: <?php echo e($textColor); ?>;
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
            color: <?php echo e($headerColor); ?>;
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
        <?php endif; ?>
        
        .print-header {
            position: relative;
            padding: 20px 15px;
            margin-bottom: 20px;
            width: 100%;
            background: #ffffff;
            page-break-inside: avoid;
            border-radius: 4px;
        }
        
        .header-content {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            table-layout: auto;
        }
        
        .logo-container {
            display: table-cell;
            vertical-align: middle;
            width: 100px;
            padding: 0 10px;
        }
        
        .logo-container:first-child {
            text-align: left;
            padding-left: 0;
        }
        
        .logo-container:last-child {
            text-align: right;
            padding-right: 20px;
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
            min-width: 0;
        }
        
        .company-name-text {
            font-size: 20px;
            font-weight: bold;
            color: <?php echo e($headerColor); ?>;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
            padding: 0;
            line-height: 1.3;
            display: block;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .company-activity {
            font-size: 11px;
            color: #666;
            margin-top: 8px;
            padding: 0;
            line-height: 1.4;
            text-align: center;
            word-wrap: break-word;
            overflow-wrap: break-word;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 6.16em;
        }
        
        .header-separator {
            width: 100%;
            height: 2px;
            background-color: <?php echo e($headerColor); ?>;
            margin-top: 10px;
        }
        
        .receipt-title {
            font-size: 22px;
            font-weight: bold;
            color: <?php echo e($headerColor); ?>;
            margin: 20px 0 25px 0;
            text-transform: uppercase;
            text-align: center;
            line-height: 1.2;
        }
        
        .receipt-info {
            display: table;
            width: 100%;
            margin: 20px 0;
            padding: 0 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px 15px 8px 0;
            width: 40%;
            color: #333;
            vertical-align: top;
            text-align: left;
            line-height: 1.5;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px 0;
            color: #555;
            vertical-align: top;
            text-align: left;
            line-height: 1.5;
        }
        
        .amount-section {
            background: #f8f9fa;
            border: 2px solid <?php echo e($headerColor); ?>;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 15px;
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
            color: <?php echo e($headerColor); ?>;
            line-height: 1.2;
        }
        
        .payment-details {
            margin: 20px 0;
            padding: 0 15px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .details-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            line-height: 1.5;
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
        
        .print-footer {
            position: relative;
            margin-top: 30px;
            padding: 10px 0;
            font-size: 9px;
            border-top: 2px solid <?php echo e($headerColor); ?>;
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
    </style>
</head>
<body>
    <?php if(!request()->has('pdf')): ?>
    <div class="print-toolbar">
        <h2><i class="fas fa-receipt mr-2"></i>Reçu de Paiement</h2>
        <div>
            <a href="<?php echo e(route('payments.print', $payment)); ?>?pdf=1" target="_blank">
                <button>
                    <i class="fas fa-print mr-2"></i>Imprimer
                </button>
            </a>
            <a href="<?php echo e(route('payments.print', $payment)); ?>?pdf=1&download=1">
                <button>
                    <i class="fas fa-download mr-2"></i>Télécharger PDF
                </button>
            </a>
        </div>
    </div>
    <div class="document-container">
    <?php endif; ?>
    
    <div class="content-wrapper">
    <!-- HEADER -->
    <div class="print-header">
        <div class="header-content">
            <!-- Logo à gauche -->
            <div class="logo-container">
                <?php if(!empty($logoUrl)): ?>
                    <img src="<?php echo e($logoUrl); ?>" alt="Logo" style="max-height: 50px; max-width: 80px; width: auto; height: auto;" onerror="this.style.display='none';">
                <?php endif; ?>
            </div>
            
            <!-- Nom de l'entreprise au centre -->
            <div class="header-company-name">
                <div class="company-name-text">
                    <?php echo e(isset($settings) && $settings->company_name ? strtoupper($settings->company_name) : 'SELF BMS'); ?>

                </div>
                <?php if(isset($settings) && $settings->activity): ?>
                <div class="company-activity">
                    <?php echo e($settings->activity); ?>

                </div>
                <?php endif; ?>
            </div>
            
            <!-- Logo à droite -->
            <div class="logo-container">
                <?php if(!empty($logoUrl)): ?>
                    <img src="<?php echo e($logoUrl); ?>" alt="Logo" style="max-height: 50px; max-width: 80px; width: auto; height: auto;" onerror="this.style.display='none';">
                <?php endif; ?>
            </div>
        </div>
        <div class="header-separator"></div>
    </div>
    
    <!-- TITRE DU REÇU -->
    <div style="text-align: center; margin: 20px 0 25px 0; padding: 0 15px;">
        <h1 class="receipt-title">REÇU DE PAIEMENT</h1>
    </div>
    
    <!-- INFORMATIONS DU REÇU -->
    <div class="receipt-info">
        <div class="info-row">
            <div class="info-label">Numéro de reçu :</div>
            <div class="info-value"><strong>REC-<?php echo e(str_pad($payment->id, 6, '0', STR_PAD_LEFT)); ?></strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de paiement :</div>
            <div class="info-value"><?php echo e($payment->payment_date->format('d/m/Y')); ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Devis N° :</div>
            <div class="info-value"><strong><?php echo e($payment->quote->quote_number); ?></strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Client :</div>
            <div class="info-value"><strong><?php echo e($payment->quote->client->name); ?></strong></div>
        </div>
        <?php if($payment->quote->client->phone): ?>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value"><?php echo e($payment->quote->client->phone); ?></div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- MONTANT -->
    <div class="amount-section">
        <div class="amount-label">MONTANT PAYÉ</div>
        <div class="amount-value"><?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> GNF</div>
    </div>
    
    <!-- DÉTAILS DU PAIEMENT -->
    <div class="payment-details">
        <table class="details-table">
            <tr>
                <td>Méthode de paiement :</td>
                <td><strong><?php echo e($payment->payment_method_label); ?></strong></td>
            </tr>
            <?php if($payment->reference): ?>
            <tr>
                <td>Référence :</td>
                <td><?php echo e($payment->reference); ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Montant total du devis :</td>
                <td><strong><?php echo e(number_format($payment->quote->total, 0, ',', ' ')); ?> GNF</strong></td>
            </tr>
            <tr>
                <td>Montant déjà payé :</td>
                <td><strong><?php echo e(number_format($payment->quote->paid_amount, 0, ',', ' ')); ?> GNF</strong></td>
            </tr>
            <tr>
                <td>Solde restant :</td>
                <td><strong><?php echo e(number_format($payment->quote->remaining_amount, 0, ',', ' ')); ?> GNF</strong></td>
            </tr>
        </table>
    </div>
    
    <?php if($payment->notes): ?>
    <div style="margin: 20px 15px; padding: 15px; background: #f8f9fa; border-left: 4px solid <?php echo e($headerColor); ?>; border-radius: 4px;">
        <strong style="line-height: 1.5;">Notes :</strong>
        <p style="margin-top: 8px; color: #555; text-align: left; line-height: 1.5;"><?php echo e($payment->notes); ?></p>
    </div>
    <?php endif; ?>
    
    <!-- SIGNATURES -->
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
                <?php if(isset($settings)): ?>
                    <?php if($settings->address): ?>
                        <p><strong>Adresse:</strong> <?php echo e($settings->address); ?></p>
                    <?php endif; ?>
                    <?php if($settings->phone): ?>
                        <p><strong>Téléphone:</strong> <?php echo e($settings->phone); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="footer-pagination">
                <?php if(request()->has('pdf')): ?>
                    <span id="page-number">Page 1 / 1</span>
                <?php else: ?>
                    <span id="page-info">Page 1</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
    
    <?php if(!request()->has('pdf')): ?>
    </div>
    <?php endif; ?>
</body>
</html>

<?php /**PATH C:\xampp\htdocs\gdevis\resources\views/payments/print.blade.php ENDPATH**/ ?>