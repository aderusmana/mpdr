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

    <!-- Custom Layout & Responsive Stylesheet (Cache-busted) -->
    <link href="{{ asset('assets/css/custom-layout.css') }}?v={{ file_exists(public_path('assets/css/custom-layout.css')) ? filemtime(public_path('assets/css/custom-layout.css')) : time() }}" rel="stylesheet" type="text/css">

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

    @stack('scripts')

</body>

</html>
