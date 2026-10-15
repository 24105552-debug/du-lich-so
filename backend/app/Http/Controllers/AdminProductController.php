<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    // Danh sách sản phẩm & Lọc Nổi Bật / Giảm Giá
    public function index(Request $request)
    {
        $query = Product::query()->orderBy('id', 'desc');

        if (class_exists(Category::class)) {
            try {
                $query->with('category');
            } catch (\Throwable $e) {
                // Bỏ qua nếu chưa khai báo quan hệ category trong Model
            }
        }

        if ($request->has('filter')) {
            if ($request->filter === 'featured') {
                $query->where('is_featured', true);
            } elseif ($request->filter === 'discount') {
                $query->where('is_discount', true);
            }
        }

        $products = $query->get();
        return view('admin.products.index', compact('products'));
    }

    // Trang giao diện thêm sản phẩm mới
    public function create()
    {
        try {
            $categories = class_exists(Category::class) ? Category::all() : collect();
        } catch (\Throwable $e) {
            $categories = collect();
        }

        return view('admin.products.create', compact('categories'));
    }

    // Xử lý lưu sản phẩm mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'category_id'      => 'nullable',
            'base_price'       => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:99',
            'stock'            => 'nullable|integer|min:0',
            'capacity'         => 'nullable|integer',
            'location'         => 'nullable|string|max:255',
            'origin'           => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'title.required'           => 'Vui lòng nhập tên sản phẩm.',
            'base_price.required'      => 'Vui lòng nhập giá gốc.',
            'discount_percent.numeric' => 'Phần trăm giảm giá phải là số.',
            'stock.integer'            => 'Số lượng tồn kho phải là số nguyên.',
            'image.image'              => 'Tệp tải lên phải là hình ảnh.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $discountPrice = null;
        $isDiscount = false;

        if (!empty($validated['discount_percent']) && $validated['discount_percent'] > 0) {
            $percent = $validated['discount_percent'];
            $discountPrice = $validated['base_price'] * (100 - $percent) / 100;
            $isDiscount = true;
        }

        Product::create([
            'title'          => $validated['title'],
            'slug'           => Str::slug($validated['title']) . '-' . time(),
            'category_id'    => $validated['category_id'] ?? null,
            'price'          => $validated['base_price'],
            'base_price'     => $validated['base_price'],
            'stock'          => $validated['stock'] ?? 10,
            'is_featured'    => $request->has('is_featured') ? (bool)$request->is_featured : true,
            'is_discount'    => $isDiscount,
            'discount_price' => $discountPrice,
            'capacity'       => $validated['capacity'] ?? 0,
            'location'       => $validated['location'] ?? null,
            'origin'         => $validated['origin'] ?? null,
            'description'    => $validated['description'] ?? null,
            'image'          => $imagePath,
            'status'         => 'published',
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // Hiển thị giao diện Chỉnh sửa sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        try {
            $categories = class_exists(Category::class) ? Category::all() : collect();
        } catch (\Throwable $e) {
            $categories = collect();
        }

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Xử lý Cập nhật thông tin sản phẩm
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'category_id'      => 'nullable',
            'base_price'       => 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:99',
            'description'      => 'nullable|string',
            'location'         => 'nullable|string|max:255',
            'origin'           => 'nullable|string|max:255',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'title.required'      => 'Vui lòng nhập tên sản phẩm.',
            'base_price.required' => 'Vui lòng nhập giá gốc.',
            'stock.required'      => 'Vui lòng nhập số lượng hàng tồn kho.',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $discountPrice = null;
        $isDiscount = false;

        if (!empty($validated['discount_percent']) && $validated['discount_percent'] > 0) {
            $percent = $validated['discount_percent'];
            $discountPrice = $validated['base_price'] * (100 - $percent) / 100;
            $isDiscount = true;
        }

        $product->update([
            'title'          => $validated['title'],
            'category_id'    => $validated['category_id'] ?? null,
            'price'          => $validated['base_price'],
            'base_price'     => $validated['base_price'],
            'stock'          => $validated['stock'],
            'is_discount'    => $isDiscount,
            'discount_price' => $discountPrice,
            'description'    => $validated['description'] ?? null,
            'location'       => $validated['location'] ?? $product->location,
            'origin'         => $validated['origin'] ?? $product->origin,
            'image'          => $product->image,
        ]);

        return redirect()->route('admin.products.index')->with('success', "Cập nhật sản phẩm \"{$product->title}\" thành công!");
    }

    // Bật / Tắt trạng thái Nổi Bật
    public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();

        $status = $product->is_featured ? 'đã thêm vào' : 'đã xóa khỏi';
        return back()->with('success', "Sản phẩm \"{$product->title}\" {$status} Danh mục Nổi bật!");
    }

    // Cập nhật phần trăm giảm giá hoặc Xóa khỏi Giảm Giá
    public function updateDiscount(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->has('remove_discount')) {
            $product->is_discount = false;
            $product->discount_price = null;
            $product->save();

            return back()->with('success', "Đã xóa sản phẩm \"{$product->title}\" khỏi Danh mục Giảm giá!");
        }

        $request->validate([
            'discount_percent' => 'required|numeric|min:1|max:99',
        ]);

        $percent = $request->discount_percent;
        $basePrice = $product->base_price ?? $product->price ?? 0;

        $product->is_discount = true;
        $product->discount_price = $basePrice * (100 - $percent) / 100;
        $product->save();

        return back()->with('success', "Đã cập nhật giảm giá {$percent}% cho sản phẩm \"{$product->title}\"!");
    }

    // Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Đã xóa sản phẩm thành công!');
    }
}