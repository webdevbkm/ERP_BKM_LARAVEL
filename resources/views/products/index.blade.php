<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Daftar Produk
        </h2>
    </x-slot>

    {{-- Memuat library untuk slider --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>


    <div class="py-5">
        <div class="container">
            {{-- Panel Filter --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-funnel-fill me-2"></i>Filter Produk</h5>
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="row g-3">
                            {{-- Baris Pertama Filter --}}
                            <div class="col-md-3">
                                <label for="jenis_id" class="form-label">Jenis Barang</label>
                                <select name="jenis_id" id="jenis_id" class="form-select">
                                    <option value="">Semua Jenis</option>
                                    @foreach($allJenis as $jenis)
                                        <option value="{{ $jenis->id }}" {{ request('jenis_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="kadar_id" class="form-label">Kadar</label>
                                <select name="kadar_id" id="kadar_id" class="form-select">
                                    <option value="">Semua Kadar</option>
                                    @foreach($allKadars as $kadar)
                                        <option value="{{ $kadar->id }}" {{ request('kadar_id') == $kadar->id ? 'selected' : '' }}>
                                            {{ $kadar->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="pabrik_id" class="form-label">Pabrik</label>
                                <select name="pabrik_id" id="pabrik_id" class="form-select">
                                    <option value="">Semua Pabrik</option>
                                    @foreach($allPabriks as $pabrik)
                                        <option value="{{ $pabrik->id }}" {{ request('pabrik_id') == $pabrik->id ? 'selected' : '' }}>
                                            {{ $pabrik->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="cabang_id" class="form-label">Cabang</label>
                                <select name="cabang_id" id="cabang_id" class="form-select">
                                    <option value="">Semua Cabang</option>
                                    @foreach($allCabangs as $cabang)
                                        <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                            {{ $cabang->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Baris Kedua Filter --}}
                             <div class="col-md-3">
                                <label for="price_type_id" class="form-label">Tipe Harga</label>
                                <select name="price_type_id" id="price_type_id" class="form-select">
                                    <option value="">Semua Tipe</option>
                                    @foreach($allPriceTypes as $priceType)
                                        <option value="{{ $priceType->id }}" {{ request('price_type_id') == $priceType->id ? 'selected' : '' }}>
                                            {{ $priceType->nama_tipe }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="berat-slider" class="form-label">Rentang Berat: <span id="berat-slider-value" class="fw-bold"></span></label>
                                <div id="berat-slider" class="mt-3"></div>
                                <input type="hidden" name="min_berat" id="min_berat">
                                <input type="hidden" name="max_berat" id="max_berat">
                            </div>
                            <div class="col-md-3 align-self-end">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-custom-pink">Terapkan Filter</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset Filter</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Daftar Produk --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-custom-pink text-dark d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-gem me-2"></i>Semua Produk</h3>
                    <a href="{{ route('products.create') }}" class="btn btn-dark">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">SKU</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Nama Produk</th>
                                    <th scope="col">Berat</th>
                                    <th scope="col">Kadar</th>
                                    <th scope="col">Pabrik</th>
                                    <th scope="col">Cabang</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="fw-bold">{{ $product->sku }}</td>
                                        <td>
                                            @if($product->foto_produk_path)
                                                <img src="{{ asset('storage/' . $product->foto_produk_path) }}" alt="{{ $product->nama }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                            @else
                                                <img src="https://placehold.co/50x50/e9ecef/6c757d?text=N/A" class="rounded">
                                            @endif
                                        </td>
                                        <td>{{ $product->nama }}</td>
                                        <td>{{ rtrim(rtrim($product->berat, '0'), '.') }} gr</td>
                                        <td>{{ $product->kadar->nama ?? '-' }}</td>
                                        <td>{{ $product->pabrik->nama ?? '-' }}</td>
                                        <td>{{ $product->cabang->nama ?? '-' }}</td>
                                        <td>Rp {{ number_format($product->harga_final, 0, ',', '.') }}</td>
                                        <td>{{ $product->stok }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-secondary" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            Tidak ada data produk yang cocok dengan filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{-- Menambahkan query string pada paginasi agar filter tetap aktif --}}
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const beratSlider = document.getElementById('berat-slider');
            if (beratSlider) {
                const minBeratInput = document.getElementById('min_berat');
                const maxBeratInput = document.getElementById('max_berat');
                const beratValueDisplay = document.getElementById('berat-slider-value');

                const maxWeight = {{ $maxWeight ?? 100 }};

                noUiSlider.create(beratSlider, {
                    start: [
                        {{ request('min_berat', 0) }},
                        {{ request('max_berat', $maxWeight ?? 100) }}
                    ],
                    connect: true,
                    range: {
                        'min': 0,
                        'max': maxWeight > 0 ? maxWeight : 100
                    },
                    step: 0.1,
                    tooltips: [true, true],
                    format: {
                        to: function (value) {
                            return value.toFixed(1) + ' gr';
                        },
                        from: function (value) {
                            return Number(value.replace(' gr', ''));
                        }
                    }
                });

                beratSlider.noUiSlider.on('update', function (values, handle) {
                    const [minVal, maxVal] = values.map(val => parseFloat(val.replace(' gr', '')));
                    minBeratInput.value = minVal;
                    maxBeratInput.value = maxVal;
                    beratValueDisplay.textContent = `${minVal.toFixed(1)} gr - ${maxVal.toFixed(1)} gr`;
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
