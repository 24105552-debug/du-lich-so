<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Bảng điều khiển Quản trị')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <!-- Thêm thư viện Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. LỜI CHÀO & QUYỀN -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Xin chào, <?php echo e(Auth::user()->name); ?>! 👋</h3>
                    <p class="text-sm text-gray-500 mt-1">Chào mừng bạn quay trở lại với Hệ thống Quản lý Du Lịch Số (CSE703073).</p>
                </div>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1.5 rounded-full uppercase">
                    <?php echo e(Auth::user()->role ?? 'ADMIN'); ?>

                </span>
            </div>

            <!-- 2. KHỐI THẺ CHỈ SỐ (KPI CARDS) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Thẻ 1: Doanh thu -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tổng doanh thu</p>
                        <h4 class="text-2xl font-extrabold text-emerald-600 mt-1">15.450.000đ</h4>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold">
                        💰
                    </div>
                </div>

                <!-- Thẻ 2: Đơn hàng -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tổng đơn hàng</p>
                        <h4 class="text-2xl font-extrabold text-indigo-600 mt-1">24 đơn</h4>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl font-bold">
                        📦
                    </div>
                </div>

                <!-- Thẻ 3: Sản phẩm -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sản phẩm / Tour</p>
                        <h4 class="text-2xl font-extrabold text-amber-600 mt-1">8 sản phẩm</h4>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl font-bold">
                        🛒
                    </div>
                </div>

                <!-- Thẻ 4: Khách hàng -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Khách hàng</p>
                        <h4 class="text-2xl font-extrabold text-blue-600 mt-1">12 người</h4>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl font-bold">
                        👥
                    </div>
                </div>
            </div>

            <!-- 3. KHỐI BIỂU ĐỒ (CHARTS) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Biểu đồ Doanh thu -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-base font-bold text-gray-900 mb-4">📈 Biểu đồ tăng trưởng doanh thu tuần</h3>
                    <div class="relative h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Biểu đồ Trạng thái Đơn -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-base font-bold text-gray-900 mb-4">📊 Tỷ lệ trạng thái đơn hàng</h3>
                    <div class="relative h-64 flex items-center justify-center">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 4. LỐI TẮT CHỨC NĂNG QUẢN TRỊ -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-bold text-gray-700 mb-4">⚡ Lối tắt Chức năng Quản trị</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="p-4 rounded-xl border border-indigo-100 bg-indigo-50/50 hover:bg-indigo-100 transition flex items-center gap-3">
                        <span class="text-2xl">📦</span>
                        <div>
                            <b class="text-sm text-gray-800 block">Quản lý Đơn hàng</b>
                            <span class="text-xs text-gray-500">Xem & Duyệt đơn thanh toán</span>
                        </div>
                    </a>
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/50 hover:bg-emerald-100 transition flex items-center gap-3">
                        <span class="text-2xl">🛒</span>
                        <div>
                            <b class="text-sm text-gray-800 block">Quản lý Sản phẩm</b>
                            <span class="text-xs text-gray-500">Danh sách, Nổi bật, Giảm giá</span>
                        </div>
                    </a>
                    <a href="<?php echo e(route('admin.products.create')); ?>" class="p-4 rounded-xl border border-amber-100 bg-amber-50/50 hover:bg-amber-100 transition flex items-center gap-3">
                        <span class="text-2xl">➕</span>
                        <div>
                            <b class="text-sm text-gray-800 block">Thêm Sản phẩm</b>
                            <span class="text-xs text-gray-500">Tạo tour/dịch vụ du lịch mới</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT KHỞI TẠO BIỂU ĐỒ -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Biểu đồ Doanh thu (Line)
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: [1200000, 1900000, 3000000, 2500000, 2200000, 3000000, 1650000],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10B981',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Biểu đồ Trạng thái Đơn (Doughnut)
            const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Hoàn thành', 'Chờ duyệt', 'Đang xử lý', 'Đã hủy'],
                    datasets: [{
                        data: [12, 5, 4, 3],
                        backgroundColor: ['#10B981', '#F59E0B', '#3B82F6', '#EF4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\Admin\Downloads\du-lich-so\backend\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>