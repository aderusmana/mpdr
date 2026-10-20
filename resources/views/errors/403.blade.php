@extends('errors.layout')

@section('title', '403 - Access Forbidden')

@section('content')
    <div class="error-badge-pill" style="background: rgba(234, 179, 8, 0.15); border-color: rgba(234, 179, 8, 0.3); color: #fde047;">
        <i class="ti ti-lock"></i> Error 403 &bull; Access Forbidden
    </div>

    <!-- Integrated Hero Centerpiece: 4 [Shield Lock Portal] 3 -->
    <div class="error-hero-digits">
        <span class="digit digit-gold">4</span>
        <div class="center-portal portal-gold">
            <div class="portal-ring ring-1"></div>
            <div class="portal-ring ring-2"></div>
            <div class="portal-glow"></div>
            <div class="portal-icon">
                <i class="ti ti-shield-lock"></i>
            </div>
        </div>
        <span class="digit digit-gold">3</span>
    </div>

    <h1 class="error-title">Access Forbidden</h1>

    <p class="error-description">
        {{ $exception && $exception->getMessage() ? $exception->getMessage() : 'You do not have the required permissions or authorization to access this page. Please contact your system administrator if you believe this is a mistake.' }}
    </p>

    <div class="error-actions">
        <a href="{{ route('dashboard') }}" class="btn-action-primary" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); box-shadow: 0 4px 15px rgba(217, 119, 6, 0.35);">
            <i class="ti ti-home"></i> Back to Dashboard
        </a>
        <button type="button" onclick="history.back()" class="btn-action-secondary">
            <i class="ti ti-arrow-left"></i> Go Back
        </button>
    </div>
@endsection
