<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0"><a href="{{ route('master.warehouses.index') }}" class="text-dark text-decoration-none">Manajemen Gudang</a> / Edit</h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 col-md-8 mx-auto">
                <div class="card-header bg-dark text-white"><h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Formulir Edit Gudang</h3></div>
                <div class="card-body">
                    <form action="{{ route('master.warehouses.update', $warehouse->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3"><label for="name" class="form-label fw-bold">Nama Gudang</label><input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $warehouse->name) }}" required>@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                        <div class="mb-3"><label for="code" class="form-label fw-bold">Kode Gudang</label><input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $warehouse->code) }}" required>@error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                        <div class="mb-3"><label for="address" class="form-label fw-bold">Alamat</label><textarea class="form-control" name="address" rows="3">{{ old('address', $warehouse->address) }}</textarea></div>
                        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @if(old('is_active', $warehouse->is_active)) checked @endif><label class="form-check-label" for="is_active">Gudang Aktif</label></div>
                        <div class="text-end"><a href="{{ route('master.warehouses.index') }}" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Update</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
