<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Laporan Transaksi Penjualan
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            {{-- Panel Filter --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-funnel-fill me-2"></i>Filter Laporan</h5>
                    <form action="{{ route('reports.transactions') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="user_id" class="form-label">Kasir</label>
                                <select name="user_id" id="user_id" class="form-select">
                                    <option value="">Semua Kasir</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="cabang_id" class="form-label">Cabang</label>
                                <select name="cabang_id" id="cabang_id" class="form-select">
                                    <option value="">Semua Cabang</option>
                                    @foreach($cabangs as $cabang)
                                        <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>{{ $cabang->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Terapkan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Ringkasan Hasil Filter --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Pendapatan</h6>
                            <p class="fs-4 fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Jumlah Transaksi</h6>
                            <p class="fs-4 fw-bold mb-0">{{ $totalTransactions }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Riwayat Transaksi --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Riwayat Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Kasir</th>
                                    <th>Cabang</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td class="fw-bold">{{ $transaction->invoice_number }}</td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $transaction->customer->nama ?? '-' }}</td>
                                        <td>{{ $transaction->user->name ?? '-' }}</td>
                                        <td>{{ $transaction->cabang->nama ?? '-' }}</td>
                                        <td class="text-end">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Tidak ada data transaksi yang cocok dengan filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
