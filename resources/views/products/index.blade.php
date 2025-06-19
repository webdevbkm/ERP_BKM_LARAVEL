<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Daftar Produk</h2>
    </x-slot>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-funnel-fill me-2"></i>Filter Produk</h5>
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="row g-3">
                            {{-- ... (Filter lain tetap sama, kecuali filter cabang dihapus) ... --}}
                            <div class="col-md-3"><label class="form-label">Jenis Barang</label><select name="jenis_id" class="form-select"><option value="">Semua Jenis</option>@foreach($allJenis as $jenis)<option value="{{ $jenis->id }}" {{ request('jenis_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>@endforeach</select></div>
                            <div class="col-md-3"><label class="form-label">Kadar</label><select name="kadar_id" class="form-select"><option value="">Semua Kadar</option>@foreach($allKadars as $kadar)<option value="{{ $kadar->id }}" {{ request('kadar_id') == $kadar->id ? 'selected' : '' }}>{{ $kadar->nama }}</option>@endforeach</select></div>
                            <div class="col-md-3"><label class="form-label">Pabrik</label><select name="pabrik_id" class="form-select"><option value="">Semua Pabrik</option>@foreach($allPabriks as $pabrik)<option value="{{ $pabrik->id }}" {{ request('pabrik_id') == $pabrik->id ? 'selected' : '' }}>{{ $pabrik->nama }}</option>@endforeach</select></div>
                            {{-- **DIHAPUS:** Filter berdasarkan cabang --}}
                            <div class="col-md-3"><label class="form-label">Tipe Harga</label><select name="price_type_id" class="form-select"><option value="">Semua Tipe</option>@foreach($allPriceTypes as $priceType)<option value="{{ $priceType->id }}" {{ request('price_type_id') == $priceType->id ? 'selected' : '' }}>{{ $priceType->nama_tipe }}</option>@endforeach</select></div>
                            <div class="col-md-6"><label class="form-label">Rentang Berat: <span id="berat-slider-value" class="fw-bold"></span></label><div id="berat-slider" class="mt-3"></div><input type="hidden" name="min_berat" id="min_berat"><input type="hidden" name="max_berat" id="max_berat"></div>
                            <div class="col-md-3 align-self-end"><div class="d-grid gap-2"><button type="submit" class="btn btn-primary">Terapkan</button><a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a></div></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-gem me-2"></i>Semua Produk</h3>
                    <a href="{{ route('products.create') }}" class="btn btn-light"><i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>SKU</th><th>Foto</th><th>Nama Produk</th><th>Berat</th>
                                    <th>Kadar</th><th>Pabrik</th><th>Total Stok</th><th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="fw-bold">{{ $product->sku }}</td>
                                        <td><img src="{{ $product->foto_produk_path ? asset('storage/' . $product->foto_produk_path) : 'https://placehold.co/50x50/e9ecef/6c757d?text=N/A' }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded"></td>
                                        <td>{{ $product->nama }}</td>
                                        <td>{{ rtrim(rtrim($product->berat, '0'), '.') }} gr</td>
                                        <td>{{ $product->kadar->nama ?? '-' }}</td>
                                        <td>{{ $product->pabrik->nama ?? '-' }}</td>
                                        {{-- **MODIFIKASI:** Menampilkan total stok --}}
                                        <td class="fw-bold">{{ $product->total_stock }}</td>
                                        <td class="text-end"><a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info" title="Lihat Detail"><i class="bi bi-eye-fill"></i></a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">Tidak ada data produk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')<script>document.addEventListener('DOMContentLoaded',function(){const beratSlider=document.getElementById('berat-slider');if(beratSlider){const minBeratInput=document.getElementById('min_berat');const maxBeratInput=document.getElementById('max_berat');const beratValueDisplay=document.getElementById('berat-slider-value');const maxWeight={{$maxWeight??100}};noUiSlider.create(beratSlider,{start:[{{request('min_berat',0)}},{{request('max_berat',$maxWeight??100)}}],connect:true,range:{'min':0,'max':maxWeight>0?maxWeight:100},step:0.1,format:{to:function(value){return value.toFixed(1)+' gr';},from:function(value){return Number(value.replace(' gr',''));}}});beratSlider.noUiSlider.on('update',function(values,handle){const[minVal,maxVal]=values.map(val=>parseFloat(val.replace(' gr','')));minBeratInput.value=minVal;maxBeratInput.value=maxVal;beratValueDisplay.textContent=`${minVal.toFixed(1)} gr - ${maxVal.toFixed(1)} gr`;});}});</script>@endpush
</x-app-layout>
