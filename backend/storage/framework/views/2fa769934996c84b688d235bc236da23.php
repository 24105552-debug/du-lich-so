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
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Thanh toán QR cho <?php echo e($orders->count()); ?> đơn hàng
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl p-6 text-center">
                
                <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Tổng tiền cần thanh toán</h3>
                <div class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mb-4">
                    <?php echo e(number_format($total, 0, ',', '.')); ?>đ
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg mb-6 inline-block">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Nội dung chuyển khoản:</span>
                    <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 text-sm ml-1"><?php echo e($transferContent); ?></span>
                </div>

                <!-- Mã QR Tự Động (VietQR) -->
                <div class="flex justify-center mb-6">
                    <?php 
                        $bankId = 'MB'; // Tên ngân hàng (MB, VCB, ICB, TCB, ...)
                        $accountNo = '0123456789'; // Số tài khoản nhận tiền
                        $accountName = 'DAC SAN VIET'; // Tên chủ tài khoản
                        $addInfo = urlencode($transferContent);
                        
                        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$total}&addInfo={$addInfo}&accountName={$accountName}";
                    ?>
                    <div class="p-3 bg-white border border-gray-200 rounded-2xl shadow-sm">
                        <img src="<?php echo e($qrUrl); ?>" alt="Mã QR Thanh Toán" class="w-64 h-64 object-contain mx-auto">
                    </div>
                </div>

                <!-- Form Upload Minh Chứng Cho Cả Nhóm Đơn -->
                <form action="<?php echo e(route('orders.upload_proof_multi')); ?>" method="POST" enctype="multipart/form-data" class="max-w-md mx-auto text-left border-t border-gray-100 dark:border-gray-700 pt-6">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="ids" value="<?php echo e($ids); ?>">

                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tải ảnh minh chứng chuyển khoản (Bill chuyển tiền)
                    </label>
                    
                    <input type="file" name="payment_proof" accept="image/*" required class="block w-full text-sm text-gray-500 dark:text-gray-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-emerald-50 file:text-emerald-700
                        hover:file:bg-emerald-100 dark:file:bg-emerald-950 dark:file:text-emerald-300 mb-4 cursor-pointer">

                    <?php $__errorArgs = ['payment_proof'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mb-4"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition-colors shadow-sm text-sm">
                        Gửi minh chứng & Hoàn tất
                    </button>
                </form>

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
<?php endif; ?><?php /**PATH C:\Users\Admin\Downloads\du-lich-so\backend\resources\views/orders/payment_qr_multi.blade.php ENDPATH**/ ?>