<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Buat Purchase Order (PO) Baru</h2>
    </x-slot>

    <div class="py-5" x-data="poForm()">
        <div class="container-fluid">
            <form action="{{ route('purchasing.purchase-orders.store') }}" method="POST">
                @csrf
                {{-- Bagian Header PO --}}
                <div class="card shadow-sm border-0 mb-4">
                     <div class="card-header bg-dark text-white">
                        <h3 class="mb-0 h5"><i class="bi bi-file-earmark-text-fill me-2"></i>Informasi Purchase Order</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Nomor PO</label>
                                <input type="text" name="po_number" class="form-control bg-light" value="{{ $po_number }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Pabrik</label>
                                <select name="pabrik_id" class="form-select" required>
                                     <option value="">Pilih Pabrik...</option>
                                    @foreach($pabriks as $pabrik)
                                        <option value="{{ $pabrik->id }}">{{ $pabrik->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Tanggal PO</label>
                                <input type="date" name="po_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                             <div class="col-md-3">
                                <label class="form-label fw-bold">Jatuh Tempo</label>
                                <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Gudang Tujuan</label>
                                <select name="warehouse_id" class="form-select" required>
                                     <option value="">Pilih Gudang...</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-md-3">
                                <label class="form-label fw-bold">Baki</label>
                                <select name="baki_id" class="form-select">
                                     <option value="">Pilih Baki...</option>
                                     @foreach($bakis as $baki)
                                        <option value="{{ $baki->id }}">{{ $baki->nama }}</option>
                                     @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Diskon Global (%)</label>
                                <input type="number" step="0.01" name="discount_percentage" class="form-control" x-model.number="globalDiscount" @input="calculateGrandTotal" min="0">
                            </div>
                             <div class="col-md-3">
                                <label class="form-label fw-bold">PPN (%)</label>
                                <input type="number" step="0.01" name="vat_percentage" class="form-control" x-model.number="vatPercentage" @input="calculateGrandTotal" min="0">
                            </div>
                             <div class="col-md-12">
                                <label class="form-label fw-bold">Catatan</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Item Baru --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 h5"><i class="bi bi-boxes me-2"></i>Item Pembelian (Produk Baru)</h3>
                        <button type="button" class="btn btn-success btn-sm" @click="addItem()">+ Tambah Item</button>
                    </div>
                    <div class="card-body">
                         <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jenis</th>
                                        <th>Kadar</th>
                                        <th>Berat (gr)</th>
                                        <th>Harga/gram (Rp)</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td><input type="text" :name="`items[${index}][nama]`" class="form-control" required></td>
                                            <td>
                                                <select :name="`items[${index}][jenis_id]`" class="form-select" required>
                                                    <option value="">Pilih Jenis</option>
                                                    @foreach($jenis as $j) <option value="{{ $j->id }}">{{ $j->nama }}</option> @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select :name="`items[${index}][kadar_id]`" class="form-select" required>
                                                     <option value="">Pilih Kadar</option>
                                                    @foreach($kadars as $k) <option value="{{ $k->id }}">{{ $k->nama }}</option> @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" step="0.01" :name="`items[${index}][berat]`" class="form-control" x-model.number="item.berat" @input="calculatePrice(index)" required></td>
                                            <td><input type="number" :name="`items[${index}][harga_per_gram]`" class="form-control" x-model.number="item.harga_per_gram" @input="calculatePrice(index)" required></td>
                                            <td><input type="number" :name="`items[${index}][quantity]`" class="form-control" x-model.number="item.quantity" @input="calculatePrice(index)" value="1" min="1" required></td>
                                            <td><input type="text" class="form-control bg-light" :value="formatCurrency(item.subtotal)" readonly></td>
                                            <td><button type="button" class="btn btn-danger btn-sm" @click="removeItem(index)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Total</td>
                                            <td class="text-end" x-text="formatCurrency(subTotalAmount)"></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">PPN (<span x-text="vatPercentage"></span>%)</td>
                                            <td class="text-end" x-text="formatCurrency(vatAmount)"></td>
                                        </tr>
                                        <tr class="border-top">
                                            <th class="h5">Grand Total</th>
                                            <th class="text-end h5" x-text="formatCurrency(grandTotal)"></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <a href="{{ route('purchasing.purchase-orders.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan PO</button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('poForm', () => ({
                items: [{ nama: '', jenis_id: '', kadar_id: '', berat: 0, harga_per_gram: 0, quantity: 1, subtotal: 0 }],
                subTotalAmount: 0, vatAmount: 0, grandTotal: 0, globalDiscount: 0, vatPercentage: 0,

                addItem() {
                    this.items.push({ nama: '', jenis_id: '', kadar_id: '', berat: 0, harga_per_gram: 0, quantity: 1, subtotal: 0 });
                },
                
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                        this.calculateGrandTotal();
                    }
                },

                calculatePrice(index) {
                    const item = this.items[index];
                    const hargaSatuan = (item.berat || 0) * (item.harga_per_gram || 0);
                    item.subtotal = hargaSatuan * (item.quantity || 0);
                    this.calculateGrandTotal();
                },
                
                calculateGrandTotal() {
                    this.subTotalAmount = this.items.reduce((sum, item) => sum + (item.subtotal || 0), 0);
                    const totalAfterDiscount = this.subTotalAmount - (this.subTotalAmount * (this.globalDiscount / 100));
                    this.vatAmount = totalAfterDiscount * (this.vatPercentage / 100);
                    this.grandTotal = totalAfterDiscount + this.vatAmount;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 2 }).format(value || 0);
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
