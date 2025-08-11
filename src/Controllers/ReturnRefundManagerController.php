<?php

namespace admin\products\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\products\Models\ReturnRefundRequest;

class ReturnRefundManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:return_refunds_manager_list')->only(['index']);
        $this->middleware('admincan_permission:return_refunds_manager_view')->only(['show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $returnRefunds = ReturnRefundRequest::filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->filterByRequestType($request->query('request_type'))
                ->sortable()
                ->latest()
                ->paginate(ReturnRefundRequest::getPerPageLimit())
                ->withQueryString();

            return view('product::admin.return_refunds.index', compact('returnRefunds'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load return refunds: ' . $e->getMessage());
        }
    }

    /**
     * show returnRefund details
     */
    public function show(ReturnRefundRequest $returnRefund)
    {
        try {
            return view('product::admin.return_refunds.show', compact('returnRefund'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load return refunds: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $returnRefund = ReturnRefundRequest::findOrFail($request->id);
            $oldStatus = $returnRefund->status;
            $returnRefund->status = $request->status;
            $returnRefund->save();

            // Get status label from config
            $statusLabels = config('product.refund_status');
            $newLabel = $statusLabels[$returnRefund->status] ?? ucfirst($returnRefund->status);

            $strMessage = "Return/Refund status updated successfully to {$newLabel}.";

            return response()->json([
                'success' => true,
                'message' => $strMessage,
                'new_status' => $returnRefund->status,
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
