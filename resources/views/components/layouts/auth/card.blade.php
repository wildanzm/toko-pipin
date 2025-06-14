<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-neutral-100 antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>

                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="flex flex-col gap-6">
                    <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                        <div class="px-10 py-8">{{ $slot }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div id="signin" class="bg-[#EFF3FA] min-h-screen pt-[30px] pb-[50px] flex flex-col">
            <nav class="container max-w-[1130px] mx-auto flex items-center justify-between bg-[#0D5CD7] p-5 rounded-3xl">
                <div class="flex shrink-0">
                    <img src="assets/logos/logo.svg" alt="icon">
                </div>
                <ul class="flex items-center gap-[30px]">
                    <li class="hover:font-bold hover:text-[#FFC736] transition-all duration-300 text-white">
                        <a href="index.html">Shop</a>
                    </li>
                    <li class="hover:font-bold hover:text-[#FFC736] transition-all duration-300 text-white">
                        <a href="">Categories</a>
                    </li>
                    <li class="hover:font-bold hover:text-[#FFC736] transition-all duration-300 text-white">
                        <a href="">Testimonials</a>
                    </li>
                    <li class="hover:font-bold hover:text-[#FFC736] transition-all duration-300 text-white">
                        <a href="">Rewards</a>
                    </li>
                </ul>
                <div class="flex items-center gap-3">
                    <a href="cart.html">
                        <div class="w-12 h-12 flex shrink-0">
                            <img src="assets/icons/cart.svg" alt="icon">
                        </div>
                    </a>
                    <a href="signin.html" class="p-[12px_20px] bg-white rounded-full font-semibold">
                        Sign In
                    </a>
                    <a href="signup.html" class="p-[12px_20px] bg-white rounded-full font-semibold">
                        Sign Up
                    </a>
                </div>
            </nav>
            <div class="container max-w-[1130px] mx-auto flex flex-1 items-center justify-center py-5">
                <form action="index.html" class="w-[500px] bg-white p-[50px_30px] flex flex-col gap-5 rounded-3xl border border-[#E5E5E5]">
                    <div class="flex justify-center">
                        <img src="assets/logos/logo-black.svg" alt="logo">
                    </div>
                    <h1 class="font-bold text-2xl leading-[34px]">Sign In</h1>
                    <div class="flex items-center gap-[10px] rounded-full border border-[#E5E5E5] p-[12px_20px] focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300">
                        <div class="flex shrink-0">
                            <img src="assets/icons/sms.svg" alt="icon">
                        </div>
                        <input type="email" id="" name="" class="appearance-none outline-none w-full placeholder:text-[#616369] placeholder:font-normal font-semibold text-black" placeholder="Write your email address">
                    </div>
                    <div class="flex flex-col gap-[10px]">
                        <div class="flex items-center gap-[10px] rounded-full border border-[#E5E5E5] p-[12px_20px] focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300">
                            <div class="flex shrink-0">
                                <img src="assets/icons/lock.svg" alt="icon">
                            </div>
                            <input type="password" id="password" name="" class="appearance-none outline-none w-full placeholder:text-[#616369] placeholder:font-normal font-semibold text-black" placeholder="Write your password">
                            <button type="button" class="reveal-password flex shrink-0" onclick="togglePasswordVisibility('password', this)">
                                <img src="assets/icons/eye.svg" alt="icon">
                            </button>
                        </div>
                        <a href="" class="text-sm text-[#616369] underline w-fit mr-0 ml-auto">Forgot Password</a>
                    </div>
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="p-[12px_24px] bg-[#0D5CD7] rounded-full text-center font-semibold text-white">Sign In to My Account</button>
                        <a href="signup.html" class="p-[12px_24px] bg-white rounded-full text-center font-semibold border border-[#E5E5E5]">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
