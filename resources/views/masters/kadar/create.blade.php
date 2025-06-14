<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Input Produk Baru
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
             <div class="card shadow-sm border-0">
                <div class="card-header bg-custom-pink text-dark">
                    <h3 class="mb-0"><i class="bi bi-plus-circle-fill me-2"></i>Formulir Input Produk</h3>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading fw-bold">Terjadi Kesalahan!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Kolom Kiri -->
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="sku" class="form-label fw-bold">SKU (Kode Unik)</label>
                                        <input type="text" class="form-control bg-light" id="sku" name="sku" value="Otomatis" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nama" class="form-label fw-bold">Nama Produk</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="model_baki_id" class="form-label fw-bold">Model dan Baki</label>
                                        <select class="form-select" id="model_baki_id" name="model_baki_id" required>
                                            <option selected disabled value="">Pilih...</option>
                                            {{-- Data akan di-loop dari controller --}}
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="jeni_id" class="form-label fw-bold">Jenis</label>
                                        <select class="form-select" id="jeni_id" name="jeni_id" required>
                                            <option selected disabled value="">Pilih...</option>
                                            {{-- Data akan di-loop dari controller --}}
                                        </select>
                                    </div>
                                    {{-- ...sisa input lainnya... --}}
                                </div>
                            </div>
                            <!-- Kolom Kanan -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="foto_produk" class="form-label fw-bold">Upload Foto Produk</label>
                                    <input class="form-control" type="file" id="foto_produk" name="foto_produk" onchange="previewImage()">
                                </div>
                                <div class="text-center p-2 border rounded bg-light">
                                    <img id="image-preview" src="https://placehold.co/400x400/e9ecef/6c757d?text=Preview+Foto" class="img-fluid rounded" alt="Image Preview">
                                </div>
                            </div>
                        </div>
                        
                        {{-- ...sisa form... --}}
                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-custom-pink"><i class="bi bi-save-fill me-2"></i>Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Memindahkan JavaScript ke dalam 'scripts' stack --}}
    @push('scripts')
    <script>
        function previewImage() {
            const image = document.querySelector('#foto_produk');
            const imgPreview = document.querySelector('#image-preview');

            // Pastikan ada file yang dipilih
            if (image.files && image.files[0]) {
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
