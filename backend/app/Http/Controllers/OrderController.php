<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // ==========================================
    // DÀNH CHO KHÁCH HÀNG (USER)
    // ==========================================

    // 1. Danh sách đơn hàng của riêng khách đang đăng nhập
    public function index()
    {
        $userId = auth()->id();
        $orders = Order::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    // 2. Xem chi tiết đơn hàng của khách hàng
    public function show($id)
    {
        $order = Order::with('product')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    // 3. Khách hàng tự hủy đơn hàng
    public function cancel(Request $request, $id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Các trạng thái được phép hủy đơn hàng
        $allowedStatuses = ['pending', 'pending_approval', 'cho_xu_ly', 'chờ xử lý'];

        if (in_array(strtolower($order->status), $allowedStatuses)) {
            $order->update([
                'status' => 'cancelled', // Cập nhật trạng thái thành Đã hủy
            ]);

            return redirect()->route('orders.index')->with('success', 'Hủy đơn hàng #' . $order->id . ' thành công!');
        }

        return redirect()->back()->with('error', 'Đơn hàng đã được duyệt hoặc đang giao, không thể hủy!');
    }

    // 4. Trang thanh toán QR cho 1 đơn (đã kiểm tra chủ đơn)
    public function paymentQr($id)
    {
        $order = Order::with('product')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('orders.payment_qr', compact('order'));
    }

    // 5. Upload ảnh minh chứng chuyển khoản cho 1 đơn
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $order->update([
                'payment_proof'  => $path,
                'payment_status' => 'pending',          // Đánh dấu thanh toán đang chờ xác nhận
                'status'         => 'pending_approval'  // Đổi trạng thái đơn thành Chờ duyệt
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Gửi minh chứng chuyển khoản thành công! Đơn hàng đang chờ duyệt.');
    }

    // Lấy các đơn QR của user hiện tại còn đang chờ thanh toán (chưa có minh chứng)
    private function pendingQrOrders(?string $ids)
    {
        $ids = array_values(array_filter(array_map('intval', explode(',', (string) $ids))));

        return Order::with('product')
            ->whereIn('id', $ids)
            ->where('user_id', auth()->id())
            ->where('payment_method', 'qr')
            ->where('status', 'pending_approval')
            ->where(function ($q) {
                $q->whereNull('payment_proof')->orWhere('payment_proof', '');
            })
            ->get();
    }

    // 6. Trang QR chung cho nhiều đơn
    public function paymentQrMulti(Request $request)
    {
        $orders = $this->pendingQrOrders($request->query('ids'));

        if ($orders->isEmpty()) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng nào đang chờ thanh toán.');
        }

        $total           = (int) $orders->sum('total_amount');
        $transferContent = $orders->pluck('order_code')->implode(' ');
        $ids             = $orders->pluck('id')->implode(',');

        return view('orders.payment_qr_multi', compact('orders', 'total', 'transferContent', 'ids'));
    }

    // 7. Upload 1 ảnh minh chứng cho cả nhóm đơn
    public function uploadProofMulti(Request $request)
    {
        $request->validate([
            'ids'           => 'required|string',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $orders = $this->pendingQrOrders($request->input('ids'));
        
        if ($orders->isEmpty()) {
            return redirect()->route('orders.index')->with('error', 'Đơn hàng đã có minh chứng hoặc không hợp lệ.');
        }

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        Order::whereIn('id', $orders->pluck('id'))->update([
            'payment_proof'  => $path,
            'payment_status' => 'pending',
            'status'         => 'pending_approval',
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Gửi minh chứng thành công cho ' . $orders->count() . ' đơn hàng! Đơn hàng đang chờ duyệt.');
    }

    // ==========================================
    // DÀNH CHO QUẢN TRỊ VIÊN (ADMIN)
    // ==========================================

    public function adminIndex(Request $request)
    {
        $query = Order::with(['product', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function adminShow($id)
    {
        $order = Order::with(['product', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'         => 'required|string',
            'payment_status' => 'nullable|string',
        ]);

        $order = Order::findOrFail($id);

        $order->update([
            'status'         => $request->status,
            'payment_status' => $request->payment_status ?? $order->payment_status,
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}