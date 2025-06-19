<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Tambah Pelanggan Baru
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-dark text-white">
                            <h3 class="mb-0 h5"><i class="bi bi-person-plus-fill me-2"></i>Formulir Data Pelanggan</h3>
                        </div>
                        <div class="card-body">
                            {{-- Menampilkan error validasi --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form action="{{ route('master.customers.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama" class="form-label fw-bold">Nama Pelanggan</label>
                                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="telepon" class="form-label fw-bold">Nomor Telepon</label>
                                    <input type="text" name="telepon" id="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" required>
                                     @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label fw-bold">Alamat (Opsional)</label>
                                    <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('master.customers.index') }}" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Pelanggan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
