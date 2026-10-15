<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Tra Cứu & Kiểm Tra Nguồn Gốc – Đặc Sản Việt</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
    body { font-family: 'Be Vietnam Pro', sans-serif; background-color: #FAF9F6; color: #1E293B; margin: 0; }
    
    /* Header Style */
    .s2 { background: #FFFFFF; border-bottom: 1px solid #E2E8F0; padding: 12px 24px; }
    .s3 { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
    .nav { display: flex; gap: 24px; align-items: center; }
    .nav a { text-decoration: none; color: #334155; font-size: 15px; font-weight: 500; padding-bottom: 4px; }
    .nav a.s7 { color: #0B3B2C; font-weight: 700; border-bottom: 2.5px solid #E2B155; }
    .s8 { display: flex; align-items: center; gap: 12px; }
    .s9 { width: 40px; height: 40px; border-radius: 50%; background: #F1F5F9; border: none; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; position: relative; }
    .s10 { width: 40px; height: 40px; border-radius: 50%; background: #0B3B2C; border: none; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }

    /* Trace Main Container */
    .trace-container { max-width: 1000px; margin: 40px auto; padding: 0 16px; }

    /* Search Banner Card */
    .search-card { background: #FEF3C7; border-radius: 24px; padding: 36px; display: grid; grid-template-columns: auto 1fr; gap: 32px; align-items: center; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .qr-icon-box { width: 120px; height: 120px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .search-title { font-size: 28px; font-weight: 800; color: #0B3B2C; margin: 0 0 8px 0; }
    .search-desc { font-size: 14px; color: #475569; margin: 0 0 20px 0; line-height: 1.5; }
    .search-form { display: flex; gap: 12px; background: white; padding: 6px 6px 6px 16px; border-radius: 14px; border: 1px solid #E2E8F0; }
    .search-input { border: none; outline: none; width: 100%; font-size: 15px; font-weight: 600; color: #0F172A; }
    .btn-search { background: #0B3B2C; color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 700; cursor: pointer; white-space: nowrap; transition: background 0.2s; }
    .btn-search:hover { background: #14532D; }

    /* Result Details Card */
    .result-card { background: white; border-radius: 24px; padding: 36px; margin-top: 32px; border: 1px solid #E2E8F0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .badge-verified { background: #DCFCE7; color: #16A34A; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; margin-bottom: 8px; }
    .product-name { font-size: 24px; font-weight: 800; color: #0B3B2C; margin: 0; }
    .batch-code { font-size: 13px; color: #64748B; text-align: right; }
    .batch-code span { display: block; font-weight: 700; color: #1E293B; font-size: 14px; }

    /* Info Grid */
    .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 24px; }
    .info-item { background: #F8FAFC; padding: 16px 20px; border-radius: 12px; }
    .info-label { font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .info-value { font-size: 14px; font-weight: 700; color: #0F172A; }
    .text-green { color: #16A34A; }

    /* Timeline Section */
    .timeline-box { margin-top: 36px; padding-top: 28px; border-top: 1px solid #F1F5F9; }
    .timeline-title { font-size: 18px; font-weight: 800; color: #0B3B2C; margin-bottom: 24px; }
    .timeline { position: relative; padding-left: 20px; border-left: 2px solid #E2E8F0; }
    .timeline-item { position: relative; margin-bottom: 24px; padding-left: 12px; }
    .timeline-item:last-child { margin-bottom: 0; }
    .timeline-item::before { content: ''; position: absolute; left: -27px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: #10B981; border: 3px solid white; box-shadow: 0 0 0 2px #10B981; }
    .timeline-time { font-size: 12px; font-weight: 700; color: #2563EB; margin-bottom: 4px; }
    .timeline-event { font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 4px; }
    .timeline-desc { font-size: 13px; color: #64748B; line-height: 1.5; }
</style>
</head>
<body>

<!-- HEADER ĐỒNG BỘ -->
<header class="s2">
<div class="s3">
    <a href="{{ url('/') }}" style="display: flex; align-items: center; text-decoration: none;">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Đặc sản Việt" style="height: 60px; width: auto; object-fit: contain;">
    </a>

    <nav class="nav">
        <a href="{{ url('/') }}">Trang chủ</a>
        <a href="{{ route('categories.index') }}">Danh mục</a>
        <a href="{{ route('traceability.index') }}" class="s7">Truy xuất</a>
        @auth
            <a href="{{ route('orders.index') }}">📦 Đơn hàng của tôi</a>
        @else
            <a href="{{ route('login') }}">Đăng nhập</a>
        @endauth
    </nav>

    <div class="s8">
        <button class="s9" aria-label="Tìm kiếm"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#17261E" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg></button>

        @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
        <a href="{{ route('cart.index') }}" class="s9" aria-label="Giỏ hàng">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#17261E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.5 11h10L20 7H6"/><circle cx="9" cy="19" r="1.3"/><circle cx="17" cy="19" r="1.3"/></svg>
            @if($cartCount > 0)
                <span style="position: absolute; top: -4px; right: -4px; background-color: #EF4444; color: #FFFFFF; font-size: 10px; font-weight: 800; border-radius: 99px; min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; padding: 0 4px; border: 2px solid #FFFFFF;">{{ $cartCount }}</span>
            @endif
        </a>

        @auth
            <a href="{{ route('dashboard') }}" class="s10" aria-label="Tài khoản">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>
            </a>
        @else
            <a href="{{ route('login') }}" class="s10" aria-label="Tài khoản">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>
            </a>
        @endauth
    </div>
</div>
</header>

<!-- KHỐI NỘI DUNG TRA CỨU -->
<div class="trace-container">
    
    <!-- Khung Nhập Mã Kiểm Tra -->
    <div class="search-card">
        <div class="qr-icon-box">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0B3B2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/>
                <rect x="7" y="7" width="4" height="4"/>
                <rect x="13" y="7" width="4" height="4"/>
                <rect x="7" y="13" width="4" height="4"/>
                <path d="M13 13h2v2M17 17h.01M15 17v.01"/>
            </svg>
        </div>
        <div>
            <h1 class="search-title">Tra Cứu & Kiểm Tra Nguồn Gốc</h1>
            <p class="search-desc">Nhập mã định danh (mã tem QR) ghi trên bao bì sản phẩm để tra cứu nhật ký sản xuất và chứng nhận chất lượng.</p>

            <form action="{{ route('traceability.index') }}" method="GET" class="search-form">
    <input type="text" name="code" value="{{ request('code', $code) }}" class="search-input" placeholder="Nhập mã sản phẩm (vd: TX-1-2026 hoặc 1)..." required>
    <button type="submit" class="btn-search">Kiểm tra ngay</button>
</form>
        </div>
    </div>

  <!-- Khối Kết Quả Tra Cứu -->
    <div class="result-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <span class="badge-verified">✓ Đã xác minh nguồn gốc</span>
                <h2 class="product-name">{{ $product->title ?? $product->name ?? 'Đặc sản Việt Nam' }}</h2>
            </div>
            <div class="batch-code">
                Mã lô hàng:
                <span>{{ $code }}</span>
            </div>
        </div>

        <!-- Thông tin cơ sở & vùng trồng ĐỘNG -->
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Cơ sở sản xuất</div>
                <div class="info-value">{{ $supplier }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Vùng trồng / Nguyên liệu</div>
                <div class="info-value">{{ $location }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tiêu chuẩn chất lượng</div>
                <div class="info-value text-green">OCOP 4 Sao - VietGAP</div>
            </div>
        </div>

        <div class="info-grid" style="margin-top: 12px; grid-template-columns: 1fr;">
            <div class="info-item">
                <div class="info-label">Ngày đóng gói</div>
                <div class="info-value">{{ $mfgDate->format('d/m/Y') }}</div>
            </div>
        </div>

        <!-- Nhật ký truy xuất nguồn gốc (Timeline) -->
        <div class="timeline-box">
            <h3 class="timeline-title">Nhật Ký Truy Xuất Nguồn Gốc</h3>
            <div class="timeline">
                @foreach($logs as $log)
                    <div class="timeline-item">
                        <div class="timeline-time">{{ $log['time'] }}</div>
                        <div class="timeline-event">{{ $log['title'] }}</div>
                        <div class="timeline-desc">{{ $log['desc'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

</body>
</html>