<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Manajemen Master: Kadar
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            {{-- Menampilkan pesan sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-custom-pink text-dark d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-rulers me-2"></i>Daftar Kadar</h3>
                    <a href="{{ route('kadar.create') }}" class="btn btn-dark">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kadar Baru
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Kadar</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Harga per Gram</th>
                                    <th scope="col" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Menampilkan data secara langsung menggunakan Blade --}}
                                @forelse ($kadars as $index => $kadar)
                                    <tr>
                                        <th scope="row">{{ $kadars->firstItem() + $index }}</th>
                                        <td>{{ $kadar->nama }}</td>
                                        <td>{{ $kadar->deskripsi ?? '-' }}</td>
                                        <td>Rp {{ number_format($kadar->harga_per_gram, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            {{-- Tombol Edit standar --}}
                                            <a href="{{ route('kadar.edit', $kadar->id) }}" class="btn btn-sm btn-secondary">
                                                <i class="bi bi-pencil-fill"></i> Edit
                                            </a>
                                            {{-- Tombol Hapus standar --}}
                                            <form action="{{ route('kadar.destroy', $kadar->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash-fill"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Tidak ada data kadar ditemukan. Silakan tambah data baru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Link Paginasi --}}
                    <div class="mt-3">
                        {{ $kadars->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
