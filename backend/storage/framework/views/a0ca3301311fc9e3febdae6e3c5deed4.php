<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title>Danh mục đặc sản - Đặc sản Việt</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

<style>
    /* KHẮC PHỤC LỖI VỠ ICON PHÂN TRANG (PAGINATION) LARAVEL */
    nav[role="navigation"] svg {
        width: 18px !important;
        height: 18px !important;
        display: inline-block !important;
        vertical-align: middle;
    }

    nav[role="navigation"] {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        width: 100%;
        margin-top: 24px;
    }

    nav[role="navigation"] > div {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    nav[role="navigation"] a, 
    nav[role="navigation"] span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        font-size: 14px;
        border-radius: 6px;
        text-decoration: none;
    }
</style>
</head>
<body>
<div class="s1">

<header class="s2">
<div class="s3">
    <!-- Logo -> Về trang chủ -->
    <a class="s4" href="<?php echo e(url('/')); ?>" style="display: flex; align-items: center; text-decoration: none;">
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo Đặc sản Việt" style="height: 70px; width: auto; object-fit: contain;">
    </a>

    <!-- Menu Điều Hướng Giống Chuẩn Trang Chủ -->
    <nav class="nav s6">
        <a href="<?php echo e(url('/')); ?>" class="<?php echo e(request()->is('/') ? 's7' : ''); ?>">Trang chủ</a>
        <a href="<?php echo e(route('categories.index')); ?>" class="<?php echo e(request()->routeIs('categories.index') ? 's7' : ''); ?>">Danh mục</a>
        <a href="<?php echo e(route('traceability.index')); ?>" class="<?php echo e(request()->routeIs('traceability.index') ? 's7' : ''); ?>">Truy xuất</a>
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('orders.index')); ?>" class="text-sm font-semibold text-gray-700 hover:text-indigo-600 transition">
                📦 Đơn hàng của tôi
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="text-sm font-semibold text-gray-700 hover:text-indigo-600 transition">
                Đăng nhập
            </a>
        <?php endif; ?>
    </nav>

    <!-- Nút Hành Động Đồng Bộ Với Trang Chủ -->
    <div class="s8">
        <button class="s9" aria-label="Tìm kiếm">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#17261E" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
        </button>

        <!-- NÚT GIỎ HÀNG TRÒN CÓ BADGE SỐ LƯỢNG MÀU ĐỎ -->
        <?php 
            $cartCount = array_sum(array_column(session('cart', []), 'quantity')); 
        ?>
        <a href="<?php echo e(route('cart.index')); ?>" class="s9" aria-label="Giỏ hàng" style="position: relative; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#17261E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 4h2l2.5 11h10L20 7H6"/><circle cx="9" cy="19" r="1.3"/><circle cx="17" cy="19" r="1.3"/>
            </svg>
            <?php if($cartCount > 0): ?>
                <span style="position: absolute; top: -4px; right: -4px; background-color: #EF4444; color: #FFFFFF; font-size: 10px; font-weight: 800; border-radius: 99px; min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; padding: 0 4px; border: 2px solid #FFFFFF; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                    <?php echo e($cartCount); ?>

                </span>
            <?php endif; ?>
        </a>

        <?php if(auth()->guard()->check()): ?>
            
            <a href="<?php echo e(route('dashboard')); ?>" class="s10" aria-label="Tài khoản" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>
            </a>
        <?php else: ?>
            
            <a href="<?php echo e(route('login')); ?>" class="s10" aria-label="Tài khoản" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>
            </a>
        <?php endif; ?>
    </div>
</div>
</header>

