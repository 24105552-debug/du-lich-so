<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckoutController extends Controller
{
    // Gom thông tin 1 dòng hàng (giá luôn lấy từ DB, không tin giá trong session)
    private function buildItem(Product $product, int $quantity): array
    {
        $unit = $product->discount_price ?? $product->base_price ?? $product->price;

        return [
            'product'    => $product,
            'quantity'   => $quantity,
            'unit_price' => $unit,
            'subtotal'   => $unit * $quantity,
        ];
    }

    // Tự điền các cột "cũ" của bảng orders mà code chưa ghi (phone, receiver_name, shipping_address, ...)
    // để không bị lỗi NOT NULL. Chỉ ghi vào cột nào thực sự tồn tại trong bảng.
    private function legacyColumns(array $data, Request $request, Product $product): array
    {
        $extra = [];
        $user  = auth()->user();

        // Cột NOT NULL không có giá trị mặc định (đọc trực tiếp được với SQLite)
        $required = [];
        if (DB::getDriverName() === 'sqlite') {
            foreach (DB::select('PRAGMA table_info(orders)') as $c) {
                if ($c->notnull && $c->dflt_value === null && !$c->pk) {
                    $required[$c->name] = strtolower((string) $c->type);
                }
            }
        }

        foreach (Schema::getColumnListing('orders') as $col) {
            if (array_key_exists($col, $data) || in_array($col, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $matched = true;
            $value   = null;

            if (in_array($col, ['product_name', 'product_title'])) {
                $value = $product->title ?? $product->name;
            } elseif (in_array($col, ['name', 'full_name', 'fullname', 'receiver', 'recipient']) || str_ends_with($col, '_name')) {
                $value = $request->name;
            } elseif (in_array($col, ['phone', 'tel', 'telephone', 'mobile', 'phone_number']) || str_ends_with($col, '_phone')) {
                $value = $request->phone;
            } elseif ($col === 'address' || str_ends_with($col, '_address')) {
                $value = $request->address;
            } elseif (str_contains($col, 'email')) {
                $value = $user->email ?? '';
            } else {
                $matched = false;
            }

            if ($matched) {
                $extra[$col] = $value;
                continue;
            }

            // Cột lạ nhưng bắt buộc có giá trị: điền 0 (cột số) hoặc chuỗi rỗng
            if (isset($required[$col])) {
                $extra[$col] = preg_match('/int|decimal|numeric|real|float|double|bool/', $required[$col]) ? 0 : '';
            }
        }

        return $extra;
    }

    // Mua ngay 1 sản phẩm
    public function index(Request $request, $product_id)
    {
        $product  = Product::findOrFail($product_id);
        $quantity = max(1, (int) $request->query('quantity', 1));

        $items    = [$this->buildItem($product, $quantity)];
        $fromCart = false;

        return view('checkout.index', compact('items', 'fromCart'));
    }

    // Mua các sản phẩm đã tick trong giỏ: /checkout?items[]=1&items[]=2
    public function cart(Request $request)
    {
        $ids  = array_unique(array_map('intval', (array) $request->query('items', [])));
        $cart = session('cart', []);

        $items = [];
        foreach ($ids as $id) {
            if (!isset($cart[$id])) continue;

            $product = Product::find($id);
            if (!$product) continue;

            $items[] = $this->buildItem($product, max(1, (int) $cart[$id]['quantity']));
        }

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để mua.');
        }

        $fromCart = true;

        return view('checkout.index', compact('items', 'fromCart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'name'               => 'required|string|max:255',
            'phone'              => 'required|string|max:20',
            'address'            => 'required|string|max:255',
            'payment_method'     => 'required|in:cod,qr',
        ]);

        // Gộp các dòng trùng sản phẩm, sắp theo id để khóa dòng theo thứ tự cố định (tránh deadlock)
        $lines = collect($request->items)
            ->groupBy('product_id')
            ->map(fn ($group, $pid) => [
                'product_id' => (int) $pid,
                'quantity'   => (int) $group->sum('quantity'),
            ])
            ->sortBy('product_id')
            ->values();

        try {
            // Một transaction cho cả giỏ: thiếu hàng ở bất kỳ sản phẩm nào thì hủy toàn bộ
            $orders = DB::transaction(function () use ($request, $lines) {
                $created = [];

                foreach ($lines as $line) {
                    $product = Product::where('id', $line['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($product->stock < $line['quantity']) {
                        throw new \Exception('Sản phẩm "' . ($product->title ?? $product->name) . '" đã hết hàng hoặc không đủ số lượng trong kho!');
                    }

                    $product->decrement('stock', $line['quantity']);

                    $unitPrice   = $product->discount_price ?? $product->base_price ?? $product->price;
                    $totalAmount = $unitPrice * $line['quantity'];

                    // Mỗi sản phẩm là một đơn hàng riêng
                    $data = [
                        'user_id'          => auth()->id(),
                        'product_id'       => $product->id,
                        'order_code'       => 'DH' . strtoupper(Str::random(6)),
                        'customer_name'    => $request->name,
                        'customer_phone'   => $request->phone,
                        'customer_address' => $request->address,
                        'note'             => $request->note ?? null,
                        'quantity'         => $line['quantity'],
                        'total_amount'     => $totalAmount,
                        'payment_method'   => $request->payment_method,
                        'payment_status'   => 'pending',
                        'status'           => $request->payment_method === 'cod' ? 'processing' : 'pending_approval',
                    ];

                    // Bổ sung các cột tên cũ (nếu bảng orders có)
                    $data = array_merge($data, $this->legacyColumns($data, $request, $product));

                    // forceFill: bỏ qua giới hạn $fillable của model Order
                    $order = new Order();
                    $order->forceFill($data)->save();

                    $created[] = $order;
                }

                return $created;
            });

            // Mua từ giỏ hàng: xóa các sản phẩm đã đặt khỏi giỏ
            if ($request->boolean('from_cart')) {
                $cart = session('cart', []);
                foreach ($lines as $line) {
                    unset($cart[$line['product_id']]);
                }
                session()->put('cart', $cart);
            }

            // Chuyển khoản QR
            if ($request->payment_method === 'qr') {
                // 1 đơn: trang QR như cũ
                if (count($orders) === 1) {
                    return redirect()->route('orders.payment_qr', $orders[0]->id)
                        ->with('info', 'Vui lòng quét mã QR để chuyển khoản và tải lên minh chứng!');
                }

                // Từ 2 đơn trở lên: 1 mã QR với tổng tiền của tất cả đơn
                $ids = collect($orders)->pluck('id')->implode(',');

                return redirect()->route('orders.payment_qr_multi', ['ids' => $ids])
                    ->with('info', 'Đã tạo ' . count($orders) . ' đơn hàng. Vui lòng quét 1 mã QR để thanh toán tất cả!');
            }

            return redirect('/')->with('success', 'Đặt hàng thành công ' . count($orders) . ' đơn! Đơn hàng của bạn đang được xử lý.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}