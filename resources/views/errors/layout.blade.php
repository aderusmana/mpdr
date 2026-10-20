<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title') - Sinar Meadow MPDR</title>
    <link rel="icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendor/fontawesome/css/all.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-accent: #3b82f6;
            --gold-accent: #eab308;
            --card-bg: rgba(15, 23, 42, 0.82);
            --card-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at 50% 25%, #1e1b4b 0%, #0f172a 45%, #07090e 100%);
            color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Glowing Background Orbs */
        .ambient-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.45;
            pointer-events: none;
            z-index: 0;
            animation: floatOrb 18s ease-in-out infinite alternate;
        }

        .orb-1 {
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, #3b82f6 0%, rgba(59, 130, 246, 0) 70%);
            top: -120px;
            left: -120px;
            animation-duration: 16s;
        }

        .orb-2 {
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, #eab308 0%, rgba(234, 179, 8, 0) 70%);
            bottom: -150px;
            right: -120px;
            animation-duration: 22s;
            animation-delay: -5s;
        }

        .orb-3 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #8b5cf6 0%, rgba(139, 92, 246, 0) 70%);
            top: 45%;
            left: 65%;
            animation-duration: 20s;
            animation-delay: -10s;
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(45px, -65px) scale(1.15); }
            100% { transform: translate(-50px, 45px) scale(0.9); }
        }

        /* Subtle Animated Grid Background Pattern */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(circle at center, black 40%, transparent 88%);
            -webkit-mask-image: radial-gradient(circle at center, black 40%, transparent 88%);
            pointer-events: none;
            z-index: 1;
        }

        /* Page Layout Wrapper */
        .error-wrapper {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 1.5rem;
            width: 100%;
        }

        /* Top Header */
        .error-header {
            width: 100%;
            max-width: 1100px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0 1rem;
            flex-shrink: 0;
        }

        .error-brand {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .error-brand:hover {
            transform: scale(1.03);
        }

        .error-brand img {
            height: 44px;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.5));
        }

        .system-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #cbd5e1;
            backdrop-filter: blur(8px);
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 10px #22c55e;
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.6; }
        }

        /* Vertical Centering Container for Card */
        .error-center-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 1rem 0;
        }

        /* Central Glass Card */
        .error-card {
            width: 100%;
            max-width: 780px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            box-shadow: 
                0 30px 70px -15px rgba(0, 0, 0, 0.65),
                0 0 50px rgba(56, 189, 248, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            padding: 3.25rem 2.75rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Ambient Top Rainbow Highlight */
        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 15%;
            right: 15%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #38bdf8, #eab308, transparent);
            filter: blur(1px);
        }

        /* HERO DIGITS CENTERPIECE: 4 [Portal] 4 */
        .error-hero-digits {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(12px, 3vw, 24px);
            margin: 0 auto 1.5rem auto;
            user-select: none;
        }

        .error-hero-digits .digit {
            font-family: 'Space Grotesk', system-ui, sans-serif;
            font-size: clamp(5.2rem, 13vw, 8.5rem);
            font-weight: 800;
            line-height: 0.95;
            letter-spacing: -3px;
            background: linear-gradient(135deg, #ffffff 0%, #bae6fd 35%, #38bdf8 70%, #1d4ed8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 10px 30px rgba(56, 189, 248, 0.3));
            animation: floatDigit 5s ease-in-out infinite alternate;
        }

        .digit-gold {
            background: linear-gradient(135deg, #ffffff 0%, #fef08a 35%, #eab308 70%, #ca8a04 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            filter: drop-shadow(0 10px 30px rgba(234, 179, 8, 0.3)) !important;
        }

        .digit-danger {
            background: linear-gradient(135deg, #ffffff 0%, #fecdd3 35%, #f43f5e 70%, #be123c 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            filter: drop-shadow(0 10px 30px rgba(244, 63, 94, 0.3)) !important;
        }

        .digit-purple {
            background: linear-gradient(135deg, #ffffff 0%, #e9d5ff 35%, #c084fc 70%, #7e22ce 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            filter: drop-shadow(0 10px 30px rgba(192, 132, 252, 0.3)) !important;
        }

        @keyframes floatDigit {
            0% { transform: translateY(0); }
            100% { transform: translateY(-6px); }
        }

        /* Center Animated Portal */
        .center-portal {
            position: relative;
            width: clamp(90px, 16vw, 130px);
            height: clamp(90px, 16vw, 130px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .portal-ring {
            position: absolute;
            border-radius: 50%;
        }

        .portal-ring.ring-1 {
            inset: 0;
            border: 2px dashed rgba(56, 189, 248, 0.5);
            animation: spinRing 16s linear infinite;
        }

        .portal-ring.ring-2 {
            inset: 12px;
            border: 1px dotted rgba(255, 255, 255, 0.25);
            animation: spinRingReverse 20s linear infinite;
        }

        .portal-glow {
            position: absolute;
            inset: 16px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.35) 0%, rgba(56, 189, 248, 0) 70%);
            filter: blur(14px);
            animation: pulseGlow 3s ease-in-out infinite alternate;
        }

        .portal-icon {
            position: relative;
            z-index: 2;
            font-size: clamp(2.8rem, 7vw, 4.2rem);
            color: #38bdf8;
            animation: floatPortalIcon 3.5s ease-in-out infinite alternate;
            filter: drop-shadow(0 0 16px rgba(56, 189, 248, 0.6));
        }

        /* Portal Theme Variants */
        .portal-gold .portal-ring.ring-1 { border-color: rgba(234, 179, 8, 0.6); }
        .portal-gold .portal-glow { background: radial-gradient(circle, rgba(234, 179, 8, 0.4) 0%, transparent 70%); }
        .portal-gold .portal-icon { color: #eab308; filter: drop-shadow(0 0 16px rgba(234, 179, 8, 0.6)); }

        .portal-danger .portal-ring.ring-1 { border-color: rgba(244, 63, 94, 0.6); }
        .portal-danger .portal-glow { background: radial-gradient(circle, rgba(244, 63, 94, 0.4) 0%, transparent 70%); }
        .portal-danger .portal-icon { color: #f43f5e; filter: drop-shadow(0 0 16px rgba(244, 63, 94, 0.6)); }

        .portal-purple .portal-ring.ring-1 { border-color: rgba(192, 132, 252, 0.6); }
        .portal-purple .portal-glow { background: radial-gradient(circle, rgba(192, 132, 252, 0.4) 0%, transparent 70%); }
        .portal-purple .portal-icon { color: #c084fc; filter: drop-shadow(0 0 16px rgba(192, 132, 252, 0.6)); }

        @keyframes spinRing {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes spinRingReverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }

        @keyframes floatPortalIcon {
            0% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-6px) scale(1.06); }
            100% { transform: translateY(4px) scale(0.97); }
        }

        @keyframes pulseGlow {
            0% { transform: scale(0.85); opacity: 0.4; }
            100% { transform: scale(1.15); opacity: 0.85; }
        }

        /* Error Status Badge */
        .error-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #93c5fd;
            padding: 6px 18px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin: 0 auto 1.25rem auto;
        }

        .error-title {
            font-size: clamp(1.6rem, 3.5vw, 2.25rem);
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 0.85rem;
            line-height: 1.25;
        }

        .error-description {
            font-size: clamp(0.95rem, 1.8vw, 1.05rem);
            color: #94a3b8;
            max-width: 580px;
            margin: 0 auto 2.2rem auto;
            line-height: 1.6;
        }

        /* Action Buttons */
        .error-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-action-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.85rem 1.85rem;
            border-radius: 12px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .btn-action-primary:hover {
            color: #ffffff;
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.5);
        }

        .btn-action-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.06);
            color: #cbd5e1;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.85rem 1.65rem;
            border-radius: 12px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-action-secondary:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
        }

        /* Bottom Footer */
        .error-footer {
            width: 100%;
            max-width: 1100px;
            padding: 1.25rem 0 0.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.82rem;
            color: #64748b;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            flex-shrink: 0;
        }

        .error-footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .error-footer a:hover {
            color: #38bdf8;
        }

        /* Responsive Breakpoints */
        @media (max-width: 767.98px) {
            .error-card {
                padding: 2.25rem 1.5rem;
                border-radius: 20px;
            }

            .error-header {
                justify-content: center;
                gap: 12px;
                flex-direction: column;
                text-align: center;
            }

            .error-footer {
                justify-content: center;
                text-align: center;
                flex-direction: column;
            }

            .btn-action-primary,
            .btn-action-secondary {
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Ambient Floating Orbs -->
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>

    <!-- Grid pattern overlay -->
    <div class="bg-grid"></div>

    <!-- Content Area -->
    <div class="error-wrapper">

        <!-- Top Bar -->
        <header class="error-header">
            <a href="{{ url('/') }}" class="error-brand" title="Sinar Meadow MPDR Portal">
                <img src="{{ asset('assets/images/logo/logohitam.png') }}" alt="Sinar Meadow">
            </a>

            <div class="system-badge">
                <span class="pulse-dot"></span>
                <span>MPDR Portal System</span>
            </div>
        </header>

        <!-- Centered Card Container -->
        <div class="error-center-container">
            <main class="error-card" id="tilt-card">
                @yield('content')
            </main>
        </div>

        <!-- Footer -->
        <footer class="error-footer">
            <div>
                &copy; {{ date('Y') }} PRE MPDR & MPDR | SINARMEADOW | MIS. All Rights Reserved.
            </div>
            <div style="display: flex; gap: 16px;">
                <a href="{{ route('dashboard') }}"><i class="ti ti-dashboard me-1"></i> Dashboard</a>
                <a href="javascript:location.reload()"><i class="ti ti-refresh me-1"></i> Refresh</a>
                <a href="mailto:mis@sinarmeadow.com"><i class="ti ti-headset me-1"></i> Support</a>
            </div>
        </footer>

    </div>

    <!-- Interactive Subtle Parallax Tilt Effect -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.getElementById('tilt-card');
            if (card && window.matchMedia('(hover: hover)').matches) {
                document.addEventListener('mousemove', (e) => {
                    const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
                    const yAxis = (window.innerHeight / 2 - e.pageY) / 50;
                    card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
                });
                document.addEventListener('mouseleave', () => {
                    card.style.transform = 'rotateY(0deg) rotateX(0deg)';
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
