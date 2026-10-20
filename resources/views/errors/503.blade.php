@extends('errors.layout')

@section('title', '503 - Service Unavailable')

@section('content')
    <div class="error-badge-pill" style="background: rgba(234, 179, 8, 0.15); border-color: rgba(234, 179, 8, 0.3); color: #fde047;">
        <i class="ti ti-settings-cog"></i> Error 503 &bull; Maintenance Mode
    </div>

    <!-- Integrated Hero Centerpiece: 5 [Maintenance Portal] 3 -->
    <div class="error-hero-digits">
        <span class="digit digit-gold">5</span>
        <div class="center-portal portal-gold">
            <div class="portal-ring ring-1"></div>
            <div class="portal-ring ring-2"></div>
            <div class="portal-glow"></div>
            <div class="portal-icon">
                <i class="ti ti-tool"></i>
            </div>
        </div>
        <span class="digit digit-gold">3</span>
    </div>

    <h1 class="error-title">System Under Maintenance</h1>

    <p class="error-description">
        {{ $exception && $exception->getMessage() ? $exception->getMessage() : 'The MPDR Portal is currently undergoing scheduled maintenance to improve system stability and performance. We will be back shortly. Thank you for your patience.' }}
    </p>

    <div class="error-actions">
        <a href="javascript:location.reload()" class="btn-action-primary" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); box-shadow: 0 4px 15px rgba(217, 119, 6, 0.35);">
            <i class="ti ti-refresh"></i> Check Availability
        </a>
    </div>
@endsection
