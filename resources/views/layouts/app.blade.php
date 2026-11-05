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

   

    <link rel="icon" href="{{ asset('assets/images/logo/sinarmeadow.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/sinarmeadow.png') }}" type="image/x-icon">

    @stack('meta')

    <!-- Preconnect to external origins used -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Figtree Font with swap -->
    <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"></noscript>

    <!-- Preload Critical Fonts -->
    <link rel="preload" href="{{ asset('assets/fonts/tabler/tabler-icons.woff2?v2.4.0') }}" as="font" type="font/woff2" crossorigin>

    <!-- Critical Core Stylesheets (Immediate render) -->
    <link href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendor/simplebar/simplebar.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.min.css') }}" rel="stylesheet" type="text/css">
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
    <style>
        /* Prevent tooltips from intercepting pointer events, lingering, or delayed animation overlapping */
        .tooltip {
            pointer-events: none !important;
            transition: none !important;
            animation: none !important;
        }
        .tooltip.fade {
            transition: none !important;
        }
    </style>
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

            // Global protection against stuck, lingering, or overlapping Bootstrap tooltips
            if (window.bootstrap && bootstrap.Tooltip && bootstrap.Tooltip.Default) {
                bootstrap.Tooltip.Default.animation = false;
                bootstrap.Tooltip.Default.trigger = 'hover';
            }

            // Strict single tooltip rule: before any tooltip appears, immediately remove any other tooltips
            document.addEventListener('show.bs.tooltip', function(e) {
                $('.tooltip').remove();
                $('[data-bs-toggle="tooltip"]').not(e.target).each(function() {
                    const inst = bootstrap.Tooltip.getInstance(this);
                    if (inst) {
                        inst.hide();
                    }
                });
            }, true);

            // When hovering onto another tooltip element, immediately dismiss other active tooltips
            $(document).on('mouseenter', '[data-bs-toggle="tooltip"]', function() {
                const self = this;
                $('[data-bs-toggle="tooltip"]').not(self).each(function() {
                    const inst = bootstrap.Tooltip.getInstance(this);
                    if (inst) {
                        inst.hide();
                    }
                });
                $('.tooltip').remove();
            });

            // When mouse leaves a tooltip element, hide and remove immediately (no lag)
            $(document).on('mouseleave', '[data-bs-toggle="tooltip"]', function() {
                const inst = bootstrap.Tooltip.getInstance(this);
                if (inst) {
                    inst.hide();
                }
                $('.tooltip').remove();
            });

            // On click or modal show/hide, clean all tooltips immediately
            $(document).on('click', '[data-bs-toggle="tooltip"]', function() {
                const inst = bootstrap.Tooltip.getInstance(this);
                if (inst) {
                    inst.hide();
                }
                $('.tooltip').remove();
            });

            $(document).on('show.bs.modal hide.bs.modal', function() {
                $('.tooltip').remove();
            });
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

    <!-- Global Dynamic Mobile Table-to-Card Label Synchronizer -->
    <script>
    (function() {
        // Values that carry no information. A card row holding only one of
        // these is hidden so a card never shows a row of empty "label : -".
        var EMPTY_VALUES = ['', '-', '--', '---', '—', '–', 'n/a', 'na', 'null', 'undefined', 'none', 'tidak ada'];

        function isCardTable(table) {
            if (!table) return false;
            if (table.classList.contains('header-table') ||
                table.classList.contains('info-table') ||
                table.classList.contains('detail-table') ||
                table.classList.contains('fc-col-header') ||
                table.classList.contains('no-mobile-card') ||
                table.getAttribute('data-mobile-card') === 'off') {
                return false;
            }
            if (table.closest('.fc') ||
                table.closest('.print-container') ||
                table.closest('.swal2-container')) {
                return false;
            }
            return true;
        }

        function readHeaderLabels(table) {
            var headers = [];

            // 1. Try fetching headers directly from the DataTables instance
            if (typeof window.jQuery !== 'undefined' && window.jQuery.fn && window.jQuery.fn.dataTable && window.jQuery.fn.dataTable.isDataTable(table)) {
                try {
                    var dt = window.jQuery(table).DataTable();
                    var dtHeaders = dt.columns().header().toArray();
                    if (dtHeaders && dtHeaders.length) {
                        for (var h = 0; h < dtHeaders.length; h++) {
                            var dtText = window.jQuery(dtHeaders[h]).text().replace(/[\r\n\t]+/g, ' ').replace(/[*#]/g, '').trim();
                            headers.push(dtText);
                        }
                    }
                } catch (err) {}
            }

            // 2. Fallback to DOM thead extraction
            if (!headers.length) {
                var ths = table.querySelectorAll('thead tr:last-child th');
                if (!ths || !ths.length) ths = table.querySelectorAll('thead th');
                if (ths && ths.length) {
                    for (var i = 0; i < ths.length; i++) {
                        var text = (ths[i].textContent || '').replace(/[\r\n\t]+/g, ' ').replace(/[*#]/g, '').trim();
                        headers.push(text);
                    }
                }
            }

            return headers;
        }

        // Wrap every child of the cell in one element so the whole value block
        // (text + icon + badge) can be aligned to the right of the card row.
        function wrapCellValue(td) {
            if (td.querySelector(':scope > .mobile-cell-value')) return;
            var wrapper = document.createElement('span');
            wrapper.className = 'mobile-cell-value';
            while (td.firstChild) {
                wrapper.appendChild(td.firstChild);
            }
            td.appendChild(wrapper);
        }

        // Desktop keeps the original cell markup: put the children back and
        // drop the wrapper once the viewport is wide again.
        function unwrapCellValue(td) {
            var wrapper = td.querySelector(':scope > .mobile-cell-value');
            if (!wrapper) return;
            while (wrapper.firstChild) {
                td.insertBefore(wrapper.firstChild, wrapper);
            }
            wrapper.remove();
        }

        function isMobileViewport() {
            return window.matchMedia('(max-width: 767.98px)').matches;
        }

        function cellHasValue(td) {
            // Form controls and media are meaningful even without text
            if (td.querySelector('input, select, textarea, img, svg, canvas, video, audio')) return true;

            var text = (td.textContent || '').replace(/\s+/g, ' ').trim().toLowerCase();
            return text !== '' && EMPTY_VALUES.indexOf(text) === -1;
        }

        function isActionCell(td, index, lastIndex) {
            if (td.classList.contains('mobile-action-cell')) return true;
            if (index !== lastIndex) return false;
            return !!td.querySelector('button, .btn, form, [data-bs-toggle="dropdown"], .dropdown');
        }

        function syncAllTableDataLabels() {
            try {
                var mobile = isMobileViewport();
                var tables = document.querySelectorAll('table');
                Array.prototype.forEach.call(tables, function(table) {
                    if (!isCardTable(table)) return;

                    var headers = readHeaderLabels(table);
                    if (!headers.length) return;

                    // Optional per-table card title column, e.g. data-mobile-title="No. Reg"
                    var titleLabel = (table.getAttribute('data-mobile-title') || '').replace(/[\r\n\t]+/g, ' ').trim().toLowerCase();

                    var rows = table.querySelectorAll('tbody tr');
                    Array.prototype.forEach.call(rows, function(tr) {
                        if (tr.querySelector('.dataTables_empty')) return;

                        var cells = tr.querySelectorAll('td');
                        var lastIndex = cells.length - 1;

                        Array.prototype.forEach.call(cells, function(td, index) {
                            var rawLabel = (headers[index] || '').replace(/[\r\n\t]+/g, ' ').trim();
                            var headerLabel = rawLabel || ('Col ' + (index + 1));
                            if (!td.getAttribute('data-label')) {
                                td.setAttribute('data-label', headerLabel);
                            }

                            // Card-only markup: on desktop restore the plain cell
                            if (!mobile) {
                                unwrapCellValue(td);
                                return;
                            }

                            wrapCellValue(td);

                            var action = isActionCell(td, index, lastIndex);

                            if (action) {
                                // Action rows keep their buttons and never count as empty
                                td.classList.add('mobile-action-cell');
                                td.classList.remove('mobile-cell-empty');
                            } else if (!rawLabel) {
                                // Utility column without a real heading (e.g. the "#"
                                // index of the notification table): hide it on mobile
                                td.classList.add('mobile-cell-empty');
                            } else {
                                td.classList.remove('mobile-action-cell');
                                if (cellHasValue(td)) {
                                    td.classList.remove('mobile-cell-empty');
                                } else {
                                    td.classList.add('mobile-cell-empty');
                                }
                            }

                            if (!action && titleLabel && headerLabel.toLowerCase() === titleLabel) {
                                td.classList.add('mobile-card-title');
                            } else {
                                td.classList.remove('mobile-card-title');
                            }
                        });
                    });
                });
            } catch (e) {
                console.warn('syncAllTableDataLabels error:', e);
            }
        }

        window.syncAllTableDataLabels = syncAllTableDataLabels;

        // Global DataTables default callback injection
        if (typeof window.jQuery !== 'undefined' && window.jQuery.fn && window.jQuery.fn.dataTable) {
            try {
                window.jQuery.extend(true, window.jQuery.fn.dataTable.defaults, {
                    drawCallback: function() {
                        setTimeout(syncAllTableDataLabels, 20);
                    }
                });
            } catch (e) {}
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', syncAllTableDataLabels);
        } else {
            syncAllTableDataLabels();
        }

        if (typeof window.jQuery !== 'undefined') {
            window.jQuery(document).on('draw.dt xhr.dt page.dt order.dt search.dt shown.bs.modal shown.bs.tab', function() {
                setTimeout(syncAllTableDataLabels, 20);
                setTimeout(syncAllTableDataLabels, 150);
                setTimeout(syncAllTableDataLabels, 350);
            });
            window.addEventListener('resize', function() {
                setTimeout(syncAllTableDataLabels, 100);
            });
        }

        if (typeof MutationObserver !== 'undefined' && document.body) {
            var debounceTimer = null;
            var tableObserver = new MutationObserver(function(mutations) {
                var hasNewRows = false;
                for (var i = 0; i < mutations.length; i++) {
                    var added = mutations[i].addedNodes;
                    for (var j = 0; j < added.length; j++) {
                        var node = added[j];
                        if (node.nodeType === 1) {
                            if (node.tagName === 'TR' || node.tagName === 'TABLE' || (node.querySelector && node.querySelector('table, tr'))) {
                                hasNewRows = true;
                                break;
                            }
                        }
                    }
                    if (hasNewRows) break;
                }
                if (hasNewRows) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(syncAllTableDataLabels, 50);
                }
            });
            tableObserver.observe(document.body, { childList: true, subtree: true });
        }
    })();
    </script>

</body>

</html>
