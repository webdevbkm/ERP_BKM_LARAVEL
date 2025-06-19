<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Point of Sale (POS)
        </h2>
    </x-slot>

    {{-- Komponen utama POS menggunakan Alpine.js --}}
    <div class="container-fluid py-4" x-data="posApp()" x-init="init()">
        <div class="row g-4">
            {{-- Kolom Kiri: Keranjang Belanja & Pembayaran --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-custom-pink text-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-cart-fill me-2"></i>Keranjang Belansa</h5>
                        <span class="badge bg-dark rounded-pill" x-text="cart.length"></span>
                    </div>
                    <div class="card-body" style="min-height: 400px; max-height: 400px; overflow-y: auto;">
                        <template x-if="cart.length === 0">
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-cart-x fs-1"></i>
                                <p class="mt-2">Keranjang masih kosong.</p>
                            </div>
                        </template>
                        <ul class="list-group list-group-flush">
                            <template x-for="(item, index) in cart" :key="item.id + '-' + index">
                                <li class="list-group-item d-flex align-items-center">
                                    <img :src="item.foto_produk_path ? `/storage/${item.foto_produk_path}` : 'https://placehold.co/60x60/e9ecef/6c757d?text=N/A'" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-bold" x-text="item.nama"></p>
                                        <p class="mb-0 text-muted small" x-text="`SKU: ${item.sku}`"></p>
                                        <p class="mb-0 fw-bold" x-text="formatCurrency(item.harga_final)"></p>
                                    </div>
                                    <button @click="removeFromCart(index)" class="btn btn-sm btn-outline-danger ms-3">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div class="card-footer p-3">
                        <div class="d-flex justify-content-between fs-5 mb-3">
                            <span class="fw-semibold">Total</span>
                            <span class="fw-bold" x-text="formatCurrency(total)"></span>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-success btn-lg" :disabled="cart.length === 0">
                                <i class="bi bi-cash-coin me-2"></i> Proses Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Pencarian Produk & Hasil --}}
            <div class="col-lg-7">
                {{-- Bagian Pencarian --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                         <div class="input-group input-group-lg flex-grow-1">
                            <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" class="form-control" placeholder="Scan atau ketik SKU produk..." x-model="skuInput" @keydown.enter.prevent="findProduct()" x-ref="skuInput">
                        </div>
                    </div>
                </div>

                {{-- Bagian Hasil Pencarian (Preview Produk) --}}
                <div class="text-center" x-show="isLoading">
                    <div class="spinner-border text-custom-pink" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="alert alert-danger" x-show="errorMessage" x-text="errorMessage" x-transition></div>
                
                <template x-if="foundProduct">
                    <div class="card shadow-sm border-0" x-transition>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <img :src="foundProduct.foto_produk_path ? `/storage/${foundProduct.foto_produk_path}` : 'https://placehold.co/200x200/e9ecef/6c757d?text=N/A'" class="img-fluid rounded border">
                                </div>
                                <div class="col-md-8">
                                    <h4 x-text="foundProduct.nama"></h4>
                                    <p class="text-muted" x-text="`SKU: ${foundProduct.sku}`"></p>
                                    <p class="fs-4 fw-bold text-success" x-text="formatCurrency(foundProduct.harga_final)"></p>
                                    <p x-text="`Stok Tersedia: ${foundProduct.stok}`"></p>
                                    <div class="d-grid">
                                        <button @click="addToCartAndClear()" class="btn btn-primary btn-lg"><i class="bi bi-cart-plus-fill me-2"></i>Tambah ke Keranjang</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function posApp() {
        return {
            skuInput: '',
            cart: [],
            total: 0,
            isLoading: false,
            errorMessage: '',
            foundProduct: null,

            init() {
                this.$refs.skuInput.focus();
            },

            async findProduct() {
                if (!this.skuInput.trim()) return;
                this.isLoading = true;
                this.errorMessage = '';
                this.foundProduct = null;

                try {
                    const response = await fetch(`/pos/find-product/${this.skuInput}`);
                    
                    // Jika respons tidak OK (misal: 404 Not Found, 500 Server Error)
                    if (!response.ok) {
                        const errorData = await response.json();
                        this.errorMessage = errorData.message || `Error: ${response.statusText}`;
                        this.isLoading = false; // Hentikan loading
                        this.skuInput = ''; // Kosongkan input
                        return; // Hentikan eksekusi
                    }
                    
                    const data = await response.json();

                    if (data.success) {
                        this.foundProduct = data.product;
                    } else {
                        this.errorMessage = data.message || `Produk tidak ditemukan.`;
                    }
                } catch (error) {
                    this.errorMessage = 'Gagal terhubung atau memproses data. Cek console (F12) untuk detail.';
                } finally {
                    this.isLoading = false;
                    this.skuInput = '';
                }
            },

            addToCartAndClear() {
                if (this.foundProduct) {
                    this.cart.push(this.foundProduct);
                    this.calculateTotal();
                    this.foundProduct = null;
                    this.$refs.skuInput.focus();
                }
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
                this.calculateTotal();
            },

            calculateTotal() {
                this.total = this.cart.reduce((sum, item) => sum + item.harga_final, 0);
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
            },
        }
    }
    </script>
    @endpush
</x-app-layout>
