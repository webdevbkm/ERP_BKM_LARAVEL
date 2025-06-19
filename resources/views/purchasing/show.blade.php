<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Detail Purchase Order: {{ $purchaseOrder->po_number }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            {{-- Detail Header PO --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white">
                    <h3 class="mb-0 h5">Informasi PO</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nomor PO:</strong> {{ $purchaseOrder->po_number }}</p>
                            <p><strong>Pabrik:</strong> {{ $purchaseOrder->pabrik->nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal PO:</strong> {{ $purchaseOrder->po_date->format('d M Y') }}</p>
                            <p><strong>Status:</strong> <span class="badge bg-{{ $purchaseOrder->status == 'Approved' ? 'success' : ($purchaseOrder->status == 'Pending' ? 'warning' : 'danger') }}">{{ $purchaseOrder->status }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Item PO --}}
            <div class="card shadow-sm border-0">
                 <div class="card-header bg-dark text-white">
                    <h3 class="mb-0 h5">Item yang Dipesan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jenis</th>
                                    <th>Kadar</th>
                                    <th>Berat</th>
                                    <th>Harga/gram</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseOrder->details as $detail)
                                <tr>
                                    <td>{{ $detail->nama_produk_baru }}</td>
                                    <td>{{ $detail->jenis->nama }}</td>
                                    <td>{{ $detail->kadar->nama }}</td>
                                    <td>{{ $detail->berat }} gr</td>
                                    <td>Rp {{ number_format($detail->harga_per_gram_input, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="fw-bold">
                                <tr>
                                    <td colspan="6" class="text-end">Grand Total</td>
                                    <td class="text-end">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi untuk Otorisasi --}}
            @can('approve purchase orders')
                @if($purchaseOrder->status == 'Pending')
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header">
                        <h3 class="mb-0 h5">Aksi Otorisasi</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>Setujui atau tolak Purchase Order ini.</p>
                        <div class="d-flex justify-content-center gap-2">
                             <form action="{{ route('purchasing.purchase-orders.approve', $purchaseOrder) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="Approved">
                                <button type="submit" class="btn btn-success">Setujui PO</button>
                            </form>
                             <form action="{{ route('purchasing.purchase-orders.approve', $purchaseOrder) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="btn btn-danger">Tolak PO</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endcan
        </div>
    </div>
</x-app-layout>