<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Laporan Stok per Gudang
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            {{-- Panel Filter --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-funnel-fill me-2"></i>Filter Laporan Stok</h5>
                    <form action="{{ route('inventory.stock-by-warehouse') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="warehouse_id" class="form-label">Pilih Gudang</label>
                                <select name="warehouse_id" id="warehouse_id" class="form-select">
                                    <option value="">Semua Gudang</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="search" class="form-label">Cari Produk (Nama/SKU)</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Masukkan nama atau SKU..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3 align-self-end">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Rincian Stok --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Rincian Stok Inventaris</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Gudang</th>
                                    <th>SKU</th>
                                    <th>Nama Produk</th>
                                    <th>Berat</th>
                                    <th class="text-end">Jumlah Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockDetails as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $item->warehouse_name }}</td>
                                        <td>{{ $item->sku }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ rtrim(rtrim($item->berat, '0'), '.') }} gr</td>
                                        <td class="text-end">{{ $item->quantity }} pcs</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted p-4">
                                            Tidak ada data stok yang ditemukan dengan filter ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $stockDetails->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
