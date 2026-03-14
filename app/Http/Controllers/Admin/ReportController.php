<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        // Sales Data
        $orders = Order::with('items.menu')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        $totalSales = $orders->sum('total_price');

        // Expenses Data
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();
        $totalExpenses = $expenses->sum('amount');

        $netProfit = $totalSales - $totalExpenses;

        $appSettings = Setting::pluck('value', 'key');

        // Revenue by Category Analytics
        $categorySales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select('categories.name', DB::raw('SUM(order_items.quantity * order_items.price) as revenue'))
            ->groupBy('categories.name')
            ->get();

        return view('admin.reports', compact(
            'orders',
            'expenses',
            'totalSales',
            'totalExpenses',
            'netProfit',
            'startDate',
            'endDate',
            'appSettings',
            'categorySales'
        ));
    }
}
