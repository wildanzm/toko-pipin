<?php

namespace App\Livewire\Admin;

use App\Models\Barang;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TokoSuplier;

class Barangs extends Component
{
    public bool $showTambahTokoModal = false;
    public string $namaToko = '';

    public function showTambahTokoModal()
    {
        $this->resetErrorBag();
        $this->namaToko = '';
        $this->showTambahTokoModal = true;
    }

    public function simpanToko()
    {
        $this->validate([
            'namaToko' => 'required|string|max:255|unique:toko_suplier,nama_toko',
        ]);

        TokoSuplier::create([
            'nama_toko' => $this->namaToko,
        ]);

        session()->flash('message', 'Toko berhasil ditambahkan.');
        $this->showTambahTokoModal = false;
    }
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'perPage' => ['except' => 10, 'as' => 'limit'],
    ];

    public function render()
    {
        $barangs = Barang::query()
            ->when($this->search, function ($query) {
                $query->where('nama_barang', 'like', '%' . $this->search . '%')
                      ->orWhere('uniq_key', 'like', '%' . $this->search . '%')
                      ->orWhere('nama_toko_suplier', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $hargaJuals = Barang::pluck('harga_jual', 'unique_key');

        return view('livewire.admin.barangs', [
            'barangs' => $barangs,
            'hargaJuals' => $hargaJuals,
        ]);
    }
}
