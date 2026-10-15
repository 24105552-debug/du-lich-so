<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($product->name ?? $product->title ?? 'Chi tiết sản phẩm'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; background-color: #FAF9F6; color: #1E293B; margin: 0; padding: 40px 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        .breadcrumb { font-size: 14px; color: #64748B; margin-bottom: 20px; }
        .breadcrumb a { color: #64748B; text-decoration: none; }
        .breadcrumb a:hover { color: #0B3B2C; }

        .product-card { background: #FFFFFF; border-radius: 20px; box-shadow: 0 4px 25px rgba(0,0,0,0.05); border: 1px solid #E2E8F0; padding: 32px; display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px; }
        @media (max-width: 768px) { .product-card { grid-template-columns: 1fr; } }

        .product-image-box { background: #F8FAFC; border-radius: 16px; border: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: center; min-height: 320px; overflow: hidden; }
        .product-image-box img { width: 100%; height: 100%; object-fit: cover; }
        .no-image { color: #94A3B8; font-size: 14px; font-weight: 500; text-align: center; }

        .product-title { font-size: 28px; font-weight: 800; color: #0B3B2C; margin: 0 0 12px 0; }
        .product-price { font-size: 26px; font-weight: 800; color: #15803D; margin-bottom: 20px; }

        .origin-box { background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; gap: 15px; }
        .badge-verified { background: #10B981; color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; margin-bottom: 8px; }
        .origin-info p { margin: 4px 0; font-size: 13px; color: #065F46; font-weight: 600; }
        
        .qr-container { background: #ffffff; padding: 6px; border-radius: 8px; border: 1px solid #A7F3D0; flex-shrink: 0; display: flex; justify-content: center; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        .info-group { border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9; padding: 16px 0; margin-bottom: 24px; }
        .info-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 8px; }
        .info-row:last-child { margin-bottom: 0; }
        .info-label { color: #64748B; font-weight: 500; }
        .info-value { color: #0F172A; font-weight: 700; }

        .order-box { background: #F8FAFC; border-radius: 12px; padding: 20px; border: 1px solid #E2E8F0; }
        .quantity-control { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .quantity-btn { width: 36px; height: 36px; border: 1px solid #CBD5E1; background: white; border-radius: 8px; font-size: 18px; font-weight: 700; cursor: pointer; color: #334155; }
        .quantity-input { width: 60px; height: 36px; text-align: center; border: 1px solid #CBD5E1; border-radius: 8px; font-weight: 700; font-size: 15px; }
        
        .btn-add-cart { width: 100%; background: #ECFDF5; color: #059669; border: 1.5px solid #059669; padding: 12px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .btn-add-cart:hover { background: #D1FAE5; }
        .btn-buy { width: 100%; background: #0B3B2C; color: white; border: none; padding: 12px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.2s; text-decoration: none; display: block; text-align: center; box-sizing: border-box; }
        .btn-buy:hover { background: #14532D; }

        .out-of-stock { background: #FEF2F2; color: #991B1B; border: 1px solid #FCA5A5; padding: 16px; border-radius: 12px; text-align: center; font-weight: 700; font-size: 15px; }

        .description-box { margin-top: 32px; background: white; border-radius: 20px; padding: 28px; border: 1px solid #E2E8F0; box-shadow: 0 4px 25px rgba(0,0,0,0.05); }
        .description-title { font-size: 18px; font-weight: 800; color: #0B3B2C; margin: 0 0 12px 0; border-bottom: 2px solid #F1F5F9; padding-bottom: 10px; }
        .description-content { font-size: 14px; line-height: 1.7; color: #334155; }
    </style>
</head>
<body>

<div class="container">
    <div class="breadcrumb">
        <a href="/">Trang chủ</a> / <a href="/san-pham">Sản phẩm</a> / <strong><?php echo e($product->name ?? $product->title ?? 'Sản phẩm'); ?></strong>
    </div>

    <div class="product-card">
        <div class="product-image-box">
            <?php
                $rawImg = $product->image_url ?? $product->image ?? null;
                $imageUrl = null;
                if ($rawImg) {
                    if (str_starts_with($rawImg, 'http://') || str_starts_with($rawImg, 'https://')) {
                        $imageUrl =$rawImg;
                    } else {
                        $clean = ltrim($rawImg, '/');
                        $imageUrl = str_starts_with($clean, 'storage/') ? asset($clean) : asset('storage/' .$clean);
                    }
                }
            ?>

            <?php if ($imageUrl): ?>
                <img src="<?php echo e($imageUrl); ?>" 
                     alt="<?php echo e($product->name ?? $product->title ?? 'Sản phẩm'); ?>" 
                     onerror="this.onerror=null; this.src='https://via.placeholder.com/500x500?text=Anh+Loi';">
            <?php else: ?>
                <div class="no-image">📷 Chưa có hình ảnh</div>
            <?php endif; ?>
        </div>

        <div>
            <h1 class="product-title"><?php echo e($product->name ?? $product->title ?? 'Chưa có tên'); ?></h1>
            
            <?php
                $salePrice =$product->sale_price 
                    ?? $product->discount_price 
                    ?? $product->promotion_price 
                    ?? $product->sell_price 
                    ?? $product->price 
                    ?? $product->gia_ban 
                    ?? $product->gia_khuyen_mai 
                    ?? null;
                
                $basePrice = $product->base_price ?? $product->gia_goc ?? 0;
                $finalPrice = ($salePrice &&$salePrice > 0) ? $salePrice :$basePrice;
            ?>

            <div class="product-price">
                <?php echo e(number_format($finalPrice, 0, ',', '.')); ?>đ

                <?php if ($basePrice > 0 && $basePrice >$finalPrice): ?>
                    <span style="text-decoration: line-through; color: #94A3B8; font-size: 18px; margin-left: 8px; font-weight: 500;">
                        <?php echo e(number_format($basePrice, 0, ',', '.')); ?>đ
                    </span>
                <?php endif; ?>
            </div>

            <div class="origin-box">
                <div class="origin-info">
                    <span class="badge-verified">✓ Đã xác minh nguồn gốc</span>
                    <p>Xuất xứ: <?php echo e($product->origin ?? $product->location ?? $product->xuat_xu ?? 'Việt Nam'); ?></p>
                    <p>Mã truy xuất: TX-<?php echo e($product->id ?? '0'); ?>-2026</p>
                </div>
                
                <div class="qr-container">
                    <?php
                        $traceCode = 'TX-' . ($product->id ?? '0') . '-2026';
                        $qrData = urlencode(url('/truy-xuat/' .$traceCode));
                    ?>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=<?php echo e($qrData); ?>" 
                         alt="QR Truy xuất nguồn gốc" 
                         style="width: 70px; height: 70px; object-fit: cover;">
                </div>
            </div>

            <div class="info-group">
                <div class="info-row">
                    <span class="info-label">Ngày sản xuất (NSX):</span>
                    <span class="info-value">
                        <?php echo e(isset($product->mfg_date) ? \Carbon\Carbon::parse($product->mfg_date)->format('d/m/Y') : 'Xem trên bao bì'); ?>

                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Hạn sử dụng (HSD):</span>
                    <span class="info-value">
                        <?php echo e(isset($product->exp_date) ? \Carbon\Carbon::parse($product->exp_date)->format('d/m/Y') : '12 tháng kể từ NSX'); ?>

                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Số lượng tồn kho:</span>
                    <?php
                        $stockQty =$product->stock ?? $product->capacity ?? $product->so_luong ?? 0;
                    ?>
                    <span class="info-value" style="color: <?php echo e($stockQty > 0 ? '#16A34A' : '#DC2626'); ?>;">
                        <?php echo e($stockQty); ?> sản phẩm
                    </span>
                </div>
            </div>

            <?php if ($stockQty > 0): ?>
                <div class="order-box">
                    <div style="font-size: 13px; font-weight: 700; margin-bottom: 8px; color: #334155;">Số lượng mua:</div>
                    <div class="quantity-control">
                        <button type="button" class="quantity-btn" onclick="decreaseQuantity()">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo e($stockQty); ?>" class="quantity-input" readonly>
                        <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 15px;">
                        <form action="<?php echo e(route('cart.add', $product->id ?? 1)); ?>" method="POST" id="formAddToCart">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="quantity" id="cartQuantity" value="1">
                            <button type="submit" class="btn-add-cart">
                                🛒 Thêm vào giỏ
                            </button>
                        </form>

                        <a id="btnCheckout" href="<?php echo e(route('checkout.index', $product->id ?? 1)); ?>?quantity=1" class="btn-buy">
                            ⚡ Mua ngay
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="out-of-stock">
                    ⚠️ Sản phẩm hiện tại đã hết hàng trong kho.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="description-box">
        <h3 class="description-title">Mô tả sản phẩm</h3>
        <div class="description-content">
            <?php echo $product->description ?? $product->mo_ta ?? '<p style="color: #64748b; font-style: italic;">Chưa có thông tin mô tả chi tiết cho sản phẩm này.</p>'; ?>

        </div>
    </div>

    <!-- KHỐI SẢN PHẨM GỢI Ý -->
    <div class="description-box" style="margin-top: 32px;">
        <h3 class="description-title" style="display: flex; align-items: center; justify-content: space-between;">
            <span>✨ Sản phẩm gợi ý dành cho bạn</span>
            <span style="font-size: 12px; color: #059669; font-weight: 600; background: #ECFDF5; padding: 4px 10px; border-radius: 20px;">AI Powered</span>
        </h3>
        
        <div id="recommendation-list" style="display: grid !important; grid-template-columns: repeat(4, minmax(0, 1fr)) !important; gap: 20px !important; width: 100% !important;">
            <?php 
                $recs = isset($recommendedProducts) ?$recommendedProducts : array();
                if (count($recs) > 0):
                    foreach ($recs as $item):$recImg = $item->image ?? $item->image_url ?? null;
                        $recImgUrl = 'https://via.placeholder.com/300x200?text=Dac+San';
                        if ($recImg) {
                            $cleanRec = ltrim($recImg, '/');
                            $recImgUrl = str_starts_with($cleanRec, 'http') ? $cleanRec : (str_starts_with($cleanRec, 'storage/') ? asset($cleanRec) : asset('storage/' .$cleanRec));
                        }
                        $itemTitle = $item->title ?? $item->name ?? 'Sản phẩm';
                        $itemPrice =$item->discount_price ?? $item->base_price ?? $item->price ?? 0;
                        $itemLoc = $item->location ?? $item->origin ?? 'Đặc sản';
            ?>
                        <a href="<?php echo route('products.show', $item->id); ?>" style="display: flex; flex-direction: column; justify-content: space-between; text-decoration: none; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
                            <div>
                                <div style="position: relative; width: 100%; height: 150px; background: #f8fafc; overflow: hidden;">
                                    <img src="<?php echo $recImgUrl; ?>" alt="<?php echo e($itemTitle); ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Dac+San';" />
                                    <span style="position: absolute; top: 10px; left: 10px; font-size: 11px; font-weight: 700; color: #0F5132; background: rgba(255, 255, 255, 0.92); padding: 3px 10px; border-radius: 20px; backdrop-filter: blur(4px); box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                                        📍 <?php echo e($itemLoc); ?>
                                    </span>
                                </div>

                                <div style="padding: 12px 14px 6px 14px;">
                                    <h4 style="font-size: 14px; font-weight: 700; color: #1F2937; margin: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px;">
                                        <?php echo e($itemTitle); ?>
                                    </h4>
                                </div>
                            </div>
                            
                            <div style="padding: 0 14px 14px 14px;">
                                <span style="font-size: 16px; font-weight: 800; color: #059669;">
                                    <?php echo number_format($itemPrice); ?>đ
                                </span>
                            </div>
                        </a>
            <?php 
                    endforeach;
                else:
            ?>
                    <div style="grid-column: 1 / -1; text-align: center; color: #94A3B8; padding: 20px 0;">
                        Đang tính toán sản phẩm phù hợp...
                    </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function increaseQuantity() {
        let input = document.getElementById('quantity');
        let cartInput = document.getElementById('cartQuantity');
        let btn = document.getElementById('btnCheckout');
        let max = parseInt(input.getAttribute('max')) || 99;
        let value = parseInt(input.value) || 1;
        if (value < max) {
            value++;
            input.value = value;
            if (cartInput) cartInput.value = value;
            btn.href = "<?php echo e(route('checkout.index', $product->id ?? 1)); ?>?quantity=" + value;
        }
    }

    function decreaseQuantity() {
        let input = document.getElementById('quantity');
        let cartInput = document.getElementById('cartQuantity');
        let btn = document.getElementById('btnCheckout');
        let value = parseInt(input.value) || 1;
        if (value > 1) {
            value--;
            input.value = value;
            if (cartInput) cartInput.value = value;
            btn.href = "<?php echo e(route('checkout.index', $product->id ?? 1)); ?>?quantity=" + value;
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const productId = <?php echo e($product->id ?? 1); ?>;

        fetch(`http://127.0.0.1:5000/api/recommend/${productId}`)
            .then(res => {
                if (!res.ok) throw new Error("Server Python chưa khả dụng");
                return res.json();
            })
            .then(res => {
                const container = document.getElementById('recommendation-list');
                if (res.data && res.data.length > 0) {
                    container.innerHTML = res.data.map(item => {
                        const rawImg = item.image || item.image_url || item.hinh_anh || '';
                        let imgSrc = 'https://via.placeholder.com/300x200?text=Dac+San';
                        
                        if (rawImg) {
                            if (rawImg.startsWith('http')) {
                                imgSrc = rawImg;
                            } else if (rawImg.startsWith('/')) {
                                imgSrc = rawImg;
                            } else {
                                imgSrc = '/storage/' + rawImg;
                            }
                        }

                        const formattedPrice = item.base_price ? Number(item.base_price).toLocaleString('vi-VN') + 'đ' : (item.price ? Number(item.price).toLocaleString('vi-VN') + 'đ' : 'Liên hệ');
                        const locationTag = item.location || item.origin ? `📍 ${item.location || item.origin}` : '📍 Đặc sản';
                        const title = item.title || item.name || 'Sản phẩm gợi ý';

                        return `
                            <a href="/san-pham/${item.id}" style="display: flex; flex-direction: column; justify-content: space-between; text-decoration: none; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 20px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
                                <div>
                                    <div style="position: relative; width: 100%; height: 150px; background: #f8fafc; overflow: hidden;">
                                        <img src="${imgSrc}" 
                                             alt="${title}" 
                                             style="width: 100%; height: 100%; object-fit: cover;" 
                                             onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Anh+San+Pham';" />
                                        
                                        <span style="position: absolute; top: 10px; left: 10px; font-size: 11px; font-weight: 700; color: #0F5132; background: rgba(255, 255, 255, 0.92); padding: 3px 10px; border-radius: 20px; backdrop-filter: blur(4px); box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                                            ${locationTag}
                                        </span>
                                    </div>

                                    <div style="padding: 12px 14px 6px 14px;">
                                        <h4 style="font-size: 14px; font-weight: 700; color: #1F2937; margin: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px;">
                                            ${title}
                                        </h4>
                                    </div>
                                </div>
                                
                                <div style="padding: 0 14px 14px 14px;">
                                    <span style="font-size: 16px; font-weight: 800; color: #059669;">
                                        ${formattedPrice}
                                    </span>
                                </div>
                            </a>
                        `;
                    }).join('');
                }
            })
            .catch(err => {
                console.warn("Dịch vụ Python AI Microservice offline. Đã sử dụng danh sách gợi ý từ Laravel.");
            });
    });
</script>
</body>
</html><?php /**PATH C:\Users\Admin\Downloads\du-lich-so\backend\resources\views/products/show.blade.php ENDPATH**/ ?>