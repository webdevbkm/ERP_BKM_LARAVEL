<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">Detail Pelanggan: {{ $customer->nama }}</h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-person-circle fs-1"></i>
                            <h4 class="card-title mt-2">{{ $customer->nama }}</h4>
                            <p class="text-muted">{{ $customer->telepon }}</p>
                            <hr>
                            <p class="mb-1 fw-bold">Saldo Poin Saat Ini</p>
                            <p class="display-4 fw-bolder text-success">{{ $customer->points_balance }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                         <div class="card-header bg-dark text-white">
                            <h3 class="mb-0 h5">Riwayat Poin</h3>
                        </div>
                        <div class="card-body p-0">
                           <table class="table table-striped mb-0">
                               <thead><tr><th>Tanggal</th><th>Deskripsi</th><th>Perubahan Poin</th></tr></thead>
                               <tbody>
                                   @forelse($customer->pointHistories as $history)
                                   <tr>
                                       <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                                       <td>{{ $history->description }}</td>
                                       <td>
                                           @if($history->points_change > 0)
                                           <span class="badge bg-success">+{{ $history->points_change }}</span>
                                           @else
                                           <span class="badge bg-danger">{{ $history->points_change }}</span>
                                           @endif
                                       </td>
                                   </tr>
                                   @empty
                                   <tr><td colspan="3" class="text-center p-4">Belum ada riwayat poin.</td></tr>
                                   @endforelse
                               </tbody>
                           </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>