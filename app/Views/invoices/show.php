<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Fatura <?= esc($invoice['invoice_number']) ?></title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .container {
            width: 100%;
        }

        .row {
            width: 100%;
            margin-bottom: 15px;
        }

        .left, .right {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        h1 {
            font-size: 22px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
            text-align: left;
        }

        .totals {
            width: 40%;
            float: right;
            margin-top: 15px;
        }

        .totals td {
            border: none;
            padding: 4px;
        }

        .footer {
            font-size: 10px;
            margin-top: 40px;
            text-align: center;
        }

        .status {
            font-size: 11px;
            padding: 3px 6px;
            border: 1px solid #000;
        }
    </style>
</head>

<body>
<div class="container">

    <!-- CABEÇALHO + CLIENTE EM COLUNAS -->
    <div class="row">
        <!-- ESQUERDA: EMPRESA -->
        <div class="left">
            <?php if (!empty($logoBase64)): ?>
                <img src="<?= $logoBase64 ?>" style="max-height:80px; margin-bottom:10px;">
            <?php endif; ?>

            <h1>FATURA (FT)</h1>
            <p>
                <strong><?= esc($invoice['company_name']) ?></strong><br>
                NIF: <?= esc($invoice['company_nif']) ?><br>
                <?= esc($invoice['company_address']) ?><br>
                <?= esc($invoice['company_email'] ?? '') ?>
            </p>
        </div>

        <!-- DIREITA: CLIENTE -->
        <div class="right">

            <div class="">
                <p>
                    <strong>Nº:</strong> <?= esc($invoice['invoice_number']) ?><br>
                    <strong>Data:</strong> <?= date('d/m/Y', strtotime($invoice['issued_at'])) ?><br>
                    <span class="status"><?= strtoupper($invoice['status']) ?></span>
                </p>
            </div>

            <strong>Cliente:</strong><br>
            <?= esc($invoice['customer_name']) ?><br>

            <?php if (!empty($invoice['customer_nif'])): ?>
                NIF: <?= esc($invoice['customer_nif']) ?><br>
            <?php endif; ?>

            <?php if (!empty($invoice['customer_phone'])): ?>
                Tel: <?= esc($invoice['customer_phone']) ?><br>
            <?php endif; ?>

            <?php if (!empty($invoice['customer_email'])): ?>
                Email: <?= esc($invoice['customer_email']) ?><br>
            <?php endif; ?>

            <?php if (!empty($invoice['customer_address'])): ?>
                Endereço: <?= esc($invoice['customer_address']) ?>
            <?php endif; ?>
        </div>
    </div>



    <!-- ITENS -->
    <table>
        <thead>
        <tr>
            <th>Descrição</th>
            <th>Qtd</th>
            <th>Preço Unit. (Kz)</th>
            <th>Total (Kz)</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= esc($item['description']) ?></td>
                <td><?= esc($item['quantity']) ?></td>
                <td><?= number_format($item['unit_price'], 2, ',', '.') ?></td>
                <td><?= number_format($item['total'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- TOTAIS -->
    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right"><?= number_format($invoice['subtotal'], 2, ',', '.') ?> Kz</td>
        </tr>

        <?php if (!empty($invoice['discount'])): ?>
        <tr>
            <td>Desconto</td>
            <td class="right"><?= number_format($invoice['discount'], 2, ',', '.') ?> Kz</td>
        </tr>
        <?php endif; ?>

        <tr>
            <td>IVA</td>
            <td class="right"><?= number_format($invoice['tax'], 2, ',', '.') ?> Kz</td>
        </tr>

        <tr>
            <td><strong>Total</strong></td>
            <td class="right"><strong><?= number_format($invoice['total'], 2, ',', '.') ?> Kz</strong></td>
        </tr>
    </table>

    <div style="clear: both"></div>

    <!-- RODAPÉ LEGAL -->
    <div class="footer">
        Documento processado por computador nos termos da lei em vigor.<br>
        Valores expressos em Kwanzas (Kz).
    </div>

</div>
</body>
</html>