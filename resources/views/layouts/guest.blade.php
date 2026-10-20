<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $pageTitle = trim($__env->yieldContent('title'));
    $displayTitle = $pageTitle ? "{$pageTitle} - Sinar Meadow MPDR Portal | PT Sinar Meadow International Indonesia" : "Login - MPDR Portal | PT Sinar Meadow International Indonesia";
    $metaDesc = "Login ke Portal Resmi MPDR (Master Product Development Request) PT Sinar Meadow International Indonesia. Sistem manajemen dan pemantauan alur inovasi dan formulasi produk margarine, shortening, dan bakery fats.";
@endphp

    <!-- Primary SEO Meta Tags -->
    <title>{{ $displayTitle }}</title>
    <meta name="title" content="{{ $displayTitle }}">
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="keywords" content="PT Sinar Meadow International Indonesia, Sinar Meadow, MPDR Login, Portal MPDR, Pre-MPDR, Product Development Request, Login Sinar Meadow, Form Permintaan Produk, Margarine, Shortening">
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

    <link rel="icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/x-icon">

    @stack('meta')

    <!-- Preconnect to Bunny Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!--font-awesome-css-->
    <link href="{{ asset('assets') }}/vendor/fontawesome/css/all-1.css" rel="stylesheet">

    <!-- iconoir icon css (optimized subset) -->
    <link href="{{ asset('assets') }}/vendor/ionio-icon/css/iconoir.min.css" rel="stylesheet">

    <!-- tabler icons-->
    <link href="{{ asset('assets') }}/vendor/tabler-icons/tabler-icons-1.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap css-->
    <link href="{{ asset('assets') }}/vendor/bootstrap/bootstrap.min-1.css" rel="stylesheet" type="text/css">

    <!-- App css-->
    <link href="{{ asset('assets') }}/css/style-1.css" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('assets') }}/css/responsive-1.css" rel="stylesheet" type="text/css">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="sign-in-bg" style="background-image: url({{ asset('assets/images/login/pic.webp') }}); background-size: 100% 100%; height: 100vh; width: 100vw;">
    <div class="app-wrapper d-block">
        <div class="main-container">
            <!-- Body main section starts -->
            {{ $slot }}
            <!-- Body main section ends -->
        </div>
    </div>
    <!-- latest jquery-->
    <script src="{{ asset('assets') }}/js/jquery-3.6.3.min-1.js"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets') }}/vendor/bootstrap/bootstrap.bundle.min-1.js"></script>

</body>

</html>
