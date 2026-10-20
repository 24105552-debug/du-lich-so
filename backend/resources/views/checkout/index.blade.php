<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="javascript:history.back()" class="p-2 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                        Thanh toán đơn hàng
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Hoàn tất thông tin để nhận hàng hoặc vé tham quan</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-xs font-medium px-3.5 py-1.5 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200 shadow-sm">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.172 2a1 1 0 011.414 0L10 10.586l3.414-3.586a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                Bảo mật mã hóa 256-bit
            </div>
        </div>
    </x-slot>

    @php
        $total = collect($items)->sum('subtotal');
        $shippingFee = 0;
        $grandTotal = $total + $shippingFee;
    @endphp

    <div class="py-8 bg-slate-50/70 min-h-[calc(100vh-80px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-medium shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                @csrf
                @foreach($items as $i => $item)
                    <input type="hidden" name="items[{{ $i }}][product_id]" value="{{ $item['product']->id }}">
                    <input type="hidden" name="items[{{ $i }}][quantity]" value="{{ $item['quantity'] }}">
                @endforeach
                @if($fromCart ?? false)
                    <input type="hidden" name="from_cart" value="1">
                @endif

                <!-- CỘT TRÁI: THÔNG TIN GIAO HÀNG & THANH TOÁN (7 CỘT) -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- Box 1: Thông tin liên hệ -->
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200/80 shadow-sm space-y-5">
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                            <span class="flex items-center justify-center w-8 h-8 bg-indigo-600 text-white font-black text-sm rounded-xl shadow-md shadow-indigo-200">1</span>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Thông tin người nhận hàng</h3>
                                <p class="text-xs text-gray-500">Vui lòng điền đúng thông tin để đơn vị vận chuyển giao tận nơi</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Họ và tên <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required placeholder="Nguyễn Văn A"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm font-medium text-gray-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Số điện thoại <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="0901 234 567"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm font-medium text-gray-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Địa chỉ giao hàng cụ thể <span class="text-rose-500">*</span></label>
                            <input type="text" name="address" value="{{ old('address') }}" required placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm font-medium text-gray-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Ghi chú cho đơn hàng (Tùy chọn)</label>
                            <textarea name="note" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-900 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <!-- Box 2: Phương thức thanh toán -->
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200/80 shadow-sm space-y-4">
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                            <span class="flex items-center justify-center w-8 h-8 bg-indigo-600 text-white font-black text-sm rounded-xl shadow-md shadow-indigo-200">2</span>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Hình thức thanh toán</h3>
                                <p class="text-xs text-gray-500">Chọn phương thức thanh toán thuận tiện nhất</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-2">
                            <label class="flex items-center justify-between p-4 rounded-xl border-2 border-indigo-600 bg-indigo-50/30 cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">Thanh toán khi nhận hàng (COD)</p>
                                        <p class="text-xs text-gray-500">Kiểm tra hàng trước, thanh toán tiền mặt sau</p>
                                    </div>
                                </div>
                                <span class="text-xl">💵</span>
                            </label>

                            <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-indigo-300 cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="qr" {{ old('payment_method') === 'qr' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">Chuyển khoản QR Code (Bank Transfer)</p>
                                        <p class="text-xs text-gray-500">Quét mã QR qua ứng dụng ngân hàng tự động</p>
                                    </div>
                                </div>
                                <span class="text-xl">🏦</span>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG (5 CỘT) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200/80 shadow-sm sticky top-6">
                        <h3 class="text-lg font-extrabold text-gray-900 pb-4 border-b border-gray-100 flex items-center justify-between">
                            <span>Đơn hàng của bạn</span>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg">{{ count($items) }} sản phẩm</span>
                        </h3>

                        <!-- Danh sách sản phẩm -->
                        <div class="divide-y divide-gray-100 max-h-[420px] overflow-y-auto">
                            @foreach($items as $item)
                                @php $product = $item['product']; @endphp
                                <div class="py-5 flex gap-4">
                                    <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 flex-shrink-0">
                                        @if(!empty($product->image))
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title ?? $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 text-indigo-400 text-xs font-bold">Nông Sản</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900 line-clamp-2 leading-snug">{{ $product->title ?? $product->name }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">Xuất xứ: <span class="font-medium text-gray-700">{{ $product->location ?? 'Chính hãng' }}</span></p>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">x{{ $item['quantity'] }}</span>
                                            <span class="text-sm font-extrabold text-indigo-600">
                                                {{ number_format($item['unit_price']) }}đ
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Bảng tính tiền -->
                        <div class="py-4 space-y-3 text-sm border-y border-gray-100">
                            <div class="flex justify-between text-gray-600 font-medium">
                                <span>Tạm tính</span>
                                <span class="text-gray-900 font-semibold">{{ number_format($total) }}đ</span>
                            </div>
                            <div class="flex justify-between text-gray-600 font-medium">
                                <span>Phí vận chuyển</span>
                                <span class="text-emerald-600 font-bold">Miễn phí</span>
                            </div>
                        </div>

                        <!-- Tổng tiền -->
                        <div class="pt-4 pb-6">
                            <div class="flex justify-between items-baseline mb-1">
                                <span class="text-base font-bold text-gray-900">Tổng thanh toán</span>
                                <span class="text-2xl font-black text-emerald-600">{{ number_format($grandTotal) }}đ</span>
                            </div>
                            <p class="text-right text-[11px] text-gray-400">(Đã bao gồm thuế VAT & phí dịch vụ)</p>
                        </div>

                        <!-- Nút đặt hàng -->
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-extrabold text-base rounded-xl transition duration-200 shadow-xl shadow-indigo-200 flex items-center justify-center gap-2 transform active:scale-[0.99]">
                            <span>ĐẶT HÀNG NGAY</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>

                        <!-- Cam kết -->
                        <div class="mt-5 p-3.5 bg-emerald-50/80 rounded-xl border border-emerald-100 flex items-center gap-3 text-xs text-emerald-800">
                            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Cam kết sản phẩm tươi sạch, đổi trả 100% nếu có lỗi từ nhà vườn.</span>
                        </div>

                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>