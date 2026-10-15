<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\PythonDataService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Khởi tạo Query Builder từ Model Product
        $query = Product::query()->with('category');

        // 2. Xử lý TÌM KIẾM an toàn (Lọc theo tên sản phẩm hoặc địa danh)
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('location', 'like', "%{$keyword}%");
            });
        }

        // 3. Xử lý LỌC THEO DANH MỤC / VÙNG MIỀN
        if ($request->filled('category')) {
            $categorySlug = $request->category;

            $query->where(function ($q) use ($categorySlug) {
                // Lọc theo relation category slug hoặc category_id
                $q->whereHas('category', function ($catQuery) use ($categorySlug) {
                    $catQuery->where('slug', $categorySlug);
                })->orWhere('category_id', $categorySlug);

                // Dự phòng lọc theo Vị trí / Tỉnh thành nếu sản phẩm chưa được gán category_id
                if ($categorySlug === 'dac-san-mien-bac') {
                    $q->orWhereIn('location', ['Hà Nội', 'Sơn La', 'Thái Bình', 'Thái Nguyên', 'Bắc Giang', 'Quảng Ninh']);
                } elseif ($categorySlug === 'dac-san-mien-trung') {
                    $q->orWhereIn('location', ['Khánh Hòa', 'Quảng Ngãi', 'Lý Sơn', 'Đà Nẵng', 'Huế', 'Nghệ An', 'Đà Lạt', 'Buôn Ma Thuột']);
                } elseif ($categorySlug === 'dac-san-mien-nam') {
                    $q->orWhereIn('location', ['Bến Tre', 'Đồng Tháp', 'Cần Thơ', 'Sài Gòn', 'Cà Mau', 'An Giang', 'Phú Quốc']);
                }
            });
        }

        // 4. Xử lý SẮP XẾP an toàn (Whitelist)
        $sortColumn = match ($request->sort) {
            'gia'   => 'base_price',
            'moi'   => 'created_at',
            default => 'id',
        };

        // 5. Thực thi truy vấn, phân trang 12 mục / trang & giữ tham số URL khi chuyển trang
        $products = $query->orderBy($sortColumn, 'desc')
                          ->paginate(12)
                          ->withQueryString();

        // 6. Lấy tất cả danh mục truyền qua View
        $categories = Category::all();

        return view('categories.categories', compact('products', 'categories'));
    }

    /**
     * Lấy danh sách sản phẩm gợi ý từ Python Microservice
     */
    public function recommend(int $id, PythonDataService $pythonService)
    {
        $items = $pythonService->recommend($id);

        return response()->json([
            'status' => 'success',
            'data'   => $items,
        ]);
    }
}