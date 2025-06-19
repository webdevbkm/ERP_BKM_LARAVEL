<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Formulir Penerimaan Barang PO: {{ $purchaseOrder->po_number }}</h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white"><h3 class="mb-0"><i class="bi bi-box-seam me-2"></i>Detail Penerimaan</h3></div>
                <div class="card-body">
                    <form action="{{ route('purchasing.goods-receipts.store', $purchaseOrder->id) }}" method="POST">
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-4"><label class="form-label fw-bold">Tanggal Terima</label><input type="date" name="receipt_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                            <div class="col-md-4"><label class="form-label fw-bold">Gudang Tujuan</label>
                                <select name="warehouse_id" class="form-select" required>
                                    <option value="" disabled selected>Pilih Gudang...</option>
                                    @foreach($warehouses as $warehouse) <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light"><tr><th>Produk</th><th>Qty Dipesan</th><th>Qty Diterima</th></tr></thead>
                                <tbody>
                                    @foreach($purchaseOrder->details as $detail)
                                    <tr>
                                        <td>
                                            {{ $detail->product->nama }}
                                            <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $detail->product_id }}">
                                        </td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td><input type="number" name="items[{{ $loop->index }}][quantity_received]" class="form-control" value="{{ $detail->quantity }}" max="{{ $detail->quantity }}" min="0" required></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3"><label class="form-label fw-bold">Catatan (Opsional)</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
                        <div class="text-end mt-4">
                            <a href="{{ route('purchasing.goods-receipts.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Konfirmasi Penerimaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
