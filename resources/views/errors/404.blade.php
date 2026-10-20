@extends('errors.layout')

@section('title', '404 - Page Not Found')

@section('content')
    <div class="error-badge-pill">
        <i class="ti ti-compass"></i> Error 404 &bull; Page Not Found
    </div>

    <!-- Integrated Hero Centerpiece: 4 [Compass Portal] 4 -->
    <div class="error-hero-digits">
        <span class="digit">4</span>
        <div class="center-portal">
            <div class="portal-ring ring-1"></div>
            <div class="portal-ring ring-2"></div>
            <div class="portal-glow"></div>
            <div class="portal-icon">
                <i class="ti ti-compass"></i>
            </div>
        </div>
        <span class="digit">4</span>
    </div>

    <h1 class="error-title">Page Not Found</h1>

    <p class="error-description">
        {{ $exception && $exception->getMessage() ? $exception->getMessage() : 'Sorry, the page or resource you are looking for does not exist, has been moved, or the link is incorrect.' }}
    </p>

    <div class="error-actions">
        <a href="{{ route('dashboard') }}" class="btn-action-primary">
            <i class="ti ti-home"></i> Back to Dashboard
        </a>
        <button type="button" onclick="history.back()" class="btn-action-secondary">
            <i class="ti ti-arrow-left"></i> Go Back
        </button>
    </div>
@endsection
