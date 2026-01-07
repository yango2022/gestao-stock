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

    public function index()
    {
        $user = auth()->user();

        if (! $user || empty($user->company_id)) {
            return redirect()->to('/logout');
        }

        $companyId = $user->company_id;

        $products  = new ProductModel();
        $sales     = new SaleModel();
        $saleItems = new SaleItemModel();

        $db = \Config\Database::connect();

        // =====================================================
        // 📦 TOTAL DE PRODUTOS
        // =====================================================
        $totalProducts = $products
            ->where('company_id', $companyId)
            ->countAllResults();

        // =====================================================
        // 🧾 VENDAS DE HOJE
        // =====================================================
        $today = date('Y-m-d');

        $salesToday = $sales
            ->where('company_id', $companyId)
            ->where("DATE(created_at)", $today)
            ->countAllResults();

        // =====================================================
        // 💰 RECEITA DO DIA
        // =====================================================
        $todayRevenue = $sales
            ->selectSum('total')
            ->where('company_id', $companyId)
            ->where("DATE(created_at)", $today)
            ->first()['total'] ?? 0;

        // =====================================================
        // ⚠️ STOCK BAIXO
        // =====================================================
        $lowStock = $products
            ->where('company_id', $companyId)
            ->where('current_stock <= min_stock')
            ->countAllResults();

        // =====================================================
        // 📊 VENDAS DOS ÚLTIMOS 7 DIAS
        // =====================================================
        $last7days = $sales
            ->select("DATE(created_at) as day, COUNT(*) as total")
            ->where('company_id', $companyId)
            ->where("created_at >=", date('Y-m-d', strtotime('-6 days')))
            ->groupBy("DATE(created_at)")
            ->orderBy("day")
            ->findAll();

        $days   = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $days[] = date('d/m', strtotime($date));

            $found = array_filter($last7days, fn ($x) => $x['day'] === $date);
            $totals[] = $found ? array_values($found)[0]['total'] : 0;
        }

        // =====================================================
        // 🥇 TOP 5 PRODUTOS MAIS VENDIDOS
        // =====================================================
        $topProducts = $saleItems
            ->select('products.name, SUM(sale_items.quantity) as qty')
            ->join('products', 'products.id = sale_items.product_id')
            ->where('products.company_id', $companyId)
            ->groupBy('products.name')
            ->orderBy('qty', 'DESC')
            ->limit(5)
            ->findAll();

        $productNames = array_column($topProducts, 'name');
        $productQty   = array_column($topProducts, 'qty');

        // =====================================================
        // 📈 RECEITA MENSAL (12 MESES)
        // =====================================================
        $revenueQuery = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total) AS revenue
            FROM sales
            WHERE company_id = ?
              AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY month
        ", [$companyId])->getResultArray();

        $months   = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $months[] = date('M/Y', strtotime($m));

            $found = array_filter($revenueQuery, fn ($x) => $x['month'] === $m);
            $revenues[] = $found ? $found[array_key_first($found)]['revenue'] : 0;
        }

        // =====================================================
        // 💳 VENDAS POR MÉTODO DE PAGAMENTO
        // =====================================================
        $paymentStats = $sales
            ->select('payment_method, COUNT(*) as total')
            ->where('company_id', $companyId)
            ->groupBy('payment_method')
            ->orderBy('total', 'DESC')
            ->findAll();

        $payLabels = array_column($paymentStats, 'payment_method');
        $payValues = array_column($paymentStats, 'total');

        // =====================================================
        // 💸 CUSTO VS RECEITA (12 MESES)
        // =====================================================
        $costQuery = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_cost) AS cost
            FROM stock_entries
            WHERE company_id = ?
              AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY month
        ", [$companyId])->getResultArray();

        $costs = [];

        foreach ($months as $index => $label) {
            $m = date('Y-m', strtotime("-" . (11 - $index) . " months"));
            $found = array_filter($costQuery, fn ($x) => $x['month'] === $m);
            $costs[] = $found ? $found[array_key_first($found)]['cost'] : 0;
        }

        // =====================================================
        // 💼 FLUXO DE CAIXA
        // =====================================================
        $cashFlow = $db->query("
            SELECT 
                DATE(created_at) AS dia,
                SUM(CASE WHEN type = 'entrada' THEN amount ELSE 0 END) AS entradas,
                SUM(CASE WHEN type = 'saida' THEN amount ELSE 0 END) AS saidas
            FROM cash_flow
            WHERE company_id = ?
            GROUP BY DATE(created_at)
            ORDER BY dia ASC
        ", [$companyId])->getResultArray();

        $dias = $entradas = $saidas = $saldo = [];

        foreach ($cashFlow as $row) {
            $dias[]     = $row['dia'];
            $entradas[] = (float) $row['entradas'];
            $saidas[]   = (float) $row['saidas'];
            $saldo[]    = (float) $row['entradas'] - (float) $row['saidas'];
        }

        // =====================================================
        // 📤 VIEW
        // =====================================================
        return view('dashboard', [
            'user' => $user,

            // Cards
            'totalProducts' => $totalProducts,
            'salesToday'    => $salesToday,
            'todayRevenue'  => $todayRevenue,
            'lowStock'      => $lowStock,

            // Gráficos
            'chart_days'    => json_encode($days),
            'chart_totals'  => json_encode($totals),
            'top_names'     => json_encode($productNames),
            'top_qty'       => json_encode($productQty),

            'months'        => json_encode($months),
            'revenues'      => json_encode($revenues),

            'pay_labels'    => json_encode($payLabels),
            'pay_values'    => json_encode($payValues),

            'cv_months'     => json_encode($months),
            'cv_revenues'   => json_encode($revenues),
            'cv_costs'      => json_encode($costs),

            'dias'          => json_encode($dias),
            'entradas'      => json_encode($entradas),
            'saidas'        => json_encode($saidas),
            'saldo'         => json_encode($saldo),
        ]);
    }
}