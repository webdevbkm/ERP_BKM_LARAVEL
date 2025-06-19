{{-- This is a new, dedicated layout file for the master data section --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold mb-0">
            Manajemen Master Data
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row">
                {{-- Column for the master data navigation menu --}}
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">Kategori Master</h5>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('master.kadar.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('master.kadar.*') ? 'active' : '' }}">
                                <i class="bi bi-rulers me-2"></i> Kadar
                            </a>
                            <a href="{{ route('master.jenis.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('master.jenis.*') ? 'active' : '' }}">
                                <i class="bi bi-tags-fill me-2"></i> Jenis
                            </a>
                            <a href="{{ route('master.warehouses.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('master.warehouses.*') ? 'active' : '' }}">
                                <i class="bi bi-shop me-2"></i> Gudang
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Column for the dynamic content of each master data page --}}
                <div class="col-md-9">
                    {{-- Success or Error Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                     @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- The content from the specific master page will be injected here --}}
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
