<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Manajemen Pelanggan</h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 h5"><i class="bi bi-people-fill me-2"></i>Daftar Pelanggan</h3>
                    <a href="{{ route('master.customers.create') }}" class="btn btn-sm btn-light"><i class="bi bi-plus-circle me-1"></i>Tambah Pelanggan</a>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama atau telepon..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th><th>Telepon</th><th>Alamat</th><th class="text-center">Poin</th><th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr>
                                    <td class="fw-bold">{{ $customer->nama }}</td>
                                    <td>{{ $customer->telepon }}</td>
                                    <td>{{ Str::limit($customer->alamat, 50) }}</td>
                                    <td class="text-center fw-bold">{{ $customer->points_balance }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('master.customers.show', $customer) }}" class="btn btn-sm btn-info" title="Lihat Detail"><i class="bi bi-eye-fill"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-5">Tidak ada data pelanggan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $customers->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>