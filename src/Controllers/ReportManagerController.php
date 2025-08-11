<?php

namespace admin\products\Controllers;

use admin\products\Models\Order;
use admin\products\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ReportManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:report_manager_list')->only(['index']);
        $this->middleware('admincan_permission:report_manager_view')->only(['show']);
    }

    public function index(Request $request)
    {
        try {
            // Optional date filter
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

            // Transaction Stats
            $transactionCount = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
            $transactionTotal = Transaction::where('status', 'success')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');

            // Get all orders in date range
            $orders = Order::with('orderItems')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            // Calculate total revenue (sum of all order amounts)
            $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])->get()
            ->sum('amount');

            // Order count
            $orderCount = $orders->count();

            // Breakdown by status
            $ordersByStatus = $orders->groupBy('status')->map->count();

            return view('product::admin.report.index', compact(
                'startDate',
                'endDate',
                'transactionCount',
                'transactionTotal',
                'totalRevenue',
                'orderCount',
                'ordersByStatus',
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load reports: ' . $e->getMessage());
        }
    }
}
