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
            padding: 12px 10px;
            margin-bottom: 15px;
            width: 100%;
            background: <?php echo e(isset($settings) && $settings->primary_color ? $settings->primary_color : '#f0f9ff'); ?>;
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
            color: <?php echo e(isset($settings) && $settings->print_text_color ? $settings->print_text_color : '#ffffff'); ?>;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }
        
        .company-activity {
            font-size: 9px;
            color: <?php echo e(isset($settings) && $settings->print_text_color ? $settings->print_text_color : '#ffffff'); ?>;
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
            background-color: <?php echo e(isset($settings) && $settings->print_header_color ? $settings->print_header_color : '#14b8a6'); ?>;
            width: 100%;
            margin-top: 5px;
        }
        
        .receipt-title {
            font-size: 22px;
            font-weight: bold;
            color: <?php echo e($primaryColor); ?>;
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
            border: 2px solid <?php echo e($primaryColor); ?>;
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
            color: <?php echo e($primaryColor); ?>;
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
            <?php if(request()->has('pdf')): ?>
            position: absolute;
            bottom: 55mm;
            left: 0;
            right: 0;
            margin-top: 0;
            width: 100%;
            <?php else: ?>
            margin-top: 25px;
            width: calc(100% - 10px);
            <?php endif; ?>
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
            border-bottom: 2px solid <?php echo e($primaryColor); ?>;
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
            <?php if(request()->has('pdf')): ?>
            position: absolute;
            bottom: 15mm;
            left: 0;
            right: 0;
            margin-top: 0;
            <?php else: ?>
            margin-top: 20px;
            <?php endif; ?>
            padding: 10px 5px 0 5px;
            border-top: 2px solid <?php echo e($primaryColor); ?>;
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
                <p class="company-name-text">
                    <?php echo e(isset($settings) && $settings->company_name ? strtoupper($settings->company_name) : 'SELF BMS'); ?>

                </p>
                <?php if(isset($settings) && $settings->activity): ?>
                <p class="company-activity">
                    <?php echo e($settings->activity); ?>

                </p>
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
    <div style="text-align: center; margin: 10px 0 15px 0; padding: 0 5px;">
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
    <div style="margin: 12px 5px; padding: 10px; background: #f8f9fa; border-left: 4px solid <?php echo e($primaryColor); ?>; border-radius: 4px;">
        <strong style="line-height: 1.3;">Notes :</strong>
        <p style="margin-top: 3px; color: #555; text-align: left; line-height: 1.3;"><?php echo e($payment->notes); ?></p>
    </div>
    <?php endif; ?>
    
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
            <?php if(isset($settings)): ?>
                <?php if($settings->address): ?>
                    <p><strong>Adresse:</strong> <?php echo e($settings->address); ?></p>
                <?php endif; ?>
                <?php if($settings->phone): ?>
                    <p><strong>Téléphone:</strong> <?php echo e($settings->phone); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <p style="margin-top: 5px; font-size: 8px; color: #999; line-height: 1.3;">
            Reçu généré le <?php echo e(now()->format('d/m/Y à H:i')); ?>

        </p>
    </div>
    </div>
    
    <?php if(!request()->has('pdf')): ?>
    </div>
    <?php endif; ?>
</body>
</html>

<?php /**PATH C:\xampp\htdocs\gdevis\resources\views/payments/print.blade.php ENDPATH**/ ?>