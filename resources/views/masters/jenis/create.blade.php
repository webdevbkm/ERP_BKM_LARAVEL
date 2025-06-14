<!-- File: resources/views/masters/jenis/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0"><a href="{{ route('jenis.index') }}" class="text-dark text-decoration-none">Manajemen Jenis</a> / Tambah</h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 col-md-8 mx-auto">
                <div class="card-body">
                    <form action="{{ route('jenis.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Jenis</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('jenis.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-custom-pink">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>