<?php

namespace App\Controllers\Master;

use App\Controllers\Master\BaseMasterController;
use App\Models\CompanyModel;
use App\Models\UserModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;
use App\Models\CashFlowModel;
use CodeIgniter\Database\ConnectionInterface;

class DashboardController extends BaseMasterController
{
    public function index()
    {
        if (!is_superadmin()) {
            return redirect()->to('/dashboard');
        }

        $companyModel  = new CompanyModel();
        $userModel     = new UserModel();
        $saleModel     = new SaleModel();
        $saleItemModel = new SaleItemModel();
        $productModel  = new ProductModel();
        $cashFlowModel = new CashFlowModel();

        $db = \Config\Database::connect();

        // =========================
        // TOTALS
        // =========================
        $totalCompanies = $companyModel->countAll();
        $totalUsers     = $userModel->countAll();
        $totalSales     = $saleModel->countAll();
        $totalProducts  = $productModel->countAll();

        // =========================
        // VENDAS ÚLTIMOS 7 DIAS
        // =========================
        $last7days = $saleModel->select("DATE(created_at) as day, COUNT(*) as total")
            ->where("created_at >=", date('Y-m-d', strtotime('-6 days')))
            ->groupBy("DATE(created_at)")
            ->orderBy("day")
            ->findAll();

        $days   = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $days[] = date('d/m', strtotime($d));

            $found = array_filter($last7days, fn($x) => $x['day'] === $d);
            $totals[] = $found ? array_values($found)[0]['total'] : 0;
        }

        // =========================
        // TOP 5 PRODUTOS MAIS VENDIDOS
        // =========================
        $topProducts = $saleItemModel->select('products.name, SUM(sale_items.quantity) as qty')
            ->join('products', 'products.id = sale_items.product_id')
            ->groupBy('products.name')
            ->orderBy('qty', 'DESC')
            ->limit(5)
            ->findAll();

        $top_names = array_column($topProducts, 'name');
        $top_qty   = array_column($topProducts, 'qty');

        // =========================
        // RECEITA MENSAL (12 MESES)
        // =========================
        $monthlySales = $saleModel->select("
            DATE_FORMAT(created_at, '%Y-%m') as month,
            SUM(total) as revenue
        ")
        ->where('created_at >=', date('Y-m-01', strtotime('-11 months')))
        ->groupBy('month')
        ->orderBy('month')
        ->findAll();

        $months    = [];
        $revenues  = [];

        for ($i = 11; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $months[] = date('M/Y', strtotime($m));

            $found = array_filter($monthlySales, fn($x) => $x['month'] === $m);
            $revenues[] = $found ? array_values($found)[0]['revenue'] : 0;
        }

        // =========================
        // FLUXO DE CAIXA
        // =========================
        $cashFlow = $db->query("
            SELECT 
                DATE(created_at) AS dia,
                SUM(CASE WHEN type = 'entrada' THEN amount ELSE 0 END) AS entradas,
                SUM(CASE WHEN type = 'saida' THEN amount ELSE 0 END) AS saidas
            FROM cash_flow
            GROUP BY DATE(created_at)
            ORDER BY dia ASC
        ")->getResultArray();

        $dias     = [];
        $entradas = [];
        $saidas   = [];
        $saldo    = [];

        foreach ($cashFlow as $row) {
            $dias[]     = $row['dia'];
            $entradas[] = (float) $row['entradas'];
            $saidas[]   = (float) $row['saidas'];
            $saldo[]    = (float) $row['entradas'] - (float) $row['saidas'];
        }

        return view('master/dashboard', [
            'totalCompanies' => $totalCompanies,
            'totalUsers'     => $totalUsers,
            'totalSales'     => $totalSales,
            'totalProducts'  => $totalProducts,

            'chart_days'  => json_encode($days),
            'chart_totals'=> json_encode($totals),
            'top_names'   => json_encode($top_names),
            'top_qty'     => json_encode($top_qty),

            'months'      => json_encode($months),
            'revenues'    => json_encode($revenues),

            'dias'        => json_encode($dias),
            'entradas'    => json_encode($entradas),
            'saidas'      => json_encode($saidas),
            'saldo'       => json_encode($saldo),
        ]);
    }
}