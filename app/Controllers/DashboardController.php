<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use CodeIgniter\Controller;

class DashboardController extends BaseController
{
    public function __construct()
    {
        helper('auth');
    }

    /**
     * Página inicial do sistema após login.
     */
    public function index2()
    {

                if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }
        $products     = new ProductModel();
        $sales        = new SaleModel();
        $saleItems    = new SaleItemModel();

        $user = auth()->user();

        // TOTAL DE PRODUTOS
        $totalProducts = $products->countAllResults();

        // VENDAS HOJE
        $today = date('Y-m-d');
        $salesToday = $sales->where("DATE(created_at)", $today)->countAllResults();

        // RECEITA DO DIA
        $todayRevenue = $sales->selectSum('total')
                              ->where("DATE(created_at)", $today)
                              ->first()['total'] ?? 0;

        // STOCK BAIXO
        $lowStock = $products->where('current_stock <= min_stock')
                             ->countAllResults();

        return view('dashboard', [
            'totalProducts' => $totalProducts,
            'salesToday'    => $salesToday,
            'todayRevenue'  => $todayRevenue,
            'lowStock'      => $lowStock,
            'user'  => $user,
        ]);
    }

    public function index()
    {
        $products  = new ProductModel();
        $sales     = new SaleModel();
        $saleItems = new SaleItemModel();
        $user = auth()->user();

        // TOTAL PRODUTOS
        $totalProducts = $products->countAllResults();

        // VENDAS HOJE
        $today = date('Y-m-d');
        $salesToday = $sales->where("DATE(created_at)", $today)->countAllResults();

        // RECEITA DO DIA
        $todayRevenue = $sales->selectSum('total')
                              ->where("DATE(created_at)", $today)
                              ->first()['total'] ?? 0;

        // STOCK BAIXO
        $lowStock = $products->where('current_stock <= min_stock')->countAllResults();


        // ======================
        // 📊 VENDAS NOS ÚLTIMOS 7 DIAS
        // ======================
        $last7days = $sales->select("DATE(created_at) as day, COUNT(*) as total")
                           ->where("created_at >=", date('Y-m-d', strtotime('-6 days')))
                           ->groupBy("DATE(created_at)")
                           ->orderBy("day")
                           ->findAll();

        $days   = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $days[] = date('d/m', strtotime($d));

            $found = array_filter($last7days, fn ($x) => $x['day'] === $d);
            $totals[] = $found ? array_values($found)[0]['total'] : 0;
        }


        // ======================
        // 📊 TOP 5 PRODUTOS MAIS VENDIDOS
        // ======================
        $topProducts = $saleItems->select('products.name, SUM(sale_items.quantity) as qty')
                                 ->join('products', 'products.id = sale_items.product_id')
                                 ->groupBy('products.name')
                                 ->orderBy('qty', 'DESC')
                                 ->limit(5)
                                 ->findAll();

        $productNames = array_column($topProducts, 'name');
        $productQty   = array_column($topProducts, 'qty');


        // ==============================
        // 📊 RECEITA MENSAL (12 MESES)
        // ==============================
        $monthlySales = $sales->select("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(total) as revenue
            ")
            ->where('created_at >=', date('Y-m-01', strtotime('-11 months')))
            ->groupBy('month')
            ->orderBy('month')
            ->findAll();

        // Preparar arrays para o gráfico
        $months = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $months[] = date('M/Y', strtotime($m));

            $found = array_filter($monthlySales, fn ($x) => $x['month'] === $m);
            $revenues[] = $found ? array_values($found)[0]['revenue'] : 0;
        }


        // ==============================
        // 📊 MOVIMENTOS POR FORMA DE PAGAMENTO
        // ==============================
        $paymentStats = $sales->select('payment_method, COUNT(*) as total')
                            ->groupBy('payment_method')
                            ->orderBy('total', 'DESC')
                            ->findAll();

        $payLabels = array_column($paymentStats, 'payment_method');
        $payValues = array_column($paymentStats, 'total');


        // ===============================================
        // 📊 CUSTO VS RECEITA (últimos 12 meses)
        // ===============================================
        $db = \Config\Database::connect();

        // Receita mensal (sales)
        $revenueQuery = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total) AS revenue
            FROM sales
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY month
        ")->getResultArray();

        // Custo mensal (stock_entries)
        $costQuery = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_cost) AS cost
            FROM stock_entries
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY month
        ")->getResultArray();

        // Meses formatados
        $months = [];
        $revenues = [];
        $costs = [];

        for ($i = 11; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $months[] = date('M/Y', strtotime($m));

            // encontrar receita do mês
            $rev = array_filter($revenueQuery, fn($x) => $x['month'] === $m);
            $revenues[] = $rev ? array_values($rev)[0]['revenue'] : 0;

            // encontrar custo do mês
            $ct = array_filter($costQuery, fn($x) => $x['month'] === $m);
            $costs[] = $ct ? array_values($ct)[0]['cost'] : 0;
        }

        // enviar para a view




        return view('dashboard', [

            // cards normais...
            'totalProducts' => $totalProducts,
            'salesToday' => $salesToday,
            'todayRevenue' => $todayRevenue,
            'lowStock' => $lowStock,

            'chart_days' => json_encode($days),
            'chart_totals' => json_encode($totals),
            'top_names' => json_encode($productNames),
            'top_qty' => json_encode($productQty),


            'months'    => json_encode($months),
            'revenues'  => json_encode($revenues),
            'pay_labels' => json_encode($payLabels),
            'pay_values' => json_encode($payValues),
            'user'  => $user,

            'cv_months' => json_encode($months),
            'cv_revenues' => json_encode($revenues),
            'cv_costs' => json_encode($costs),

        ]);
 
    }
}