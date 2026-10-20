<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" href="{{ url('assets/images/login/logo-login.png') }}">


    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Sinarmeadow product development request portal" name="description">
    <meta content="requisition slip, sinarmeadow, pengeluaran barang, form, inventory, admin" name="keywords">
    <meta content="Sinarmeadow" name="author">
    <link href="{{ asset('assets/') }}/images/logo/favicon.png" rel="icon" type="image/x-icon">
    <link href="{{ asset('assets/') }}/images/logo/favicon.png" rel="shortcut icon" type="image/x-icon">

    <title>MPDR - @yield('title')</title>

    <!-- Fonts -->

    <!--font-awesome-css-->
    <link href="{{ asset('assets/') }}/vendor/fontawesome/css/all.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">


    <!-- iconoir icon css  -->
    <link href="{{ asset('assets/') }}/vendor/ionio-icon/css/iconoir.css" rel="stylesheet">

    <!-- tabler icons-->
    <link href="{{ asset('assets/') }}/vendor/tabler-icons/tabler-icons.css" rel="stylesheet" type="text/css">

    <!--animation-css-->
    <link href="{{ asset('assets/') }}/vendor/animation/animate.min.css" rel="stylesheet">

    <!--flag Icon css-->
    <link href="{{ asset('assets/') }}/vendor/flag-icons-master/flag-icon.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap css-->
    <link href="{{ asset('assets/') }}/vendor/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">

    <!-- simplebar css-->
    <link href="{{ asset('assets/') }}/vendor/simplebar/simplebar.css" rel="stylesheet" type="text/css">

    <!-- App css-->
    <link href="{{ asset('assets/') }}/css/style.css" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('assets/') }}/css/responsive.css" rel="stylesheet" type="text/css">

    <!-- Data Table css-->
    <link href="{{ asset('assets') }}/vendor/datatable/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets') }}/vendor/datatable/datatable2/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/searchbuilder/1.6.0/css/searchBuilder.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css" rel="stylesheet" type="text/css">

    <style>
        /* --- Definisi Warna Status --- */
        :root {
            --bs-primary-rgb: 72, 94, 222;
            --bs-success-rgb: 26, 172, 110;
            --bs-warning-rgb: 247, 184, 75;
            --bs-danger-rgb: 239, 71, 111;
            --bs-info-rgb: 23, 162, 184;
        }

        /* --- Kartu Metrik KPI --- */
        .metric-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #ffffff;
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(var(--bs-primary-rgb), 0.08);
        }

        .metric-icon {
            padding: 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            color: rgb(var(--bs-primary-rgb));
            font-size: 1.5rem;
            /* Ukuran icon ti */
        }

        .metric-card.success .metric-icon {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: rgb(var(--bs-success-rgb));
        }

        .metric-card.warning .metric-icon {
            background-color: rgba(var(--bs-warning-rgb), 0.1);
            color: rgb(var(--bs-warning-rgb));
        }

        .metric-card.danger .metric-icon {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
        }

        .metric-card.info .metric-icon {
            background-color: rgba(var(--bs-info-rgb), 0.1);
            color: rgb(var(--bs-info-rgb));
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .metric-change {
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* --- Daftar Tindakan & Log Aktivitas --- */
        .action-list-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s ease;
        }

        .action-list-item:hover {
            background-color: #fcfcfc;
            cursor: pointer;
        }

        .action-list-item:last-child {
            border-bottom: none;
        }

        .action-icon {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-right: 1rem;
        }

        /* --- Badge Status --- */
        .status-badge {
            padding: 0.3em 0.7em;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 20px;
        }

        .status-pending {
            background-color: rgba(var(--bs-warning-rgb), 0.15);
            color: #a1741c;
        }

        .status-approved {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: rgb(var(--bs-success-rgb));
        }

        .status-rejected {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
        }

        .status-processing {
            background-color: rgba(var(--bs-info-rgb), 0.1);
            color: rgb(var(--bs-info-rgb));
        }

        /* --- Visualisasi Alur Kerja --- */
        .workflow-guide .nav-tabs .nav-link {
            font-weight: 600;
            color: #6c757d;
            border-bottom-width: 3px;
            border-color: transparent;
            padding: 0.75rem 1rem;
        }

        .workflow-guide .nav-tabs .nav-link.active {
            color: rgb(var(--bs-primary-rgb));
            border-color: rgb(var(--bs-primary-rgb));
        }

        .workflow-step {
            display: flex;
            align-items: center;
            position: relative;
            padding: 0.75rem 0;
        }

        .workflow-step .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #eef2ff;
            color: #1e40af;
            font-weight: 600;
            flex-shrink: 0;
            margin-right: 1rem;
            z-index: 2;
        }

        .workflow-step .step-content strong {
            display: block;
            font-size: 1rem;
            color: #333;
        }

        .workflow-step .step-content small {
            color: #555;
            font-size: 0.9rem;
        }

        .workflow-connector {
            position: absolute;
            left: 20px;
            /* Tengahkan dengan ikon */
            top: 0;
            width: 2px;
            height: 100%;
            background-color: #dbeafe;
            z-index: 1;
        }

        .workflow-step:first-child .workflow-connector {
            top: 50%;
            height: 50%;
        }

        .workflow-step:last-child .workflow-connector {
            height: 50%;
        }

        /* Styling Titik Keputusan */
        .workflow-decision {
            border-left: 3px solid rgb(var(--bs-warning-rgb));
            background-color: rgba(var(--bs-warning-rgb), 0.05);
            padding: 1rem;
            margin-left: 1rem;
            border-radius: 8px;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .workflow-decision .branch {
            margin-left: 2.5rem;
            position: relative;
            padding: 0.25rem 0;
        }

        .workflow-decision .branch::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 12px;
            width: 15px;
            height: 2px;
            background-color: rgb(var(--bs-warning-rgb));
        }

        .workflow-decision.bg-light-danger {
            border-left-color: rgb(var(--bs-danger-rgb));
            background-color: rgba(var(--bs-danger-rgb), 0.05);
        }

        .workflow-decision.bg-light-danger .branch::before {
            background-color: rgb(var(--bs-danger-rgb));
        }

        /* Penyesuaian Responsif untuk Garis Pemisah */
        @media (max-width: 991.98px) {
            .border-end-lg {
                border-right: none !important;
                border-bottom: 1px solid #dee2e6;
                padding-bottom: 1rem;
                margin-bottom: 1rem;
            }
        }

        /* =========================================================
           ULTRA-PREMIUM SIDEBAR & NAVBAR STYLING ENHANCEMENT
           ========================================================= */

        /* 1. SIDEBAR BASE CONTAINER */
        nav,
        nav.dark-sidebar,
        .semi-nav {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.06) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            height: 100vh !important;
            max-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            z-index: 1003 !important;
        }

        /* 2. APP LOGO CONTAINER IN SIDEBAR */
        nav .app-logo,
        .semi-nav .app-logo {
            background: rgba(15, 23, 42, 0.95) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 1.25rem 1.5rem !important;
            backdrop-filter: blur(10px) !important;
            flex: 0 0 auto !important;
        }

        nav .app-logo img,
        .semi-nav .app-logo img {
            max-height: 40px !important;
            width: auto !important;
            object-fit: contain !important;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.25)) !important;
            transition: transform 0.3s ease !important;
        }

        nav .app-logo img:hover,
        .semi-nav .app-logo img:hover {
            transform: scale(1.03) !important;
        }

        /* 2.1 SIDEBAR SCROLLABLE AREA (#app-simple-bar & .app-nav) */
        nav:not(.horizontal-sidebar) .app-nav,
        nav .app-nav,
        #app-simple-bar {
            flex: 1 1 auto !important;
            height: calc(100vh - 85px) !important;
            max-height: calc(100vh - 85px) !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            -webkit-overflow-scrolling: touch !important;
            overscroll-behavior-y: contain !important;
            scrollbar-width: thin !important;
            scrollbar-color: rgba(255, 255, 255, 0.25) transparent !important;
        }

        /* SimpleBar Container Height Fix */
        nav .simplebar-wrapper,
        nav .simplebar-mask,
        nav .simplebar-offset {
            height: 100% !important;
            max-height: 100% !important;
        }

        nav .simplebar-content-wrapper {
            height: 100% !important;
            max-height: 100% !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }

        /* Sleek Modern Scrollbar for Sidebar */
        #app-simple-bar::-webkit-scrollbar,
        nav .app-nav::-webkit-scrollbar,
        nav .simplebar-content-wrapper::-webkit-scrollbar {
            width: 5px !important;
        }

        #app-simple-bar::-webkit-scrollbar-track,
        nav .app-nav::-webkit-scrollbar-track,
        nav .simplebar-content-wrapper::-webkit-scrollbar-track {
            background: transparent !important;
        }

        #app-simple-bar::-webkit-scrollbar-thumb,
        nav .app-nav::-webkit-scrollbar-thumb,
        nav .simplebar-content-wrapper::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2) !important;
            border-radius: 10px !important;
        }

        #app-simple-bar::-webkit-scrollbar-thumb:hover,
        nav .app-nav::-webkit-scrollbar-thumb:hover,
        nav .simplebar-content-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.45) !important;
        }

        /* 3. MENU SECTION TITLES */
        nav .main-nav .menu-title,
        .semi-nav .main-nav .menu-title {
            padding: 1.2rem 1.5rem 0.5rem 1.5rem !important;
        }

        nav .main-nav .menu-title span,
        .semi-nav .main-nav .menu-title span {
            color: #64748b !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            letter-spacing: 1.2px !important;
            text-transform: uppercase !important;
        }

        /* 4. MAIN NAV LINKS (NAV ITEMS) */
        nav .main-nav li a,
        .semi-nav .main-nav li a {
            color: #cbd5e1 !important;
            font-size: 0.92rem !important;
            font-weight: 500 !important;
            padding: 0.75rem 1.25rem !important;
            margin: 0.2rem 0.8rem !important;
            border-radius: 10px !important;
            transition: all 0.25s ease !important;
        }

        nav .main-nav li a i,
        .semi-nav .main-nav li a i {
            font-size: 1.2rem !important;
            color: #94a3b8 !important;
            transition: color 0.25s ease, transform 0.25s ease !important;
            margin-right: 8px !important;
        }

        /* 5. HOVER & ACTIVE STATES FOR MENU LINKS */
        nav .main-nav li a:hover,
        .semi-nav .main-nav li a:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08) !important;
            transform: translateX(4px) !important;
        }

        nav .main-nav li a:hover i,
        .semi-nav .main-nav li a:hover i {
            color: #38bdf8 !important;
            transform: scale(1.1) !important;
        }

        nav .main-nav li.active > a,
        nav .main-nav li a.active,
        .semi-nav .main-nav li.active > a,
        .semi-nav .main-nav li a.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35) !important;
            font-weight: 600 !important;
        }

        nav .main-nav li.active > a i,
        nav .main-nav li a.active i,
        .semi-nav .main-nav li.active > a i,
        .semi-nav .main-nav li a.active i {
            color: #ffffff !important;
        }

        /* 6. SUBMENU (COLLAPSE ITEMS) */
        nav .main-nav ul.collapse,
        .semi-nav .main-nav ul.collapse {
            background: rgba(15, 23, 42, 0.4) !important;
            margin: 0.2rem 0.8rem !important;
            padding: 0.4rem 0 !important;
            border-radius: 10px !important;
            border-left: 2px solid rgba(59, 130, 246, 0.3) !important;
        }

        nav .main-nav ul.collapse li a,
        .semi-nav .main-nav ul.collapse li a {
            padding: 0.5rem 1.25rem 0.5rem 2rem !important;
            font-size: 0.86rem !important;
            color: #94a3b8 !important;
        }

        nav .main-nav ul.collapse li a:hover,
        .semi-nav .main-nav ul.collapse li a:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.05) !important;
        }

        nav .main-nav ul.collapse li.active a,
        .semi-nav .main-nav ul.collapse li.active a {
            color: #38bdf8 !important;
            background: rgba(56, 189, 248, 0.12) !important;
            font-weight: 600 !important;
        }

        /* 6.1 GENEROUS BOTTOM SPACING FOR SIDEBAR MENU (JARAK BOTTOM KETIKA MAXIMIZE) */
        nav .app-nav .main-nav,
        #app-simple-bar .main-nav,
        .semi-nav .main-nav {
            padding-bottom: 120px !important;
            margin-bottom: 0 !important;
        }

        /* 7. TOP HEADER NAVBAR STYLING */
        .header-main {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            padding: 0.75rem 1.5rem !important;
        }

        .header-main .header-left .header-toggle {
            background: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 10px !important;
            width: 40px !important;
            height: 40px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #334155 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
        }

        .header-main .header-left .header-toggle:hover {
            background: #e2e8f0 !important;
            color: #1e293b !important;
            transform: scale(1.05) !important;
        }
    </style>

    @stack('css')
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>

