<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.auth')]
#[Title('Login')]
class Login extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    protected function messages(): array
    {
        return [

            'email.required' => 'Alamat email wajib diisi.',
            'email.string' => 'Alamat email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',

            'password.required' => 'Password wajib diisi.',
        ];
    }

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate(); // Asumsi rules() sudah didefinisikan

        // Asumsi metode ensureIsNotRateLimited() dan throttleKey() sudah ada di komponen Anda
        // seperti pada implementasi Laravel Breeze/Fortify
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'), // Mengambil pesan error dari file lang/id/auth.php
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // Session::regenerate(); // Ini valid jika menggunakan facade Session
        // Alternatif yang umum:
        request()->session()->regenerate(); // Atau session()->regenerate();

        // Dapatkan user yang terautentikasi
        $user = Auth::user();

        // Tentukan URL redirect default berdasarkan role user
        $defaultRedirectUrl = '';

        if ($user && $user->hasRole('admin')) {
            // Jika user adalah admin, arahkan ke dashboard admin
            // Pastikan Anda memiliki route dengan nama 'admin.dashboard'
            $defaultRedirectUrl = route('admin.dashboard', absolute: false);
        } else if ($user && $user->hasRole('user')) {
            // Jika user adalah pengguna biasa (atau role lain yang bukan admin), arahkan ke halaman home
            // Pastikan Anda memiliki route dengan nama 'home'
            $defaultRedirectUrl = route('home', absolute: false);
        }

        $this->redirectIntended(default: $defaultRedirectUrl, navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
