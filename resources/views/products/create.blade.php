<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Input Produk Baru
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
             <div class="card shadow-sm border-0">
                <div class="card-header bg-custom-pink text-dark">
                    <h3 class="mb-0"><i class="bi bi-plus-circle-fill me-2"></i>Formulir Input Produk</h3>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading fw-bold">Terjadi Kesalahan!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Kolom Kiri -->
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="sku" class="form-label fw-bold">SKU (Kode Unik)</label>
                                        <input type="text" class="form-control bg-light" id="sku" name="sku" value="Otomatis" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nama" class="form-label fw-bold">Nama Produk</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="model_baki_id" class="form-label fw-bold">Model dan Baki</label>
                                        <select class="form-select" id="model_baki_id" name="model_baki_id" required>
                                            <option selected disabled value="">Pilih...</option>
                                            @foreach($modelBakis as $item)
                                                <option value="{{ $item->id }}" {{ old('model_baki_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="jeni_id" class="form-label fw-bold">Jenis</label>
                                        <select class="form-select" id="jeni_id" name="jeni_id" required>
                                            <option selected disabled value="">Pilih...</option>
                                            @foreach($jenis as $item)
                                                <option value="{{ $item->id }}" {{ old('jeni_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="kadar_id" class="form-label fw-bold">Kadar</label>
                                        <select class="form-select" id="kadar_id" name="kadar_id" required>
                                            <option selected disabled value="">Pilih...</option>
                                            @foreach($kadars as $item)
                                                <option value="{{ $item->id }}" {{ old('kadar_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="warna_id" class="form-label fw-bold">Warna</label>
                                        <select class="form-select" id="warna_id" name="warna_id" required>
                                            <option selected disabled value="">Pilih...</option>
                                            @foreach($warnas as $item)
                                                <option value="{{ $item->id }}" {{ old('warna_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="berat" class="form-label fw-bold">Berat (gram)</label>
                                        <input type="number" step="0.001" class="form-control" id="berat" name="berat" value="{{ old('berat') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="panjang" class="form-label fw-bold">Panjang (cm)</label>
                                        <input type="number" step="0.01" class="form-control" id="panjang" name="panjang" value="{{ old('panjang') }}">
                                    </div>
                                     <div class="col-md-4">
                                        <label for="stok" class="form-label fw-bold">Stok Awal</label>
                                        <input type="number" class="form-control" id="stok" name="stok" value="{{ old('stok', 1) }}" required>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="price_type_id" class="form-label fw-bold">Type Harga</label>
                                        <select class="form-select" id="price_type_id" name="price_type_id">
                                            <option selected value="">Harga Normal</option>
                                             @foreach($priceTypes as $item)
                                                <option value="{{ $item->id }}" {{ old('price_type_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_tipe }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="harga_dasar_batu" class="form-label fw-bold">Harga Dasar Batu</label>
                                        <input type="number" step="100" class="form-control" id="harga_dasar_batu" name="harga_dasar_batu" value="{{ old('harga_dasar_batu', 0) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ongkos_per_item" class="form-label fw-bold">Ongkos per Item</label>
                                        <input type="number" step="100" class="form-control" id="ongkos_per_item" name="ongkos_per_item" value="{{ old('ongkos_per_item', 0) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="pabrik_id" class="form-label fw-bold">Pabrik</label>
                                        <select class="form-select" id="pabrik_id" name="pabrik_id">
                                            <option selected value="">Pilih jika ada...</option>
                                            @foreach($pabriks as $item)
                                                <option value="{{ $item->id }}" {{ old('pabrik_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cabang_id" class="form-label fw-bold">Lokasi Cabang</label>
                                        <select class="form-select" id="cabang_id" name="cabang_id" required>
                                            <option selected disabled value="">Pilih...</option>
                                            @foreach($cabangs as $item)
                                                <option value="{{ $item->id }}" {{ old('cabang_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="kokot_id" class="form-label fw-bold">KKT (Kokot)</label>
                                        <select class="form-select" id="kokot_id" name="kokot_id">
                                            <option selected value="">Tidak Ada</option>
                                            @foreach($kokots as $item)
                                                <option value="{{ $item->id }}" {{ old('kokot_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="klp" class="form-label">KLP</label>
                                        <input type="text" class="form-control" id="klp" name="klp" value="{{ old('klp') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="kd" class="form-label">KD</label>
                                        <input type="text" class="form-control" id="kd" name="kd" value="{{ old('kd') }}">
                                    </div>
                                </div>
                            </div>
                            <!-- Kolom Kanan -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="foto_produk" class="form-label fw-bold">Upload Foto Produk</label>
                                    {{-- Menggunakan onchange standar --}}
                                    <input class="form-control" type="file" id="foto_produk" name="foto_produk" onchange="previewImage(event)">
                                </div>
                                <div class="text-center p-2 border rounded bg-light">
                                    {{-- Memberi ID pada gambar agar bisa ditarget oleh JavaScript --}}
                                    <img id="image-preview" src="https://placehold.co/400x400/e9ecef/6c757d?text=Preview+Foto" class="img-fluid rounded" alt="Image Preview">
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <div class="row">
                             <div class="col-12">
                                <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-custom-pink"><i class="bi bi-save-fill me-2"></i>Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript standar untuk Preview Foto --}}
    @push('scripts')
    <script>
        function previewImage(event) {
            // Dapatkan elemen gambar berdasarkan ID-nya
            var preview = document.getElementById('image-preview');
            
            // Dapatkan file yang dipilih oleh pengguna
            var file = event.target.files[0];
            
            // Buat objek untuk membaca file
            var reader = new FileReader();

            // Saat file selesai dibaca, jalankan fungsi ini
            reader.onload = function() {
                // Ubah sumber (src) dari elemen gambar dengan hasil bacaan file
                preview.src = reader.result;
            };

            // Jika ada file yang dipilih, mulai proses pembacaan file
            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
    @endpush
</x-app-layout>
