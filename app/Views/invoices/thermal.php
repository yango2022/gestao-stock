<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Fatura <?= esc($invoice['invoice_number']) ?></title>

    <style>
        @page {
            size: 80mm auto; /* usar 58mm se necessário */
            margin: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
        }

        .small {
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 3px 0;
            font-size: 11px;
        }

        th {
            border-bottom: 1px dashed #000;
            text-align: left;
        }

        .totals td {
            font-weight: bold;
        }

        .print-btn {
            margin: 10px;
            padding: 6px 10px;
            font-size: 12px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="center">

    <?php if (!empty($logoBase64)): ?>
        <img src="<?= $logoBase64 ?>" style="max-width:120px; margin-bottom:5px;">
    <?php endif; ?>

    <strong><?= esc($invoice['company_name']) ?></strong><br>
    NIF: <?= esc($invoice['company_nif']) ?><br>
    <?= esc($invoice['company_address']) ?><br>
    <?= esc($invoice['company_email'] ?? '') ?>

</div>

<div class="line"></div>

<div class="small">
    <div class="row">
        <span>Fatura:</span>
        <span><?= esc($invoice['invoice_number']) ?></span>
    </div>
    <div class="row">
        <span>Data:</span>
        <span><?= date('d/m/Y H:i') ?></span>
    </div>
</div>

<div class="line"></div>

<strong>Cliente</strong><br>
<?= esc($invoice['customer_name']) ?><br>

<?php if ($invoice['customer_nif']): ?>
NIF: <?= esc($invoice['customer_nif']) ?><br>
<?php endif; ?>

<div class="line"></div>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th style="text-align:center">Qtd</th>
            <th style="text-align:right">Total</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= esc($item['description']) ?></td>
            <td style="text-align:center"><?= $item['quantity'] ?></td>
            <td style="text-align:right"><?= number_format($item['total'], 2, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="line"></div>

<table class="totals">
    <tr>
        <td>Subtotal</td>
        <td style="text-align:right"><?= number_format($invoice['subtotal'], 2, ',', '.') ?> Kz</td>
    </tr>

    <?php if ($invoice['discount'] > 0): ?>
    <tr>
        <td>Desconto</td>
        <td style="text-align:right"><?= number_format($invoice['discount'], 2, ',', '.') ?> Kz</td>
    </tr>
    <?php endif; ?>

    <tr>
        <td>Total</td>
        <td style="text-align:right"><?= number_format($invoice['total'], 2, ',', '.') ?> Kz</td>
    </tr>
</table>

<div class="line"></div>

<div class="center small">
    Obrigado pela preferência!<br>
    Documento processado por computador
</div>

<!-- BOTÃO -->
<div class="center no-print">
    <button class="print-btn" onclick="window.print()">🖨️ Imprimir</button>
</div>

</body>
</html>