<body>
    <div class="app-wrapper gold">

        <div class="loader-wrapper">
            <div class="app-loader">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Menu Navigation starts -->
        @include('layouts.partials.nav')
        <!-- Menu Navigation ends -->


        <div class="app-content">
            <div class="">

                <!-- Header Section starts -->
                @include('layouts.partials.header')
                <!-- Header Section ends -->

                <!-- Body main section starts -->
                <main>
                    <div class="container-fluid mt-3">

                        {{ $slot }}

                    </div>
                </main>
                <!-- Body main section ends -->

                <!-- tap on top -->
                <div class="go-top">
                    <span class="progress-value">
                        <i class="ti ti-chevron-up"></i>
                    </span>
                </div>

                <!-- Footer Section starts-->
                @include('layouts.partials.footer')
                <!-- Footer Section ends-->

            </div>
        </div>
    </div>

    <!-- latest jquery-->
    <script src="{{ asset('assets') }}/js/jquery-3.6.3.min.js"></script>

    <!-- Simple bar js-->
    <script src="{{ asset('assets') }}/vendor/simplebar/simplebar.js"></script>

    <!-- phosphor js -->
    <script src="{{ asset('assets') }}/vendor/phosphor/phosphor.js"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets') }}/vendor/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- App js-->
    <script src="{{ asset('assets') }}/js/script.js"></script>

    <script>
        $(function() {
            const bar = document.getElementById('app-simple-bar');
            function recalcSidebar() {
                if (bar && window.SimpleBar) {
                    const inst = SimpleBar.instances.get(bar);
                    if (inst) inst.recalculate();
                }
            }
            $('.main-nav').on('shown.bs.collapse hidden.bs.collapse', function() {
                recalcSidebar();
            });
            $(window).on('resize', function() {
                recalcSidebar();
            });
            // Initial recalculate
            setTimeout(recalcSidebar, 200);
        });
    </script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- latest jquery-->
    <script src="{{ asset('assets') }}/vendor/datatable/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.6.0/js/dataTables.searchBuilder.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
    <!--js-->
    <script src="{{ asset('assets') }}/js/select.js"></script>

    @stack('scripts')

</body>

</html>
