<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Danh mục đặc sản - Đặc sản Việt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Khống chế kích thước logo tuyệt đối, chống tràn màn hình */
        .site-logo {
            max-height: 50px !important;
            max-width: 200px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
        }

        /* Tối ưu giao diện thanh phân trang Laravel */
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
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- HEADER / NAVIGATION -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2" style="max-height: 50px;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Đặc sản Việt" class="site-logo" style="max-height: 50px; max-width: 180px; object-fit: contain;" onerror="this.style.display='none'">
                <span class="font-extrabold text-xl text-emerald-900 tracking-tight">ĐẶC SẢN VIỆT</span>
            </a>

            <!-- Menu Điều Hướng -->
            <nav class="hidden md:flex items-center space-x-8 text-sm font-semibold">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'text-emerald-700 font-bold border-b-2 border-emerald-600 pb-1' : 'text-gray-700 hover:text-emerald-600 transition' }}">Trang chủ</a>
                <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.index') ? 'text-emerald-700 font-bold border-b-2 border-emerald-600 pb-1' : 'text-gray-700 hover:text-emerald-600 transition' }}">Danh mục</a>
                <a href="{{ route('traceability.index') }}" class="{{ request()->routeIs('traceability.index') ? 'text-emerald-700 font-bold border-b-2 border-emerald-600 pb-1' : 'text-gray-700 hover:text-emerald-600 transition' }}">Truy xuất</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-emerald-600 transition">📦 Đơn hàng của tôi</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-600 transition">Đăng nhập</a>
                @endauth
            </nav>

            <!-- Nút Giỏ hàng & Tài khoản -->
            <div class="flex items-center space-x-4">
                @php 
                    $cartCount = array_sum(array_column(session('cart', []), 'quantity')); 
                @endphp
                <a href="{{ route('cart.index') }}" class="relative w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition" aria-label="Giỏ hàng">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 4h2l2.5 11h10L20 7H6"/><circle cx="9" cy="19" r="1.3"/><circle cx="17" cy="19" r="1.3"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 border-2 border-white shadow-sm">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-full bg-emerald-800 text-white flex items-center justify-center hover:bg-emerald-900 transition" aria-label="Tài khoản">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-10 h-10 rounded-full bg-emerald-800 text-white flex items-center justify-center hover:bg-emerald-900 transition" aria-label="Tài khoản">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- NỘI DUNG DANH MỤC SẢN PHẨM -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">

        <!-- Tiêu Đề & Thanh Tìm Kiếm -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-amber-600 font-bold uppercase text-xs sm:text-sm tracking-widest">KHÁM PHÁ ĐẶC SẢN</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-emerald-950 mt-2">Danh Mục Sản Phẩm Cội Nguồn</h1>
            <p class="text-gray-500 mt-2 text-sm sm:text-base">Tuyển chọn đặc sản đạt chuẩn OCOP và sản phẩm làng nghề truyền thống ba miền</p>

            <!-- Thanh Tìm Kiếm -->
            <form action="{{ route('categories.index') }}" method="GET" class="mt-6 max-w-2xl mx-auto">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <div class="flex items-center bg-white rounded-full p-1.5 pl-5 shadow-md border border-gray-200 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-200 transition">
                    <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>

                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Tìm kiếm đặc sản, sản phẩm.." 
                           class="w-full bg-transparent border-none outline-none text-gray-800 text-sm sm:text-base focus:ring-0">

                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-emerald-950 font-bold rounded-full px-6 py-2.5 text-sm sm:text-base transition whitespace-nowrap ml-2 shadow-sm">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>

        @php
            $currentCategory = request('category');
        @endphp

        <!-- 1. Theo Vùng Miền -->
        <div class="mb-12">
            <h2 class="text-xl sm:text-2xl font-bold text-emerald-950 mb-6">1. Theo Vùng Miền</h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
                <!-- Miền Bắc -->
                <a href="{{ request()->url() }}?category=dac-san-mien-bac" 
                   class="flex flex-col sm:flex-row items-center p-4 bg-white rounded-2xl border transition-all duration-200 gap-3 shadow-sm hover:shadow-md hover:border-emerald-500 group {{ $currentCategory == 'dac-san-mien-bac' ? 'border-2 border-emerald-600 bg-emerald-50/40' : 'border-gray-200' }}">
                   <div class="w-12 h-12 rounded-xl bg-emerald-100/60 text-emerald-800 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                       <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 20l7-12 4 6 3-4 4 10H3z"/></svg>
                   </div>
                   <span class="font-bold text-gray-800 text-sm sm:text-base text-center sm:text-left leading-tight">Đặc sản<br class="hidden sm:inline"> miền Bắc</span>
                </a>

                <!-- Miền Trung -->
                <a href="{{ request()->url() }}?category=dac-san-mien-trung" 
                   class="flex flex-col sm:flex-row items-center p-4 bg-white rounded-2xl border transition-all duration-200 gap-3 shadow-sm hover:shadow-md hover:border-emerald-500 group {{ $currentCategory == 'dac-san-mien-trung' ? 'border-2 border-emerald-600 bg-emerald-50/40' : 'border-gray-200' }}">
                   <div class="w-12 h-12 rounded-xl bg-amber-100/60 text-amber-800 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                       <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h16M12 4v16M8 8h8M8 12h8"/></svg>
                   </div>
                   <span class="font-bold text-gray-800 text-sm sm:text-base text-center sm:text-left leading-tight">Đặc sản<br class="hidden sm:inline"> miền Trung</span>
                </a>

                <!-- Miền Nam -->
                <a href="{{ request()->url() }}?category=dac-san-mien-nam" 
                   class="flex flex-col sm:flex-row items-center p-4 bg-white rounded-2xl border transition-all duration-200 gap-3 shadow-sm hover:shadow-md hover:border-emerald-500 group {{ $currentCategory == 'dac-san-mien-nam' ? 'border-2 border-emerald-600 bg-emerald-50/40' : 'border-gray-200' }}">
                   <div class="w-12 h-12 rounded-xl bg-sky-100/60 text-sky-800 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                       <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                   </div>
                   <span class="font-bold text-gray-800 text-sm sm:text-base text-center sm:text-left leading-tight">Đặc sản<br class="hidden sm:inline"> miền Nam</span>
                </a>

                <!-- Quà lưu niệm -->
                <a href="{{ request()->url() }}?category=qua-luu-niem" 
                   class="flex flex-col sm:flex-row items-center p-4 bg-white rounded-2xl border transition-all duration-200 gap-3 shadow-sm hover:shadow-md hover:border-emerald-500 group {{ $currentCategory == 'qua-luu-niem' ? 'border-2 border-emerald-600 bg-emerald-50/40' : 'border-gray-200' }}">
                   <div class="w-12 h-12 rounded-xl bg-rose-100/60 text-rose-800 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                       <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="8" width="18" height="12" rx="2"/><path d="M12 8v12M3 12h18"/></svg>
                   </div>
                   <span class="font-bold text-gray-800 text-sm sm:text-base text-center sm:text-left leading-tight">Quà<br class="hidden sm:inline"> lưu niệm</span>
                </a>
            </div>
        </div>

        <!-- 2. Tất Cả Sản Phẩm -->
        <div class="mt-12">
            <h2 class="text-xl sm:text-2xl font-bold text-emerald-950 mb-6">2. Tất Cả Sản Phẩm</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                        <!-- Ảnh sản phẩm -->
                        <div class="relative w-full h-48 bg-slate-100 overflow-hidden flex items-center justify-center">
                            <span class="absolute top-3 right-3 bg-emerald-700/90 text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm z-10 backdrop-blur-sm">Đã xác minh</span>

                            @if(!empty($product->image))
                                <img src="{{ asset('storage/' . ltrim($product->image, '/')) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                            @endif
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-gray-900 text-base mb-1 line-clamp-2 group-hover:text-emerald-700 transition-colors">{{ $product->title }}</h3>
                            
                            <div class="text-emerald-700 font-extrabold text-lg mb-2">
                                {{ number_format($product->discount_price ?? $product->base_price) }}đ
                            </div>

                            <div class="flex items-center text-xs text-gray-500 gap-1 mb-4">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                                <span class="truncate">{{ $product->location ?? 'Việt Nam' }}</span>
                            </div>

                            <a href="{{ route('products.show', $product->id) }}" class="mt-auto w-full text-center bg-emerald-800 hover:bg-emerald-900 text-white font-bold py-2.5 px-4 rounded-xl transition text-sm shadow-sm">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                        Chưa có sản phẩm nào trong hệ thống.
                    </div>
                @endforelse
            </div>

            <!-- Phân trang -->
            @if(method_exists($products, 'links'))
                <div class="mt-8 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-emerald-950 text-gray-300 mt-16 border-t border-emerald-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <span class="text-xl font-extrabold text-white block mb-2">Đặc sản Việt</span>
                    <p class="text-sm text-emerald-200/80">Đặc sản Việt – Gìn giữ giá trị quê hương</p>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-sm uppercase tracking-wider">Liên kết nhanh</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/') }}" class="hover:text-amber-400 transition">Trang chủ</a></li>
                        <li><a href="{{ route('categories.index') }}" class="hover:text-amber-400 transition">Danh mục</a></li>
                        <li><a href="{{ route('traceability.index') }}" class="hover:text-amber-400 transition">Truy xuất nguồn gốc</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-sm uppercase tracking-wider">Hỗ trợ</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-amber-400 transition">Về chúng tôi</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition">Điều khoản sử dụng</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-sm uppercase tracking-wider">Kết nối với chúng tôi</h4>
                    <div class="flex space-x-3">
                        <a href="#" class="w-9 h-9 rounded-full bg-emerald-900 flex items-center justify-center text-white hover:bg-amber-500 hover:text-emerald-950 transition font-bold" aria-label="Facebook">f</a>
                        <a href="#" class="w-9 h-9 rounded-full bg-emerald-900 flex items-center justify-center text-white hover:bg-amber-500 hover:text-emerald-950 transition" aria-label="YouTube"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4l14 8-14 8z"/></svg></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-emerald-900 flex items-center justify-center text-white hover:bg-amber-500 hover:text-emerald-950 transition" aria-label="Instagram"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/></svg></a>
                    </div>
                </div>
            </div>
            <div class="pt-8 border-t border-emerald-900 text-center text-xs text-emerald-200/60">
                © {{ date('Y') }}. Sàn thương mại đặc sản và quà lưu niệm. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
