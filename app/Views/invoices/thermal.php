<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: Courier, monospace;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 226px; /* 80mm */
            margin: auto;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .small {
            font-size: 10px;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
<div class="container">

    <!-- EMPRESA -->
    <div class="center bold">
        <?= esc($invoice['company_name']) ?>
    </div>

    <div class="center small">
        NIF: <?= esc($invoice['company_nif']) ?><br>
        <?= esc($invoice['company_address']) ?><br>
        <?= esc($invoice['company_email'] ?? '') ?>
    </div>

    <hr>

    <!-- FATURA -->
    <div class="small">
        <strong>FATURA (FT)</strong><br>
        Nº: <?= esc($invoice['invoice_number']) ?><br>
        Data: <?= date('d/m/Y H:i', strtotime($invoice['issued_at'])) ?><br>
        Estado: <?= strtoupper($invoice['status']) ?>
    </div>

    <hr>

    <!-- CLIENTE -->
    <div class="small">
        <strong>Cliente:</strong><br>
        <?= esc($invoice['customer_name']) ?><br>

        <?php if (!empty($invoice['customer_nif'])): ?>
            NIF: <?= esc($invoice['customer_nif']) ?><br>
        <?php endif; ?>
    </div>

    <hr>

    <!-- ITENS -->
    <table>
        <?php foreach ($items as $item): ?>
            <tr>
                <td colspan="2" class="bold">
                    <?= esc($item['description']) ?>
                </td>
            </tr>
            <tr class="small">
                <td>
                    <?= $item['quantity'] ?> x <?= number_format($item['unit_price'], 2, ',', '.') ?>
                </td>
                <td class="right">
                    <?= number_format($item['total'], 2, ',', '.') ?>
                </td>
            </tr>

            <?php if (!empty($item['iva_rate']) && $item['iva_rate'] > 0): ?>
                <tr class="small">
                    <td>
                        IVA (<?= $item['iva_rate'] ?>%)
                    </td>
                    <td class="right">
                        <?= number_format($item['iva_total'], 2, ',', '.') ?>
                    </td>
                </tr>
            <?php else: ?>
                <tr class="small">
                    <td colspan="2">
                        IVA: Isento
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>

    <hr>

    <!-- TOTAIS -->
    <table class="small">
        <tr>
            <td>Subtotal</td>
            <td class="right"><?= number_format($invoice['subtotal'], 2, ',', '.') ?></td>
        </tr>

        <?php if (!empty($invoice['discount']) && $invoice['discount'] > 0): ?>
            <tr>
                <td>Desconto</td>
                <td class="right"><?= number_format($invoice['discount'], 2, ',', '.') ?></td>
            </tr>
        <?php endif; ?>

        <tr>
            <td>IVA</td>
            <td class="right"><?= number_format($invoice['tax'], 2, ',', '.') ?></td>
        </tr>

        <tr class="bold">
            <td>TOTAL</td>
            <td class="right"><?= number_format($invoice['total'], 2, ',', '.') ?> Kz</td>
        </tr>
    </table>

    <hr>

    <!-- RODAPÉ -->
    <div class="center small">
        Documento processado por computador<br>
        Valores expressos em Kz<br><br>
        Obrigado pela preferência
    </div>

</div>
</body>
</html>