<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Penerimaan Barang dari Supplier</h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h3 class="mb-0"><i class="bi bi-list-check me-2"></i>Daftar PO Disetujui (Siap Diterima)</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr><th>No. PO</th><th>Tanggal</th><th>Supplier</th><th>Status</th><th class="text-end">Aksi</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($purchaseOrders as $po)
                                    <tr>
                                        <td>{{ $po->po_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($po->po_date)->format('d/m/Y') }}</td>
                                        <td>{{ $po->pabrik->nama }}</td>
                                        <td><span class="badge bg-success">{{ $po->status }}</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('purchasing.goods-receipts.create', $po->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-box-arrow-in-down me-1"></i> Terima Barang
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Tidak ada PO yang perlu diterima.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $purchaseOrders->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>