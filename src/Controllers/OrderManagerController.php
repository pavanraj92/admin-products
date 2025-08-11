<?php

namespace admin\products\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\products\Models\Order;
use admin\products\Models\OrderItem;

class OrderManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:product_orders_manager_list')->only(['index']);
        $this->middleware('admincan_permission:product_orders_manager_view')->only(['show']);
    }

    public function index(Request $request)
    {
        try {
            $orders = Order::filter($request->query('order_number'))
                ->filterByStatus($request->query('status'))
                ->sortable()
                ->latest()
                ->paginate(Order::getPerPageLimit())
                ->withQueryString();

            return view('product::admin.orders.index', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load orders: ' . $e->getMessage());
        }
    }

    /**
     * show order details
     */
    public function show(Order $order)
    {
        try {
            $order->load('orderItems.product');

            return view('product::admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load orders: ' . $e->getMessage());
        }
    }


    public function updateStatus(Request $request)
    {
        try {
            $order = Order::findOrFail($request->id);
            $oldStatus = $order->status;
            $order->status = $request->status;
            $order->save();

            // Get status label from config
            $statusLabels = config('product.order_status');
            $newLabel = $statusLabels[$order->status] ?? ucfirst($order->status);

            $strMessage = "Order status updated successfully to {$newLabel}.";

            return response()->json([
                'success' => true,
                'message' => $strMessage,
                'new_status' => $order->status,
                'new_label' => $newLabel,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
