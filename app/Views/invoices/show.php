<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Fatura <?= esc($invoice['invoice_number']) ?></title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            width: 100%;
            margin: auto;
        }

        .header, .footer {
            width: 100%;
            margin-bottom: 20px;
        }

        .company, .invoice-info {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        .invoice-info {
            text-align: right;
        }

        h1 {
            margin: 0;
            font-size: 22px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        table th {
            background: #f5f5f5;
        }

        .totals {
            width: 40%;
            float: right;
            margin-top: 20px;
        }

        .totals td {
            border: none;
            padding: 6px;
        }

        .right {
            text-align: right;
        }

        .status {
            padding: 5px 10px;
            display: inline-block;
            font-size: 11px;
            background: #d1e7dd;
            color: #0f5132;
        }

        .footer {
            font-size: 11px;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>

<body>
<div class="container">

    <!-- CABEÇALHO -->
    <div class="header">
        <div class="company">
            <h1>FATURA</h1>
            <p>
                <strong><?= esc(auth()->user()->company_name ?? 'Empresa') ?></strong><br>
                NIF: <?= esc(auth()->user()->company_nif ?? '---') ?><br>
                <?= esc(auth()->user()->company_address ?? '') ?>
            </p>
        </div>

        <div class="invoice-info">
            <p>
                <strong>Nº Factura:</strong> <?= esc($invoice['invoice_number']) ?><br>
                <strong>Data:</strong> <?= date('d/m/Y', strtotime($invoice['created_at'])) ?><br>
                <span class="status">PROCESSADA</span>
            </p>
        </div>
    </div>

    <!-- CLIENTE -->
    <p>
        <strong>Cliente:</strong><br>
        <?= esc($invoice['customer_name']) ?>
    </p>

    <!-- ITENS -->
    <table>
        <thead>
        <tr>
            <th>Descrição</th>
            <th class="right">Qtd</th>
            <th class="right">Preço Unit.</th>
            <th class="right">Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= esc($item['product_name'] ?? '') ?></td>
                <td class="right"><?= $item['quantity'] ?></td>
                <td class="right"><?= number_format($item['unit_price'], 2, ',', '.') ?></td>
                <td class="right"><?= number_format($item['total'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- TOTAIS -->
    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right"><?= number_format($invoice['subtotal'], 2, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Desconto</td>
            <td class="right"><?= number_format($invoice['discount'], 2, ',', '.') ?></td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td class="right">
                <strong><?= number_format($invoice['total'], 2, ',', '.') ?></strong>
            </td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <!-- RODAPÉ -->
    <div class="footer">
        Documento processado por computador • Não requer assinatura
    </div>

</div>
</body>
</html>