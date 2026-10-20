<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $pageTitle = trim($__env->yieldContent('title'));
    $displayTitle = $pageTitle ? "{$pageTitle} - MPDR | PT Sinar Meadow International Indonesia" : "MPDR - Portal Permintaan Pengembangan Produk | PT Sinar Meadow International Indonesia";
    $metaDesc = "Portal resmi Master Product Development Request (MPDR & Pre-MPDR) PT Sinar Meadow International Indonesia untuk alur pengajuan, tracking, evaluasi, dan approval pengembangan produk secara terintegrasi.";
@endphp

    <!-- Primary SEO Meta Tags -->
    <title>{{ $displayTitle }}</title>
    <meta name="title" content="{{ $displayTitle }}">
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="keywords" content="PT Sinar Meadow International Indonesia, Sinar Meadow, MPDR, Pre-MPDR, Product Development Request, Portal MPDR Sinar Meadow, Form Pengembangan Produk, R&D Sinar Meadow, Margarine, Shortening, Specialty Fats, Bakery Ingredient">
    <meta name="author" content="PT Sinar Meadow International Indonesia">
    <meta name="application-name" content="Sinar Meadow MPDR Portal">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook / LinkedIn / WhatsApp Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Sinar Meadow MPDR Portal">
    <meta property="og:title" content="{{ $displayTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:image" content="{{ asset('assets/images/login/logo-login.png') }}">
    <meta property="og:image:alt" content="PT Sinar Meadow International Indonesia - MPDR Portal">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $displayTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ asset('assets/images/login/logo-login.png') }}">

   

    <link rel="icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/x-icon">

    @stack('meta')

    <!-- Preconnect to external origins used -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Figtree Font with swap -->
    <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"></noscript>

    <!-- Critical Core Stylesheets (Immediate render) -->
    <link href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendor/simplebar/simplebar.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet" type="text/css">

    <!-- Iconoir Optimized Subset (19KB instead of 2.58MB) -->
    <link href="{{ asset('assets/vendor/ionio-icon/css/iconoir.min.css') }}?v=1" rel="stylesheet" type="text/css">

    <!-- Custom Layout & Responsive Stylesheet (Cache-busted) -->
    <link href="{{ asset('assets/css/custom-layout.css') }}?v={{ file_exists(public_path('assets/css/custom-layout.css')) ? filemtime(public_path('assets/css/custom-layout.css')) : time() }}" rel="stylesheet" type="text/css">

    <!-- Non-Critical Stylesheets (Asynchronous Preload to prevent render blocking) -->
    <link rel="preload" href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}"></noscript>

    <link rel="preload" href="{{ asset('assets/vendor/fontawesome/css/all.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.css') }}"></noscript>

    <link rel="preload" href="{{ asset('assets/vendor/animation/animate.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/vendor/animation/animate.min.css') }}"></noscript>

    <link rel="preload" href="{{ asset('assets/vendor/flag-icons-master/flag-icon.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/vendor/flag-icons-master/flag-icon.css') }}"></noscript>

    <link rel="preload" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}"></noscript>

    <link rel="preload" href="{{ asset('assets/vendor/datatable/datatable2/buttons.dataTables.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/vendor/datatable/datatable2/buttons.dataTables.min.css') }}"></noscript>

    <link rel="preload" href="https://cdn.datatables.net/searchbuilder/1.6.0/css/searchBuilder.dataTables.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.6.0/css/searchBuilder.dataTables.min.css"></noscript>

    <link rel="preload" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css"></noscript>

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

        <!-- Mobile Sidebar Backdrop Overlay -->
        <div class="sidebar-overlay"></div>

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
            function isMobileScreen() {
                return window.innerWidth < 768 || $(window).width() < 768;
            }

            // Function to close mobile sidebar drawer
            function closeMobileSidebar() {
                $('body').removeClass('sidebar-mobile-open');
                $('nav').removeClass('mobile-active');
                $('.sidebar-overlay').removeClass('active');
            }

            // Function to open mobile sidebar drawer
            function openMobileSidebar() {
                $('body').addClass('sidebar-mobile-open');
                $('nav').addClass('mobile-active');
                $('.sidebar-overlay').addClass('active');
            }

            // Function to toggle mobile sidebar drawer
            function toggleMobileSidebar() {
                if ($('body').hasClass('sidebar-mobile-open') || $('nav').hasClass('mobile-active')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            }

            // 1. Header toggle icon click handler
            $(document).on('click', '.header-toggle', function(e) {
                if (isMobileScreen()) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMobileSidebar();
                }
            });

            // 2. Close when clicking backdrop overlay or close button (X)
            $(document).on('click', '.sidebar-overlay, .btn-close-sidebar', function(e) {
                e.preventDefault();
                closeMobileSidebar();
            });

            // 3. Auto-close mobile drawer on link navigation
            $(document).on('click', 'nav .main-nav li.no-sub a, nav .main-nav ul.collapse li a', function() {
                if (isMobileScreen()) {
                    closeMobileSidebar();
                }
            });

            // 4. SimpleBar recalculate and Window resize management
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
                if (!isMobileScreen()) {
                    closeMobileSidebar();
                }
                recalcSidebar();
            });

            // Initial recalculate after render
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

     <!-- Search Engine Schema.org Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {!! json_encode([
        chr(64) . 'context' => 'https://schema.org',
        chr(64) . 'graph' => [
            [
                chr(64) . 'type' => 'Organization',
                chr(64) . 'id' => url('/') . '/#organization',
                'name' => 'PT Sinar Meadow International Indonesia',
                'alternateName' => 'Sinar Meadow',
                'url' => url('/'),
                'logo' => [
                    chr(64) . 'type' => 'ImageObject',
                    'url' => asset('assets/images/logo/logohitam.png'),
                    'caption' => 'PT Sinar Meadow International Indonesia'
                ]
            ],
            [
                chr(64) . 'type' => 'WebSite',
                chr(64) . 'id' => url('/') . '/#website',
                'url' => url('/'),
                'name' => 'Sinar Meadow MPDR Portal',
                'description' => 'Portal resmi Master Product Development Request (MPDR) PT Sinar Meadow International Indonesia',
                'publisher' => [
                    chr(64) . 'id' => url('/') . '/#organization'
                ],
                'inLanguage' => 'id-ID'
            ]
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    
    @stack('scripts')

</body>

</html>
