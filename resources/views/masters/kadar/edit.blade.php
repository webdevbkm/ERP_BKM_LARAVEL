<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            <a href="{{ route('kadar.index') }}" class="text-dark text-decoration-none">Manajemen Kadar</a> / Edit Data
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 col-md-8 mx-auto">
                <div class="card-header bg-custom-pink text-dark">
                    <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Formulir Edit Kadar</h3>
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

                    <form action="{{ route('kadar.update', $kadar->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Metode untuk update --}}
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Kadar</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $kadar->nama) }}" required>
                            <div class="form-text">Contoh: 999.9 (24K), 750 (18K), dll.</div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi (Opsional)</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $kadar->deskripsi) }}</textarea>
                        </div>
                        
                        {{-- Input Harga per Gram yang hilang --}}
                        <div class="mb-3">
                            <label for="harga_per_gram" class="form-label fw-bold">Harga per Gram</label>
                            <input type="number" class="form-control" id="harga_per_gram" name="harga_per_gram" value="{{ old('harga_per_gram', $kadar->harga_per_gram) }}" required>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('kadar.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-custom-pink">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
