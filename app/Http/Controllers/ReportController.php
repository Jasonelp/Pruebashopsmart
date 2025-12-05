<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Store a new report (user or product)
     */
    public function store(Request $request)
    {
        $type = $request->input('type', 'user');

        if ($type === 'product') {
            return $this->storeProductReport($request);
        }

        return $this->storeUserReport($request);
    }

    /**
     * Store a user report
     */
    protected function storeUserReport(Request $request)
    {
        $validated = $request->validate([
            'reported_id' => 'required|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'reason' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
        ]);

        if ($validated['reported_id'] == Auth::id()) {
            return response()->json(['error' => 'No puedes reportarte a ti mismo'], 400);
        }

        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('reported_id', $validated['reported_id'])
            ->where('order_id', $validated['order_id'])
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {
            return response()->json(['error' => 'Ya has reportado a este usuario por este pedido'], 400);
        }

        Report::create([
            'type' => 'user',
            'reporter_id' => Auth::id(),
            'reported_id' => $validated['reported_id'],
            'order_id' => $validated['order_id'],
            'reason' => $validated['reason'],
            'description' => $validated['description'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reporte enviado correctamente. Un administrador lo revisará pronto.'
        ]);
    }

    /**
     * Store a product report
     */
    protected function storeProductReport(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'reason' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {
            return response()->json(['error' => 'Ya has reportado este producto'], 400);
        }

        Report::create([
            'type' => 'product',
            'reporter_id' => Auth::id(),
            'reported_id' => $product->user_id,
            'product_id' => $validated['product_id'],
            'reason' => $validated['reason'],
            'description' => $validated['description'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reporte de producto enviado. Un administrador lo revisará pronto.'
        ]);
    }

    /**
     * Admin: List all reports
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');

        $query = Report::with(['reporter', 'reported', 'product', 'order', 'reviewer']);

        if ($type === 'user') {
            $query->where('type', 'user');
        } elseif ($type === 'product') {
            $query->where('type', 'product');
        }

        $reports = $query
            ->orderByRaw("FIELD(status, 'pending', 'reviewed', 'resolved', 'dismissed')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $pendingCount = Report::where('status', 'pending')->count();
        $pendingUserReports = Report::where('type', 'user')->where('status', 'pending')->count();
        $pendingProductReports = Report::where('type', 'product')->where('status', 'pending')->count();

        return view('admin.reports.index', compact('reports', 'pendingCount', 'pendingUserReports', 'pendingProductReports', 'type'));
    }

    /**
     * Admin: Show single report
     */
    public function show($id)
    {
        $report = Report::with(['reporter', 'reported', 'product.user', 'order.products', 'reviewer'])
            ->findOrFail($id);

        $previousReports = [];
        if ($report->type === 'user' && $report->reported_id) {
            $previousReports = Report::where('reported_id', $report->reported_id)
                ->where('id', '!=', $report->id)
                ->with('reporter')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } elseif ($report->type === 'product' && $report->product_id) {
            $previousReports = Report::where('product_id', $report->product_id)
                ->where('id', '!=', $report->id)
                ->with('reporter')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('admin.reports.show', compact('report', 'previousReports'));
    }

    /**
     * Admin: Update report status
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report = Report::findOrFail($id);

        $report->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Reporte actualizado correctamente');
    }

    /**
     * Admin: Suspend a user
     */
    public function suspendUser(Request $request, $userId)
    {
        $validated = $request->validate([
            'suspension_reason' => 'required|string|max:500',
        ]);

        $user = User::findOrFail($userId);

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'No se puede suspender a un administrador');
        }

        $user->update([
            'suspended_at' => now(),
            'suspension_reason' => $validated['suspension_reason'],
        ]);

        return redirect()->back()->with('success', 'Usuario suspendido indefinidamente');
    }

    /**
     * Admin: Unsuspend a user
     */
    public function unsuspendUser($userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Suspensión levantada');
    }

    /**
     * Admin: Deactivate a product
     */
    public function deactivateProduct(Request $request, $productId)
    {
        $validated = $request->validate([
            'deactivation_reason' => 'required|string|max:500',
        ]);

        $product = Product::findOrFail($productId);

        $product->update([
            'is_active' => false,
            'deactivation_reason' => $validated['deactivation_reason'],
        ]);

        return redirect()->back()->with('success', 'Producto retirado de la tienda');
    }

    /**
     * Admin: Reactivate a product
     */
    public function reactivateProduct($productId)
    {
        $product = Product::findOrFail($productId);

        $product->update([
            'is_active' => true,
            'deactivation_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Producto reactivado');
    }
}
