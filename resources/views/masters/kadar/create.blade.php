<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0"><a href="{{ route('master.kadar.index') }}" class="text-dark text-decoration-none">Manajemen Kadar</a> / Tambah</h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 col-md-8 mx-auto">
                 <div class="card-header bg-dark text-white"><h3 class="mb-0">Formulir Kadar Baru</h3></div>
                <div class="card-body">
                    <form action="{{ route('master.kadar.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Kadar</label>
                            <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="harga_per_gram" class="form-label fw-bold">Harga per Gram</label>
                            <input type="number" class="form-control" name="harga_per_gram" value="{{ old('harga_per_gram', 0) }}" required>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('master.kadar.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
