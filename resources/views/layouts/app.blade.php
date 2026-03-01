<!DOCTYPE html>
<!-- resources/views/layouts/app.blade.php -->
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Setting::getValue('system_name', 'SMA Muhammadiyah Kasihan') }} - @yield('title')</title>
    
    @php
        $favicon = \App\Models\Setting::getImageUrl('favicon');
    @endphp
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ $favicon }}">
    @endif
    
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/css/OverlayScrollbars.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .mobile-dock {
            display: none;
        }

        @media (max-width: 767.98px) {
            .content-header {
                padding-top: .35rem;
            }

            .content-header h1,
            .content-header .h1,
            .content-header .m-0 {
                font-size: 1.2rem !important;
                font-weight: 600;
                line-height: 1.25;
            }

            .content {
                padding-left: 0;
                padding-right: 0;
            }

            .content .container-fluid {
                padding-left: .65rem;
                padding-right: .65rem;
            }

            .content-wrapper {
                padding-bottom: 108px;
                transition: transform .2s ease, opacity .2s ease;
            }

            .card {
                border: 0;
                border-radius: 14px;
                box-shadow: 0 6px 16px rgba(16, 24, 40, .08);
                overflow: hidden;
                margin-bottom: .85rem;
            }

            .card-header {
                padding: .7rem .85rem;
                border-bottom: 1px solid rgba(0, 0, 0, .05);
            }

            .card-title {
                font-size: .95rem;
                font-weight: 600;
                margin: 0;
            }

            .card-body {
                padding: .85rem;
            }

            .form-group {
                margin-bottom: .85rem;
            }

            .form-group label {
                font-size: .85rem;
                margin-bottom: .35rem;
                font-weight: 600;
            }

            .form-control,
            .custom-file-label,
            .custom-select {
                border-radius: 10px;
                min-height: 40px;
                font-size: .9rem;
            }

            .btn {
                border-radius: 10px;
                font-weight: 600;
            }

            .btn-group > .btn,
            .btn-group-vertical > .btn {
                border-radius: 8px !important;
            }


                    body.role-siswa {
                        --student-font-xs: .68rem;
                        --student-font-sm: .74rem;
                        --student-font-md: .82rem;
                        --student-font-lg: .92rem;
                    }
            .content-header .float-right,
            .content-header .float-sm-right {
                float: none !important;
                margin-top: .55rem;
            }

            .content-header .float-right .btn,
            .content-header .float-sm-right .btn {
                width: 100%;
                        font-size: var(--student-font-md);
            }

                        font-size: var(--student-font-xs);
                border-radius: 10px;
                overflow: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table {
                        font-size: var(--student-font-sm);
                margin-bottom: 0;
            }

                        font-size: var(--student-font-sm);
                white-space: nowrap;
                font-size: .78rem;
                text-transform: uppercase;
                letter-spacing: .02em;
                        font-size: var(--student-font-lg);

            .table td {
                vertical-align: middle;
            }
                        font-size: var(--student-font-sm);
            .modal-dialog {
                margin: .6rem;
            }

            .modal-content {
                border-radius: 14px;
                        font-size: var(--student-font-md);
                box-shadow: 0 14px 30px rgba(16, 24, 40, .2);
            }

            .modal-header,
            .modal-footer {
                padding: .75rem .9rem;
                        font-size: var(--student-font-sm);

            .modal-body {
                padding: .9rem;
            }

                        font-size: var(--student-font-sm);
                display: flex;
                flex-direction: column-reverse;
                gap: .5rem;
            }

                        font-size: .56rem;
                width: 100%;

                    body.role-siswa .card-title {
                        font-size: var(--student-font-lg);
                    }

                    body.role-siswa .table td,
                    body.role-siswa .table th,
                    body.role-siswa .badge,
                    body.role-siswa .btn,
                    body.role-siswa .alert {
                        font-size: var(--student-font-sm);
                    }

                    body.role-siswa .table th {
                        font-size: var(--student-font-xs);
                    }
                margin: 0 !important;
            }

            .main-sidebar {
                position: fixed;
                top: 60px;
                left: 14px;
                bottom: calc(88px + env(safe-area-inset-bottom));
                width: calc(100vw - 56px);
                max-width: 300px;
                height: auto;
                border-radius: 18px;
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, .18);
                background: linear-gradient(180deg, rgba(30, 41, 59, .96) 0%, rgba(23, 32, 46, .96) 100%);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                transition: transform .25s ease, opacity .2s ease, box-shadow .25s ease;
                z-index: 1040;
            }

            body.is-mobile:not(.sidebar-open) .main-sidebar {
                box-shadow: none;
                opacity: 0;
                pointer-events: none;
                transform: translateX(-16px) translateY(4px) scale(.98);
            }

            body.is-mobile.sidebar-open .main-sidebar {
                box-shadow: 0 10px 32px rgba(0, 0, 0, .38), 0 0 0 1px rgba(255, 255, 255, .05) inset;
                opacity: 1;
                pointer-events: auto;
                transform: none;
                animation: sidebarFloatIn .34s cubic-bezier(.18, .9, .32, 1.16);
            }

            .main-sidebar .brand-link {
                padding: .8rem .9rem;
                border-bottom: 1px solid rgba(255, 255, 255, .08);
            }

            .main-sidebar .sidebar {
                padding: .35rem 0 .75rem;
            }

            .main-sidebar .user-panel {
                margin: .35rem .7rem .7rem !important;
                padding: .7rem !important;
                border-radius: 12px;
                background: rgba(255, 255, 255, .06);
                border-bottom: 0 !important;
            }

            .main-sidebar .nav-sidebar > .nav-item > .nav-link {
                margin: .18rem .55rem;
                border-radius: 10px;
                padding: .65rem .75rem;
                transition: background-color .2s ease, color .2s ease, transform .2s ease;
            }

            .main-sidebar .nav-sidebar > .nav-item > .nav-link.menu-link-modern {
                display: flex;
                align-items: center;
                min-height: 44px;
            }

            body.is-mobile.sidebar-open .main-sidebar .nav-sidebar > .nav-item {
                opacity: 0;
                transform: translateX(-8px);
                animation: menuItemIn .22s ease forwards;
            }

            body.is-mobile.sidebar-open .main-sidebar .nav-sidebar > .nav-item:nth-child(2) { animation-delay: .02s; }
            body.is-mobile.sidebar-open .main-sidebar .nav-sidebar > .nav-item:nth-child(3) { animation-delay: .04s; }
            body.is-mobile.sidebar-open .main-sidebar .nav-sidebar > .nav-item:nth-child(4) { animation-delay: .06s; }
            body.is-mobile.sidebar-open .main-sidebar .nav-sidebar > .nav-item:nth-child(5) { animation-delay: .08s; }
            body.is-mobile.sidebar-open .main-sidebar .nav-sidebar > .nav-item:nth-child(6) { animation-delay: .10s; }
            body.is-mobile.sidebar-open .main-sidebar .nav-sidebar > .nav-item:nth-child(7) { animation-delay: .12s; }
            body.is-mobile.sidebar-open .main-sidebar .nav-sidebar > .nav-item:nth-child(8) { animation-delay: .14s; }

            .main-sidebar .nav-sidebar > .nav-item > .nav-link.menu-link-modern p {
                margin: 0;
                font-weight: 500;
                letter-spacing: .01em;
            }

            .main-sidebar .nav-sidebar > .nav-item > .nav-link.active {
                background: linear-gradient(135deg, rgba(0, 123, 255, .9) 0%, rgba(0, 86, 179, .9) 100%);
                color: #fff;
                transform: translateX(2px);
                box-shadow: 0 8px 14px rgba(0, 123, 255, .25);
            }

            .main-sidebar .nav-sidebar > .nav-item > .nav-link:not(.active):hover {
                background: rgba(255, 255, 255, .08);
            }

            .main-sidebar .nav-sidebar .nav-icon {
                font-size: .95rem;
                width: 1.4rem;
                margin-right: .4rem;
            }

            .main-sidebar .nav-sidebar .menu-link-modern .nav-icon {
                width: 30px;
                min-width: 30px;
                height: 30px;
                line-height: 30px;
                text-align: center;
                margin-right: .6rem;
                border-radius: 9px;
                background: rgba(255, 255, 255, .08);
                color: rgba(255, 255, 255, .92);
                transition: background-color .2s ease, transform .2s ease;
            }

            .main-sidebar .nav-sidebar .menu-link-modern.active .nav-icon {
                background: rgba(255, 255, 255, .22);
                transform: scale(1.04);
            }

            .main-sidebar .menu-section-label {
                color: rgba(255, 255, 255, .55);
                font-size: .72rem;
                text-transform: uppercase;
                letter-spacing: .04em;
                padding: .2rem .95rem .3rem;
                margin-top: .35rem;
            }

            .main-sidebar .nav-item.mt-3 {
                margin-top: .55rem !important;
                padding-top: .55rem;
                border-top: 1px solid rgba(255, 255, 255, .1);
            }

            .sidebar-close-mobile {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                border-radius: 7px;
                color: rgba(255, 255, 255, .85) !important;
                background: rgba(255, 255, 255, .08);
                text-decoration: none !important;
            }

            .main-header .navbar-nav .nav-item .nav-link small {
                display: none;
            }

            .mobile-dock {
                position: fixed;
                left: 50%;
                transform: translateX(-50%);
                bottom: calc(8px + env(safe-area-inset-bottom));
                width: calc(100% - 20px);
                max-width: 560px;
                z-index: 1050;
                display: flex;
                align-items: stretch;
                justify-content: space-around;
                background: rgba(255, 255, 255, .92);
                border: 1px solid rgba(15, 23, 42, .08);
                border-radius: 18px;
                box-shadow: 0 10px 24px rgba(15, 23, 42, .14);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                padding: 4px;
            }

            .mobile-dock-item {
                flex: 1;
                text-align: center;
                color: #6c757d;
                text-decoration: none !important;
                padding: 9px 4px 7px;
                font-size: 11px;
                font-weight: 500;
                line-height: 1.1;
                transition: color .2s ease, background-color .2s ease, transform .15s ease;
                border-radius: 14px;
            }

            .mobile-dock-item span {
                display: block;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mobile-dock-item i {
                display: block;
                font-size: 16px;
                margin-bottom: 4px;
                transition: transform .2s ease;
            }

            .mobile-dock-item.active {
                color: #007bff;
                background: rgba(0, 123, 255, .12);
                position: relative;
            }

            .mobile-dock-item.active i {
                transform: translateY(-1px) scale(1.08);
                animation: dockIconPulse .35s ease;
            }

            .mobile-dock-item:active {
                transform: scale(.97);
            }

            .mobile-dock-item:not(.active):hover {
                background: rgba(0, 0, 0, .03);
            }

            .mobile-dock-item.active::before {
                content: '';
                position: absolute;
                top: 4px;
                left: 24%;
                width: 52%;
                height: 2px;
                border-radius: 0 0 8px 8px;
                background: #007bff;
            }

            .mobile-dock-item.text-danger {
                color: #dc3545 !important;
            }

            body.is-mobile.sidebar-open .content-wrapper::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, .25);
                z-index: 1035;
            }

            .scroll-to-top {
                bottom: 122px !important;
            }

            @keyframes dockIconPulse {
                0% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-2px) scale(1.14); }
                100% { transform: translateY(-1px) scale(1.08); }
            }

            @keyframes sidebarFloatIn {
                0% {
                    opacity: 0;
                    transform: translateX(-20px) translateY(6px) scale(.96);
                }
                65% {
                    opacity: 1;
                    transform: translateX(2px) translateY(0) scale(1.01);
                }
                100% {
                    opacity: 1;
                    transform: none;
                }
            }

            @keyframes menuItemIn {
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            body.role-siswa .student-mobile-only {
                display: none;
            }

            @media (max-width: 767.98px) {
                body.role-siswa {
                    background: #f1f5f9;
                    --student-font-xs: .68rem;
                    --student-font-sm: .74rem;
                    --student-font-md: .82rem;
                    --student-font-lg: .92rem;
                }

                body.role-siswa .student-mobile-navbar-id {
                    display: inline-flex;
                    align-items: center;
                    gap: .5rem;
                    max-width: 190px;
                    text-decoration: none !important;
                    color: #0f4c64;
                    font-weight: 700;
                    font-size: .9rem;
                    white-space: nowrap;
                    padding-top: .4rem;
                    padding-bottom: .4rem;
                }

                body.role-siswa .student-mobile-navbar-id img {
                    width: 24px;
                    height: 24px;
                    object-fit: contain;
                    border-radius: 6px;
                    background: #fff;
                    border: 1px solid rgba(15, 23, 42, .12);
                }

                body.role-siswa .student-mobile-navbar-id i {
                    font-size: 1.2rem;
                    color: #0e7490;
                }

                body.role-siswa .student-mobile-navbar-id span {
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                body.role-siswa .desktop-user-identity {
                    display: none !important;
                }

                body.role-siswa .content-header {
                    display: none !important;
                }

                body.role-siswa .content {
                    padding-top: .5rem;
                }

                body.role-siswa .main-header {
                    border-bottom: 0;
                    box-shadow: 0 8px 16px rgba(15, 23, 42, .08);
                    background: #f1f5f9;
                }

                body.role-siswa .content-header {
                    padding: .35rem 0 0;
                    margin-bottom: .15rem;
                }

                body.role-siswa .content-wrapper {
                    background: #f1f5f9;
                }

                body.role-siswa .student-mobile-only {
                    display: block;
                }

                body.role-siswa .student-mobile-card {
                    border-radius: 16px;
                    background: #fff;
                    border: 1px solid rgba(15, 23, 42, .06);
                    box-shadow: 0 8px 20px rgba(15, 23, 42, .08);
                    margin-bottom: .75rem;
                    overflow: hidden;
                }

                body.role-siswa .student-mobile-hero {
                    border-radius: 18px;
                    background: linear-gradient(145deg, #0a5b7a 0%, #1f7da0 54%, #2f8eb2 100%);
                    color: #fff;
                    padding: .95rem;
                    margin-bottom: .85rem;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 10px 24px rgba(12, 74, 110, .3);
                }

                body.role-siswa .student-mobile-hero::after {
                    content: '';
                    position: absolute;
                    width: 130px;
                    height: 130px;
                    border-radius: 28px;
                    border: 10px solid rgba(255, 255, 255, .08);
                    right: -40px;
                    top: -24px;
                    transform: rotate(16deg);
                }

                body.role-siswa .student-mobile-title {
                    font-size: 1.35rem;
                    font-weight: 700;
                    margin: 0 0 .1rem;
                    color: #0f172a;
                }

                body.role-siswa .student-mobile-subtitle {
                    color: #64748b;
                    margin: 0 0 .9rem;
                    font-size: var(--student-font-md);
                }

                body.role-siswa .student-mobile-hero-label {
                    font-size: var(--student-font-xs);
                    letter-spacing: .05em;
                    text-transform: uppercase;
                    opacity: .86;
                    margin-bottom: .3rem;
                }

                body.role-siswa .student-mobile-hero-amount {
                    font-size: 2rem;
                    font-weight: 700;
                    line-height: 1.1;
                    margin-bottom: .55rem;
                }

                body.role-siswa .student-mobile-hero-meta {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: .5rem;
                }

                body.role-siswa .student-mobile-hero-date {
                    font-size: var(--student-font-sm);
                    opacity: .88;
                }

                body.role-siswa .student-mobile-pay-btn {
                    border: 0;
                    border-radius: 10px;
                    background: #fff;
                    color: #0b5f84;
                    font-size: var(--student-font-sm);
                    font-weight: 700;
                    padding: .42rem .9rem;
                    text-transform: uppercase;
                    letter-spacing: .03em;
                    text-decoration: none !important;
                }

                body.role-siswa .student-mobile-section-head {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin: .2rem 0 .55rem;
                }

                body.role-siswa .student-mobile-section-head h3 {
                    margin: 0;
                    font-size: var(--student-font-lg);
                    font-weight: 700;
                    color: #0f172a;
                }

                body.role-siswa .student-mobile-section-head a {
                    font-size: var(--student-font-sm);
                    color: #0369a1;
                    font-weight: 600;
                    text-decoration: none;
                }

                body.role-siswa .mobile-dock-item.active {
                    color: #0369a1;
                    background: rgba(14, 116, 144, .14);
                }

                body.role-siswa .mobile-dock-item.active::before {
                    background: #0284c7;
                }

                body.role-siswa .student-mobile-list {
                    background: #fff;
                    border: 1px solid rgba(15, 23, 42, .06);
                    border-radius: 14px;
                    box-shadow: 0 8px 20px rgba(15, 23, 42, .06);
                    padding: .2rem .65rem;
                }

                body.role-siswa .student-mobile-item {
                    display: flex;
                    align-items: center;
                    gap: .6rem;
                    padding: .6rem .1rem;
                    border-bottom: 1px solid rgba(148, 163, 184, .2);
                }

                body.role-siswa .student-mobile-item:last-child {
                    border-bottom: 0;
                }

                body.role-siswa .student-mobile-icon {
                    width: 34px;
                    height: 34px;
                    border-radius: 10px;
                    background: #eff6ff;
                    color: #2563eb;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: .85rem;
                    flex-shrink: 0;
                }

                body.role-siswa .student-mobile-main {
                    flex: 1;
                    min-width: 0;
                }

                body.role-siswa .student-mobile-main strong {
                    display: block;
                    font-size: var(--student-font-md);
                    color: #0f172a;
                    margin-bottom: .05rem;
                }

                body.role-siswa .student-mobile-main span {
                    display: block;
                    font-size: var(--student-font-sm);
                    color: #64748b;
                }

                body.role-siswa .student-mobile-side {
                    text-align: right;
                    font-size: var(--student-font-sm);
                    color: #0f172a;
                    font-weight: 700;
                }

                body.role-siswa .card-title {
                    font-size: var(--student-font-lg);
                }

                body.role-siswa .table td,
                body.role-siswa .badge,
                body.role-siswa .btn,
                body.role-siswa .alert {
                    font-size: var(--student-font-sm);
                }

                body.role-siswa .table th {
                    font-size: var(--student-font-xs);
                }

                body.role-siswa .student-mobile-side .badge {
                    margin-top: .2rem;
                    font-size: .58rem;
                    letter-spacing: .02em;
                }

                body.role-siswa .mobile-dock {
                    background: #ffffff;
                    border-radius: 16px;
                    border: 1px solid rgba(15, 23, 42, .08);
                }
            }

            @media (max-width: 390px) {
                .mobile-dock {
                    width: calc(100% - 14px);
                    border-radius: 16px;
                    padding: 3px;
                }

                .mobile-dock-item {
                    font-size: 10px;
                    padding: 8px 2px 6px;
                }

                .mobile-dock-item i {
                    font-size: 15px;
                    margin-bottom: 3px;
                }
            }

            @media (min-width: 768px) {
                .main-sidebar {
                    opacity: 1 !important;
                    transform: none !important;
                    pointer-events: auto !important;
                    visibility: visible !important;
                }

                body:not(.sidebar-open) .main-sidebar {
                    opacity: 1 !important;
                    transform: none !important;
                    pointer-events: auto !important;
                }
            }
        }
    </style>

    @yield('styles')
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed {{ Auth::check() ? 'role-' . Auth::user()->role : 'role-guest' }}">
<div class="wrapper">

    <!-- Navbar - Fixed -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            @auth
                @if(Auth::user()->isSiswa())
                    @php
                        $navbarLogo = \App\Models\Setting::getImageUrl('system_logo');
                    @endphp
                    <li class="nav-item d-md-none">
                        <a href="{{ route('siswa.dashboard') }}" class="nav-link student-mobile-navbar-id" title="{{ Auth::user()->name }}">
                            @if($navbarLogo)
                                <img src="{{ $navbarLogo }}" alt="Logo">
                            @else
                                <i class="fas fa-user-circle"></i>
                            @endif
                            <span>{{ Str::of(Auth::user()->name)->before(' ') }}</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                @endif
            @else
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            @endauth

            <li class="nav-item d-none d-sm-inline-block">
                @auth
                    <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="nav-link">
                        <i class="fas fa-home"></i> Home
                    </a>
                @else
                    <a href="{{ url('/') }}" class="nav-link">
                        <i class="fas fa-home"></i> Home
                    </a>
                @endauth
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            @auth
            <li class="nav-item desktop-user-identity">
                <span class="nav-link">
                    <i class="fas fa-user-circle mr-1"></i>
                    <strong>{{ Auth::user()->name }}</strong>
                    <small class="text-muted ml-2">({{ ucfirst(Auth::user()->role) }})</small>
                </span>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-cog"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('logout') }}" class="dropdown-item" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
            @endauth
        </ul>
    </nav>

    <!-- Sidebar - Fixed -->
    @auth
        @include('layouts.sidebar')
    @endauth

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    @auth
    <nav class="mobile-dock d-md-none" aria-label="Mobile Dock Navigation">
        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="mobile-dock-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('admin.data-siswa.index') }}" class="mobile-dock-item {{ request()->routeIs('admin.data-siswa.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Siswa</span>
            </a>
            <a href="{{ route('admin.jenis-pembayaran.index') }}" class="mobile-dock-item {{ request()->routeIs('admin.jenis-pembayaran.*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i>
                <span>Bayar</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="mobile-dock-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i>
                <span>Setting</span>
            </a>
        @elseif(Auth::user()->isBendahara())
            <a href="{{ route('bendahara.dashboard') }}" class="mobile-dock-item {{ request()->routeIs('bendahara.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('bendahara.approval-pembayaran.index') }}" class="mobile-dock-item {{ request()->routeIs('bendahara.approval-pembayaran.*') ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i>
                <span>Approval</span>
            </a>
            <a href="{{ route('bendahara.data-siswa.index') }}" class="mobile-dock-item {{ request()->routeIs('bendahara.data-siswa.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Siswa</span>
            </a>
            <a href="{{ route('bendahara.laporan-keuangan.index') }}" class="mobile-dock-item {{ request()->routeIs('bendahara.laporan-keuangan.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i>
                <span>Laporan</span>
            </a>
        @elseif(Auth::user()->isSiswa())
            <a href="{{ route('siswa.dashboard') }}" class="mobile-dock-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('siswa.tagihan.index') }}" class="mobile-dock-item {{ request()->routeIs('siswa.tagihan.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                <span>Tagihan</span>
            </a>
            <a href="{{ route('siswa.riwayat.index') }}" class="mobile-dock-item {{ request()->routeIs('siswa.riwayat.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </a>
            <a href="{{ route('siswa.profile.index') }}" class="mobile-dock-item {{ request()->routeIs('siswa.profile.*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </a>
        @else
            <a href="#" data-widget="pushmenu" role="button" class="mobile-dock-item">
                <i class="fas fa-bars"></i>
                <span>Menu</span>
            </a>
            <a href="{{ route('logout') }}" class="mobile-dock-item text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        @endif
    </nav>
    @endauth

</div>

<!-- Scroll to Top Button -->
<a href="#" class="scroll-to-top" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 1000; background: #007bff; color: white; width: 40px; height: 40px; border-radius: 50%; text-align: center; line-height: 40px;">
    <i class="fas fa-chevron-up"></i>
</a>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/js/jquery.overlayScrollbars.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // Setup CSRF token untuk semua AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize AdminLTE
    $(document).ready(function() {
        function syncSidebarByViewport() {
            var isMobile = window.matchMedia('(max-width: 767.98px)').matches;
            var $sidebar = $('.main-sidebar');

            $('body').toggleClass('is-mobile', isMobile);
            $('body').toggleClass('is-desktop', !isMobile);

            if (!isMobile) {
                $('body').removeClass('sidebar-collapse sidebar-closed sidebar-open');
                $sidebar.css({
                    opacity: '1',
                    transform: 'none',
                    pointerEvents: 'auto',
                    visibility: 'visible',
                    display: 'block'
                });
            } else {
                $('body').removeClass('sidebar-open');
                $sidebar.css({
                    opacity: '',
                    transform: '',
                    pointerEvents: '',
                    visibility: '',
                    display: ''
                });
            }
        }

        syncSidebarByViewport();
        $(window).on('resize', syncSidebarByViewport);

        $(document).on('click', '[data-widget="pushmenu"]', function(e) {
            if (!window.matchMedia('(max-width: 767.98px)').matches) {
                e.preventDefault();
                $('body').removeClass('sidebar-collapse sidebar-closed sidebar-open');
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 5000);
        
        // Smooth scroll to top
        $('.scroll-to-top').click(function() {
            $('html, body').animate({ scrollTop: 0 }, 500);
            return false;
        });
        
        // Show/hide scroll to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.scroll-to-top').fadeIn();
            } else {
                $('.scroll-to-top').fadeOut();
            }
        });
        
        // Debug: Check if CSRF token is available
        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));

        // Close sidebar on mobile when tapping content area
        $(document).on('click touchstart', '.content-wrapper', function() {
            if (window.matchMedia('(max-width: 767.98px)').matches && $('body').hasClass('sidebar-open')) {
                $('[data-widget="pushmenu"]').first().trigger('click');
            }
        });

        // Auto-close sidebar after selecting a mobile dock menu item
        $(document).on('click', '.mobile-dock .mobile-dock-item', function() {
            if (window.matchMedia('(max-width: 767.98px)').matches && $('body').hasClass('sidebar-open')) {
                $('[data-widget="pushmenu"]').first().trigger('click');
            }
        });
    });
</script>

@stack('scripts')
@yield('scripts')
</body>
</html>