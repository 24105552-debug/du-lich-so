<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Quản lý Sản phẩm / Tour') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-sm">
                + Thêm mới
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Thông báo thành công -->
            @if(session('success'))
                <div class="p-4 bg-emerald-100 border border-emerald-400 text-emerald-800 rounded-xl text-sm font-medium shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Bộ lọc sản phẩm -->
            <div class="flex space-x-2">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-xs font-semibold rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm">Tất cả</a>
                <a href="{{ route('admin.products.index', ['filter' => 'featured']) }}" class="px-4 py-2 text-xs font-semibold rounded-lg bg-amber-50 border border-amber-200 text-amber-700 hover:bg-amber-100 shadow-sm">⭐ Nổi bật</a>
                <a href="{{ route('admin.products.index', ['filter' => 'discount']) }}" class="px-4 py-2 text-xs font-semibold rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100 shadow-sm">🏷️ Giảm giá</a>
            </div>

            <!-- Bảng danh sách sản phẩm -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Tên sản phẩm</th>
                                <th class="px-4 py-3">Mã Truy Xuất (QR)</th>
                                <th class="px-4 py-3">Giá gốc</th>
                                <th class="px-4 py-3">Giá giảm</th>
                                <th class="px-4 py-3 text-center">Tồn kho</th>
                                <th class="px-4 py-3">Nổi bật</th>
                                <th class="px-4 py-3 text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3 font-semibold">{{ $product->id }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $product->title ?? $product->name }}
                                    </td>
                                    <!-- Cột Mã Truy Xuất QR -->
                                    <td class="px-4 py-3">
                                        <a href="{{ $product->qr_url }}" target="_blank" class="inline-flex items-center gap-1 font-mono font-bold text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 px-2.5 py-1 rounded-lg transition" title="Bấm để xem trang truy xuất">
                                            🔍 {{ $product->qr_code }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">{{ number_format($product->base_price ?? $product->price) }}đ</td>
                                    <td class="px-4 py-3">
                                        @if($product->is_discount && $product->discount_price)
                                            <span class="text-emerald-600 font-bold">{{ number_format($product->discount_price) }}đ</span>
                                        @else
                                            <span class="text-gray-400">Không giảm</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ ($product->stock ?? 0) > 0 ? 'bg-blue-50 text-blue-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $product->stock ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('admin.products.toggleFeatured', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-semibold transition {{ $product->is_featured ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                                {{ $product->is_featured ? '★ Nổi bật' : '☆ Đặt nổi bật' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <!-- Nút Xem QR -->
                                        <a href="{{ $product->qr_url }}" target="_blank" class="inline-block text-emerald-600 hover:text-emerald-800 font-semibold text-xs bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg transition">
                                            Tra cứu
                                        </a>

                                        <!-- Nút Sửa -->
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-block text-indigo-600 hover:text-indigo-800 font-semibold text-xs bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                            Sửa
                                        </a>

                                        <!-- Nút Xóa -->
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-xs bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition">
                                                Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Chưa có sản phẩm nào trong hệ thống.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>