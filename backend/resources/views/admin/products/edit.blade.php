<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chỉnh sửa sản phẩm - {{ $product->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; background-color: #FAF9F6; margin: 0; padding: 20px; }
    </style>
</head>
<body>

<div style="max-width: 800px; margin: 30px auto; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
    
    <!-- Tiêu đề trang -->
    <div style="background: #1E4D3B; color: #ffffff; padding: 18px 24px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
        ✏️ Chỉnh sửa sản phẩm: {{ $product->title }}
    </div>

    <!-- Form Cập nhật -->
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div style="background: #FDE8E8; border: 1px solid #F98080; color: #9B1C1C; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tên sản phẩm -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155; font-size: 14px;">Tên sản phẩm <span style="color: red;">*</span></label>
            <input type="text" name="title" value="{{ old('title', $product->title) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none;">
        </div>

        <!-- Hàng 2 cột: Giá gốc & Phần trăm giảm giá -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155; font-size: 14px;">Giá bán gốc (VNĐ) <span style="color: red;">*</span></label>
                <input type="number" name="base_price" value="{{ old('base_price', $product->base_price ?? $product->price) }}" step="1000" min="0" required style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                @php
                    $currentDiscountPercent = 0;
                    if($product->is_discount && $product->base_price > 0 && $product->discount_price) {
                        $currentDiscountPercent = round((($product->base_price - $product->discount_price) / $product->base_price) * 100);
                    }
                @endphp
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #D97706; font-size: 14px;">Giảm giá (%) <span style="font-weight: 400; color: #64748B;">(Bỏ trống nếu không giảm)</span></label>
                <input type="number" name="discount_percent" value="{{ old('discount_percent', $currentDiscountPercent > 0 ? $currentDiscountPercent : '') }}" min="0" max="99" placeholder="Ví dụ: 15 (là giảm 15%)" style="width: 100%; padding: 10px 14px; border: 1px solid #F59E0B; background-color: #FEF3C7; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none; font-weight: 600;">
            </div>
        </div>

        <!-- Hàng 2 cột: Số lượng kho & Danh mục -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155; font-size: 14px;">Số lượng tồn kho <span style="color: red;">*</span></label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155; font-size: 14px;">Danh mục sản phẩm</label>
                <select name="category_id" style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box; background: white; outline: none;">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name ?? $cat->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Hàng 2 cột: Xuất xứ & Nguồn gốc -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155; font-size: 14px;">Xuất xứ / Tỉnh thành</label>
                <input type="text" name="location" value="{{ old('location', $product->location) }}" placeholder="Ví dụ: Sơn La, Hà Nội..." style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155; font-size: 14px;">Cơ sở / Nguồn gốc</label>
                <input type="text" name="origin" value="{{ old('origin', $product->origin) }}" placeholder="Ví dụ: Làng nghề truyền thống..." style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none;">
            </div>
        </div>

        <!-- Ảnh đại diện -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155; font-size: 14px;">Hình ảnh sản phẩm</label>
            @if($product->image)
                <div style="margin-bottom: 8px;">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Ảnh sản phẩm" style="width: 90px; height: 90px; object-fit: cover; border-radius: 8px; border: 1px solid #E2E8F0;">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" style="font-size: 14px; color: #475569;">
        </div>

        <!-- Mô tả sản phẩm -->
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155; font-size: 14px;">Mô tả sản phẩm</label>
            <textarea name="description" rows="5" style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; box-sizing: border-box; resize: vertical; outline: none;">{{ old('description', $product->description) }}</textarea>
        </div>

        <!-- Nút thao tác -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #E2E8F0; padding-top: 18px;">
            <a href="{{ route('admin.products.index') }}" style="background: #64748B; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
                ⬅ Quay lại
            </a>

            <button type="submit" style="background: #10B981; color: #ffffff; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                💾 Lưu thay đổi
            </button>
        </div>
    </form>
</div>

</body>
</html>