<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BookingController extends Controller
{
    /**
     * Hiển thị trang chi tiết sản phẩm quà lưu niệm và form đặt mua
     */
    public function showProduct($id)
    {
        // 1. Tìm sản phẩm theo ID trong CSDL (nếu bảng products tồn tại)
        $product = null;
        if (Schema::hasTable('products')) {
            $product = Product::find($id);
        }

        // 2. Nếu không tìm thấy sản phẩm trong CSDL, tạo đối tượng mẫu quà lưu niệm dự phòng
        if (!$product) {
            $product = (object) [
                'id'          => $id,
                'title'       => 'Mật ong hoa nhãn Sơn La',
                'base_price'  => 180000,
                'capacity'    => 10, // Số lượng tồn kho
                'location'    => 'Sơn La',
                'description' => 'Mật ong hoa nhãn nguyên chất, quà lưu niệm đặc sản vùng núi Tây Bắc có tem QR truy xuất nguồn gốc.',
            ];

            $bookedSeats = 0;
            $remainingSeats = $product->capacity;
            $totalSeats = $product->capacity;
        } else {
            // Tính số lượng sản phẩm đã được đặt mua thành công
            if (Schema::hasTable('bookings')) {
                $bookedSeats = Booking::where('product_id', $product->id)
                    ->where('status', 'confirmed')
                    ->sum('quantity');
            } else {
                $bookedSeats = 0;
            }

            $remainingSeats = $product->capacity;
            $totalSeats = $remainingSeats + $bookedSeats;
        }

        // 3. Truy vấn danh sách 4 sản phẩm gợi ý (bỏ qua sản phẩm hiện tại)
        $recommendedProducts = collect();
        if (Schema::hasTable('products')) {
            $recommendedProducts = Product::where('id', '!=', $id)
                ->inRandomOrder()
                ->take(4)
                ->get();

            // Nếu danh sách rỗng (ví dụ CSDL chỉ có 1 sản phẩm), lấy ngẫu nhiên tất cả sản phẩm hiện có
            if ($recommendedProducts->isEmpty()) {
                $recommendedProducts = Product::take(4)->get();
            }
        }

        // Kiểm tra tình trạng sản phẩm còn hàng hay không
        $availability = $remainingSeats > 0;

        // Trả về JSON nếu là request API / AJAX
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status'              => 'success',
                'data'                => $product,
                'recommendedProducts' => $recommendedProducts,
                'availability'        => $availability,
                'totalSeats'          => $totalSeats,
                'bookedSeats'         => $bookedSeats,
                'remainingSeats'      => $remainingSeats
            ]);
        }

        // 4. Trả về View giao diện chi tiết sản phẩm (Đã truyền thêm $recommendedProducts)
        return view('products.show', compact(
            'product',
            'recommendedProducts',
            'availability',
            'totalSeats',
            'bookedSeats',
            'remainingSeats'
        ));
    }

    /**
     * Hiển thị danh sách đơn đặt hàng sản phẩm
     */
    public function index()
    {
        $bookings = collect();
        if (Schema::hasTable('bookings')) {
            $bookings = Booking::with('product')->orderBy('created_at', 'desc')->get();
        }
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['status' => 'success', 'data' => $bookings]);
        }

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Xử lý Đặt mua sản phẩm (Khóa giao dịch đồng thời - Lock For Update)
     */
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        try {
            // 2. Bắt đầu DB Transaction để xử lý mua hàng an toàn (chống Race Condition)
            $booking = DB::transaction(function () use ($validated, $request) {
                
                // Khóa dòng dữ liệu sản phẩm để tránh xung đột tồn kho khi nhiều người mua cùng lúc
                $product = null;
                if (Schema::hasTable('products')) {
                    $product = Product::where('id', $validated['product_id'])
                        ->lockForUpdate()
                        ->first();
                }

                // Kiểm tra tồn kho sản phẩm
                if (!$product || $product->capacity < $validated['quantity']) {
                    throw new \Exception('Rất tiếc, sản phẩm lưu niệm này hiện đã hết hàng hoặc không đủ số lượng trong kho.');
                }

                // Trừ số lượng tồn kho sản phẩm
                $product->capacity -= $validated['quantity'];
                $product->save();

                // Tạo đơn đặt hàng mới
                return Booking::create([
                    'user_id'    => $request->user()?->id ?? 1,
                    'product_id' => $product->id,
                    'quantity'   => $validated['quantity'],
                    'status'     => 'confirmed',
                    'code'       => 'SP-' . strtoupper(uniqid()),
                ]);
            });

            // 3. Trả về Response thành công
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Đặt hàng sản phẩm thành công!',
                    'data'    => $booking
                ], 200);
            }

            return back()->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $booking->code);

        } catch (\Exception $e) {
            Log::warning('Đặt hàng thất bại', ['error' => $e->getMessage()]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], 400);
            }

            return back()->withInput()->withErrors(['quantity' => $e->getMessage()]);
        }
    }

    /**
     * Xem chi tiết 1 đơn hàng quà lưu niệm
     */
    public function show($id)
    {
        $booking = Booking::with('product')->findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['status' => 'success', 'data' => $booking]);
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Hủy đơn hàng và hoàn trả số lượng vào kho sản phẩm
     */
    public function cancel($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $booking = Booking::findOrFail($id);

                if ($booking->status === 'cancelled') {
                    throw new \Exception('Đơn hàng đã được hủy trước đó.');
                }

                // Hoàn trả lại số lượng tồn kho cho sản phẩm
                $product = Product::where('id', $booking->product_id)->lockForUpdate()->first();
                if ($product) {
                    $product->capacity += $booking->quantity;
                    $product->save();
                }

                $booking->update(['status' => 'cancelled']);
            });

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Hủy đơn hàng thành công!']);
            }

            return back()->with('success', 'Đã hủy đơn hàng thành công!');

        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}