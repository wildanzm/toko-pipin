<div>
    {{-- Wrapper utama untuk centering di tengah halaman --}}
    <div class="flex flex-col min-h-screen items-center justify-center p-4 sm:p-6">
        <form wire:submit.prevent="login"
            class="w-full sm:max-w-md md:w-[500px] bg-white p-6 md:py-[50px] md:px-[30px] flex flex-col gap-5 rounded-xl md:rounded-3xl border border-[#E5E5E5] shadow-md">
            @csrf
            <div class="flex justify-center mb-4">
                <h1 class="font-bold text-black text-2xl sm:text-3xl">Tokopipin</h1>
            </div>

            {{-- General Login Error --}}
            @error('login_failed')
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-0" role="alert">
                    <span class="block sm:inline">{{ $message }}</span>
                </div>
            @enderror

            {{-- Email Input --}}
            <div>
                <div
                    class="flex items-center gap-3 rounded-full border border-[#E5E5E5] p-3 sm:p-4 focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300 @error('email') border-red-500 ring-red-500 @enderror">
                    <div class="flex shrink-0">
                        <img src="{{ asset('assets/images/icons/sms.svg') }}" alt="Email Icon"
                            class="w-5 h-5 sm:w-6 sm:h-6">
                    </div>
                    <input type="email" wire:model.lazy="email"
                        class="appearance-none outline-none w-full placeholder:text-[#616369] placeholder:font-normal font-semibold text-black text-sm sm:text-base 
                          bg-transparent border-0 focus:ring-0"
                        {{-- Added: bg-transparent border-0 focus:ring-0 --}} placeholder="Tulis email kamu" autofocus>
                </div>
                @error('email')
                    <span class="text-red-500 text-xs mt-1 ml-4">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password Input --}}
            <div>
                <div x-data="{ showPassword: false }"
                    class="flex items-center gap-3 rounded-full border border-[#E5E5E5] p-3 sm:p-4 focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300 @error('password') border-red-500 ring-red-500 @enderror">
                    <div class="flex shrink-0">
                        <img src="{{ asset('assets/images/icons/lock.svg') }}" alt="Lock Icon"
                            class="w-5 h-5 sm:w-6 sm:h-6">
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" wire:model.lazy="password"
                        class="appearance-none outline-none w-full placeholder:text-[#616369] placeholder:font-normal font-semibold text-black text-sm sm:text-base 
                          bg-transparent border-0 focus:ring-0"
                        {{-- Added: bg-transparent border-0 focus:ring-0 --}} placeholder="Tulis password kamu">
                    <button type="button" @click="showPassword = !showPassword" class="reveal-password flex shrink-0">
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

            <div class="flex flex-col gap-3 mt-4">
                <button type="submit" wire:loading.attr="disabled" wire:target="login"
                    class="p-3 sm:p-4 bg-[#0D5CD7] rounded-full text-center font-semibold text-white hover:bg-blue-700 transition-colors duration-300 disabled:opacity-75">
                    <span wire:loading.remove wire:target="login">Login</span>
                    <span wire:loading wire:target="login">Memproses...</span>
                </button>
            </div>
        </form>
    </div>
</div>
