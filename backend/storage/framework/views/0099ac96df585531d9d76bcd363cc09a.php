<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm sản phẩm mới - Quản trị</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; background: #F8FAFC; color: #1E293B; margin: 0; padding: 24px; }
        .form-container { max-width: 680px; margin: 0 auto; background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 600; font-size: 14px; margin-bottom: 8px; color: #0B3B2C; }
        .form-control { width: 100%; padding: 12px 16px; border: 1.5px solid #E2E8F0; border-radius: 10px; font-size: 14px; outline: none; transition: all 0.2s; box-sizing: border-box; }
        .form-control:focus { border-color: #0B3B2C; box-shadow: 0 0 0 3px rgba(11, 59, 44, 0.1); }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: #0B3B2C; cursor: pointer; user-select: none; }
        .checkbox-label input[type="checkbox"] { width: 18px; height: 18px; accent-color: #0B3B2C; cursor: pointer; }
        .btn-submit { background: #0B3B2C; color: #FFFFFF; font-weight: 700; border: none; padding: 14px 24px; border-radius: 10px; cursor: pointer; width: 100%; font-size: 15px; margin-top: 10px; }
        .btn-submit:hover { background: #14532D; }
        .btn-back { display: inline-block; margin-bottom: 20px; color: #64748B; text-decoration: none; font-size: 14px; font-weight: 500; }
        .image-preview { margin-top: 10px; max-width: 180px; max-height: 140px; border-radius: 8px; display: none; border: 1px solid #CBD5E1; object-fit: cover; }
    </style>
</head>
<body>

<div class="form-container">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn-back">← Quay lại trang quản trị</a>
    <h2 style="font-size: 24px; font-weight: 800; color: #0B3B2C; margin-bottom: 24px;">Thêm sản phẩm mới</h2>

    <form action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <!-- 1. TÊN SẢN PHẨM -->
        <div class="form-group">
            <label class="form-label" for="title">Tên sản phẩm <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Nhập tên sản phẩm..." value="<?php echo e(old('title')); ?>" required>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span style="color: red; font-size: 12px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- 2. DANH MỤC SẢN PHẨM -->
        <div class="form-group">
            <label class="form-label" for="category_id">Danh mục sản phẩm</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">-- Chọn danh mục --</option>
                <?php if(isset($categories) && count($categories) > 0): ?>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- 3. TẢI ẢNH SẢN PHẨM -->
        <div class="form-group">
            <label class="form-label" for="image">Ảnh sản phẩm</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
            <img id="preview" class="image-preview" alt="Xem trước ảnh">
            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span style="color: red; font-size: 12px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- 4. GIÁ CẢ & GIẢM GIÁ (%) -->
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="base_price">Giá gốc (VNĐ) <span style="color: red;">*</span></label>
                <input type="number" id="base_price" name="base_price" class="form-control" placeholder="Ví dụ: 150000" value="<?php echo e(old('base_price')); ?>" min="0" required>
                <?php $__errorArgs = ['base_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span style="color: red; font-size: 12px;"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="discount_percent">Giảm giá (%)</label>
                <input type="number" id="discount_percent" name="discount_percent" class="form-control" placeholder="Nhập % (Ví dụ: 10, 20...)" value="<?php echo e(old('discount_percent')); ?>" min="0" max="99">
                <small id="calc_preview" style="display: block; font-size: 12px; color: #16a34a; font-weight: 600; margin-top: 6px;"></small>
                <?php $__errorArgs = ['discount_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span style="color: red; font-size: 12px;"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- 5. XUẤT XỨ & SỨC CHỨA/DUNG TÍCH -->
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="location">Xuất xứ / Địa phương</label>
                <input type="text" id="location" name="location" class="form-control" placeholder="Ví dụ: Thái Nguyên, Bến Tre..." value="<?php echo e(old('location')); ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="capacity">Sức chứa / Số chỗ / Số lượng</label>
                <input type="number" id="capacity" name="capacity" class="form-control" placeholder="Ví dụ: 10" value="<?php echo e(old('capacity')); ?>" min="0">
            </div>
        </div>

        <!-- 6. CẤU HÌNH SẢN PHẨM NỔI BẬT -->
        <div class="form-group" style="background: #F1F5F9; padding: 14px 16px; border-radius: 10px; margin-bottom: 24px;">
            <label class="checkbox-label">
                <input type="checkbox" name="is_featured" value="1" <?php echo e(old('is_featured', true) ? 'checked' : ''); ?>>
                <span>Đặt làm sản phẩm Nổi Bật (Hiển thị ở trang chủ)</span>
            </label>
        </div>

        <button type="submit" class="btn-submit">Lưu sản phẩm</button>
    </form>
</div>

<script>
    // Hàm hiển thị xem trước ảnh khi chọn file
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Tự động tính thử số tiền sau khi giảm giá real-time
    const basePriceInput = document.getElementById('base_price');
    const discountPercentInput = document.getElementById('discount_percent');
    const calcPreview = document.getElementById('calc_preview');

    function updatePreviewPrice() {
        const basePrice = parseFloat(basePriceInput.value) || 0;
        const percent = parseFloat(discountPercentInput.value) || 0;

        if (basePrice > 0 && percent > 0 && percent < 100) {
            const finalPrice = basePrice * (100 - percent) / 100;
            calcPreview.textContent = `➔ Giá sau giảm (${percent}%): ` + finalPrice.toLocaleString('vi-VN') + ' VNĐ';
        } else {
            calcPreview.textContent = '';
        }
    }

    if (basePriceInput && discountPercentInput) {
        basePriceInput.addEventListener('input', updatePreviewPrice);
        discountPercentInput.addEventListener('input', updatePreviewPrice);
    }
</script>

</body>
</html><?php /**PATH C:\Users\Admin\Downloads\du-lich-so\backend\resources\views/admin/products/create.blade.php ENDPATH**/ ?>