<!-- NỘI DUNG DANH MỤC SẢN PHẨM -->
<section class="s28" style="padding: 40px 16px;">
    <div style="max-width: 1200px; margin: 0 auto;">

        <!-- Khối Tiêu Đề & Thanh Tìm Kiếm Căn Giữa Duy Nhất -->
        <div style="text-align: center; margin-bottom: 48px;">
            <span class="s30" style="color: #E5B252; font-weight: 700; text-transform: uppercase; font-size: 14px; letter-spacing: 1px;">KHÁM PHÁ ĐẶC SẢN</span>
            <h1 class="s31" style="font-size: 32px; font-weight: 800; color: #0B3B2C; margin-top: 8px;">Danh Mục Sản Phẩm Cội Nguồn</h1>
            <p style="color: #64748B; margin-top: 8px; font-size: 15px;">Tuyển chọn đặc sản đạt chuẩn OCOP và sản phẩm làng nghề truyền thống ba miền</p>

            <!-- Thanh Tìm Kiếm -->
            <form action="<?php echo e(route('categories.index')); ?>" method="GET" style="max-width: 620px; margin: 24px auto 0 auto;">
                <?php if(request('category')): ?>
                    <input type="hidden" name="category" value="<?php echo e(request('category')); ?>">
                <?php endif; ?>

                <div style="display: flex; align-items: center; background: #FFFFFF; border-radius: 50px; padding: 5px 6px 5px 20px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); border: 1px solid #E5E7EB;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px; flex-shrink: 0;">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>

                    <input type="text" 
                           name="q" 
                           value="<?php echo e(request('q')); ?>" 
                           placeholder="Tìm kiếm đặc sản, sản phẩm.." 
                           style="border: none; outline: none; flex: 1; font-size: 15px; color: #1F2937; background: transparent; padding: 10px 0;">

                    <button type="submit" 
                            style="background: #E5B252; color: #0B3B2C; border: none; border-radius: 50px; padding: 11px 28px; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.2s ease; white-space: nowrap; margin-left: 8px;">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
        <?php
            $currentCategory = request('category');
        ?>
        <div style="margin-bottom: 48px;">
            <h2 style="font-size: 20px; font-weight: 700; color: #0B3B2C; margin-bottom: 20px;">1. Theo Vùng Miền</h2>
            <div class="grid4 s32">
                <!-- 1. Miền Bắc -->
                <a href="<?php echo e(request()->url()); ?>?category=dac-san-mien-bac" 
                   class="cat s33" 
                   style="<?php echo e($currentCategory == 'dac-san-mien-bac' ? 'border: 2px solid #000000 !important;' : ''); ?>">
                   <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#1E4D3B" stroke-width="1.6"><path d="M3 20l7-12 4 6 3-4 4 10H3z"/></svg>
                   <span>Đặc sản<br>miền Bắc</span>
                </a>

                <!-- 2. Miền Trung -->
                <a href="<?php echo e(request()->url()); ?>?category=dac-san-mien-trung" 
                   class="cat s36" 
                   style="<?php echo e($currentCategory == 'dac-san-mien-trung' ? 'border: 2px solid #000000 !important;' : ''); ?>">
                   <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#8A5A12" stroke-width="1.6"><path d="M4 20h16M12 4v16M8 8h8M8 12h8"/></svg>
                   <span>Đặc sản<br>miền Trung</span>
                </a>

                <!-- 3. Miền Nam -->
                <a href="<?php echo e(request()->url()); ?>?category=dac-san-mien-nam" 
                   class="cat s37" 
                   style="<?php echo e($currentCategory == 'dac-san-mien-nam' ? 'border: 2px solid #000000 !important;' : ''); ?>">
                   <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#1F5A70" stroke-width="1.6"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                   <span>Đặc sản<br>miền Nam</span>
                </a>

                <!-- 4. Quà lưu niệm -->
                <a href="<?php echo e(request()->url()); ?>?category=qua-luu-niem" 
                   class="cat s38" 
                   style="<?php echo e($currentCategory == 'qua-luu-niem' ? 'border: 2px solid #000000 !important;' : ''); ?>">
                   <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#9A4A32" stroke-width="1.6"><rect x="3" y="8" width="18" height="12" rx="2"/><path d="M12 8v12M3 12h18"/></svg>
                   <span>Quà<br>lưu niệm</span>
                </a>
            </div>
        </div>

    <!-- KHỐI HIỂN THỊ TẤT CẢ SẢN PHẨM TỪ DATABASE -->
    <div style="margin-top: 48px;">
        <h2 style="font-size: 22px; font-weight: 700; color: #0B3B2C; margin-bottom: 24px;">
            2. Tất Cả Sản Phẩm
        </h2>
        
        <div class="grid4 s32">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card">
                    <!-- Ảnh sản phẩm từ Storage -->
                    <div class="ph s42" style="position: relative; overflow: hidden; height: 180px; display: flex; align-items: center; justify-content: center; background: #F1F5F9;">
                        <span class="badge" style="position: absolute; top: 10px; right: 10px; z-index: 2;">Đã xác minh</span>

                        <?php if(!empty($product->image)): ?>
                            <img src="<?php echo e(asset('storage/' . ltrim($product->image, '/'))); ?>" alt="<?php echo e($product->title); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="s43">
                        <b class="s44"><?php echo e($product->title); ?></b>
                        <span class="s45"><?php echo e(number_format($product->discount_price ?? $product->base_price)); ?>đ</span>
                        <span class="s46">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                            <?php echo e($product->location ?? 'Việt Nam'); ?>

                        </span>
                    </div>
                    <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn">Xem chi tiết</a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="grid-column: 1 / -1; text-align: center; color: #888; padding: 40px 0;">
                    Chưa có sản phẩm nào trong hệ thống.
                </div>
            <?php endif; ?>
        </div>

        <!-- Phân trang đã được sửa giao diện -->
        <?php if(method_exists($products, 'links')): ?>
            <div style="margin-top: 32px; display: flex; justify-content: center;">
                <?php echo e($products->links()); ?>

            </div>
        <?php endif; ?>
    </div>

</div>
</section>

<!-- FOOTER -->
<footer class="s77">
<div class="s78">
<div class="foot s79">
<div class="s72"><span class="s80">Đặc sản Việt</span><span class="s81">Đặc sản Việt – Gìn giữ giá trị quê hương</span></div>
<div class="fl s82"><b class="s83">Liên kết nhanh</b><a href="<?php echo e(url('/')); ?>">Trang chủ</a><a href="<?php echo e(route('categories.index')); ?>">Danh mục</a><a href="<?php echo e(route('traceability.index')); ?>">Truy xuất nguồn gốc</a></div>
<div class="fl s82"><b class="s83">Hỗ trợ</b><a href="#">Về chúng tôi</a><a href="#">Chính sách bảo mật</a><a href="#">Điều khoản sử dụng</a><a href="#">Liên hệ</a></div>
<div class="s72"><b class="s83">Kết nối với chúng tôi</b>
<div class="s84">
<a class="s85" href="#" aria-label="Facebook">f</a>
<a class="s86" href="#" aria-label="YouTube"><svg width="16" height="16" viewBox="0 0 24 24" fill="#fff"><path d="M6 4l14 8-14 8z"/></svg></a>
<a class="s86" href="#" aria-label="Instagram"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/></svg></a>
</div></div>
</div>
<div class="s87">© <?php echo e(date('Y')); ?>. Sàn thương mại đặc sản và quà lưu niệm. All rights reserved.</div>
</div>
</footer>

</div>
</body>
</html><?php /**PATH C:\Users\Admin\Downloads\du-lich-so\backend\resources\views/categories/categories.blade.php ENDPATH**/ ?>