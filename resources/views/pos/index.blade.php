<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Point of Sale (POS)</h2>
    </x-slot>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    {{-- Menggunakan AlpineJS untuk mengelola state interaktif --}}
    <div class="container-fluid py-4" x-data="posForm()">
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
                    <input type="hidden" name="customer_id" :value="selectedCustomer.id">
                    <input type="hidden" name="points_redeemed" :value="pointsToRedeem">
                    <input type="hidden" name="redeemed_amount" :value="redeemedAmount">

                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header"><h5 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>Data Pelanggan & Pembayaran</h5></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label fw-bold">No. Telepon</label>
                                <input type="text" id="customer_phone" class="form-control" x-model="customerPhone" @input.debounce.750ms="searchCustomer()" placeholder="Ketik no. telepon untuk cari pelanggan...">
                            </div>
                            <div class="mb-3">
                                <label for="customer_name" class="form-label fw-bold">Nama Pelanggan</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control" required x-model="selectedCustomer.nama">
                            </div>
                            <div class="mb-3">
                                <label for="customer_address" class="form-label fw-bold">Alamat (Opsional)</label>
                                <textarea name="customer_address" id="customer_address" class="form-control" rows="2" x-model="selectedCustomer.alamat"></textarea>
                            </div>

                            {{-- [BARU] Bagian Poin Pelanggan & Form Penukaran --}}
                            <div x-show="selectedCustomer.id" class="mt-3 p-3 bg-light rounded" style="display: none;" x-transition>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><span class="fw-bold">Saldo Poin:</span> <span class="fs-5 fw-bolder text-success" x-text="selectedCustomer.points_balance"></span></div>
                                    <button type="button" class="btn btn-sm" :class="showRedeemForm ? 'btn-outline-secondary' : 'btn-outline-primary'" @click="toggleRedeemForm()" :disabled="selectedCustomer.points_balance <= 0 || redeemedAmount > 0">
                                        <span x-show="!showRedeemForm">Tukar Poin</span>
                                        <span x-show="showRedeemForm">Batal Tukar</span>
                                    </button>
                                </div>
                                
                                {{-- Form Penukaran Poin (tersembunyi secara default) --}}
                                <div x-show="showRedeemForm" class="mt-3 border-top pt-3">
                                    <div x-show="!otpSent">
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah Poin untuk Ditukar</label>
                                            <input type="number" class="form-control" x-model.number="pointsToRedeem" :max="selectedCustomer.points_balance" min="1">
                                            <div class="form-text">Nilai Tukar: <span x-text="formatCurrency(pointsToRedeem * 5000)"></span></div>
                                        </div>
                                        <button type="button" class="btn btn-primary w-100" @click="requestOtp()">Kirim OTP</button>
                                    </div>
                                    <div x-show="otpSent">
                                        <p class="text-center">Kode OTP telah dikirim ke <b x-text="selectedCustomer.telepon"></b>.</p>
                                        <div class="mb-3">
                                            <label class="form-label">Masukkan Kode OTP</label>
                                            <input type="text" class="form-control" x-model="otpCode" maxlength="6">
                                        </div>
                                        <button type="button" class="btn btn-success w-100" @click="verifyOtp()">Verifikasi & Terapkan Diskon</button>
                                    </div>
                                    <div x-show="redeemMessage" class="alert mt-3" :class="redeemSuccess ? 'alert-success' : 'alert-danger'" x-text="redeemMessage" style="display:none;"></div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            {{-- ... (Input Metode Pembayaran & Cabang) ... --}}

                            <div class="d-flex justify-content-between fs-5 mb-2"><span>Total Belanja</span><span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                            <div x-show="redeemedAmount > 0" class="d-flex justify-content-between fs-6 mb-2 text-danger" style="display: none;"><span>Diskon Poin</span><span class="fw-bold" x-text="'- ' + formatCurrency(redeemedAmount)"></span></div>
                            <hr x-show="redeemedAmount > 0" style="display: none;">
                            <div class="d-flex justify-content-between fs-4 mb-2 fw-bolder"><span>Total Akhir</span><span x-text="formatCurrency(grandTotal)"></span></div>
                            <div class="input-group mb-2"><span class="input-group-text">Bayar</span><input type="number" name="amount_paid" class="form-control" value="{{ old('amount_paid', $total) }}" required></div>
                            <div class="d-grid mt-4"><button type="submit" class="btn btn-lg btn-success" @if(count($cart) == 0) disabled @endif><i class="bi bi-printer-fill me-2"></i> Simpan & Cetak Invoice</button></div>
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

    
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posForm', () => ({
                customerPhone: '',
                selectedCustomer: { id: null, nama: '', alamat: '', points_balance: 0, telepon: '' },
                grandTotal: {{ $total ?? 0 }},
                showRedeemForm: false, // State untuk menampilkan/menyembunyikan form
                pointsToRedeem: 0,
                redeemedAmount: 0,
                otpSent: false,
                otpCode: '',
                redeemMessage: '',
                redeemSuccess: false,

                init() { this.calculateGrandTotal(); },
                
                async searchCustomer() {
                    this.resetCustomer();
                    if (this.customerPhone.length < 5) return;
                    try {
                        const response = await fetch(`{{ route('api.customers.search') }}?phone=${this.customerPhone}`);
                        if (!response.ok) throw new Error('Pelanggan tidak ditemukan');
                        const data = await response.json();
                        this.selectedCustomer = data;
                    } catch (error) { console.error(error.message); }
                },
                
                resetCustomer() {
                    this.selectedCustomer = { id: null, nama: '', alamat: '', points_balance: 0, telepon: '' };
                    this.redeemedAmount = 0;
                    this.showRedeemForm = false;
                    this.calculateGrandTotal();
                },
                
                toggleRedeemForm() {
                    this.showRedeemForm = !this.showRedeemForm;
                    this.otpSent = false;
                    this.redeemMessage = '';
                },

                async requestOtp() {
                    // ... (logika requestOtp sama seperti rencana sebelumnya) ...
                },
                
                async verifyOtp() {
                    // ... (logika verifyOtp sama seperti rencana sebelumnya) ...
                },
                
                calculateGrandTotal() {
                    this.grandTotal = ({{ $total ?? 0 }}) - this.redeemedAmount;
                },

                formatCurrency(value) {
                    return !isNaN(value) ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value) : 'Rp 0';
                },
            }));
        });
        
        // ... (Script scanner Anda yang sudah ada bisa diletakkan di sini) ...
    </script>
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
