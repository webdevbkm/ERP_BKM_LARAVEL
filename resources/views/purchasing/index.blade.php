<!-- File: resources/views/purchasing/index.blade.php -->
<x-app-layout>
    <x-slot name="header"><h2 class="h4 fw-bold">Daftar Purchase Order</h2></x-slot>
    <div class="py-5">
        <div class="container">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat PO</h5>
                    <a href="{{ route('purchase-orders.create') }}" class="btn btn-dark">Buat PO Baru</a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr><th>No. PO</th><th>Tanggal</th><th>Supplier</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($purchaseOrders as $po)
                                <tr>
                                    <td>{{ $po->po_number }}</td>
                                    <td>{{ $po->po_date }}</td>
                                    <td>{{ $po->pabrik->nama }}</td>
                                    <td>Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-{{ $po->status == 'Pending' ? 'warning' : ($po->status == 'Approved' ? 'success' : 'danger') }}">{{ $po->status }}</span></td>
                                    <td><a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-sm btn-info">Detail</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">Belum ada Purchase Order.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $purchaseOrders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>