<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            POS - Halaman Diagnostik
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Tes Koneksi API Pencarian Produk</h5>
                    <p>Masukkan SKU yang valid lalu klik "Tes Cari". Hasil mentah dari server akan muncul di bawah.</p>
                    
                    <div class="input-group mb-3">
                        <input type="text" id="sku-test-input" class="form-control" placeholder="Masukkan SKU di sini...">
                        <button class="btn btn-primary" type="button" id="test-button">Tes Cari</button>
                    </div>

                    <hr>
                    <h6>Hasil dari Server:</h6>
                    <pre id="response-output" class="bg-light p-3 rounded" style="min-height: 100px;"></pre>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('test-button').addEventListener('click', async function() {
            const sku = document.getElementById('sku-test-input').value;
            const output = document.getElementById('response-output');
            
            if (!sku) {
                output.textContent = 'Harap masukkan SKU.';
                return;
            }

            output.textContent = 'Mengirim permintaan ke server...';
            const url = `/pos/find-product/${sku}`;

            try {
                const response = await fetch(url);
                const responseText = await response.text(); // Ambil respons sebagai teks mentah

                // Tampilkan teks mentah di output
                output.textContent = `Status: ${response.status} ${response.statusText}\n\n`;
                output.textContent += '------------------\n';
                output.textContent += 'Isi Respons:\n';
                output.textContent += '------------------\n';
                output.textContent += responseText;

            } catch (error) {
                output.textContent = 'ERROR FATAL PADA JAVASCRIPT!\n\n';
                output.textContent += `Pesan Error: ${error.message}\n\n`;
                output.textContent += 'Ini biasanya terjadi jika URL tidak bisa dijangkau atau ada masalah koneksi.';
            }
        });
    </script>
    @endpush
</x-app-layout>
