<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - ERP Bokor Mas Gold</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #fdf6f7;
            padding: 2rem;
        }
        .login-box {
            background: white;
            padding: 2.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .logo {
            width: 120px;
        }
        .form-label {
            font-weight: 600;
        }
        .btn-submit {
             background-color: #e75480;
             border-color: #e75480;
             color: #fff;
             width: 100%;
             padding: 0.75rem;
             font-weight: 600;
        }
         .btn-submit:hover {
             background-color: #d6436f;
             border-color: #d6436f;
             color: #fff;
         }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo-container">
                {{-- Ganti URL ini dengan URL logo Bokor Mas Gold Anda --}}
                <a href="/">
                    <img src="https://bokormas.gold/cdn/shop/files/5_Logo_Crop_Tight.png?v=1716451897&width=330" alt="Bokor Mas Gold Logo" class="logo">
                </a>
            </div>

            <!-- Session Status (jika ada pesan, misal: link reset password terkirim) -->
            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Menampilkan error validasi -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" class="form-control"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label">{{ __('Ingat Saya') }}</label>
                </div>

                <div class="d-flex justify-content-end align-items-center mb-3">
                    @if (Route::has('password.request'))
                        <a class="text-muted text-decoration-none small" href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-submit">
                    {{ __('Log in') }}
                </button>
            </form>
        </div>
    </div>
</body>
</html>
