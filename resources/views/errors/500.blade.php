@extends('errors.layout')

@section('title', '500 - Internal Server Error')

@section('content')
    <div class="error-badge-pill" style="background: rgba(244, 63, 94, 0.15); border-color: rgba(244, 63, 94, 0.3); color: #fda4af;">
        <i class="ti ti-alert-triangle"></i> Error 500 &bull; Internal Server Error
    </div>

    <!-- Integrated Hero Centerpiece: 5 [Server Bolt Portal] 0 -->
    <div class="error-hero-digits">
        <span class="digit digit-danger">5</span>
        <div class="center-portal portal-danger">
            <div class="portal-ring ring-1"></div>
            <div class="portal-ring ring-2"></div>
            <div class="portal-glow"></div>
            <div class="portal-icon">
                <i class="ti ti-server-bolt"></i>
            </div>
        </div>
        <span class="digit digit-danger">0</span>
    </div>

    <h1 class="error-title">Internal Server Error</h1>

    <p class="error-description">
        {{ $exception && $exception->getMessage() ? $exception->getMessage() : 'An unexpected error occurred while processing your request on the server. Our technical team has been notified and is investigating the issue.' }}
    </p>

    <div class="error-actions">
        <a href="javascript:location.reload()" class="btn-action-primary" style="background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); box-shadow: 0 4px 15px rgba(225, 29, 72, 0.35);">
            <i class="ti ti-refresh"></i> Reload Page
        </a>
        <a href="{{ route('dashboard') }}" class="btn-action-secondary">
            <i class="ti ti-home"></i> Back to Dashboard
        </a>
    </div>
@endsection
