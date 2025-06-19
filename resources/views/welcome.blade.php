<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Selamat Datang di Bokor Mas Gold ERP</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            body, html {
                height: 100%;
                margin: 0;
                font-family: 'Figtree', sans-serif;
                background-color: #fdf6f7; /* Warna latar belakang pink muda */
            }
            .bg-dots-darker {
                background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(231,84,128,0.2)'/%3E%3C/svg%3E");
            }
            .main-container {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                padding: 2rem;
            }
            .content-box {
                background: white;
                padding: 3rem;
                border-radius: 0.75rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                text-align: center;
                max-width: 500px;
                width: 100%;
            }
            .logo {
                width: 150px;
                height: auto;
                margin-bottom: 1.5rem;
            }
            h1 {
                font-size: 1.875rem;
                font-weight: 600;
                color: #111827;
            }
            p {
                color: #6b7280;
                margin-top: 0.5rem;
                margin-bottom: 2rem;
            }
            .login-btn {
                display: inline-block;
                background-color: #e75480; /* Warna pink utama */
                color: white;
                padding: 0.75rem 2rem;
                border-radius: 0.5rem;
                text-decoration: none;
                font-weight: 600;
                transition: background-color 0.3s;
            }
            .login-btn:hover {
                background-color: #d6436f;
            }
            .footer {
                position: absolute;
                bottom: 1rem;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 0.875rem;
                color: #9ca3af;
            }
        </style>
    </head>
    <body class="bg-dots-darker">
        <div class="main-container">
            <div class="content-box">
                {{-- Ganti URL ini dengan URL logo Bokor Mas Gold Anda --}}
                <img src="https://bokormas.gold/cdn/shop/files/5_Logo_Crop_Tight.png?v=1716451897&width=330" alt="Bokor Mas Gold Logo" class="logo">
                <h1>Bokor Mas Gold ERP</h1>
                <p>Sistem Manajemen Terintegrasi untuk Operasional Bokor Mas Gold.</p>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="login-btn">Masuk ke Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="login-btn">Masuk ke Sistem</a>
                    @endauth
                @endif
            </div>
        </div>
        <footer class="footer">
            ERP BKM &copy; {{ date('Y') }}
        </footer>
    </body>
</html>
