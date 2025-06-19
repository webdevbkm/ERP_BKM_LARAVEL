<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Input Produk Baru</h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
             <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white"><h3 class="mb-0"><i class="bi bi-plus-circle-fill me-2"></i>Formulir Input Produk</h3></div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading fw-bold">Terjadi Kesalahan!</h5>
                            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Kolom Kiri -->
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6"><label class="form-label fw-bold">Nama Produk</label><input type="text" class="form-control" name="nama" value="{{ old('nama') }}" required></div>
                                    <div class="col-md-6"><label class="form-label fw-bold">Model dan Baki</label><select class="form-select" name="model_baki_id" required><option selected disabled value="">Pilih...</option>@foreach($modelBakis as $item)<option value="{{ $item->id }}" {{ old('model_baki_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>@endforeach</select></div>
                                    <div class="col-md-6"><label class="form-label fw-bold">Jenis</label><select class="form-select" name="jeni_id" required><option selected disabled value="">Pilih...</option>@foreach($jenis as $item)<option value="{{ $item->id }}" {{ old('jeni_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>@endforeach</select></div>
                                    <div class="col-md-6"><label class="form-label fw-bold">Kadar</label><select class="form-select" name="kadar_id" required><option selected disabled value="">Pilih...</option>@foreach($kadars as $item)<option value="{{ $item->id }}" {{ old('kadar_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>@endforeach</select></div>
                                    <div class="col-md-6"><label class="form-label fw-bold">Warna</label><select class="form-select" name="warna_id" required><option selected disabled value="">Pilih...</option>@foreach($warnas as $item)<option value="{{ $item->id }}" {{ old('warna_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>@endforeach</select></div>
                                    <div class="col-md-6"><label class="form-label fw-bold">Pabrik</label><select class="form-select" name="pabrik_id"><option selected value="">Pilih jika ada...</option>@foreach($pabriks as $item)<option value="{{ $item->id }}" {{ old('pabrik_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>@endforeach</select></div>
                                    <div class="col-md-4"><label class="form-label fw-bold">Berat (gram)</label><input type="number" step="0.001" class="form-control" name="berat" value="{{ old('berat') }}" required></div>
                                    <div class="col-md-4"><label class="form-label fw-bold">Panjang (cm)</label><input type="number" step="0.01" class="form-control" name="panjang" value="{{ old('panjang') }}"></div>
                                    <div class="col-md-4"><label class="form-label fw-bold">Type Harga</label><select class="form-select" name="price_type_id"><option selected value="">Harga Normal</option>@foreach($priceTypes as $item)<option value="{{ $item->id }}" {{ old('price_type_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_tipe }}</option>@endforeach</select></div>
                                    
                                    {{-- **DIKEMBALIKAN:** Input detail biaya --}}
                                    <div class="col-md-6"><label for="harga_dasar_batu" class="form-label fw-bold">Harga Dasar Batu</label><input type="number" step="100" class="form-control" name="harga_dasar_batu" value="{{ old('harga_dasar_batu', 0) }}"></div>
                                    <div class="col-md-6"><label for="ongkos_per_item" class="form-label fw-bold">Ongkos per Item</label><input type="number" step="100" class="form-control" name="ongkos_per_item" value="{{ old('ongkos_per_item', 0) }}"></div>

                                    {{-- **DIKEMBALIKAN:** Input detail lainnya --}}
                                    <div class="col-md-4"><label for="kokot_id" class="form-label fw-bold">KKT (Kokot)</label><select class="form-select" name="kokot_id"><option selected value="">Tidak Ada</option>@foreach($kokots as $item)<option value="{{ $item->id }}" {{ old('kokot_id') == $item->id ? 'selected' : '' }}>{{ $item->nama ?? $item->id }}</option>@endforeach</select></div>
                                    <div class="col-md-4"><label for="klp" class="form-label">KLP</label><input type="text" class="form-control" name="klp" value="{{ old('klp') }}"></div>
                                    <div class="col-md-4"><label for="kd" class="form-label">KD</label><input type="text" class="form-control" name="kd" value="{{ old('kd') }}"></div>
                                </div>
                            </div>
                            <!-- Kolom Kanan -->
                            <div class="col-md-4">
                                <div class="mb-3"><label class="form-label fw-bold">Upload Foto</label><input class="form-control" type="file" name="foto_produk" onchange="previewImage(event)"></div>
                                <div class="text-center p-2 border rounded bg-light"><img id="image-preview" src="https://placehold.co/400x400/e9ecef/6c757d?text=Preview+Foto" class="img-fluid rounded"></div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="fw-bold mb-3">Inventaris Awal</h5>
                        <div class="row g-3">
                            @forelse($warehouses as $warehouse)
                                <div class="col-md-4">
                                    <label for="stock_{{ $warehouse->id }}" class="form-label">{{ $warehouse->name }}</label>
                                    <input type="number" class="form-control" name="stocks[{{ $warehouse->id }}]" id="stock_{{ $warehouse->id }}" value="{{ old('stocks.' . $warehouse->id, 0) }}" min="0">
                                </div>
                            @empty
                                <div class="col-12"><div class="alert alert-warning">Tidak ada gudang aktif. Silakan tambahkan master gudang terlebih dahulu.</div></div>
                            @endforelse
                        </div>

                        {{-- **DIKEMBALIKAN:** Input Keterangan --}}
                        <hr class="my-4">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')<script>function previewImage(event){var preview=document.getElementById('image-preview');var file=event.target.files[0];var reader=new FileReader();reader.onload=function(){preview.src=reader.result;};if(file){reader.readAsDataURL(file);}}</script>@endpush
</x-app-layout>
