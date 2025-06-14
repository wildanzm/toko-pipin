<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Models\StockBarang;

#[Layout('components.layouts.admin')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public float $totalBarangMasukBulanIni = 0;
    public int $totalStokBarang = 0;
    public int $jumlahTransaksiBarangBulanIni = 0;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;

        // 1. Total harga barang masuk bulan ini
        $this->totalBarangMasukBulanIni = DB::table('stock_barangs')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->sum('kuantitas');

        // 2. Total stok barang (jumlah data barang yang pernah masuk)
        $this->totalStokBarang = StockBarang::count();

        // 3. Jumlah transaksi/stok masuk bulan ini
        $this->jumlahTransaksiBarangBulanIni = StockBarang::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
