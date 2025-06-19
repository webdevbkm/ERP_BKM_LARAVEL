{{-- resources/views/inventory/warehouse-stocks/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Detail Stok Gudang: <span class="text-primary">{{ $warehouse->name }}</span>
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            {{-- Kartu Filter --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-funnel-fill me-2"></i>Filter Produk</h5>
                    <form action="{{ route('inventory.warehouse-stocks.show', $warehouse) }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-9">
                                <label for="search" class="form-label">Cari SKU atau Nama Produk</label>
                                <input type="text" name="search" id="search" class="form-control"
                                       placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3 align-self-end">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Terapkan</button>
                                    <a href="{{ route('inventory.warehouse-stocks.show', $warehouse) }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kartu Tabel Produk --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 h5"><i class="bi bi-boxes me-2"></i>Daftar Produk di Gudang {{ $warehouse->name }}</h3>
                     <a href="{{ route('inventory.warehouse-stocks.index') }}" class="btn btn-sm btn-outline-light">&larr; Kembali ke Daftar Gudang</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>SKU</th>
                                    <th>Nama Produk</th>
                                    <th class="text-center">Jumlah Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stocks as $stock)
                                <tr>
                                    <td class="fw-bold">{{ $stock->sku }}</td>
                                    <td>{{ $stock->nama }}</td> {{-- DIGANTI dari 'name' ke 'nama' --}}
                                    <td class="text-center fw-bold">{{ $stock->quantity }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        @if(request('search'))
                                            Produk dengan kata kunci "{{ request('search') }}" tidak ditemukan.
                                        @else
                                            Tidak ada data produk di gudang ini.
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($stocks->hasPages())
                        <div class="mt-3">
                            {{ $stocks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
