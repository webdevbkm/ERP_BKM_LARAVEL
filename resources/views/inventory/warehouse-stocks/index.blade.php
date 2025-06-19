{{-- resources/views/inventory/warehouse-stocks/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Stok per Gudang</h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 h5"><i class="bi bi-shop me-2"></i>Semua Gudang</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Gudang</th>
                                    <th>Lokasi</th>
                                    <th class="text-center">Jml. Tipe Produk</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($warehouses as $warehouse)
                                <tr>
                                    <td class="fw-bold">{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->location ?? '-' }}</td>
                                    <td class="text-center">{{ $warehouse->products_count }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('inventory.warehouse-stocks.show', $warehouse) }}" class="btn btn-sm btn-info" title="Lihat Stok Gudang">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">Tidak ada data gudang.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     @if($warehouses->hasPages())
                        <div class="mt-3">
                            {{ $warehouses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>