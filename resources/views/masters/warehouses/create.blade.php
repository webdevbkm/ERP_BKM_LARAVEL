<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0"><a href="{{ route('master.warehouses.index') }}" class="text-dark text-decoration-none">Manajemen Gudang</a> / Tambah</h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 col-md-8 mx-auto">
                <div class="card-header bg-dark text-white"><h3 class="mb-0"><i class="bi bi-plus-circle-fill me-2"></i>Formulir Gudang Baru</h3></div>
                <div class="card-body">
                    <form action="{{ route('master.warehouses.store') }}" method="POST">
                        @csrf
                        <div class="mb-3"><label for="name" class="form-label fw-bold">Nama Gudang</label><input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                        <div class="mb-3"><label for="code" class="form-label fw-bold">Kode Gudang</label><input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required>@error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                        <div class="mb-3"><label for="address" class="form-label fw-bold">Alamat</label><textarea class="form-control" name="address" rows="3">{{ old('address') }}</textarea></div>
                        <div class="text-end"><a href="{{ route('master.warehouses.index') }}" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
