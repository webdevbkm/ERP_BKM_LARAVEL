<x-app-layout>
    {{-- Header Konten --}}
    <x-slot name="header">
        <header class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h2 class="h4 fw-bold">Hi, {{ Auth::user()->name }}</h2>
                <p class="text-muted mb-0">Selamat datang kembali di dashboard Anda.</p>
            </div>
            <div class="d-flex align-items-center">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search...">
                    <button class="btn btn-light border" type="button"><i class="bi bi-search"></i></button>
                </div>
                <button class="btn btn-light border ms-2 rounded-circle" style="width: 40px; height: 40px;"><i class="bi bi-bell-fill"></i></button>
            </div>
        </header>
    </x-slot>

    {{-- Kartu Selamat Datang --}}
    <div class="card shadow-sm border-0 mb-4" style="background-color: #FDF2F8;">
        <div class="card-body p-4">
            <h4 class="card-title fw-bold">Selamat datang di Perencanaan Sumber Daya Terintegrasi Bokor Mas Gold.</h4>
            <p class="card-text text-muted mt-2">
                Ini adalah sistem informasi terintegrasi yang digunakan untuk mengelola dan mengotomatisasi berbagai proses bisnis dalam Bokor Mas Gold.
            </p>
        </div>
    </div>
    
    <div class="row g-4">
        {{-- Ringkasan Penjualan --}}
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Ringkasan Penjualan per Cabang</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($salesSummaryPerBranch as $cabang)
                            <div class="col-md-3"> {{-- Diubah menjadi col-md-3 agar muat 4 item --}}
                                <div class="p-3 bg-light rounded d-flex align-items-center">
                                    <div class="p-2 me-3 bg-success bg-opacity-10 rounded">
                                        <i class="bi bi-shop fs-4 text-success"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">{{ $cabang->nama }}</p>
                                        <h5 class="fw-bold mb-0">Rp {{ number_format($cabang->transactions_sum_total_amount ?? 0, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col text-center text-muted">Belum ada data penjualan.</div>
                        @endforelse
                        {{-- KARTU BARU UNTUK JUMLAH PENGGUNA --}}
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded d-flex align-items-center">
                                <div class="p-2 me-3 bg-info bg-opacity-10 rounded">
                                    <i class="bi bi-people-fill fs-4 text-info"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted">Total Pengguna</p>
                                    <h5 class="fw-bold mb-0">{{ $totalUsers ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Nilai Aset & Stok --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                 <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-wallet2 me-2 text-primary"></i>Total Nilai Aset per Jenis</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($assetSummaryPerJenis as $jenis)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                {{ $jenis->nama }}
                                <span class="badge bg-primary rounded-pill">Rp {{ number_format($jenis->total_asset_value ?? 0, 0, ',', '.') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted px-0">Belum ada data aset.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                 <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2" style="color: #EC4899;"></i>Ringkasan Stok per Jenis</h5>
                </div>
                <div class="card-body">
                     <ul class="list-group list-group-flush">
                        @forelse($stockSummary as $jenis)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                {{ $jenis->nama }}
                                <span class="badge rounded-pill" style="background-color: #FDF2F8; color: #DB2777;">{{ $jenis->products_sum_stok ?? 0 }} pcs</span>
                            </li>
                        @empty
                             <li class="list-group-item text-center text-muted px-0">Belum ada data stok.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
