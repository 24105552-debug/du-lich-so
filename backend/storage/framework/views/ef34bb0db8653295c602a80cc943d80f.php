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
            <?php echo e(Auth::user()?->role === 'admin' ? __('Bảng điều khiển Quản trị') : __('Bảng điều khiển Cá nhân')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <!-- Nhúng thư viện Chart.js qua CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Banner xin chào -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-indigo-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Xin chào, <?php echo e(Auth::user()->name); ?>! 👋</h3>
                        <p class="mt-1 text-sm text-gray-600">Chào mừng bạn quay trở lại với Hệ thống Quản lý Du Lịch Số (CSE703073).</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-full uppercase tracking-wider">
                        <?php echo e(Auth::user()->role ?? 'customer'); ?>

                    </span>
                </div>
            </div>

            <?php if(Auth::user()?->role === 'admin'): ?>
            <!-- ================= KHỐI THỐNG KÊ VÀ BIỂU ĐỒ QUẢN TRỊ ================= -->
            
            <!-- 1. CÁC THẺ CHỈ SỐ KPI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Thẻ Doanh Thu -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tổng doanh thu</p>
                        <h4 class="text-2xl font-extrabold text-emerald-600 mt-1">15.450.000đ</h4>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center text-xl">
                        💰
                    </div>
                </div>

                <!-- Thẻ Đơn Hàng -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tổng đơn hàng</p>
                        <h4 class="text-2xl font-extrabold text-indigo-600 mt-1">24 đơn</h4>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-xl">
                        📦
                    </div>
                </div>

                <!-- Thẻ Sản Phẩm -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sản phẩm / Tour</p>
                        <h4 class="text-2xl font-extrabold text-amber-600 mt-1">8 sản phẩm</h4>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center text-xl">
                        🛒
                    </div>
                </div>

                <!-- Thẻ Khách Hàng -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Khách hàng</p>
                        <h4 class="text-2xl font-extrabold text-sky-600 mt-1">12 người</h4>
                    </div>
                    <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-lg flex items-center justify-center text-xl">
                        👥
                    </div>
                </div>
            </div>

            <!-- 2. KHỐI BIỂU ĐỒ BÁO CÁO -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Biểu đồ Doanh Thu -->
                <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h4 class="text-base font-bold text-gray-900 mb-4 flex items-center">
                        📈 Biểu đồ tăng trưởng doanh thu tuần
                    </h4>
                    <div class="relative h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Biểu đồ Trạng Thái Đơn -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h4 class="text-base font-bold text-gray-900 mb-4 flex items-center">
                        📊 Tỷ lệ trạng thái đơn hàng
                    </h4>
                    <div class="relative h-64 flex items-center justify-center">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 3. LỐI TẮT CHỨC NĂNG QUẢN TRỊ -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h4 class="text-base font-semibold text-gray-900 border-b pb-3 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Lối tắt Chức năng Quản trị
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Nút Quản lý Sản phẩm -->
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-xl transition-all duration-200 group shadow-sm hover:shadow">
                        <div class="p-3 bg-indigo-600 text-white rounded-lg group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-gray-900">Quản lý Sản phẩm</p>
                            <p class="text-xs text-gray-500 mt-0.5">Danh sách, Nổi bật, Giảm giá</p>
                        </div>
                    </a>

                    <!-- Nút Thêm Sản phẩm mới -->
                    <a href="<?php echo e(route('admin.products.create')); ?>" class="flex items-center p-4 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 rounded-xl transition-all duration-200 group shadow-sm hover:shadow">
                        <div class="p-3 bg-emerald-600 text-white rounded-lg group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-gray-900">Thêm Sản phẩm</p>
                            <p class="text-xs text-gray-500 mt-0.5">Tạo tour/dịch vụ du lịch mới</p>
                        </div>
                    </a>

                    <!-- Nút Quản lý Tài khoản Người dùng -->
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="flex items-center p-4 bg-sky-50 hover:bg-sky-100 border border-sky-100 rounded-xl transition-all duration-200 group shadow-sm hover:shadow">
                        <div class="p-3 bg-sky-600 text-white rounded-lg group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-gray-900">Quản lý Người dùng</p>
                            <p class="text-xs text-gray-500 mt-0.5">Xem & Đổi mật khẩu tài khoản</p>
                        </div>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Khung thông tin chi tiết -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Thẻ thông tin cá nhân -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 md:col-span-1">
                    <h4 class="text-base font-semibold text-gray-900 border-b pb-3 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Thông tin tài khoản
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</span>
                            <p class="font-medium text-gray-800 mt-0.5"><?php echo e(Auth::user()->email); ?></p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Vai trò</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold <?php echo e(Auth::user()?->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800'); ?>">
                                    <?php echo e(Auth::user()->role ?? 'customer'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thẻ thông tin dự án/học phần -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 md:col-span-2">
                    <h4 class="text-base font-semibold text-gray-900 border-b pb-3 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Thông tin hệ thống & Học phần
                    </h4>
                    <div class="space-y-2 text-sm text-gray-700">
                        <p><strong class="font-medium text-gray-900">Học phần:</strong> CSE703073 - Lập trình ứng dụng web trong du lịch 2</p>
                        <p><strong class="font-medium text-gray-900">Thực hiện:</strong> Nhóm Nhom03 · Trường Công nghệ thông tin, Đại học Phenikaa</p>
                    </div>

                    <div class="mt-4 p-4 bg-amber-50 rounded-lg border border-amber-200 text-xs text-amber-800 leading-relaxed">
                        Sản phẩm học thuật – phục vụ mục đích đào tạo. Dữ liệu trong hệ thống là dữ liệu mô phỏng hoặc dữ liệu công khai; giao dịch thanh toán ở chế độ thử nghiệm.
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400">
                        <span>Trạng thái: Hoạt động</span>
                        <span>Phiên bản v1.0.0</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT TẠO BIỂU ĐỒ -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // 1. Biểu đồ Doanh Thu
            const elRevenue = document.getElementById('revenueChart');
            if (elRevenue) {
                new Chart(elRevenue.getContext('2d'), {
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
                            pointRadius: 4
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
            }

            // 2. Biểu đồ Trạng Thái Đơn
            const elStatus = document.getElementById('orderStatusChart');
            if (elStatus) {
                new Chart(elStatus.getContext('2d'), {
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
            }
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
<?php endif; ?><?php /**PATH C:\Users\Admin\Downloads\du-lich-so\backend\resources\views/dashboard.blade.php ENDPATH**/ ?>