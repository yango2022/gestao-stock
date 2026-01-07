<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\InvoiceSequenceModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\CompanyModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoicesController extends BaseController
{
    protected $invoice;
    protected $invoiceItems;
    protected $sequence;
    protected $sales;
    protected $saleItems;
    protected $user;
    protected $companyId;
    protected $companies;

    public function __construct()
    {
        $this->invoice      = new InvoiceModel();
        $this->invoiceItems = new InvoiceItemModel();
        $this->sequence     = new InvoiceSequenceModel();
        $this->sales        = new SaleModel();
        $this->saleItems    = new SaleItemModel();
        $this->companies     = new CompanyModel();

        $this->user      = auth()->user();
        $this->companyId = $this->user->company_id;
    }

    //LISTAR FATURAS DA EMPRESA
    public function index(int $saleId)
    {
        $sale = $this->sales
            ->where('id', $saleId)
            ->where('company_id', $this->companyId)
            ->first();

        return view('invoices/show', $sale);
    }

    //GERAR FATURA A PARTIR DE UMA VENDA
    public function generateFromSale(int $saleId)
    {

            // Venda da empresa
            $sale = $this->sales
                ->where('id', $saleId)
                ->where('company_id', $this->companyId)
                ->first();

            if (!$sale) {
                return redirect()->back()->with('error', 'Venda não encontrada.');
            }

            // Evitar duplicação
            $invoice = $this->invoice
                ->where('sale_id', $saleId)
                ->where('company_id', $this->companyId)
                ->first();

            if ($invoice && !empty($invoice['pdf_path'])) {
                return redirect()->to(site_url('invoices/download/' . $invoice['id']));
            }

            // Número da fatura
            $invoiceNumber = $this->sequence->nextNumber($this->companyId);

            $company = $this->companies
                ->where('id', $this->companyId)
                ->first();

            if (!$company) {
                return redirect()->back()->with('error', 'Empresa não encontrada.');
            }

            // Criar fatura
            $invoiceId = $this->invoice->insert([
                'company_id'        => $this->companyId,
                'sale_id'           => $sale['id'],
                'invoice_number'    => $invoiceNumber,
                'invoice_type'      => 'FT',

                // Empresa (snapshot)
                'company_name'      => $company['name'],
                'company_nif'       => $company['nif'],
                'company_address'   => $company['address'],
                'company_email'     => $company['email'] ?? null,
                'company_logo'      => $company['logo'] ?? null,

                // Cliente
                'customer_name'     => $sale['customer_name'],
                'customer_nif'      => $sale['customer_nif'],
                'customer_phone'    => $sale['customer_phone'],
                'customer_email'    => $sale['customer_email'],
                'customer_address'  => $sale['customer_address'],

                // Valores
                'subtotal'          => $sale['subtotal'],
                'discount'          => $sale['discount'] ?? 0,
                'tax'               => 0,
                'total'             => $sale['total'],

                'status'            => 'emitida',
                'issued_at'         => date('Y-m-d H:i:s'),
            ]);

            // Itens da fatura
            $items = $this->saleItems
                ->where('sale_id', $sale['id'])
                ->findAll();

            foreach ($items as $item) {
                $this->invoiceItems->insert([
                    'invoice_id' => $invoiceId,
                    'description'=> $item['product_name'] ?? 'Produto',
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total'      => $item['total'],
                ]);
            }

            //BUSCAR FATURA COMPLETA
            $invoice = $this->invoice->find($invoiceId);

            //LOGO EM BASE64 (AQUI É O PONTO CRÍTICO)
            $logoBase64 = null;

            if (!empty($invoice['company_logo'])) {
                $logoPath = FCPATH . 'uploads/companies/' . $invoice['company_logo'];

                if (file_exists($logoPath)) {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }

            //GERAR HTML
            $html = view('invoices/show', [
                'invoice'    => $invoice,
                'items'      => $this->invoiceItems->where('invoice_id', $invoiceId)->findAll(),
                'logoBase64' => $logoBase64,
            ]);

            // GERAR PDF
            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Diretório
            $dir = WRITEPATH . 'uploads/invoices/' . $this->companyId;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            // Nome do ficheiro
            $fileName = $invoiceNumber . '.pdf';
            $filePath = $dir . '/' . $fileName;

            file_put_contents($filePath, $dompdf->output());

            // Guardar caminho no banco
            $pdfPath = 'uploads/invoices/' . $this->companyId . '/' . $fileName;

            $this->invoice
            ->where('id', $invoiceId)
                ->set(['pdf_path' => $pdfPath])
                ->update();

            return redirect()->to(site_url('invoices/download/' . $invoiceId))
                ->with('success', 'Fatura gerada com sucesso.');
    }

    public function printThermal($id)
    {
        $invoice = $this->invoice
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (! $invoice) {
            return redirect()->back()->with('error', 'Fatura não encontrada.');
        }

        $items = $this->invoiceItems
            ->where('invoice_id', $id)
            ->findAll();

        // Logo em Base64
        $logoBase64 = null;
        if (!empty($invoice['company_logo'])) {
            $path = FCPATH . 'uploads/companies/' . $invoice['company_logo'];
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $logoBase64 = 'data:image/'.$type.';base64,'.base64_encode(file_get_contents($path));
            }
        }

        return view('invoices/thermal', [
            'invoice'    => $invoice,
            'items'      => $items,
            'logoBase64' => $logoBase64,
        ]);
    }


    public function download(int $id)
    {
        $invoice = $this->invoice
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (!$invoice || empty($invoice['pdf_path'])) {
            return redirect()->back()->with('error', 'PDF não encontrado.');
        }

        $file = WRITEPATH . $invoice['pdf_path'];

        return $this->response->download($file, null);
    }

    //VISUALIZAR FATURA
    public function show2($id)
    {
        $invoiceModel = new InvoiceModel();
        $itemModel    = new InvoiceItemModel();

        $invoice = $invoiceModel->find($id);
        $items   = $itemModel->where('invoice_id', $id)->findAll();

        return view('invoices/show', [
            'invoice' => $invoice,
            'items'   => $items
        ]);
    }

    //CANCELAR / ANULAR FATURA
    public function cancel(int $id)
    {
        $invoice = $this->invoice
            ->where('id', $id)
            ->where('company_id', $this->companyId)
            ->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'Fatura não encontrada.');
        }

        if ($invoice->status === 'anulada') {
            return redirect()->back()->with('warning', 'Esta fatura já está anulada.');
        }

        $this->invoice->update($id, [
            'status' => 'anulada',
        ]);

        return redirect()
            ->to('/invoices/' . $id)
            ->with('success', 'Fatura anulada com sucesso.');
    }

    public function pdf($id)
    {
        $invoiceModel = new InvoiceModel();
        $itemModel    = new InvoiceItemModel();

        //Buscar fatura (idealmente filtrar por company_id)
        $invoice = $invoiceModel->find($id);

        if (!$invoice) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Fatura não encontrada');
        }

        $items = $itemModel
            ->where('invoice_id', $id)
            ->findAll();

        //Gerar HTML
        $html = view('invoices/show', [
            'invoice' => $invoice,
            'items'   => $items
        ]);

        //Configuração Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        //Download (true = força download)
        return $this->response
            ->setContentType('application/pdf')
            ->setBody($dompdf->output());
    }

}