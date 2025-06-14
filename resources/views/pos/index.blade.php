<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Point of Sale (POS)
        </h2>
    </x-slot>

    {{-- 1. Memuat library JavaScript untuk QR Code Scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <div class="container-fluid py-4">
         @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
             <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Kolom Kiri: Input Produk & Keranjang --}}
            <div class="col-lg-7">
                {{-- Input Produk --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        {{-- Form pencarian SKU sekarang terpisah dan menggunakan metode GET --}}
                        <form action="{{ route('pos.index') }}" method="GET" id="search-form">
                            <label for="sku" class="form-label fw-bold">Scan atau Ketik SKU Produk</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" class="form-control" name="sku" id="sku-input" placeholder="Ketik SKU atau gunakan scanner..." value="{{ request('sku') }}" autofocus>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scannerModal">
                                    <i class="bi bi-qr-code-scan"></i> Scan
                                </button>
                                <button type="submit" class="btn btn-dark">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Hasil Pencarian / Preview Produk --}}
                @if($errorMessage)
                    <div class="alert alert-danger">{{ $errorMessage }}</div>
                @endif
                @if($foundProduct)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 text-success"><i class="bi bi-check-circle-fill me-2"></i>Produk Ditemukan</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-4 text-center">
                                    <img src="{{ $foundProduct->foto_produk_path ? asset('storage/' . $foundProduct->foto_produk_path) : 'https://placehold.co/200x200/e9ecef/6c757d?text=N/A' }}" class="img-fluid rounded border p-2">
                                </div>
                                <div class="col-md-8">
                                    <h4>{{ $foundProduct->nama }}</h4>
                                    <p class="text-muted mb-2">SKU: {{ $foundProduct->sku }}</p>
                                    
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td style="width: 120px;">Berat</td>
                                            <td>: {{ rtrim(rtrim($foundProduct->berat, '0'), '.') }} gr</td>
                                        </tr>
                                        <tr>
                                            <td>Kadar</td>
                                            <td>: {{ $foundProduct->kadar->nama ?? '-' }}</td>
                                        </tr>
                                         <tr>
                                            <td>Tipe Harga</td>
                                            <td>: {{ $foundProduct->priceType->nama_tipe ?? 'Normal' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pabrik</td>
                                            <td>: {{ $foundProduct->pabrik->nama ?? '-' }}</td>
                                        </tr>
                                    </table>

                                    <p class="fs-4 fw-bold text-success mt-2">
                                        Rp {{ number_format($foundProduct->harga_final, 0, ',', '.') }}
                                    </p>
                                    
                                    <form action="{{ route('pos.addToCart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $foundProduct->id }}">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-cart-plus-fill me-2"></i>Tambah ke Keranjang</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tabel Keranjang Belanja --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-custom-pink text-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-cart-fill me-2"></i>Keranjang Belanja</h5>
                        @if(count($cart) > 0)
                        <form action="{{ route('pos.clearCart') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Kosongkan Keranjang</button>
                        </form>
                        @endif
                    </div>
                    <div class="card-body p-0">
                         <div class="table-responsive" style="max-height: 300px;">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Produk</th>
                                        <th>Harga</th>
                                        <th class="text-end pe-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cart as $key => $item)
                                        <tr>
                                            <td class="ps-3">
                                                <strong>{{ $item['nama'] }}</strong><br>
                                                <small class="text-muted">SKU: {{ $item['sku'] }}</small>
                                            </td>
                                            <td>Rp {{ number_format($item['harga_final'], 0, ',', '.') }}</td>
                                            <td class="text-end pe-3">
                                                <form action="{{ route('pos.removeFromCart') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="cart_key" value="{{ $key }}">
                                                    <button type="submit" class="btn btn-sm btn-danger">&times;</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted p-4">Keranjang kosong.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Pembayaran --}}
            <div class="col-lg-5">
                <form action="{{ route('pos.store') }}" method="POST">
                    @csrf
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>Data Pelanggan & Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            {{-- Input-input ini sekarang menjadi bagian dari form utama --}}
                            <div class="mb-3">
                                <label for="customer_name" class="form-label fw-bold">Nama Pelanggan</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control" required value="{{ old('customer_name') }}">
                            </div>
                             <div class="mb-3">
                                <label for="customer_phone" class="form-label fw-bold">No. Telepon</label>
                                <input type="text" name="customer_phone" id="customer_phone" class="form-control" required value="{{ old('customer_phone') }}">
                            </div>
                            <div class="mb-3">
                                <label for="customer_address" class="form-label fw-bold">Alamat (Opsional)</label>
                                <textarea name="customer_address" id="customer_address" class="form-control" rows="2">{{ old('customer_address') }}</textarea>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label for="payment_method" class="form-label fw-bold">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-select">
                                    <option value="cash">Tunai (Cash)</option>
                                    <option value="card">Kartu Debit/Kredit</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>
                             <div class="mb-3">
                                <label for="cabang_id" class="form-label fw-bold">Cabang Transaksi</label>
                                <select name="cabang_id" id="cabang_id" class="form-select" required>
                                    <option value="" disabled selected>Pilih Cabang...</option>
                                    @foreach($cabangs as $cabang)
                                        <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fs-5 mb-2">
                                <span>Total Belanja</span>
                                <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Bayar</span>
                                <input type="number" name="amount_paid" class="form-control" value="{{ old('amount_paid', $total) }}" required>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-lg btn-success" @if(count($cart) == 0) disabled @endif>
                                    <i class="bi bi-printer-fill me-2"></i> Simpan & Cetak Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal untuk menampilkan kamera scanner --}}
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scannerModalLabel">Arahkan Kamera ke QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="qr-reader" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk mengaktifkan scanner --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const scannerModal = document.getElementById('scannerModal');
            let html5QrcodeScanner;

            function onScanSuccess(decodedText, decodedResult) {
                document.getElementById('sku-input').value = decodedText;
                
                if (html5QrcodeScanner) {
                    try {
                        html5QrcodeScanner.clear();
                    } catch(e) {}
                }
                
                const modal = bootstrap.Modal.getInstance(scannerModal);
                if (modal) {
                    modal.hide();
                }

                document.getElementById('search-form').submit();
            }

            if(scannerModal) {
                scannerModal.addEventListener('shown.bs.modal', function () {
                    html5QrcodeScanner = new Html5QrcodeScanner(
                        "qr-reader", 
                        { fps: 10, qrbox: {width: 250, height: 250} },
                        false
                    );
                    html5QrcodeScanner.render(onScanSuccess);
                });

                scannerModal.addEventListener('hidden.bs.modal', function () {
                    if (html5QrcodeScanner) {
                        try {
                            if(html5QrcodeScanner.getState() !== 3) { // 3 is NOT_STARTED
                                html5QrcodeScanner.clear();
                            }
                        } catch (error) {
                            console.error("Gagal membersihkan scanner:", error);
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
