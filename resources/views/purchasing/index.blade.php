<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Daftar Purchase Order</h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Riwayat PO</h5>
                    {{-- **FIX:** Corrected the route name to include the 'purchasing.' prefix --}}
                    <a href="{{ route('purchasing.purchase-orders.create') }}" class="btn btn-light">
                        <i class="bi bi-plus-circle me-1"></i> Buat PO Baru
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No. PO</th>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th>Penerimaan</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchaseOrders as $po)
                                    <tr>
                                        <td class="fw-bold">{{ $po->po_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($po->po_date)->format('d/m/Y') }}</td>
                                        <td>{{ $po->pabrik->nama }}</td>
                                        <td>
                                            <span class="badge bg-{{ $po->status == 'Pending' ? 'warning text-dark' : ($po->status == 'Approved' ? 'success' : 'danger') }}">
                                                {{ $po->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                {{ $po->receipt_status }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            {{-- **FIX:** Corrected the route name to include the 'purchasing.' prefix --}}
                                            <a href="{{ route('purchasing.purchase-orders.show', $po->id) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye-fill"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">Belum ada Purchase Order.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $purchaseOrders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
