<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Thống kê chỉ số cơ bản
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::whereIn('role', ['customer', 'user'])->count();

        // Tự động kiểm tra tên cột tổng tiền trong bảng orders
        $priceColumn = 'total';
        if (Schema::hasColumn('orders', 'total_amount')) {
            $priceColumn = 'total_amount';
        } elseif (Schema::hasColumn('orders', 'grand_total')) {
            $priceColumn = 'grand_total';
        } elseif (Schema::hasColumn('orders', 'total_price')) {
            $priceColumn = 'total_price';
        } elseif (Schema::hasColumn('orders', 'total')) {
            $priceColumn = 'total';
        }

        // Tự động kiểm tra cột trạng thái (payment_status hoặc status)
        $statusColumn = Schema::hasColumn('orders', 'payment_status') ? 'payment_status' : 'status';

        // Tính tổng doanh thu
        try {
            $totalRevenue = Order::where($statusColumn, 'completed')->sum($priceColumn) ?? 0;
            if ($totalRevenue == 0) {
                $totalRevenue = Order::sum($priceColumn) ?? 0;
            }
        } catch (\Throwable $e) {
            $totalRevenue = Order::sum($priceColumn) ?? 0;
        }

        // 2. Dữ liệu biểu đồ doanh thu 7 ngày gần nhất
        try {
            $revenueData = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw("SUM({$priceColumn}) as total")
                )
                ->where($statusColumn, 'completed')
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->limit(7)
                ->get();

            if ($revenueData->isEmpty()) {
                $revenueData = Order::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw("SUM({$priceColumn}) as total")
                    )
                    ->groupBy('date')
                    ->orderBy('date', 'ASC')
                    ->limit(7)
                    ->get();
            }
        } catch (\Throwable $e) {
            $revenueData = collect();
        }

        $chartLabels = $revenueData->pluck('date')->map(fn($d) => date('d/m', strtotime($d)));
        $chartData = $revenueData->pluck('total');

        return view('admin.dashboard', compact(
            'totalOrders', 
            'totalRevenue', 
            'totalProducts', 
            'totalCustomers',
            'chartLabels',
            'chartData'
        ));
    }
}