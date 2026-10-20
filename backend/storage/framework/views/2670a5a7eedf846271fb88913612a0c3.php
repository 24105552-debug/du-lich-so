<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giỏ hàng – Đặc sản Việt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; background-color: #F8FAFC; color: #1E293B; }
        .cart-header { background: #0B3B2C; color: white; padding: 20px 0; }
        .product-img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; }
        .btn-green { background-color: #0B3B2C; color: white; font-weight: 700; }
        .btn-green:hover { background-color: #14532D; color: white; }
        .btn-green.disabled { background-color: #94A3B8; border-color: #94A3B8; color: #fff; pointer-events: none; }
        .cart-check { width: 20px; height: 20px; cursor: pointer; accent-color: #0B3B2C; }
        tr.row-unchecked { opacity: .55; }
    </style>
</head>
<body>

<div class="cart-header mb-4 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold">🛒 Giỏ hàng của bạn</h4>
        <a href="<?php echo e(url('/')); ?>" class="text-white text-decoration-none font-semibold">← Quay lại trang chủ</a>
    </div>
</div>

<div class="container py-2">
    <?php if(session('error')): ?>
        <div class="alert alert-danger rounded-3"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <?php if(session('cart') && count(session('cart')) > 0): ?>
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">
                                <input type="checkbox" id="check-all" class="cart-check" title="Chọn tất cả" checked>
                            </th>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th>Thành tiền</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = session('cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $subtotal = $item['price'] * $item['quantity'];
                            ?>
                            <tr data-id="<?php echo e($id); ?>" data-subtotal="<?php echo e($subtotal); ?>">
                                <td class="text-center">
                                    <input type="checkbox" class="cart-check row-check" checked>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php
                                            $rawImg = $item['image'] ?? null;
                                            $imgUrl = 'https://via.placeholder.com/70?text=No+Img';
                                            if ($rawImg) {
                                                $imgUrl = str_starts_with($rawImg, 'http') ? $rawImg : asset('storage/' . ltrim($rawImg, '/'));
                                            }
                                        ?>
                                        <img src="<?php echo e($imgUrl); ?>" class="product-img border" alt="<?php echo e($item['title']); ?>">
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark"><?php echo e($item['title']); ?></h6>
                                            <small class="text-muted">Xuất xứ: <?php echo e($item['origin'] ?? 'Việt Nam'); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-semibold"><?php echo e(number_format($item['price'], 0, ',', '.')); ?>đ</td>
                                <td>
                                    <input type="number" value="<?php echo e($item['quantity']); ?>" min="1" class="form-control update-cart text-center mx-auto" style="width: 75px;">
                                </td>
                                <td class="text-success fw-bold"><?php echo e(number_format($subtotal, 0, ',', '.')); ?>đ</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger remove-from-cart px-3 rounded-3">Xóa</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-secondary rounded-3 px-4">← Tiếp tục mua sắm</a>
                <div class="text-end">
                    <div class="text-muted small mb-1">Đã chọn <b id="selected-count">0</b> sản phẩm</div>
                    <h5 class="mb-2">Tổng thanh toán: <span id="selected-total" class="text-danger fs-3 fw-bold ms-2">0đ</span></h5>
                    <a href="#" id="btn-buy" class="btn btn-green btn-lg px-5 py-2 rounded-3 mt-2 disabled">
                        Mua hàng
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="py-4">
                <div class="fs-1 text-muted mb-3">🛒</div>
                <h5 class="fw-bold text-secondary">Giỏ hàng của bạn đang trống</h5>
                <p class="text-muted mb-4">Hãy dạo một vòng để chọn cho mình những món đặc sản ngon nhất nhé!</p>
                <a href="<?php echo e(url('/')); ?>" class="btn btn-green px-4 py-2 rounded-3">Khám phá sản phẩm ngay</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // ===== CHỌN SẢN PHẨM =====
    var CHECKOUT_URL = '<?php echo e(route("checkout.cart")); ?>';
    var UNCHECKED_KEY = 'cart_unchecked_ids'; // lưu các sp BỎ tick, để sp mới thêm vào mặc định được tick

    function getUnchecked() {
        try { return JSON.parse(localStorage.getItem(UNCHECKED_KEY)) || []; } catch (e) { return []; }
    }
    function saveUnchecked() {
        var arr = [];
        $('.row-check').each(function () {
            if (!this.checked) arr.push(String($(this).closest('tr').attr('data-id')));
        });
        try { localStorage.setItem(UNCHECKED_KEY, JSON.stringify(arr)); } catch (e) {}
    }
    function formatVND(n) { return Number(n).toLocaleString('vi-VN') + 'đ'; }

    function refreshSelection() {
        var total = 0, ids = [];
        var $rows = $('tbody tr');

        $rows.each(function () {
            var $tr = $(this);
            var checked = $tr.find('.row-check').prop('checked');
            $tr.toggleClass('row-unchecked', !checked);
            if (checked) {
                total += parseFloat($tr.attr('data-subtotal')) || 0;
                ids.push($tr.attr('data-id'));
            }
        });

        var count = ids.length, all = $rows.length, $all = $('#check-all');
        $('#selected-total').text(formatVND(total));
        $('#selected-count').text(count);
        $all.prop('checked', count === all && all > 0);
        $all.prop('indeterminate', count > 0 && count < all);

        var $btn = $('#btn-buy');
        if (count === 0) {
            $btn.addClass('disabled').attr('href', '#');
        } else {
            var query = ids.map(function (id) { return 'items[]=' + encodeURIComponent(id); }).join('&');
            $btn.removeClass('disabled').attr('href', CHECKOUT_URL + '?' + query);
        }
    }

    // Khôi phục trạng thái tick sau khi trang tải lại
    (function restore() {
        var unchecked = getUnchecked();
        $('tbody tr').each(function () {
            var id = String($(this).attr('data-id'));
            $(this).find('.row-check').prop('checked', unchecked.indexOf(id) === -1);
        });
        refreshSelection();
    })();

    $(document).on('change', '.row-check', function () { saveUnchecked(); refreshSelection(); });
    $('#check-all').on('change', function () {
        $('.row-check').prop('checked', this.checked);
        saveUnchecked(); refreshSelection();
    });
    $('#btn-buy').on('click', function (e) {
        if ($(this).hasClass('disabled')) e.preventDefault();
    });

    // ===== Cập nhật số lượng bằng AJAX =====
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: '<?php echo e(route("cart.update")); ?>',
            method: "PATCH",
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                id: ele.parents("tr").attr("data-id"),
                quantity: ele.val()
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });

    // ===== Xóa sản phẩm khỏi giỏ bằng AJAX =====
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?")) {
            $.ajax({
                url: '<?php echo e(route("cart.remove")); ?>',
                method: "DELETE",
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
</script>
</body>
</html><?php /**PATH C:\Users\Admin\Downloads\du-lich-so\backend\resources\views/cart/index.blade.php ENDPATH**/ ?>