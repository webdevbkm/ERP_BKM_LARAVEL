<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0"><a href="{{ route('admin.users.index') }}" class="text-dark text-decoration-none">Manajemen Pengguna</a> / Edit</h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 col-md-8 mx-auto">
                <div class="card-header bg-dark text-white"><h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Formulir Edit Pengguna</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3"><label for="name" class="form-label fw-bold">Nama Lengkap</label><input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required></div>
                        <div class="mb-3"><label for="email" class="form-label fw-bold">Alamat Email</label><input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required></div>
                        <div class="mb-3"><label for="password" class="form-label fw-bold">Password Baru (Opsional)</label><input type="password" class="form-control" name="password"><div class="form-text">Kosongkan jika tidak ingin mengubah password.</div></div>
                        <div class="mb-3"><label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru</label><input type="password" class="form-control" name="password_confirmation"></div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Peran (Roles)</label>
                            @foreach ($roles as $role)
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}" @if(in_array($role->name, $userRoles)) checked @endif><label class="form-check-label" for="role_{{ $role->id }}">{{ $role->name }}</label></div>
                            @endforeach
                        </div>
                         {{-- Field Baru untuk Warehouse --}}
                        <div class="mb-3">
                            <label for="warehouse_id" class="form-label fw-bold">Penanggung Jawab Gudang (Opsional)</label>
                            <select class="form-select" name="warehouse_id" id="warehouse_id">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $user->warehouse_id) == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-end"><a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Update</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
