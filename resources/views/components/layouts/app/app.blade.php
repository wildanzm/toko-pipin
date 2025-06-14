<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Memuat semua meta tag, title, font, dan Vite assets dari partial head --}}
    @include('partials.head')
</head>

<body class="min-h-screen bg-white">
    {{-- Header dengan state Alpine.js untuk menu mobile dan dropdown transaksi --}}
    <header class="bg-[#EFF3FA] pt-6 pb-6 sm:pt-[30px] sm:pb-[30px]" x-data="{ mobileMenuOpen: false, transactionDropdownOpen: false }">
        <nav
            class="container max-w-[1130px] mx-auto px-4 sm:px-6 lg:px-6 flex items-center justify-between bg-[#0D5CD7] p-4 sm:p-5 rounded-2xl sm:rounded-3xl">
            {{-- Logo --}}
            <div class="flex shrink-0">
                <a href="{{ url('/') }}" wire:navigate>
                    <h2 class="font-bold text-white text-2xl sm:text-3xl">Gadget Official</h2>
                </a>
            </div>

            {{-- Tombol Menu Mobile --}}
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle menu"
                    class="text-white focus:outline-none p-2 rounded-md hover:bg-white/20 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                    </svg>
                </button>
            </div>

            {{-- Menu Desktop --}}
            <ul class="hidden md:flex items-center gap-5 lg:gap-[30px] text-sm lg:text-base">
                <li>
                    <a href="{{ route('home') }}" wire:navigate
                        class="transition-all duration-300 {{ request()->routeIs('home') ? 'font-bold text-[#FFC736]' : 'text-white hover:text-[#FFC736]' }}">Home</a>
                </li>
                <li>
                    {{-- Arahkan ke route halaman belanja Anda --}}
                    <a href="#"
                        class="transition-all duration-300 {{ request()->routeIs('shop.index') ? 'font-bold text-[#FFC736]' : 'text-white hover:text-[#FFC736]' }}">Belanja</a>
                </li>
                <li>
                    {{-- Arahkan ke route halaman katalog Anda --}}
                    <a href="#"
                        class="transition-all duration-300 {{ request()->routeIs('catalog.index') ? 'font-bold text-[#FFC736]' : 'text-white hover:text-[#FFC736]' }}">Katalog</a>
                </li>

                {{-- Dropdown untuk Transaksi --}}
                <li class="relative" @click.away="transactionDropdownOpen = false" x-cloak>
                    <button @click="transactionDropdownOpen = !transactionDropdownOpen"
                        class="flex items-center gap-1 transition-all duration-300 {{ request()->routeIs('transaction') || request()->routeIs('installment') ? 'font-bold text-[#FFC736]' : 'text-white hover:text-[#FFC736]' }}">
                        <span>Transaksi</span>
                    </button>
                    {{-- Panel Dropdown --}}
                    <div x-show="transactionDropdownOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute z-10 mt-2 w-48 origin-top-left rounded-md bg-white py-1 shadow-lg focus:outline-none"
                        style="display: none;">
                        <a href="{{ route('transaction') }}" wire:navigate
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('transaction') ? 'font-bold text-blue-600' : '' }}">Daftar
                            Transaksi</a>
                        <a href="{{ route('installment') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('installment') ? 'font-bold text-blue-600' : '' }}">Tagihan
                            Kredit</a>
                    </div>
                </li>
            </ul>

            {{-- Tombol Aksi Desktop --}}
            <div class="hidden md:flex items-center gap-2 lg:gap-3">
                <livewire:components.cart-counter />
                @auth
                    {{-- Wrapper untuk Dropdown Profil Pengguna --}}
                    <div class="relative ml-3" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" type="button"
                                class="flex items-center gap-2 rounded-full text-sm focus:outline-none focus:ring-offset-[#0D5CD7]"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Buka menu pengguna</span>
                                <div
                                    class="w-10 h-10 rounded-full bg-white text-[#0D5CD7] flex items-center justify-center text-lg font-semibold overflow-hidden">
                                    @php
                                        $name = auth()->user()->name;
                                        $words = explode(' ', $name);
                                        $initials = strtoupper(substr($words[0], 0, 1));
                                        if (count($words) > 1) {
                                            $initials .= strtoupper(substr(end($words), 0, 1));
                                        } elseif (strlen($words[0]) > 1 && count($words) == 1) {
                                            $initials = strtoupper(substr($words[0], 0, 2));
                                        }
                                    @endphp
                                    {{ $initials }}
                                </div>
                                <span
                                    class="text-white font-bold text-sm hidden lg:inline-block hover:text-[#FFC736] transition-colors">
                                    Halo, {{ Str::words(auth()->user()->name, 1, '') }}
                                </span>
                            </button>
                        </div>

                        {{-- Panel Dropdown Profil --}}
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-20 mt-2 w-64 origin-top-right rounded-md bg-white py-1 shadow-lg focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                            x-cloak style="display: none;">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-[#0D5CD7] text-white flex items-center justify-center text-lg font-semibold">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate"
                                            title="{{ auth()->user()->name }}">
                                            {{ auth()->user()->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate" title="{{ auth()->user()->email }}">
                                            {{ auth()->user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="py-1" role="none">
                                <form method="POST" action="{{ route('logout') }}" role="none">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"
                                        role="menuitem" tabindex="-1" id="user-menu-item-logout">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="p-[8px_16px] lg:p-[12px_20px] bg-white text-black rounded-full font-semibold text-sm hover:bg-gray-100 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="p-[8px_16px] lg:p-[12px_20px] text-black bg-white rounded-full font-semibold text-sm hover:bg-gray-100 transition-colors">Daftar
                        Akun</a>
                @endguest
            </div>
        </nav>

        {{-- Panel Menu Mobile --}}
        <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform -translate-x-full"
            class="fixed inset-y-0 left-0 w-3/4 max-w-xs bg-[#0D5CD7] shadow-xl z-50 p-6 md:hidden overflow-y-auto"
            x-cloak>

            <div class="flex justify-between items-center mb-6">
                <a href="{{ url('/') }}">
                    <h2 class="font-bold text-white text-2xl">Gadget Official</h2>
                </a>
                <button @click="mobileMenuOpen = false" aria-label="Close menu" class="text-white p-1">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @auth
                <div class="mb-6 border-b border-white/20 pb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-full bg-white text-[#0D5CD7] flex items-center justify-center text-xl font-semibold">
                            @php
                                $nameMobile = auth()->user()->name;
                                $wordsMobile = explode(' ', $nameMobile);
                                $initialsMobile = strtoupper(substr($wordsMobile[0], 0, 1));
                                if (count($wordsMobile) > 1) {
                                    $initialsMobile .= strtoupper(substr(end($wordsMobile), 0, 1));
                                }
                            @endphp
                            {{ $initialsMobile }}
                        </div>
                        <div>
                            <p class="font-semibold text-white text-base">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-300">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
            @endauth

            <ul class="flex flex-col space-y-2 mb-6">
                <li><a href="{{ route('home') }}"
                        class="block py-2.5 px-3 rounded-md text-base transition-colors {{ request()->routeIs('home') ? 'bg-white/20 font-semibold' : 'font-medium text-white hover:bg-white/10' }}"
                        wire:navigate>Beranda</a></li>
                <li><a href="#"
                        class="block py-2.5 px-3 rounded-md text-base transition-colors {{ request()->routeIs('shop.index') ? 'bg-white/20 font-semibold' : 'font-medium text-white hover:bg-white/10' }}">Belanja</a>
                </li>
                <li><a href="#"
                        class="block py-2.5 px-3 rounded-md text-base transition-colors {{ request()->routeIs('catalog.index') ? 'bg-white/20 font-semibold' : 'font-medium text-white hover:bg-white/10' }}">Katalog</a>
                </li>

                {{-- Dropdown Transaksi untuk Mobile --}}
                <li x-data="{ open: false }"
                    class="block rounded-md text-base {{ request()->routeIs('transaction') || request()->routeIs('user.credit.invoices') ? 'bg-white/20 font-semibold' : 'font-medium text-white' }}">
                    <button @click="open = !open"
                        class="w-full flex justify-between items-center py-2.5 px-3 hover:bg-white/10 rounded-md transition-colors">
                        <span>Transaksi</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-1 pl-4 space-y-1" x-collapse>
                        <a href="{{ route('transaction') }}" wire:navigate
                            class="block py-2 px-3 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('transaction') ? 'font-bold' : '' }}">Daftar
                            Transaksi</a>
                        <a href="{{ route('installment') }}"
                            class="block py-2 px-3 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('installment') ? 'font-bold' : '' }}">Tagihan
                            Kredit</a>
                    </div>
                </li>
            </ul>

            <div class="pt-4 border-t border-white/20 space-y-3">
                <livewire:components.cart-counter />
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="block w-full text-center py-2.5 px-3 bg-red-500 text-white rounded-full font-semibold text-base hover:bg-red-600 transition-colors">
                            Keluar
                        </a>
                    </form>
                @else
                    <a href="{{ route('login') }}" wire:navigate
                        class="block w-full text-center border-2 border-white py-2 px-3 bg-transparent text-white rounded-full font-semibold text-base hover:bg-white hover:text-[#0D5CD7] transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" wire:navigate
                        class="block w-full text-center py-2 px-3 bg-white text-[#0D5CD7] rounded-full font-semibold text-base hover:bg-gray-200 transition-colors">
                        Daftar
                    </a>
                @endguest
            </div>
        </div>

        {{-- Overlay --}}
        <div x-show="mobileMenuOpen" class="fixed inset-0 bg-black/30 z-40 md:hidden" @click="mobileMenuOpen = false"
            x-cloak></div>
    </header>

    <main>
        {{ $slot }}
    </main>

    @fluxScripts {{-- Asumsi ini adalah tempat script AlpineJS dan Livewire di-load --}}
    @livewireScripts
</body>

</html>
