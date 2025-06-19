<div class="d-flex flex-column p-3 h-100">
    <!-- Logo -->
    <div class="text-center pt-2 pb-3 mb-4">
        <a href="{{ route('dashboard') }}">
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
        .nav-link.active {
            background-color: var(--sidebar-link-active-bg);
            color: var(--sidebar-link-active-color);
            font-weight: 500;
        }
        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }
        /* Styles for submenu */
        .submenu {
            padding-left: 2.5rem; /* Indentasi untuk sub-menu */
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        .submenu.show {
            max-height: 500px; /* Atur tinggi maksimal yang cukup */
        }
        .submenu .nav-link {
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
        }
    </style>
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Dropdown untuk Purchasing --}}
        <li class="nav-item" x-data="{ open: {{ request()->routeIs('purchasing.*') ? 'true' : 'false' }} }">
            <a href="#" class="nav-link d-flex justify-content-between {{ request()->routeIs('purchasing.*') ? 'active' : '' }}" @click.prevent="open = !open">
                <span>
                    <i class="bi bi-cart-check-fill"></i>
                    <span>Purchasing</span>
                </span>
                <i class="bi bi-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </a>
            <div class="submenu" :class="open ? 'show' : ''">
                <ul class="nav flex-column ps-2">
                    @role('Admin|Purchasing|CEO')
                    <li class="nav-item">
                        <a href="{{ route('purchasing.purchase-orders.index') }}" class="nav-link {{ request()->routeIs('purchasing.purchase-orders.*') ? 'active' : '' }}">Purchase Orders</a>
                    </li>
                    @endrole
                    @role('Admin|Staf Gudang|Kepala Toko')
                    <li class="nav-item">
                        <a href="{{ route('purchasing.goods-receipts.index') }}" class="nav-link {{ request()->routeIs('purchasing.goods-receipts.*') ? 'active' : '' }}">Penerimaan Barang</a>
                    </li>
                    @endrole
                </ul>
            </div>
        </li>

        @role('Admin|Staf Gudang')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i class="bi bi-gem"></i>
                <span>Produk</span>
            </a>
        </li>
        @endrole
        
        {{-- [BARU] Dropdown untuk Inventaris --}}
        <li class="nav-item" x-data="{ open: {{ request()->routeIs('inventory.*') ? 'true' : 'false' }} }">
            <a href="#" class="nav-link d-flex justify-content-between {{ request()->routeIs('inventory.*') ? 'active' : '' }}" @click.prevent="open = !open">
                <span>
                    <i class="bi bi-box-seam-fill"></i>
                    <span>Inventaris</span>
                </span>
                <i class="bi bi-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </a>
            <div class="submenu" :class="open ? 'show' : ''">
                <ul class="nav flex-column ps-2">
                    @role('Admin|Staf Gudang')
                    <li class="nav-item">
                        <a href="{{ route('inventory.warehouse-stocks.index') }}" class="nav-link {{ request()->routeIs('inventory.warehouse-stocks.*') ? 'active' : '' }}">Stok Gudang</a>
                    </li>
                    @endrole
                </ul>
            </div>
        </li>

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

        {{-- Dropdown untuk Master Data --}}
        <li class="nav-item" x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }">
            <a href="#" class="nav-link d-flex justify-content-between {{ request()->routeIs('master.*') ? 'active' : '' }}" @click.prevent="open = !open">
                <span>
                    <i class="bi bi-archive-fill"></i>
                    <span>Master Data</span>
                </span>
                <i class="bi bi-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </a>
            <div class="submenu" :class="open ? 'show' : ''">
                <ul class="nav flex-column ps-2">
                    <li class="nav-item">
                        <a href="{{ route('master.kadar.index') }}" class="nav-link {{ request()->routeIs('master.kadar.*') ? 'active' : '' }}">Kadar</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.jenis.index') }}" class="nav-link {{ request()->routeIs('master.jenis.*') ? 'active' : '' }}">Jenis</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.warehouses.index') }}" class="nav-link {{ request()->routeIs('master.warehouses.*') ? 'active' : '' }}">Gudang</a>
                    </li>
                    <li class="nav-item">
                <a href="{{ route('master.customers.index') }}" class="nav-link {{ request()->routeIs('master.customers.*') ? 'active' : '' }}">Pelanggan</a>
            </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="bi bi-people-fill"></i>
                <span>Pengguna</span>
            </a>
        </li>
        @endrole
    </ul>

    <!-- User Profile Dropdown -->
    <hr class="text-secondary">
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
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
