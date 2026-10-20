<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Đặc sản Việt – Trang chủ</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])

<script>
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<style>
    * { box-sizing: border-box; }
    svg { max-width: 100%; vertical-align: middle; flex-shrink: 0; }
    
    body { 
        font-family: 'Be Vietnam Pro', sans-serif; 
        margin: 0; 
        padding: 0; 
        background-color: #FAF9F6; 
        color: #1E293B;
        overflow-x: hidden; 
    }
    
    .hidden { display: none !important; }

    .container-wide {
        max-width: 1440px;
        width: 92%;
        margin-left: auto;
        margin-right: auto;
    }

    .mb-20 { margin-bottom: 60px; }

    /* HEADER */
    .s2 { padding: 12px 0; background: #ffffff; border-bottom: 1px solid #E2E8F0; }
    .s3 { max-width: 1440px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; }
    .nav.s6 { display: flex; align-items: center; gap: 32px; }
    .nav.s6 a { font-size: 16px; font-weight: 600; color: #1E293B; text-decoration: none; transition: color 0.2s ease; }
    .nav.s6 a:hover, .nav.s6 a.s7 { color: #0B3B2C; font-weight: 700; }
    .s8 { display: flex; align-items: center; gap: 12px; }
    .icon-btn-lg { width: 44px; height: 44px; border-radius: 50%; background: #F1F5F9; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease; text-decoration: none; border: none; cursor: pointer; }
    .icon-btn-lg:hover { background: #E2E8F0; transform: translateY(-1px); }

    /* THANH 3 TÍNH NỔI BẬT */
    .feat-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 12px 32px rgba(0,0,0,0.06);
        border: 1px solid #f1f5f9;
        margin-top: -48px;
        margin-bottom: 60px;
        position: relative;
        z-index: 10;
        padding: 8px;
    }
    .feat-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        border-right: 1px solid #f1f5f9;
    }
    .feat-item:last-child { border-right: none; }
    .feat-icon {
        width: 46px; height: 46px; border-radius: 16px;
        background: #e6f0ea; color: #0b3b2c;
        display: flex; align-items: center; justify-content: center;
    }
    .feat-title { font-size: 16px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0; }
    .feat-desc { font-size: 13.5px; color: #64748b; margin: 0; line-height: 1.5; }

    /* KHỐI 4 DANH MỤC MÀU MỀM */
    .cat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 60px;
    }
    .cat-card {
        padding: 28px 24px;
        border-radius: 24px;
        text-decoration: none;
        display: block;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .cat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,0.06); }
    .cat-card-icon {
        width: 48px; height: 48px; border-radius: 16px; background: rgba(255,255,255,0.85);
        display: flex; align-items: center; justify-content: center; margin-bottom: 28px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .cat-card-title { font-size: 20px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0; }
    .cat-card-desc { font-size: 14px; color: #475569; margin: 0; line-height: 1.5; }

    .cat-bac { background: #e6f0ea; }
    .cat-trung { background: #f8ede1; }
    .cat-nam { background: #e3edf4; }
    .cat-qua { background: #f7e6e5; }

    /* TIMELINE */
    .timeline-banner {
        background: #ffffff;
        border-radius: 24px;
        padding: 40px;
        border: 1px solid #f1f5f9;
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 32px;
        align-items: center;
        margin-bottom: 60px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.02);
    }
    .timeline-flow { display: flex; justify-content: space-between; position: relative; }
    .timeline-line { position: absolute; top: 10px; left: 20px; right: 20px; height: 2px; border-top: 2px dashed #b45309; z-index: 1; }
    .timeline-node { position: relative; z-index: 2; text-align: center; }
    .timeline-dot { width: 20px; height: 20px; border-radius: 50%; background: #92400e; border: 4px solid #fef3c7; margin: 0 auto 12px auto; }

    .discount-box {
        background: rgba(254, 243, 199, 0.4);
        border: 1px solid #fde68a;
        border-radius: 24px;
        padding: 32px;
        margin-bottom: 60px;
    }
    .discount-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .s54 { display: flex; align-items: center; gap: 8px; }
    .tm { background: #0B3B2C; color: #fff; padding: 6px 10px; border-radius: 8px; text-align: center; display: flex; flex-direction: column; align-items: center; min-width: 44px; }
    .tm b { font-size: 16px; font-weight: 800; line-height: 1; }
    .tm span { font-size: 10px; opacity: 0.8; }

    .trace-box {
        background: #0b3b2c;
        color: #ffffff;
        border-radius: 24px;
        padding: 40px;
        display: flex;
        align-items: center;
        gap: 32px;
        margin-bottom: 60px;
        box-shadow: 0 12px 32px rgba(11, 59, 44, 0.15);
    }

    .product-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 24px; 
    }
    .card {
        background: #ffffff; 
        border: 1px solid #E2E8F0; 
        border-radius: 20px; 
        overflow: hidden;
        display: flex; 
        flex-direction: column; 
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08); }
    .card .ph { position: relative; overflow: hidden; height: 190px; display: flex; align-items: center; justify-content: center; background: #F1F5F9; }
    .card .ph img { width: 100%; height: 100%; object-fit: cover; }
    .card .s43, .card .s60 { padding: 16px; display: flex; flex-direction: column; gap: 6px; flex-grow: 1; }
    .card .s44 { font-size: 16px; color: #0F172A; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-weight: 700; }
    .card .s45, .card .s62 { font-size: 18px; font-weight: 800; color: #059669; }
    .card .btn, .card .buy { margin: 0 16px 16px 16px; padding: 10px 16px; background: #0B3B2C; color: #ffffff !important; text-align: center; border-radius: 12px; font-weight: 700; text-decoration: none; display: block; }
    .badge { background: #059669; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 99px; }
    .off { background: #EF4444; color: #fff; font-size: 12px; font-weight: 800; padding: 4px 10px; border-radius: 99px; }
    .old { text-decoration: line-through; color: #94A3B8; font-size: 14px; margin-left: 6px; font-weight: 400; }

    footer.s77 { background: #0B3B2C; color: #F8FAFC; padding: 48px 0 24px 0; margin-top: 60px; }
    .s78 { max-width: 1440px; margin: 0 auto; padding: 0 24px; }
    .s79 { display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr; gap: 32px; }
    .s82 { display: flex; flex-direction: column; gap: 10px; }
    .s82 a { color: #CBD5E1; text-decoration: none; font-size: 14px; }
    .s87 { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 40px; padding-top: 24px; text-align: center; color: #94A3B8; font-size: 14px; }

    @media (max-width: 1024px) {
        .cat-grid, .product-grid { grid-template-columns: repeat(2, 1fr) !important; }
        .feat-bar { grid-template-columns: 1fr; margin-top: 20px; }
        .feat-item { border-right: none; border-bottom: 1px solid #f1f5f9; }
        .timeline-banner { grid-template-columns: 1fr; }
        .trace-box { flex-direction: column; text-align: center; }
        .s79 { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .cat-grid, .product-grid { grid-template-columns: 1fr !important; }
    }

    html.dark, html.dark body, html.dark .s1 { background-color: #0F172A !important; color: #F8FAFC !important; }
    html.dark .s2 { background-color: #1E293B !important; border-bottom-color: #334155 !important; }
    html.dark nav.s6 a { color: #E2E8F0 !important; }
    html.dark .icon-btn-lg { background-color: #334155 !important; color: #F8FAFC !important; }
    html.dark .feat-bar, html.dark .timeline-banner, html.dark .card { background-color: #1E293B !important; border-color: #334155 !important; }
    html.dark .feat-item { border-bottom-color: #334155; border-right-color: #334155; }
    html.dark .feat-icon { background: #064e3b; color: #34d399; }
    html.dark .feat-title, html.dark .cat-card-title, html.dark h2, html.dark h3, html.dark h4, html.dark .s44 { color: #F8FAFC !important; }
    html.dark .feat-desc, html.dark .cat-card-desc, html.dark p { color: #CBD5E1 !important; }
    html.dark .discount-box { background: rgba(30, 41, 59, 0.6) !important; border-color: #334155 !important; }
    html.dark .trace-box { background: #022c22 !important; }
    html.dark footer.s77 { background-color: #020617 !important; }

    /* ===== BỘ LỌC GIÁ ===== */
    .pf-layout { display: grid; grid-template-columns: 270px 1fr; gap: 28px; align-items: start; }
    .pf-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .pf-filter { position: sticky; top: 16px; border: 1px solid #E2E8F0; border-radius: 18px; padding: 22px; background: #fff; }
    .pf-filter h4 { margin: 0 0 4px; font-size: 17px; font-weight: 800; color: #0f172a; }
    .pf-sub { font-size: 12px; color: #64748b; margin: 0 0 14px; }
    .pf-opt { display: flex; align-items: center; gap: 10px; padding: 9px 0; font-size: 14px; cursor: pointer; }
    .pf-opt input { accent-color: #0B3B2C; width: 16px; height: 16px; margin: 0; }
    .pf-sep { border: 0; border-top: 1px solid #E2E8F0; margin: 14px 0; }
    .pf-lbl { font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 10px; text-transform: uppercase; letter-spacing: .06em; }
    .pf-slider { position: relative; height: 22px; margin: 4px 6px 14px; }
    .pf-track, .pf-fill { position: absolute; top: 9px; height: 4px; border-radius: 4px; }
    .pf-track { left: 0; right: 0; background: #E2E8F0; }
    .pf-fill { background: #0B3B2C; }
    .pf-slider input[type=range] { position: absolute; left: -6px; width: calc(100% + 12px); top: 0; margin: 0; height: 22px; background: none; pointer-events: none; -webkit-appearance: none; appearance: none; }
    .pf-slider input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; pointer-events: auto; width: 18px; height: 18px; border-radius: 50%; background: #fff; border: 3px solid #0B3B2C; cursor: pointer; }
    .pf-slider input[type=range]::-moz-range-thumb { pointer-events: auto; width: 12px; height: 12px; border-radius: 50%; background: #fff; border: 3px solid #0B3B2C; cursor: pointer; }
    .pf-inputs { display: flex; gap: 8px; align-items: center; }
    .pf-inputs label { flex: 1; font-size: 11px; color: #64748b; }
    .pf-inputs input { width: 100%; margin-top: 4px; padding: 9px 10px; border: 1px solid #E2E8F0; border-radius: 10px; background: #FAF9F6; color: #0f172a; font: inherit; font-size: 13px; }
    .pf-btns { display: flex; gap: 8px; margin-top: 16px; }
    .pf-btns button { flex: 1; padding: 11px; border-radius: 12px; font: inherit; font-size: 13px; font-weight: 700; cursor: pointer; border: 1px solid #E2E8F0; background: none; color: #0f172a; }
    .pf-btns .pf-go { background: #0B3B2C; border-color: #0B3B2C; color: #fff; }
    .pf-count { font-size: 13px; color: #64748b; margin: 0 0 14px; }

    @media (max-width: 1024px) {
        .pf-layout { grid-template-columns: 1fr; }
        .pf-filter { position: static; }
    }

    html.dark .pf-filter { background: #1E293B; border-color: #334155; }
    html.dark .pf-filter h4, html.dark .pf-opt { color: #F8FAFC; }
    html.dark .pf-track { background: #334155; }
    html.dark .pf-fill { background: #34d399; }
    html.dark .pf-slider input[type=range]::-webkit-slider-thumb { background: #1E293B; border-color: #34d399; }
    html.dark .pf-slider input[type=range]::-moz-range-thumb { background: #1E293B; border-color: #34d399; }
    html.dark .pf-inputs input { background: #0F172A; border-color: #334155; color: #F8FAFC; }
    html.dark .pf-btns button { border-color: #334155; color: #F8FAFC; }
    html.dark .pf-btns .pf-go { background: #059669; border-color: #059669; }
</style>
</head>
<body>
<div class="s1">

<!-- HEADER -->
<header class="s2">
<div class="s3">
<a href="{{ url('/') }}" style="display: flex; align-items: center; text-decoration: none;">
    <img src="{{ asset('images/logo.png') }}" alt="Logo Đặc sản Việt" style="height: 75px; width: auto; object-fit: contain;">
</a>

<nav class="nav s6">
    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 's7' : '' }}">Trang chủ</a>
    <a href="{{ route('categories.index') }}">Danh mục</a>
    <a href="{{ route('traceability.index') }}">Truy xuất</a>
    @auth
    <a href="{{ route('orders.index') }}">📦 Đơn hàng của tôi</a>
    @else
    <a href="{{ route('login') }}">Đăng nhập</a>
    @endauth
</nav>

<div class="s8">
    <button id="theme-toggle" type="button" class="icon-btn-lg" aria-label="Đổi giao diện">
        <svg id="theme-toggle-dark-icon" class="hidden" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
        </svg>
        <svg id="theme-toggle-light-icon" class="hidden" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 0z" fill-rule="evenodd" clip-rule="evenodd"></path>
        </svg>
    </button>

    <button class="icon-btn-lg" aria-label="Tìm kiếm">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
    </button>

    @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
    <a href="{{ route('cart.index') }}" class="icon-btn-lg" style="position: relative;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.5 11h10L20 7H6"/><circle cx="9" cy="19" r="1.3"/><circle cx="17" cy="19" r="1.3"/></svg>
        @if($cartCount > 0)
            <span style="position: absolute; top: -3px; right: -3px; background-color: #EF4444; color: #FFFFFF; font-size: 11px; font-weight: 800; border-radius: 99px; min-width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; padding: 0 4px; border: 2px solid #FFFFFF;">
                {{ $cartCount }}
            </span>
        @endif
    </a>

    @auth
        <a href="{{ route('dashboard') }}" class="icon-btn-lg" style="background: #0B3B2C;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>
        </a>
    @else
        <a href="{{ route('login') }}" class="icon-btn-lg" style="background: #0B3B2C;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>
        </a>
    @endauth
</div>
</div>
</header>

<!-- BANNER HERO -->
<section style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%); position: relative; overflow: hidden; background-color: #0B3B2C;">
    <div style="
        position: relative; width: 100%; min-height: 580px;
        background: linear-gradient(90deg, #0B3B2C 0%, #0B3B2C 35%, rgba(11, 59, 44, 0.7) 60%, rgba(11, 59, 44, 0) 80%),
            url('{{ asset('images/adfgg.png') }}') right center / cover no-repeat;
        display: flex; align-items: center; padding: 60px 40px 100px 80px;
    ">
        <div style="max-width: 650px; z-index: 2;">
            <div style="display: inline-flex; align-items: center; gap: 10px; background: rgba(11, 59, 44, 0.7); border: 1.5px solid #E2B155; color: #E2B155; padding: 8px 22px; border-radius: 99px; font-size: 14px; font-weight: 700; margin-bottom: 24px;">
                <span style="width: 22px; height: 22px; background: #E2B155; color: #0B3B2C; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 900;">✓</span>
                QUÀ TỪ BA MIỀN – CÓ NGUỒN GỐC RÕ RÀNG
            </div>

            <h1 style="font-size: 3.4rem; font-weight: 800; line-height: 1.15; margin: 0 0 20px 0;">
                <span style="color: #FFFFFF;">Đặc sản Việt</span><br>
                <span style="color: #FFFFFF;">Nam,</span><br>
                <span style="color: #E2B155;">mua là biết từ</span><br>
                <span style="color: #E2B155;">đâu.</span>
            </h1>

            <p style="color: #CBD5E1; font-size: 18px; line-height: 1.6; margin-bottom: 36px; max-width: 520px;">
                Đặc sản và quà lưu niệm được xác minh, quét mã QR để xem nơi sản xuất của từng sản phẩm.
            </p>

            <form action="{{ route('categories.index') }}" method="GET" style="background: #FFFFFF; padding: 8px 8px 8px 24px; border-radius: 99px; display: flex; align-items: center; gap: 14px; box-shadow: 0 15px 35px rgba(0,0,0,0.35); max-width: 560px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="keyword" placeholder="Tìm kiếm đặc sản, sản phẩm..." style="border: none; outline: none; width: 100%; font-size: 17px; background: transparent; color: #0F172A; font-weight: 500;">
                <button type="submit" style="background: #E2B155; color: #0B3B2C; border: none; padding: 14px 34px; border-radius: 99px; font-weight: 800; font-size: 17px; cursor: pointer; white-space: nowrap;">Tìm kiếm</button>
            </form>
        </div>
    </div>
</section>

<!-- KHỐI 1: THANH 3 TÍNH NỔI BẬT -->
<div class="container-wide">
    <div class="feat-bar">
        <div class="feat-item">
            <div class="feat-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </div>
            <div>
                <h4 class="feat-title">Mã lô cho từng sản phẩm</h4>
                <p class="feat-desc">Quét mã là thấy nguồn gốc, không cần hỏi người bán.</p>
            </div>
        </div>

        <div class="feat-item">
            <div class="feat-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <h4 class="feat-title">Nhà cung cấp được kiểm duyệt</h4>
                <p class="feat-desc">Chỉ hiện nhãn “Đã xác minh” sau khi quản trị viên duyệt hồ sơ.</p>
            </div>
        </div>

        <div class="feat-item">
            <div class="feat-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h4 class="feat-title">Biết rõ vùng sản xuất</h4>
                <p class="feat-desc">Mỗi món gắn với tỉnh thành và làng nghề cụ thể.</p>
            </div>
        </div>
    </div>
</div>

<!-- KHỐI 2: 4 DANH MỤC MÀU MỀM -->
<section class="container-wide mb-20">
    <div style="margin-bottom: 24px;">
        <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #b45309;">DANH MỤC</span>
        <h2 style="font-size: 32px; font-weight: 800; color: #0f172a; margin: 4px 0 0 0;">Khám phá theo danh mục</h2>
        <p style="color: #64748b; margin-top: 4px; font-size: 15px;">Chọn theo vùng miền hoặc tìm quà mang về làm kỷ niệm.</p>
    </div>

    <div class="cat-grid">
        <a href="{{ route('categories.index') }}" class="cat-card cat-bac">
            <div class="cat-card-icon">
                <svg width="24" height="24" fill="none" stroke="#065f46" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 19l7-7 7 7M5 13l7-7 7 7"></path></svg>
            </div>
            <h3 class="cat-card-title">Đặc sản miền Bắc</h3>
            <p class="cat-card-desc">Cốm, ô mai, trà sen, thịt gác bếp</p>
        </a>

        <a href="{{ route('categories.index') }}" class="cat-card cat-trung">
            <div class="cat-card-icon">
                <svg width="24" height="24" fill="none" stroke="#92400e" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 v5m-4 0h4"></path></svg>
            </div>
            <h3 class="cat-card-title">Đặc sản miền Trung</h3>
            <p class="cat-card-desc">Mè xửng, tré, mì Quảng, nước mắm</p>
        </a>

        <a href="{{ route('categories.index') }}" class="cat-card cat-nam">
            <div class="cat-card-icon">
                <svg width="24" height="24" fill="none" stroke="#075985" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </div>
            <h3 class="cat-card-title">Đặc sản miền Nam</h3>
            <p class="cat-card-desc">Kẹo dừa, bánh pía, khô cá, hạt điều</p>
        </a>

        <a href="{{ route('categories.index') }}" class="cat-card cat-qua">
            <div class="cat-card-icon">
                <svg width="24" height="24" fill="none" stroke="#9f1239" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="cat-card-title">Quà lưu niệm</h3>
            <p class="cat-card-desc">Đèn lồng, gốm, đồ thủ công</p>
        </a>
    </div>
</section>

<!-- KHỐI 3: TIMELINE TỪ BẮC VÀO NAM -->
<section class="container-wide mb-20">
    <div class="timeline-banner">
        <div>
            <h2 style="font-size: 30px; font-weight: 800; color: #0f172a; margin: 0; line-height: 1.25;">Một chuyến đi từ Bắc vào Nam</h2>
            <p style="color: #64748b; margin-top: 12px; font-size: 15px; line-height: 1.6;">Mỗi vùng có nghề riêng. Chúng tôi gom về một nơi, có thông tin minh bạch để bạn yên tâm thưởng thức.</p>
        </div>

        <div class="timeline-flow">
            <div class="timeline-line"></div>

            <div class="timeline-node">
                <div class="timeline-dot"></div>
                <h4 style="font-weight: 700; color: #0f172a; margin: 0; font-size: 15px;">Hà Nội, Hà Tĩnh</h4>
                <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Cốm, ô mai, kẹo cu đơ</p>
            </div>

            <div class="timeline-node">
                <div class="timeline-dot"></div>
                <h4 style="font-weight: 700; color: #0f172a; margin: 0; font-size: 15px;">Huế, Hội An</h4>
                <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Mè xửng, đèn lồng</p>
            </div>

            <div class="timeline-node">
                <div class="timeline-dot"></div>
                <h4 style="font-weight: 700; color: #0f172a; margin: 0; font-size: 15px;">Bến Tre, Sóc Trăng</h4>
                <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Kẹo dừa, bánh pía</p>
            </div>
        </div>
    </div>
</section>

<!-- KHỐI: TẤT CẢ SẢN PHẨM (CÓ LỌC GIÁ) -->
@php
    $listProducts = $allProducts ?? $featuredProducts;
    $maxPrice = (int) ceil(max(100000, (int) $listProducts->map(fn($p) => $p->discount_price ?? $p->base_price)->max()) / 50000) * 50000;
@endphp
<section class="container-wide mb-20">
    <div style="background: #ffffff; border-radius: 24px; padding: 32px; border: 1px solid #f1f5f9; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">

        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
            <div>
                <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #b45309;">DANH SÁCH</span>
                <h2 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 4px 0 0 0;">Tất cả sản phẩm</h2>
            </div>
            <a style="color: #047857; font-weight: 700; text-decoration: none; font-size: 15px;" href="{{ route('categories.index') }}">Xem tất cả danh mục →</a>
        </div>

        <div class="pf-layout">
            <!-- CỘT LỌC GIÁ (BÊN TRÁI) -->
            <aside class="pf-filter" aria-label="Lọc theo giá">
                <h4>Lọc theo giá</h4>
                <p class="pf-sub">Chọn mức giá phù hợp với bạn</p>

                <div id="pf-presets">
                    <label class="pf-opt"><input type="radio" name="pf" value="0-{{ $maxPrice }}" checked> Tất cả mức giá</label>
                    <label class="pf-opt"><input type="radio" name="pf" value="0-50000"> Dưới 50.000đ</label>
                    <label class="pf-opt"><input type="radio" name="pf" value="50000-100000"> 50.000đ – 100.000đ</label>
                    <label class="pf-opt"><input type="radio" name="pf" value="100000-200000"> 100.000đ – 200.000đ</label>
                    <label class="pf-opt"><input type="radio" name="pf" value="200000-{{ $maxPrice }}"> Trên 200.000đ</label>
                </div>

                <hr class="pf-sep">
                <div class="pf-lbl">Khoảng giá tuỳ chọn</div>
                <div class="pf-slider">
                    <div class="pf-track"></div><div class="pf-fill" id="pf-fill"></div>
                    <input type="range" id="pf-rmin" min="0" max="{{ $maxPrice }}" step="5000" value="0">
                    <input type="range" id="pf-rmax" min="0" max="{{ $maxPrice }}" step="5000" value="{{ $maxPrice }}">
                </div>
                <div class="pf-inputs">
                    <label>Từ (đ)<input type="number" id="pf-nmin" min="0" step="5000" value="0"></label>
                    <span>–</span>
                    <label>Đến (đ)<input type="number" id="pf-nmax" min="0" step="5000" value="{{ $maxPrice }}"></label>
                </div>
                <div class="pf-btns">
                    <button type="button" id="pf-reset">Xoá lọc</button>
                    <button type="button" id="pf-apply" class="pf-go">Áp dụng</button>
                </div>
            </aside>

            <!-- DANH SÁCH SẢN PHẨM (BÊN PHẢI) -->
            <div>
                <p class="pf-count" id="pf-count"></p>
                <div class="product-grid pf-grid" id="pf-grid">
                    @forelse($listProducts as $product)
                        <div class="card pf-item" data-price="{{ (int) ($product->discount_price ?? $product->base_price) }}" style="min-width: 0;">

                            <div class="ph">
                                <span class="badge" style="position: absolute; top: 12px; left: 12px; z-index: 2; background: rgba(255, 255, 255, 0.95); color: #0f172a; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 99px;">
                                    📍 {{ $product->location ?? 'Việt Nam' }}
                                </span>

                                @if(!empty($product->image))
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                                @else
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                @endif
                            </div>

                            <div class="s43">
                                <b class="s44" style="height: 44px;">{{ $product->title }}</b>
                                <span class="s45">{{ number_format($product->discount_price ?? $product->base_price) }}đ</span>
                            </div>

                            <a href="{{ route('products.show', $product->id) }}" class="btn">Xem chi tiết</a>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; text-align: center; color: #888; padding: 30px 0;">
                            Chưa có sản phẩm nào.
                        </div>
                    @endforelse
                    <div id="pf-empty" class="hidden" style="grid-column: 1 / -1; text-align: center; color: #888; padding: 40px 0;">
                        Không có sản phẩm trong khoảng giá này.
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- KHỐI 5: SẢN PHẨM NỔI BẬT -->
<section class="container-wide mb-20">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
        <div>
            <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: #b45309;">ĐƯỢC YÊU THÍCH ({{ $featuredProducts->count() }} sản phẩm)</span>
            <h2 style="font-size: 32px; font-weight: 800; color: #0f172a; margin: 4px 0 0 0;">Sản phẩm nổi bật</h2>
        </div>
        <a style="color: #047857; font-weight: 700; text-decoration: none; font-size: 15px;" href="{{ route('categories.index') }}">Xem tất cả →</a>
    </div>

    <div class="product-grid">
        @forelse($featuredProducts as $product)
            <div class="card">
                <div class="ph">
                    <span class="badge" style="position: absolute; top: 12px; right: 12px; z-index: 2;">Đã xác minh</span>
                    @if(!empty($product->image))
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    @endif
                </div>
                <div class="s43">
                    <b class="s44">{{ $product->title }}</b>
                    <span class="s45">{{ number_format($product->discount_price ?? $product->base_price) }}đ</span>
                </div>
                <a href="{{ route('products.show', $product->id) }}" class="btn">Xem chi tiết</a>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; color: #888; padding: 40px 0;">
                Chưa có sản phẩm nổi bật nào.
            </div>
        @endforelse
    </div>
</section>

<!-- KHỐI 6: ĐẶC SẢN ĐANG GIẢM GIÁ (CÓ ĐỒNG HỒ ĐẾM NGƯỢC) -->
<section class="container-wide mb-20">
    <div class="discount-box">
        <div class="discount-header">
            <div>
                <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: #dc2626;">ƯU ĐÃI CÓ HẠN ({{ $discountProducts->count() }} sản phẩm)</span>
                <h2 style="font-size: 32px; font-weight: 800; color: #0f172a; margin: 4px 0 0 0;">Đặc sản đang giảm giá</h2>
                <span style="font-size: 14px; color: #64748b; margin-top: 4px; display: block;">Số lượng có hạn, hết thời gian là trở về giá gốc.</span>
            </div>
            
            <div class="s54">
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-right: 4px;">Kết thúc sau</span>
                <div class="tm"><b>02</b><span>Ngày</span></div>
                <div class="tm"><b>14</b><span>Giờ</span></div>
                <div class="tm"><b>35</b><span>Phút</span></div>
                <div class="tm" style="background: #dc2626;"><b>08</b><span>Giây</span></div>
            </div>
        </div>

        <div class="product-grid">
            @forelse($discountProducts as $product)
                <div class="card">
                    <div class="ph">
                        @if($product->base_price && $product->discount_price && $product->base_price > $product->discount_price)
                            <span class="off" style="position: absolute; top: 12px; left: 12px; z-index: 2;">-{{ round((($product->base_price - $product->discount_price) / $product->base_price) * 100) }}%</span>
                        @else
                            <span class="off" style="position: absolute; top: 12px; left: 12px; z-index: 2;">Ưu đãi</span>
                        @endif

                        @if(!empty($product->image))
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#6B4A33" stroke-width="1.3"><rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="9" cy="10" r="1.6"/><path d="M4 18l5-5 4 4 3-3 4 4"/></svg>
                        @endif
                    </div>
                    <div class="s60">
                        <b class="s44">{{ $product->title }}</b>
                        <div>
                            <span class="s62">{{ number_format($product->discount_price ?? $product->base_price) }}đ</span>
                            @if($product->discount_price && $product->base_price)
                                <span class="old">{{ number_format($product->base_price) }}đ</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('products.show', $product->id) }}" class="buy">Xem sản phẩm</a>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; color: #888; padding: 30px 0;">
                    Chưa có sản phẩm giảm giá nào.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- KHỐI 7: KIỂM TRA TRUY XUẤT NGUỒN GỐC -->
<section class="container-wide mb-20">
    <div class="trace-box">
        <div style="padding: 16px; background: rgba(255,255,255,0.1); border-radius: 20px; flex-shrink: 0;">
            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#E2B155" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8V5a2 2 0 012-2h3M16 3h3a2 2 0 012 2v3M21 16v3a2 2 0 01-2 2h-3M8 21H5a2 2 0 01-2-2v-3"/><rect x="7" y="7" width="4" height="4"/><rect x="13" y="7" width="4" height="4"/><rect x="7" y="13" width="4" height="4"/><path d="M13 13h2v2M17 17h0M15 17v0"/></svg>
        </div>
        <div style="flex-grow: 1;">
            <h2 style="font-size: 28px; font-weight: 800; color: #ffffff; margin: 0;">Kiểm tra nguồn gốc đặc sản</h2>
            <p style="color: #a7f3d0; margin-top: 8px; font-size: 15px;">Nhập mã sản phẩm hoặc mã lô hàng để xem đầy đủ nhật ký canh tác & chứng nhận nguồn gốc.</p>

            <form action="{{ route('traceability.index') }}" method="GET" style="margin-top: 20px; display: flex; gap: 12px; max-width: 580px; flex-wrap: wrap;">
                <input type="text" name="code" placeholder="Nhập mã sản phẩm (vd: TX-1-2026)..." required style="padding: 14px 20px; border-radius: 16px; border: none; outline: none; flex-grow: 1; font-size: 15px; color: #0f172a;">
                <button type="submit" style="background: #eab308; color: #022c22; font-weight: 800; padding: 14px 28px; border-radius: 16px; border: none; cursor: pointer; white-space: nowrap; font-size: 15px;">Kiểm tra ngay</button>
            </form>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="s77">
<div class="s78">
    <div class="s79">
        <div>
            <span style="font-size: 24px; font-weight: 900; color: #f59e0b; display: block; margin-bottom: 8px;">Đặc sản Việt</span>
            <p style="color: #cbd5e1; font-size: 14px; line-height: 1.6;">Đặc sản Việt – Gìn giữ giá trị quê hương. Nơi kết nối nông sản sạch và quà lưu niệm chính gốc trên toàn quốc.</p>
        </div>
        <div class="s82">
            <b style="color: #fff; margin-bottom: 8px;">Liên kết nhanh</b>
            <a href="{{ url('/') }}">Trang chủ</a>
            <a href="{{ route('categories.index') }}">Danh mục</a>
            <a href="{{ route('traceability.index') }}">Truy xuất nguồn gốc</a>
        </div>
        <div class="s82">
            <b style="color: #fff; margin-bottom: 8px;">Hỗ trợ</b>
            <a href="#">Về chúng tôi</a>
            <a href="#">Chính sách bảo mật</a>
            <a href="#">Điều khoản sử dụng</a>
        </div>
        <div>
            <b style="color: #fff; margin-bottom: 8px; display: block;">Kết nối với chúng tôi</b>
            <p style="color: #cbd5e1; font-size: 14px;">Hotline: 1900 xxxx (8h - 21h)</p>
        </div>
    </div>
    <div class="s87">© 2026. Sàn thương mại đặc sản và quà lưu niệm có truy xuất nguồn gốc. All rights reserved.</div>
</div>
</footer>

</div>

<!-- SCRIPT TOGGLE DARK MODE -->
<script>
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    var themeToggleBtn = document.getElementById('theme-toggle');

    if (document.documentElement.classList.contains('dark')) {
        if(themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
    } else {
        if(themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
    }

    if(themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });
    }
</script>

<!-- SCRIPT LỌC GIÁ -->
<script>
(function () {
    var MAX = {{ $maxPrice }}, lo = 0, hi = MAX;
    var $ = function (id) { return document.getElementById(id); };
    var items = document.querySelectorAll('.pf-item');
var grid = document.getElementById('pf-grid');
items.forEach(function (el, i) { el.dataset.order = i; }); // nhớ thứ tự gốc
    if (!$('pf-grid')) return;

    function render() {
    var shown = 0;
    var filtering = lo > 0 || hi < MAX;

    // Lọc: ẩn/hiện sản phẩm theo khoảng giá
    items.forEach(function (el) {
        var p = parseInt(el.dataset.price, 10) || 0;
        var ok = p >= lo && (hi >= MAX || p <= hi);
        el.classList.toggle('hidden', !ok);
        if (ok) shown++;
    });

    // Sắp xếp: có lọc giá thì giá thấp -> cao, không lọc thì về thứ tự gốc
    var sorted = Array.prototype.slice.call(items).sort(function (a, b) {
        if (filtering) {
            var d = (parseInt(a.dataset.price, 10) || 0) - (parseInt(b.dataset.price, 10) || 0);
            if (d !== 0) return d;
        }
        return a.dataset.order - b.dataset.order;
    });
    var emptyEl = $('pf-empty');
    sorted.forEach(function (el) { grid.insertBefore(el, emptyEl); });

    $('pf-count').textContent = 'Hiển thị ' + shown + ' / ' + items.length + ' sản phẩm' + (filtering ? ' · giá thấp → cao' : '');
    emptyEl.classList.toggle('hidden', shown > 0 || items.length === 0);
}
    function sync() {
        $('pf-rmin').value = lo;  $('pf-rmax').value = hi;
        $('pf-nmin').value = lo;  $('pf-nmax').value = hi;
        $('pf-fill').style.left  = (lo / MAX * 100) + '%';
        $('pf-fill').style.right = (100 - hi / MAX * 100) + '%';
    }
    function setRange(a, b) { lo = a; hi = Math.min(b, MAX); sync(); render(); }
    function clearRadios() {
        document.querySelectorAll('#pf-presets input').forEach(function (r) { r.checked = false; });
    }

    document.querySelectorAll('#pf-presets input').forEach(function (r) {
        r.addEventListener('change', function () {
            var v = r.value.split('-').map(Number);
            setRange(v[0], v[1]);
        });
    });
    $('pf-rmin').addEventListener('input', function () { lo = Math.min(+this.value, hi); clearRadios(); sync(); render(); });
    $('pf-rmax').addEventListener('input', function () { hi = Math.max(+this.value, lo); clearRadios(); sync(); render(); });
    $('pf-apply').addEventListener('click', function () {
        var a = Math.max(0, +$('pf-nmin').value || 0);
        var b = Math.max(a, +$('pf-nmax').value || MAX);
        clearRadios(); setRange(a, b);
    });
    $('pf-reset').addEventListener('click', function () {
        document.querySelector('#pf-presets input').checked = true;
        setRange(0, MAX);
    });

    sync(); render();
})();
</script>
</body>
</html>