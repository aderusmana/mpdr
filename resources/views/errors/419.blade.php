@extends('errors.layout')

@section('title', '419 - Page Expired')

@section('content')
    <div class="error-badge-pill" style="background: rgba(168, 85, 247, 0.15); border-color: rgba(168, 85, 247, 0.3); color: #d8b4fe;">
        <i class="ti ti-clock-pause"></i> Error 419 &bull; Page Expired
    </div>

    <!-- Integrated Hero Centerpiece: 4 [Hourglass Portal] 9 -->
    <div class="error-hero-digits">
        <span class="digit digit-purple">4</span>
        <div class="center-portal portal-purple">
            <div class="portal-ring ring-1"></div>
            <div class="portal-ring ring-2"></div>
            <div class="portal-glow"></div>
            <div class="portal-icon">
                <i class="ti ti-hourglass-empty"></i>
            </div>
        </div>
        <span class="digit digit-purple">9</span>
    </div>

    <h1 class="error-title">Page Session Expired</h1>

    <p class="error-description">
        {{ $exception && $exception->getMessage() ? $exception->getMessage() : 'Your security token (CSRF token) or session has expired due to inactivity. Please reload the page to refresh your session.' }}
    </p>

    <div class="error-actions">
        <a href="javascript:location.reload()" class="btn-action-primary" style="background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); box-shadow: 0 4px 15px rgba(147, 51, 234, 0.35);">
            <i class="ti ti-reload"></i> Reload Page
        </a>
        <a href="{{ route('login') }}" class="btn-action-secondary">
            <i class="ti ti-login"></i> Login Page
        </a>
    </div>
@endsection
