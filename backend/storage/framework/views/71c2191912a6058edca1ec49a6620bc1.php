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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quản lý Đơn hàng Admin</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <?php if(session('success')): ?>
                <div class="p-4 bg-emerald-100 border border-emerald-400 text-emerald-800 rounded-xl text-sm font-medium">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left font-semibold text-gray-500 uppercase text-xs">
                            <th class="p-3">Mã đơn</th>
                            <th class="p-3">Khách hàng</th>
                            <th class="p-3">Sản phẩm</th>
                            <th class="p-3">Tổng tiền</th>
                            <th class="p-3">Minh chứng QR</th>
                            <th class="p-3">Trạng thái</th>
                            <th class="p-3 text-right">Thao tác duyệt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="p-3 font-bold text-indigo-600">#<?php echo e($order->order_code); ?></td>
                                <td class="p-3">
                                    <div class="font-bold"><?php echo e($order->customer_name); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($order->customer_phone); ?></div>
                                </td>
                                <td class="p-3">
    <?php echo e($order->product?->title ?? $order->product?->name ?? 'Sản phẩm không tồn tại / Đã xóa'); ?> 
    (x<?php echo e($order->quantity ?? 1); ?>)
</td>
                                <td class="p-3 font-bold text-emerald-600"><?php echo e(number_format($order->total_amount)); ?>đ</td>
                                <td class="p-3">
                                    <?php if($order->payment_proof): ?>
                                        <a href="<?php echo e(asset('storage/' . $order->payment_proof)); ?>" target="_blank" class="text-xs font-bold text-indigo-600 hover:underline flex items-center gap-1">
                                            🔍 Xem ảnh CK
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">Không có</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                        <?php echo e($order->status == 'pending_approval' ? 'bg-amber-100 text-amber-800' : ''); ?>

                                        <?php echo e($order->status == 'processing' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                        <?php echo e($order->status == 'shipping' ? 'bg-indigo-100 text-indigo-800' : ''); ?>

                                        <?php echo e($order->status == 'completed' ? 'bg-emerald-100 text-emerald-800' : ''); ?>">
                                        <?php echo e($order->status == 'pending_approval' ? 'Chờ duyệt' : ''); ?>

                                        <?php echo e($order->status == 'processing' ? 'Đang chuẩn bị' : ''); ?>

                                        <?php echo e($order->status == 'shipping' ? 'Đang giao' : ''); ?>

                                        <?php echo e($order->status == 'completed' ? 'Đã giao' : ''); ?>

                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <form action="<?php echo e(route('admin.orders.updateStatus', $order->id)); ?>" method="POST" class="inline-flex gap-1">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 py-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="pending_approval" <?php echo e($order->status == 'pending_approval' ? 'selected' : ''); ?>>Chờ duyệt</option>
                                            <option value="processing" <?php echo e($order->status == 'processing' ? 'selected' : ''); ?>>Đang chuẩn bị hàng</option>
                                            <option value="shipping" <?php echo e($order->status == 'shipping' ? 'selected' : ''); ?>>Đang giao hàng</option>
                                            <option value="completed" <?php echo e($order->status == 'completed' ? 'selected' : ''); ?>>Đã giao thành công</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="mt-4">
                    <?php echo e($orders->links()); ?>

                </div>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\Admin\Downloads\du-lich-so\backend\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>