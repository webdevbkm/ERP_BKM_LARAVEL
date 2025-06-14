<div class="d-flex flex-column p-3 h-100">
    <!-- Logo -->
    <div class="text-center pt-2 pb-3 mb-4">
        <a href="{{ route('dashboard') }}">
            {{-- Ukuran logo diperbesar menjadi 80px --}}
            <img src="https://bokormas.gold/cdn/shop/files/5_Logo_Crop_Tight.png?v=1716451897&width=330" alt="Bokor Mas Gold Logo" style="width: 80px;">
        </a>
        
    </div>

    <!-- Navigation Links -->
    <style>
        .nav-link { 
            color: var(--sidebar-link-color); 
            border-radius: 0.375rem; 
            display: flex;
            align-items: center;
            padding: 0.6rem 1rem;
            margin-bottom: 0.25rem;
            transition: background-color 0.2s, color 0.2s;
        }
        .nav-link:hover { 
            background-color: var(--sidebar-link-hover-bg);
            color: white;
        }
        /* Menggunakan variabel warna pink baru untuk link aktif */
        .nav-link.active {
            background-color: var(--sidebar-link-active-bg);
            color: white;
            font-weight: 500;
        }
        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }
    </style>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        @role('Admin|Staf Gudang')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i class="bi bi-gem"></i>
                <span>Produk</span>
            </a>
        </li>
        @endrole
        @role('Admin|Kasir')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                <i class="bi bi-pc-display"></i>
                <span>Point of Sale</span>
            </a>
        </li>
        @endrole
        @role('Admin')
         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.transactions') }}">
                <i class="bi bi-journal-text"></i>
                <span>Laporan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kadar.*') || request()->routeIs('jenis.*') ? 'active' : '' }}" href="{{ route('kadar.index') }}">
                <i class="bi bi-archive-fill"></i>
                <span>Master Data</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="bi bi-people-fill"></i>
                <span>Pengguna</span>
            </a>
        </li>
        @endrole
    </ul>

    <!-- User Profile Dropdown -->
    <hr class="text-secondary">
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-4 me-2"></i>
            <strong>{{ Auth::user()->name }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        Sign out
                    </a>
                </form>
            </li>
        </ul>
    </div>
</div>
