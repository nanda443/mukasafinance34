<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ \App\Models\Setting::getValue('system_name', 'SMA Muhammadiyah Kasihan') }}</title>
    @php
        $favicon = \App\Models\Setting::getValue('favicon');
    @endphp
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}">
    @endif
    
    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* Left Side - Image */
        .login-image {
            width: 60%;
            @php
                $bgImage = \App\Models\Setting::getValue('login_bg_image');
                $bgUrl = $bgImage ? asset('storage/' . $bgImage) : 'https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1986&auto=format&fit=crop';
            @endphp
            background-image: url('{{ $bgUrl }}'); /* Modern Architecture/School vibe */
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 50px;
            color: white;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.7) 100%);
            z-index: 1;
        }

        .image-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
        }

        .image-content h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .image-content p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Right Side - Form */
        .login-form-container {
            width: 40%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            position: relative;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
        }

        .brand-logo span {
            color: #2563eb; /* Blue primary */
        }

        .login-subtext {
            color: #6c757d;
            margin-bottom: 40px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 500;
            color: #344767;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #e1e5ea;
            border-radius: 8px;
            padding: 12px 15px;
            height: auto;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background-color: #fff;
        }

        .input-group-text {
            background-color: transparent;
            border: 1px solid #e1e5ea;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #adb5bd;
        }
        
        /* Fix for input group with icon */
        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: #2563eb;
        }

        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.25);
        }

        .footer-copyright {
            margin-top: 30px;
            text-align: center;
            font-size: 0.8rem;
            color: #adb5bd;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-image {
                width: 50%;
            }
            .login-form-container {
                width: 50%;
            }
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }
            
            .login-image {
                width: 100%;
                height: 250px;
                padding: 30px;
                flex: none;
            }

            .image-content h1 {
                font-size: 1.8rem;
            }
            
            .login-form-container {
                width: 100%;
                padding: 40px 0;
                flex: 1;
            }
            
            body {
                overflow: auto;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-image">
        <div class="image-overlay"></div>
        <div class="image-content">
            <h1>{!! \App\Models\Setting::getValue('system_title', 'Sistem Informasi Keuangan<br>Sekolah') !!}</h1>
            <p>Kelola pembayaran SPP, administrasi, dan laporan keuangan siswa {{ \App\Models\Setting::getValue('system_name', 'SMA Muhammadiyah Kasihan') }} dengan mudah, transparan, dan efisien.</p>
        </div>
    </div>
    
    <div class="login-form-container">
        <div class="login-form-wrapper">
            <a href="#" class="brand-logo" style="display: flex; align-items: center; gap: 10px;">
                @php
                    $logo = \App\Models\Setting::getValue('system_logo');
                    $sysName = \App\Models\Setting::getValue('system_name', 'SMA Muhammadiyah Kasihan');
                    // Simple logic to bold first word if standard name, otherwise just show full name
                    $parts = explode(' ', $sysName, 2);
                    $firstWord = $parts[0] ?? '';
                    $rest = $parts[1] ?? '';
                @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" style="height: 40px;">
                @endif
                <div>
                    <span>{{ $firstWord }}</span> {{ $rest }}
                </div>
            </a>
            <p class="login-subtext">Silakan login untuk melanjutkan ke dashboard.</p>

            @if ($errors->any())
                <div class="alert alert-danger fade show" role="alert" style="border-radius: 8px; font-size: 0.9rem; border: none; background-color: #feecf0; color: #cc0f35;">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success fade show" role="alert" style="border-radius: 8px; font-size: 0.9rem; border: none; background-color: #ecfdf5; color: #059669;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="post">
                @csrf
                <div class="form-group">
                    <label>Email atau NIS</label>
                    <input type="text" name="login" class="form-control" placeholder="Masukkan Email / NIS" value="{{ old('login') }}" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-4">
                    Sign In
                </button>
            </form>

            <div class="footer-copyright">
                &copy; {{ date('Y') }} SMA Muhammadiyah Kasihan
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>