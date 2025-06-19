<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Manajemen Pengguna</h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-people-fill me-2"></i>Daftar Pengguna</h3>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-light"><i class="bi bi-plus-circle me-1"></i> Tambah Pengguna</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Penanggung Jawab Gudang</th> {{-- Kolom Baru --}}
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr>
                                        <th>{{ $users->firstItem() + $index }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        {{-- Tampilkan nama gudang jika ada --}}
                                        <td>{{ $user->warehouse->name ?? '-' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">Tidak ada data pengguna.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<hr>