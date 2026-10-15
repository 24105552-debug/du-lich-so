<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Hiển thị trang giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // Thêm sản phẩm vào giỏ hàng (AJAX hoặc Form Submit)
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        // Xác định giá bán chuẩn (ưu tiên giá giảm nếu có)
        $price = $product->is_discount && $product->discount_price 
            ? $product->discount_price 
            : ($product->base_price ?? $product->price ?? 0);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id'       => $product->id,
                'title'    => $product->title ?? $product->name,
                'price'    => $price,
                'image'    => $product->image,
                'quantity' => $quantity,
                'origin'   => $product->origin ?? 'Sơn La',
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'status'     => 'success',
                'message'    => 'Đã thêm sản phẩm vào giỏ hàng!',
                'cart_count' => array_sum(array_column($cart, 'quantity'))
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // Cập nhật số lượng trong giỏ
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart', []);
            if (isset($cart[$request->id])) {
                $cart[$request->id]['quantity'] = (int) $request->quantity;
                session()->put('cart', $cart);
            }
        }
        return response()->json(['status' => 'success']);
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart', []);
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
        }
        return response()->json(['status' => 'success']);
    }
}