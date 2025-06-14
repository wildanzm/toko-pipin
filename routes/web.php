<?php

use App\Livewire\Cart;
use App\Livewire\Admin\Sale;
use App\Livewire\Admin\Order;
use App\Livewire\Admin\Retur;
use App\Livewire\Installment;
use App\Livewire\LandingPage;
use App\Livewire\Transaction;
use App\Livewire\Admin\Credit;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Admin\Barang\Index;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Barang\Create;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SaleReportController;
use App\Livewire\Admin\Recap;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\Barang\Hutang;
use App\Livewire\Admin\Barangs;


Route::get('/', Login::class)->name('login');


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    // Product Route
    Route::get('/barang', Index::class)->name('barang.index');
    Route::get('/barang/tambah-barang', Create::class)->name('barang.create');
    

    // Route::get('/toko-dan-barang', Barangs::class)->name('barangs');
    // Route::get('/toko-dan-barang/toko', [Barangs::class, 'showTambahTokoModal'])->name('barangs.toko');

    Route::get('/hutang', Hutang::class)->name('hutang');
    Route::get('/hutang/export', [Hutang::class, 'exportPdf'])->name('hutang.export');

    
});


// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Route::get('settings/profile', Profile::class)->name('settings.profile');
//     Route::get('settings/password', Password::class)->name('settings.password');
//     Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
// });

require __DIR__ . '/auth.php';
