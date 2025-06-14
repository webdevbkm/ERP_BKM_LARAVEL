<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Detail Produk: {{ $product->nama }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-custom-pink text-dark">
                     <h3 class="mb-0"><i class="bi bi-file-earmark-text-fill me-2"></i>Informasi Produk</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="fw-bold">Foto Produk</h5>
                            @if($product->foto_produk_path)
                                <img src="{{ asset('storage/' . $product->foto_produk_path) }}" alt="{{ $product->nama }}" class="img-fluid rounded border mb-3">
                            @else
                                <img src="https://placehold.co/400x400/e9ecef/6c757d?text=Tidak+Ada+Foto" class="img-fluid rounded border mb-3" alt="Tidak ada foto">
                            @endif
                            
                            <h5 class="fw-bold mt-4">QR Code</h5>
                            @if($product->qr_code_path)
                                <img src="{{ asset('storage/' . $product->qr_code_path) }}" alt="QR Code for {{ $product->sku }}" class="img-fluid border p-2 rounded">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $product->nama }}</h3>
                            <p class="text-muted fs-5">SKU: <strong class="text-dark">{{ $product->sku }}</strong></p>
                            <hr>
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">Model & Baki</th>
                                        <td>{{ $product->modelBaki->nama ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kadar</th>
                                        <td>{{ $product->kadar->nama ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis</th>
                                        <td>{{ $product->jenis->nama ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Berat</th>
                                        <td>{{ rtrim(rtrim($product->berat, '0'), '.') }} gram</td>
                                    </tr>
                                     <tr>
                                        <th>Ongkos & Biaya Lain</th>
                                        <td>Rp {{ number_format($product->ongkos_per_item + $product->harga_dasar_batu, 0, ',', '.') }}</td>
                                    </tr>
                                     <tr>
                                        <th>Tipe Harga</th>
                                        <td>{{ $product->priceType->nama_tipe ?? 'Harga Normal' }} ({{ $product->priceType ? ($product->priceType->jumlah_penambahan_rupiah >= 0 ? '+' : '') . 'Rp ' . number_format($product->priceType->jumlah_penambahan_rupiah, 0, ',', '.') : 'Rp 0' }})</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="alert alert-success mt-4">
                                <h4 class="alert-heading">Harga Produk Final</h4>
                                <p class="fs-3 fw-bold mb-0">
                                    Rp {{ number_format($product->harga_final, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('products.create') }}" class="btn btn-custom-pink">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Produk Lain
                                </a>
                                <a href="#" class="btn btn-secondary">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Produk Ini
                                </a>
                                <button onclick="printLabel()" class="btn btn-dark">
                                    <i class="bi bi-printer-fill me-1"></i> Cetak Label
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Area Tersembunyi Khusus untuk Print dengan layout 2x2 cm --}}
    <div id="print-area" class="d-none">
        <div style="width: 2cm; height: 2cm; text-align: center; font-family: 'Arial', sans-serif; page-break-inside: avoid; display: flex; flex-direction: column; justify-content: center;">
            
            {{-- Bagian Atas: QR Code --}}
            <div style="margin-bottom: 8px;"> {{-- Jarak ditambah --}}
                @if($product->qr_code_path)
                    <img src="{{ url(Storage::url($product->qr_code_path)) }}" alt="QR Code" style="width: 0.8cm; height: 0.8cm;">
                @endif
            </div>

            {{-- Bagian Bawah: Keterangan --}}
            <div style="line-height: 1.1; font-size: 6pt;"> {{-- Font dikecilkan --}}
                <p style="margin: 0; font-weight: bold;">{{ $product->sku }}</p>
                <p style="margin: 0;">{{ rtrim(rtrim($product->berat, '0'), '.') }} gr | {{ $product->kadar->nama ?? '' }}</p>
                <p style="margin: 0;">Tipe: {{ $product->priceType->kode_tipe ?? 'REG' }}</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function printLabel() {
            const printContent = document.getElementById('print-area').innerHTML;
            
            const iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            document.body.appendChild(iframe);

            const doc = iframe.contentWindow.document;
            doc.open();
            // Menambahkan style untuk memastikan ukuran cetak benar: 2cm x 2cm
            doc.write('<html><head><title>Cetak Label</title>');
            doc.write('<style>@page { size: 2cm 2cm; margin: 0; } body { margin: 0; }</style>');
            doc.write('</head><body>');
            doc.write(printContent);
            doc.write('</body></html>');
            doc.close();

            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                document.body.removeChild(iframe);
            }, 500);
        }
    </script>
    @endpush
</x-app-layout>
