<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ERP Bokor Mas Gold') }}</title>

        <!-- Scripts & Fonts -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Custom Styles for Modern Layout -->
        <style>
            :root {
                --sidebar-bg:rgb(255, 229, 239); /* Warna Pink Tua Elegan */
                --main-bg:rgb(255, 255, 255); /* Latar Belakang Pink Sangat Muda */
                --sidebar-link-color:rgb(0, 0, 0); /* Warna link pink pucat */
                --sidebar-link-hover-bg: #9D2459;
                --sidebar-link-active-bg: #FFFFFF; /* Link aktif menjadi putih */
                --sidebar-link-active-color: #831843; /* Teks link aktif menjadi pink tua */
            }
            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--main-bg);
            }
            .main-wrapper { display: flex; min-height: 100vh; }
            .sidebar {
                width: 260px;
                color: white;
                position: fixed;
                top: 0; left: 0; height: 100%;
                transition: transform 0.3s ease-in-out;
                z-index: 1000;
                
                /* EFEK GLASSMORPHISM & SHADOW PADA SIDEBAR */
                background: var(--sidebar-bg);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            }
            .main-content {
                margin-left: 260px;
                width: calc(100% - 260px);
                padding: 1.5rem 2rem;
            }
            .card {
                border-radius: 0.75rem;
                border: none;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.07);
            }
            
            /* Mobile responsiveness */
            @media (max-width: 992px) {
                .sidebar { transform: translateX(-100%); }
                .sidebar.is-open { transform: translateX(0); }
                .main-content { margin-left: 0; width: 100%; }
            }
        </style>
    </head>
    <body x-data="{ sidebarOpen: false }">
        <div class="main-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar" :class="{ 'is-open': sidebarOpen }">
                @include('layouts.navigation')
            </aside>

            <!-- Main Content -->
            <div class="main-content">
                <button class="btn btn-dark d-lg-none mb-3" @click="sidebarOpen = !sidebarOpen">
                    <i class="bi bi-list"></i> Menu
                </button>
                @if (isset($header))
                    {{ $header }}
                @endif
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
