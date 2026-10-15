<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:flex h-full">
                    <x-nav-link href="/" :active="request()->is('/')" class="whitespace-nowrap">
                        {{ __('Trang chủ Website') }}
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="whitespace-nowrap">
                        {{ __('Bảng điều khiển') }}
                    </x-nav-link>

                    {{-- Link Đơn hàng ở thanh menu chính --}}
                    @if(Route::has('orders.index'))
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="whitespace-nowrap">
                            {{ __('📦 Đơn hàng của tôi') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown & Icon Giỏ hàng (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                
                <!-- ICON GIỎ HÀNG CÓ BADGE ĐẾM SỐ LƯỢNG -->
                @php 
                    $cartCount = array_sum(array_column(session('cart', []), 'quantity')); 
                @endphp
                <a href="{{ Route::has('cart.index') ? route('cart.index') : '#' }}" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[18px] h-[18px]">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- User Dropdown Menu -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition duration-150 ease-in-out">
                            <span class="font-semibold">{{ Auth::user()->name }}</span>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs text-gray-400 font-semibold uppercase">Tài khoản</p>
                            <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('👤 Hồ sơ cá nhân') }}
                        </x-dropdown-link>

                        {{-- Link đến Đơn hàng của khách hàng --}}
                        @if(Route::has('orders.index'))
                            <x-dropdown-link :href="route('orders.index')">
                                {{ __('📦 Đơn hàng của tôi') }}
                            </x-dropdown-link>
                        @endif

                        {{-- Link đến Quản lý Đơn hàng (Admin) --}}
                        @if(Route::has('admin.orders.index'))
                            <x-dropdown-link :href="route('admin.orders.index')" class="text-indigo-600 font-bold">
                                {{ __('🛠️ Quản lý đơn hàng (Admin)') }}
                            </x-dropdown-link>
                        @endif

                        <div class="border-t border-gray-100"></div>

                        <!-- Form Đăng xuất -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 font-semibold hover:bg-red-50">
                                {{ __('🚪 Đăng xuất') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden space-x-2">
                <!-- Icon Giỏ Hàng trên Mobile -->
                <a href="{{ Route::has('cart.index') ? route('cart.index') : '#' }}" class="relative p-2 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[18px] h-[18px]">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="/" :active="request()->is('/')">
                {{ __('Trang chủ Website') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Bảng điều khiển') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="Route::has('cart.index') ? route('cart.index') : '#'" :active="request()->routeIs('cart.*')">
                {{ __('🛒 Giỏ hàng') }} ({{ $cartCount }})
            </x-responsive-nav-link>

            @if(Route::has('orders.index'))
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                    {{ __('📦 Đơn hàng của tôi') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('👤 Hồ sơ cá nhân') }}
                </x-responsive-nav-link>

                @if(Route::has('orders.index'))
                    <x-responsive-nav-link :href="route('orders.index')">
                        {{ __('📦 Đơn hàng của tôi') }}
                    </x-responsive-nav-link>
                @endif

                @if(Route::has('admin.orders.index'))
                    <x-responsive-nav-link :href="route('admin.orders.index')" class="text-indigo-600 font-bold">
                        {{ __('🛠️ Quản lý đơn hàng (Admin)') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 font-semibold">
                        {{ __('🚪 Đăng xuất') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>