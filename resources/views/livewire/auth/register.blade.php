<div>
    {{-- Wrapper utama untuk centering di tengah halaman --}}
    <div class="flex flex-col min-h-screen items-center justify-center p-4 sm:p-6">
        <form wire:submit.prevent="register"
            class="w-full sm:max-w-md md:w-[500px] bg-white p-6 md:py-[50px] md:px-[30px] flex flex-col gap-5 rounded-xl md:rounded-3xl border border-[#E5E5E5] shadow-md">

            <div class="flex justify-center mb-4">
                <h2 class="font-bold text-black text-2xl sm:text-3xl">Gadget Official</h2>
            </div>
            <h1 class="font-bold text-black text-xl sm:text-2xl leading-tight text-center sm:text-left">Daftar Akun Baru
            </h1>

            {{-- Name Input --}}
            <div>
                <div
                    class="flex items-center gap-3 rounded-full border border-[#E5E5E5] p-3 sm:p-4 focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300 @error('name') border-red-500 ring-red-500 @enderror">
                    <div class="flex shrink-0">
                        <img src="{{ asset('assets/images/icons/profile-circle.svg') }}" alt="Ikon Nama Pengguna"
                            class="w-5 h-5 sm:w-6 sm:h-6">
                    </div>
                    <input type="text" wire:model.defer="name"
                        class="appearance-none outline-none w-full placeholder:text-[#616369] placeholder:font-normal font-semibold text-black text-sm sm:text-base
                           bg-transparent border-0 focus:ring-0"
                        {{-- Ditambahkan: bg-transparent border-0 focus:ring-0 --}} placeholder="Masukan Nama Lengkap" autofocus>
                </div>
                @error('name')
                    <span class="text-red-500 text-xs mt-1 ml-4">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email Input --}}
            <div>
                <div
                    class="flex items-center gap-3 rounded-full border border-[#E5E5E5] p-3 sm:p-4 focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300 @error('email') border-red-500 ring-red-500 @enderror">
                    <div class="flex shrink-0">
                        <img src="{{ asset('assets/images/icons/sms.svg') }}" alt="Ikon Email"
                            class="w-5 h-5 sm:w-6 sm:h-6">
                    </div>
                    <input type="email" wire:model.defer="email"
                        class="appearance-none outline-none w-full placeholder:text-[#616369] placeholder:font-normal font-semibold text-black text-sm sm:text-base
                           bg-transparent border-0 focus:ring-0"
                        {{-- Ditambahkan: bg-transparent border-0 focus:ring-0 --}} placeholder="Masukan Email">
                </div>
                @error('email')
                    <span class="text-red-500 text-xs mt-1 ml-4">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password Input --}}
            <div class="flex flex-col gap-1">
                <div x-data="{ showPassword: false }"
                    class="flex items-center gap-3 rounded-full border border-[#E5E5E5] p-3 sm:p-4 focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300 @error('password') border-red-500 ring-red-500 @enderror">
                    <div class="flex shrink-0">
                        <img src="{{ asset('assets/images/icons/lock.svg') }}" alt="Ikon Kunci Password"
                            class="w-5 h-5 sm:w-6 sm:h-6">
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" wire:model.defer="password"
                        class="appearance-none outline-none w-full placeholder:text-[#616369] placeholder:font-normal font-semibold text-black text-sm sm:text-base
                           bg-transparent border-0 focus:ring-0"
                        {{-- Ditambahkan: bg-transparent border-0 focus:ring-0 --}} placeholder="Masukan Password">
                    <button type="button" @click="showPassword = !showPassword" class="reveal-password flex shrink-0"
                        aria-label="Toggle Password Visibility">
                        <img x-show="!showPassword" src="{{ asset('assets/images/icons/eye.svg') }}"
                            alt="Tampilkan password" class="w-5 h-5 sm:w-6 sm:h-6">
                        <img x-show="showPassword" src="{{ asset('assets/images/icons/eye.svg') }}"
                            alt="Sembunyikan password" class="w-5 h-5 sm:w-6 sm:h-6" style="display: none;">
                    </button>
                </div>
                @error('password')
                    <span class="text-red-500 text-xs mt-1 ml-4">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password Confirmation Input --}}
            <div class="flex flex-col gap-1">
                <div x-data="{ showPassword: false }"
                    class="flex items-center gap-3 rounded-full border border-[#E5E5E5] p-3 sm:p-4 focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300 @error('password_confirmation') border-red-500 ring-red-500 @enderror">
                    <div class="flex shrink-0">
                        <img src="{{ asset('assets/images/icons/lock.svg') }}" alt="Ikon Kunci Konfirmasi Password"
                            class="w-5 h-5 sm:w-6 sm:h-6">
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" wire:model.defer="password_confirmation"
                        class="appearance-none outline-none w-full placeholder:text-[#616369] placeholder:font-normal font-semibold text-black text-sm sm:text-base
                           bg-transparent border-0 focus:ring-0"
                        {{-- Ditambahkan: bg-transparent border-0 focus:ring-0 --}} placeholder="Konfirmasi Password">
                    <button type="button" @click="showPassword = !showPassword" class="reveal-password flex shrink-0"
                        aria-label="Toggle Password Confirmation Visibility">
                        <img x-show="!showPassword" src="{{ asset('assets/images/icons/eye.svg') }}"
                            alt="Tampilkan password" class="w-5 h-5 sm:w-6 sm:h-6">
                        <img x-show="showPassword" src="{{ asset('assets/images/icons/eye.svg') }}"
                            alt="Sembunyikan password" class="w-5 h-5 sm:w-6 sm:h-6" style="display: none;">
                    </button>
                </div>
                @error('password_confirmation')
                    <span class="text-red-500 text-xs mt-1 ml-4">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-3 mt-4">
                <button type="submit" wire:loading.attr="disabled" wire:target="register"
                    class="p-3 sm:p-4 bg-[#0D5CD7] rounded-full text-center font-semibold text-white hover:bg-blue-700 transition-colors duration-300 disabled:opacity-75">
                    <span wire:loading.remove wire:target="register">Buat Akun Baru</span>
                    <span wire:loading wire:target="register">Memproses...</span>
                </button>
                <a href="{{ route('login') }}" wire:navigate
                    class="p-3 sm:p-4 bg-white rounded-full text-center font-semibold border text-black border-[#E5E5E5] hover:bg-gray-50 transition-colors duration-300">
                    Sudah Punya Akun? Masuk
                </a>
            </div>
        </form>
    </div>
</div>
