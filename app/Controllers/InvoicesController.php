<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\InvoiceSequenceModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
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

    public function __construct()
    {
        $this->invoice      = new InvoiceModel();
        $this->invoiceItems = new InvoiceItemModel();
        $this->sequence     = new InvoiceSequenceModel();
        $this->sales        = new SaleModel();
        $this->saleItems    = new SaleItemModel();

        $this->user      = auth()->user();
        $this->companyId = $this->user->company_id;
    }

    //LISTAR FATURAS DA EMPRESA
    public function index()
    {
        $data['invoices'] = $this->invoice
            ->byCompany($this->companyId)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('invoices/index', $data);
    }

    //GERAR FATURA A PARTIR DE UMA VENDA
    public function generateFromSale(int $saleId)
    {
        //Venda da empresa
        $sale = $this->sales
            ->where('id', $saleId)
            ->where('company_id', $this->companyId)
            ->first();

        if (!$sale) {
            return redirect()->back()->with('error', 'Venda não encontrada.');
        }

        //Evitar duplicação
        $invoice = $this->invoice
            ->where('sale_id', $saleId)
            ->where('company_id', $this->companyId)
            ->first();

        if ($invoice && !empty($invoice['pdf_path'])) {
            return redirect()->to(site_url('invoices/download/' . $invoice['id']));
        }

        //Número da fatura
        $invoiceNumber = $this->sequence->nextNumber($this->companyId);

        //Criar fatura
        $invoiceId = $this->invoice->insert([
            'company_id'     => $this->companyId,
            'sale_id'        => $sale['id'],
            'invoice_number' => $invoiceNumber,
            'invoice_type'   => 'FT',
            'customer_name'  => $sale['customer_name'],
            'subtotal'       => $sale['subtotal'],
            'discount'       => $sale['discount'] ?? 0,
            'tax'            => 0,
            'total'          => $sale['total'],
            'status'         => 'emitida',
            'issued_at'      => date('Y-m-d H:i:s'),
        ]);

        //Itens
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

        //GERAR PDF
        $html = view('invoices/show', [
            'invoice' => $this->invoice->find($invoiceId),
            'items'   => $this->invoiceItems->where('invoice_id', $invoiceId)->findAll(),
        ]);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        //Diretório
        $dir = WRITEPATH . 'uploads/invoices/' . $this->companyId;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        //Nome do ficheiro
        $fileName = $invoiceNumber . '.pdf';
        $filePath = $dir . '/' . $fileName;

        file_put_contents($filePath, $dompdf->output());

        //Guardar caminho no banco
        $pdfPath = 'uploads/invoices/' . $this->companyId . '/' . $fileName;

        $this->invoice->where('id', $invoiceId)
              ->set([
                  'pdf_path' => $pdfPath
              ])
              ->update();


        return redirect()->to(site_url('invoices/download/' . $invoiceId))
            ->with('success', 'Fatura gerada com sucesso.');
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

    public function show(int $id)
    {
        $invoice = $this->invoice
            ->select('invoices.*, companies.name AS company_name, companies.nif AS company_nif, companies.address AS company_address, companies.email AS company_email')
            ->join('companies', 'companies.id = invoices.company_id')
            ->where('invoices.id', $id)
            ->where('invoices.company_id', $this->companyId)
            ->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'Fatura não encontrada.');
        }

        $items = $this->invoiceItems
            ->where('invoice_id', $invoice['id'])
            ->findAll();

        return view('invoices/show', [
            'invoice' => (object) $invoice,
            'items'   => $items,
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