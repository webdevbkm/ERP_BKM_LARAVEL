<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Manajemen Master: Jenis</h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card shadow-sm border-0">
                <div class="card-header bg-custom-pink text-dark d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-tags-fill me-2"></i>Daftar Jenis</h3>
                    <a href="{{ route('jenis.create') }}" class="btn btn-dark">Tambah Jenis Baru</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr><th>Nama Jenis</th><th>Deskripsi</th><th class="text-end">Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($jenisItems as $item)
                                <tr>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->deskripsi ?? '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('jenis.edit', $item->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                                        <form action="{{ route('jenis.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $jenisItems->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>