<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @livewireStyles
</head>

<body class="min-h-screen bg-white dark:bg-gray-900">

    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        {{-- Kontainer utama sidebar dibuat flex column untuk mendorong profil ke bawah --}}
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800 flex flex-col">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center ps-2.5 mb-5">
                <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Tokopipin</span>
            </a>

            {{-- Menu Utama --}}
            <ul class="space-y-2 font-medium flex-grow">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center p-2 rounded-lg group 
                           {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.dashboard.*')
                               ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white'
                               : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.dashboard.*') ? 'text-gray-900 dark:text-white' : '' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.barang.index') }}"
                        class="flex items-center p-2 rounded-lg group
                           {{ request()->routeIs('admin.barang.index') || request()->routeIs('admin.barang.*')
                               ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white'
                               : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.*') ? 'text-gray-900 dark:text-white' : '' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 20">
                            <path
                                d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Kelola Barang</span>
                    </a>
                </li>
                <!-- <li>
                    <a href=""
                        class="flex items-center p-2 rounded-lg group {{ request()->routeIs('admin.barangs') || request()->routeIs('admin.barangs')
                            ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white'
                            : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <svg class="shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M20.29 8.567c.133.323.334.613.59.85v.002a3.536 3.536 0 0 1 0 5.166 2.442 2.442 0 0 0-.776 1.868 3.534 3.534 0 0 1-3.651 3.653 2.483 2.483 0 0 0-1.87.776 3.537 3.537 0 0 1-5.164 0 2.44 2.44 0 0 0-1.87-.776 3.533 3.533 0 0 1-3.653-3.654 2.44 2.44 0 0 0-.775-1.868 3.537 3.537 0 0 1 0-5.166 2.44 2.44 0 0 0 .775-1.87 3.55 3.55 0 0 1 1.033-2.62 3.594 3.594 0 0 1 2.62-1.032 2.401 2.401 0 0 0 1.87-.775 3.535 3.535 0 0 1 5.165 0 2.444 2.444 0 0 0 1.869.775 3.532 3.532 0 0 1 3.652 3.652c-.012.35.051.697.184 1.02ZM9.927 7.371a1 1 0 1 0 0 2h.01a1 1 0 0 0 0-2h-.01Zm5.889 2.226a1 1 0 0 0-1.414-1.415L8.184 14.4a1 1 0 0 0 1.414 1.414l6.218-6.217Zm-2.79 5.028a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Data barang & Toko</span>
                    </a>
                </li> -->
                <li>
                    <a href="{{ route('admin.hutang') }}"
                        class="flex items-center p-2 rounded-lg group {{ request()->routeIs('admin.credit') || request()->routeIs('admin.credit')
                            ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white'
                            : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <svg class="shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H4Zm0 6h16v6H4v-6Z"
                                clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M5 14a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1Zm5 0a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2h-5a1 1 0 0 1-1-1Z"
                                clip-rule="evenodd" />
                        </svg>



                        <span class="flex-1 ms-3 whitespace-nowrap">Hutang</span>
                    </a>
                </li>
            </ul>

            {{-- Bagian Profil Pengguna dan Logout (Dropdown) di paling bawah --}}
            <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                @auth
                    <div x-data="{ open: false }" class="relative">
                        {{-- Tombol Pemicu Dropdown (Avatar, Nama, Email) --}}
                        <button @click="open = !open" type="button"
                            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group focus:outline-none">
                            {{-- Avatar dengan Inisial --}}
                            <div
                                class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 flex items-center justify-center text-sm font-semibold shrink-0">
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
                            {{-- Nama dan Email --}}
                            <div class="ms-3 text-left flex-1 overflow-hidden">
                                <span class="block text-sm font-medium truncate">{{ auth()->user()->name }}</span>
                                <span
                                    class="block text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</span>
                            </div>
                            {{-- Ikon Chevron --}}
                            <svg class="ms-auto w-4 h-4 shrink-0 text-gray-500 dark:text-gray-400 transition-transform duration-200 group-hover:text-gray-900 dark:group-hover:text-white"
                                :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- Panel Dropdown (Membuka ke atas karena berada di bawah) --}}
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute bottom-full left-0 mb-2 w-[calc(100%-0.75rem)] {{-- Sesuaikan lebar agar pas --}} origin-bottom-left rounded-md bg-white dark:bg-gray-700 py-1 shadow-lg focus:outline-none z-10 mx-1.5"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-profile-menu-button" x-cloak
                            style="display: none;">

                            <form method="POST" action="{{ route('logout') }}" role="none">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-600"
                                    role="menuitem">
                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        {{ $slot }}

    </div>



    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @livewireScripts

</body>

</html>
