<x-app-layout>
    {{-- Set Title --}}
    @section('title')
        Dashboard
    @endsection

    @push('css')
        <link href="{{ asset('assets/css/dashboard-premium.css') }}?v={{ file_exists(public_path('assets/css/dashboard-premium.css')) ? filemtime(public_path('assets/css/dashboard-premium.css')) : time() }}" rel="stylesheet" type="text/css">
    @endpush

    {{-- Hero Section --}}
    <div class="card dashboard-hero mb-4">
        <div class="card-body dashboard-hero-content p-4 p-lg-5">
            <div class="dashboard-eyebrow mb-2">Product Development Workspace</div>
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <h1 class="fw-bold mb-2 text-white">Product Development Dashboard</h1>
                    <p class="mb-0 text-white-50">Welcome back, {{ auth()->user()->name }}. Track and monitor the complete Pre-MPDR to MPDR product lifecycle in one unified portal.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="hero-glass-badge">
                        <span class="status-indicator-dot"></span>
                        <i class="ti ti-layout-dashboard me-1"></i> PRE-MPDR &bull; MPDR
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Choose a Workflow --}}
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="dashboard-section-title mb-1"><span class="dashboard-section-icon"><i class="ti ti-route"></i></span>Choose a Workflow</h4>
            <p class="text-muted mb-0 small">Manage early-stage concept development or submit and track a formal product request.</p>
        </div>
    </div>

    @php
        $canViewPreMpdr = auth()->user()->can('view pre');
        $canViewMpdr = auth()->user()->can('view mpdr');
    @endphp
    <div class="row g-3 mb-4">
        @can('view pre')
            <div class="col-12 {{ $canViewMpdr ? 'col-lg-6' : '' }}">
                <div class="workflow-card prempdr-card p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="workflow-icon bg-light-primary text-primary">
                            <i class="ti ti-bulb"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <h5 class="mb-0 fw-bold text-dark">Pre-MPDR</h5>
                                <span class="workflow-step step-prempdr">
                                    <i class="ti ti-sparkles"></i> 01 &bull; EARLY STAGE
                                </span>
                            </div>
                            <p class="text-muted mb-3 small">Plan product concepts, validate market requirements, and verify feasibility before submitting an MPDR.</p>
                            <a class="btn btn-workflow-action btn-outline-primary" href="{{ route('prempdr.index') }}">
                                Open Pre-MPDR <i class="ti ti-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('view mpdr')
            <div class="col-12 {{ $canViewPreMpdr ? 'col-lg-6' : '' }}">
                <div class="workflow-card mpdr-card p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="workflow-icon bg-light-info text-info">
                            <i class="ti ti-box"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <h5 class="mb-0 fw-bold text-dark">MPDR</h5>
                                <span class="workflow-step step-mpdr">
                                    <i class="ti ti-checkup-list"></i> 02 &bull; PRODUCT REQUEST
                                </span>
                            </div>
                            <p class="text-muted mb-3 small">Convert approved Pre-MPDRs into formal product requests and track multi-department approval stages.</p>
                            <a class="btn btn-workflow-action btn-outline-info" href="{{ route('mpdr.index') }}">
                                Open MPDR <i class="ti ti-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    {{-- Section: Workflow Overview --}}
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="dashboard-section-title mb-1"><span class="dashboard-section-icon icon-cyan"><i class="ti ti-chart-donut-3"></i></span>Workflow Overview</h4>
            <p class="text-muted mb-0 small">Real-time status metrics of requests under your review or authority.</p>
        </div>
    </div>

    {{-- Metric Overview Boxes --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card card-accent-primary">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="metric-icon bg-light-primary text-primary">
                        <i class="ti ti-bulb"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="metric-label mb-1">Pre-MPDR In Progress</div>
                        <div class="metric-value" id="preMpdrOutstanding">0</div>
                        <div class="metric-change">
                            <i class="ti ti-clock-hour-3 me-1 text-primary"></i>Drafts &amp; in approval
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card card-accent-info">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="metric-icon bg-light-info text-info">
                        <i class="ti ti-box"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="metric-label mb-1">MPDR In Progress</div>
                        <div class="metric-value" id="mpdrOutstanding">0</div>
                        <div class="metric-change">
                            <i class="ti ti-loader-2 me-1 text-info"></i>Awaiting approval
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card card-accent-warning">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="metric-icon bg-light-warning text-warning">
                        <i class="ti ti-user-check"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="metric-label mb-1">Awaiting My Approval</div>
                        <div class="metric-value" id="needsMyApproval">0</div>
                        <div class="metric-change text-warning fw-semibold">
                            <i class="ti ti-bell-ringing me-1"></i>Action required by you
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card card-accent-danger">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="metric-icon bg-light-danger text-danger">
                        <i class="ti ti-alert-triangle"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="metric-label mb-1">High-Priority Requests</div>
                        <div class="metric-value" id="highPriority">0</div>
                        <div class="metric-change">
                            <i class="ti ti-flame me-1 text-danger"></i>Priority 'A' in queue
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Conversion Pipeline Section --}}
    @if ($canViewPreMpdr && $canViewMpdr)
        <div class="mb-3 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="dashboard-section-title mb-1"><span class="dashboard-section-icon icon-teal"><i class="ti ti-arrows-transfer-up"></i></span>Pre-MPDR to MPDR Conversion</h4>
                <p class="text-muted mb-0 small">Conversion performance from concept approval stage into formal MPDR requests.</p>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card h-100 funnel-card card-accent-success">
                    <div class="card-body p-4">
                        <span class="funnel-step-badge">STEP 01</span>
                        <div class="funnel-icon bg-light-success text-success mb-3">
                            <i class="ti ti-circle-check"></i>
                        </div>
                        <div class="metric-label mb-1">Approved Pre-MPDRs</div>
                        <div class="funnel-value" id="approvedPreMpdr">0</div>
                        <small class="text-muted d-block mt-1">Successfully approved at initial development stage</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 funnel-card card-accent-info">
                    <div class="card-body p-4">
                        <span class="funnel-step-badge">STEP 02</span>
                        <div class="funnel-icon bg-light-info text-info mb-3">
                            <i class="ti ti-arrows-transfer-up"></i>
                        </div>
                        <div class="metric-label mb-1">Converted to MPDR</div>
                        <div class="funnel-value" id="convertedPreMpdr">0</div>
                        <small class="text-muted d-block mt-1">Approved Pre-MPDRs linked to an active MPDR</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 funnel-card card-accent-primary">
                    <div class="card-body p-4">
                        <span class="funnel-step-badge text-primary fw-bold" style="background: #eff6ff;">PIPELINE YIELD</span>
                        <div class="funnel-icon bg-light-primary text-primary mb-3">
                            <i class="ti ti-chart-pie"></i>
                        </div>
                        <div class="metric-label mb-1">Conversion Rate</div>
                        <div class="funnel-value"><span id="preMpdrConversionRate">0</span>%</div>
                        <div class="progress dashboard-progress mt-3">
                            <div class="progress-bar" id="preMpdrConversionProgress" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted d-block mt-2">Converted Pre-MPDRs divided by total approved</small>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Approval Queue Section --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <i class="ti ti-list-check text-primary me-2"></i>Approval Queue
                        </h5>
                        <small class="text-muted">Requests currently awaiting action, ordered by elapsed time</small>
                    </div>
                    <div id="staleApprovalContainer">
                        <span class="badge rounded-pill bg-light-secondary text-secondary border px-3 py-2">
                            <span id="staleApprovalCount">0</span> waiting 7+ days
                        </span>
                    </div>
                </div>
                <div class="card-body pt-0 px-4">
                    <div class="table-responsive">
                        <table class="table dashboard-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 130px;">Workflow</th>
                                    <th>Request</th>
                                    <th>Pending Approvers</th>
                                    <th class="text-end" style="width: 140px;">Waiting Time</th>
                                </tr>
                            </thead>
                            <tbody id="approvalQueueList">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                                            <span>Loading approval queue...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Chart and Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card h-100 dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3 px-4 flex-wrap gap-2">
                    <div>
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <i class="ti ti-activity-heartbeat text-primary me-2"></i>Request Activity
                        </h5>
                        <small class="text-muted">Monthly trend &amp; status breakdown</small>
                    </div>
                    <div class="d-flex align-items-center gap-2 dashboard-chart-filters">
                        <select class="form-select form-select-sm rounded-pill border-light-subtle shadow-sm px-3" id="chartDataType" style="width: auto;" aria-label="Select workflow">
                            @can('view pre')
                                <option value="prempdr" selected>Pre-MPDR</option>
                            @endcan
                            @can('view mpdr')
                                <option value="mpdr" @cannot('view pre') selected @endcannot>MPDR</option>
                            @endcan
                        </select>
                        <select class="form-select form-select-sm" id="yearFilterSelect" style="width: auto;" aria-label="Select year">
                            {{-- Years will be populated by JavaScript --}}
                        </select>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div id="monthlyRequisitionChart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            {{-- Summary Cards (Refined Color-Coded Style) --}}
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="card h-100 summary-metric-card summary-card-primary">
                        <div class="card-body p-3 p-xl-4 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="summary-icon">
                                    <i class="ti ti-file-plus"></i>
                                </div>
                                <span class="summary-period-badge font-monospace">Year <span class="summaryPeriod"></span></span>
                            </div>
                            <div>
                                <div class="summary-label mb-1">Total Requests</div>
                                <div class="summary-count mb-1" id="summaryCreated">0</div>
                                <small class="summary-subtext">Created in year</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card h-100 summary-metric-card summary-card-success">
                        <div class="card-body p-3 p-xl-4 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="summary-icon">
                                    <i class="ti ti-check"></i>
                                </div>
                                <span class="summary-period-badge font-monospace">Year <span class="summaryPeriod"></span></span>
                            </div>
                            <div>
                                <div class="summary-label mb-1">Approved</div>
                                <div class="summary-count mb-1" id="summaryApproved">0</div>
                                <small class="summary-subtext">Successfully approved</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card h-100 summary-metric-card summary-card-warning">
                        <div class="card-body p-3 p-xl-4 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="summary-icon">
                                    <i class="ti ti-clock"></i>
                                </div>
                                <span class="summary-period-badge font-monospace">Year <span class="summaryPeriod"></span></span>
                            </div>
                            <div>
                                <div class="summary-label mb-1">Pending Approval</div>
                                <div class="summary-count mb-1" id="summaryPending">0</div>
                                <small class="summary-subtext">Currently in review</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card h-100 summary-metric-card summary-card-danger">
                        <div class="card-body p-3 p-xl-4 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="summary-icon">
                                    <i class="ti ti-x"></i>
                                </div>
                                <span class="summary-period-badge font-monospace">Year <span class="summaryPeriod"></span></span>
                            </div>
                            <div>
                                <div class="summary-label mb-1">Rejected</div>
                                <div class="summary-count mb-1" id="summaryRejected">0</div>
                                <small class="summary-subtext">Declined requests</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top 5 Ranked Lists --}}
    {{-- <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card h-100 dashboard-card">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <i class="ti ti-package text-info me-2"></i><span id="topProductsTitle">Top 5 Products in Progress</span>
                        </h5>
                        <small class="text-muted">Highest approval progress</small>
                    </div>
                </div>
                <div class="card-body pt-0 px-4" style="max-height: 320px; overflow-y: auto;">
                    <ul class="list-unstyled mb-0" id="topProductsList">
                        <li class="text-muted text-center py-4">
                            <span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span>Loading products...
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 dashboard-card">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <i class="ti ti-users text-warning me-2"></i><span id="topRequestersTitle">Top 5 Requesters</span>
                        </h5>
                        <small class="text-muted">Most active initiators this year</small>
                    </div>
                </div>
                <div class="card-body pt-0 px-4" style="max-height: 320px; overflow-y: auto;">
                    <ul class="list-unstyled mb-0" id="topRequestersList">
                        <li class="text-muted text-center py-4">
                            <span class="spinner-border spinner-border-sm text-warning me-2" role="status"></span>Loading requesters...
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- Error Banner --}}
    {{-- <div class="alert alert-danger d-none shadow-sm rounded-4 border-0 mb-4" id="dashboardError" role="alert">
        <div class="d-flex align-items-center">
            <i class="ti ti-alert-circle fs-3 me-3 text-danger"></i>
            <div>
                <strong>Failed to load dashboard data.</strong>
                <div class="small">Please check your network connection or reload the page.</div>
            </div>
        </div>
    </div> --}}

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts" defer></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const yearFilter = document.getElementById('yearFilterSelect');
                const chartDataType = document.getElementById('chartDataType');
                const dashboardError = document.getElementById('dashboardError');
                const defaultSeries = {
                    created: Array(12).fill(0),
                    approved: Array(12).fill(0),
                    pending: Array(12).fill(0),
                    rejected: Array(12).fill(0)
                };
                let currentChart;
                let dashboardData;

                const getChartOptions = (data) => ({
                    series: [
                        {
                            name: 'Created',
                            type: 'column',
                            data: data.created,
                            color: '#3b82f6'
                        },
                        {
                            name: 'Approved',
                            type: 'line',
                            data: data.approved,
                            color: '#10b981'
                        },
                        {
                            name: 'Pending',
                            type: 'line',
                            data: data.pending,
                            color: '#f59e0b'
                        },
                        {
                            name: 'Rejected',
                            type: 'line',
                            data: data.rejected,
                            color: '#ef4444'
                        }
                    ],
                    chart: {
                        height: 350,
                        type: 'line',
                        stacked: false,
                        fontFamily: "'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
                        toolbar: {
                            show: true,
                            tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false }
                        },
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 0,
                            blur: 4,
                            opacity: 0.05
                        }
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '40%',
                            borderRadius: 6,
                            borderRadiusApplication: 'end'
                        }
                    },
                    stroke: {
                        width: [0, 3, 3, 3],
                        curve: 'smooth',
                        dashArray: [0, 0, 4, 4]
                    },
                    markers: {
                        size: [0, 4, 4, 4],
                        strokeWidth: 2,
                        hover: { size: 6 }
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        axisBorder: { show: true, color: '#cbd5e1' },
                        axisTicks: { show: true, color: '#cbd5e1' },
                        labels: {
                            style: { colors: '#64748b', fontSize: '12px', fontWeight: 600 }
                        }
                    },
                    yaxis: {
                        decimalsInFloat: 0,
                        forceNiceScale: true,
                        min: 0,
                        title: {
                            text: 'Number of Requests',
                            style: { color: '#64748b', fontWeight: 600, fontSize: '12px' }
                        },
                        labels: {
                            style: { colors: '#64748b', fontSize: '12px', fontWeight: 500 },
                            formatter: (val) => Math.round(val)
                        }
                    },
                    grid: {
                        show: true,
                        borderColor: '#e2e8f0',
                        strokeDashArray: 4,
                        position: 'back',
                        xaxis: {
                            lines: {
                                show: true
                            }
                        },
                        yaxis: {
                            lines: {
                                show: true
                            }
                        },
                        row: {
                            colors: ['rgba(248, 250, 252, 0.75)', 'transparent'],
                            opacity: 0.7
                        },
                        padding: { top: 15, right: 20, bottom: 5, left: 20 }
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        theme: 'light',
                        style: { fontSize: '12px' },
                        y: {
                            formatter: val => `${val} requests`
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '13px',
                        fontWeight: 600,
                        markers: { radius: 12 }
                    },
                    dataLabels: { enabled: false }
                });

                function showEmptyState(list, message, iconClass = 'ti ti-inbox') {
                    if (!list) {
                        return;
                    }

                    const item = document.createElement('li');
                    item.className = 'text-center py-5 px-2';
                    item.innerHTML = `
                        <div class="mb-2">
                            <span class="d-inline-flex p-3 rounded-circle bg-light text-muted">
                                <i class="${iconClass} fs-3"></i>
                            </span>
                        </div>
                        <p class="text-muted mb-0 small fw-medium">${message}</p>
                    `;
                    list.replaceChildren(item);
                }

                function renderApprovalQueue(data) {
                    const queue = document.getElementById('approvalQueueList');
                    const items = data.approvalQueue ?? [];
                    const staleCount = Number(data.metrics?.staleApprovals) || 0;

                    const staleContainer = document.getElementById('staleApprovalContainer');
                    if (staleContainer) {
                        if (staleCount > 0) {
                            staleContainer.innerHTML = `
                                <span class="badge rounded-pill bg-light-danger text-danger border border-danger-subtle px-3 py-2">
                                    <span class="badge-dot-pulse bg-danger me-1"></span>
                                    <span id="staleApprovalCount">${staleCount}</span> waiting 7+ days
                                </span>
                            `;
                        } else {
                            staleContainer.innerHTML = `
                                <span class="badge rounded-pill bg-light-success text-success border border-success-subtle px-3 py-2">
                                    <i class="ti ti-circle-check me-1"></i> All requests up-to-date
                                </span>
                            `;
                        }
                    }

                    if (!items.length) {
                        const row = document.createElement('tr');
                        const cell = document.createElement('td');
                        cell.colSpan = 4;
                        cell.className = 'text-center text-muted py-5';
                        cell.innerHTML = `
                            <div class="py-3">
                                <span class="d-inline-flex p-3 rounded-circle bg-light-success text-success mb-2">
                                    <i class="ti ti-circle-check fs-2"></i>
                                </span>
                                <div class="fw-bold text-dark fs-6">No Pending Approvals</div>
                                <small class="text-muted">There are currently no requests awaiting action.</small>
                            </div>
                        `;
                        row.append(cell);
                        queue.replaceChildren(row);
                        return;
                    }

                    queue.replaceChildren(...items.map(item => {
                        const row = document.createElement('tr');

                        // 1. Workflow
                        const workflow = document.createElement('td');
                        const isPreMpdr = item.workflow === 'Pre-MPDR';
                        const badge = document.createElement('span');
                        badge.className = `badge rounded-pill ${isPreMpdr ? 'bg-light-primary text-primary border border-primary-subtle' : 'bg-light-info text-info border border-info-subtle'} px-2.5 py-1.5`;
                        badge.innerHTML = `<i class="${isPreMpdr ? 'ti ti-bulb' : 'ti ti-box'} me-1"></i>${item.workflow}`;
                        workflow.append(badge);

                        // 2. Request
                        const request = document.createElement('td');
                        const requestName = document.createElement('div');
                        requestName.className = 'fw-bold text-dark text-truncate';
                        requestName.style.maxWidth = '300px';
                        requestName.textContent = item.project_name || 'Untitled request';
                        const requestNumber = document.createElement('span');
                        requestNumber.className = 'badge bg-light text-secondary font-monospace mt-1';
                        requestNumber.textContent = item.form_no;
                        request.append(requestName, requestNumber);

                        // 3. Approvers
                        const approver = document.createElement('td');
                        if (Array.isArray(item.approver_positions) && item.approver_positions.length) {
                            const positionList = document.createElement('div');
                            positionList.className = 'approval-position-list';
                            item.approver_positions.forEach(position => {
                                const positionChip = document.createElement('span');
                                positionChip.className = 'approval-position-chip';
                                const icon = document.createElement('i');
                                icon.className = 'ti ti-briefcase'; // Fixed from ti-briefcase-2
                                positionChip.append(icon, document.createTextNode(position));
                                positionList.append(positionChip);
                            });
                            approver.append(positionList);
                        } else {
                            const stageChip = document.createElement('span');
                            stageChip.className = 'approval-position-chip stage-chip';
                            const icon = document.createElement('i');
                            icon.className = 'ti ti-route';
                            stageChip.append(icon, document.createTextNode(item.stage || 'Approval in progress'));
                            approver.append(stageChip);
                        }

                        // 4. Age
                        const age = document.createElement('td');
                        age.className = 'queue-age text-end';
                        const ageBadge = document.createElement('span');
                        const daysSinceUpdate = Number(item.days_since_update) || 0;
                        const dayLabel = `${daysSinceUpdate} ${daysSinceUpdate === 1 ? 'day' : 'days'}`;

                        if (daysSinceUpdate >= 7) {
                            ageBadge.className = 'badge rounded-pill bg-light-danger text-danger border border-danger-subtle px-2.5 py-1.5';
                            ageBadge.innerHTML = `<i class="ti ti-alert-triangle me-1"></i>${dayLabel}`;
                        } else if (daysSinceUpdate >= 3) {
                            ageBadge.className = 'badge rounded-pill bg-light-warning text-warning border border-warning-subtle px-2.5 py-1.5';
                            ageBadge.innerHTML = `<i class="ti ti-clock me-1"></i>${dayLabel}`;
                        } else {
                            ageBadge.className = 'badge rounded-pill bg-light-success text-success border border-success-subtle px-2.5 py-1.5';
                            ageBadge.innerHTML = `<i class="ti ti-check me-1"></i>${dayLabel}`;
                        }
                        age.append(ageBadge);

                        row.append(workflow, request, approver, age);
                        return row;
                    }));
                }

                function makeProgressItem({ rank, title, subtitle, count, total, colorClass, label }) {
                    const item = document.createElement('li');
                    item.className = 'top-list-item';

                    const rankBadge = document.createElement('span');
                    const rankNum = rank + 1;
                    rankBadge.className = `top-rank-badge ${rankNum === 1 ? 'top-rank-1' : (rankNum === 2 ? 'top-rank-2' : (rankNum === 3 ? 'top-rank-3' : 'top-rank-other'))}`;
                    rankBadge.textContent = `#${rankNum}`;

                    const details = document.createElement('div');
                    details.className = 'flex-grow-1 min-w-0';

                    const name = document.createElement('div');
                    name.className = 'top-item-title text-truncate';
                    name.textContent = title || 'Unknown';
                    details.append(name);

                    if (subtitle) {
                        const caption = document.createElement('small');
                        caption.className = 'top-item-sub text-truncate d-block';
                        caption.textContent = subtitle;
                        details.append(caption);
                    }

                    const progress = document.createElement('div');
                    progress.className = 'top-item-progress';
                    const bar = document.createElement('div');
                    bar.className = `progress-bar ${colorClass}`;
                    const percentage = total > 0 ? Math.min(100, Math.round((count / total) * 100)) : 0;
                    bar.style.width = `${percentage}%`;
                    bar.setAttribute('role', 'progressbar');
                    bar.setAttribute('aria-valuenow', percentage);
                    bar.setAttribute('aria-valuemin', 0);
                    bar.setAttribute('aria-valuemax', 100);
                    progress.append(bar);
                    details.append(progress);

                    const value = document.createElement('div');
                    value.className = 'ms-3 text-end flex-shrink-0';
                    const number = document.createElement('div');
                    number.className = 'top-item-ratio';
                    number.textContent = `${count}/${total}`;
                    const caption = document.createElement('div');
                    caption.className = 'top-item-label';
                    caption.textContent = label;
                    value.append(number, caption);

                    item.append(rankBadge, details, value);
                    return item;
                }

                function renderTopLists(data) {
                    const type = chartDataType.value;
                    const label = type === 'prempdr' ? 'Pre-MPDR' : 'MPDR';
                    const topData = data.top5?.[type] ?? { products: [], requesters: { list: [], max_count: 0 } };
                    const topProductsList = document.getElementById('topProductsList');
                    const topRequestersList = document.getElementById('topRequestersList');
                    const topProductsTitle = document.getElementById('topProductsTitle');
                    const topRequestersTitle = document.getElementById('topRequestersTitle');

                    if (topProductsTitle) {
                        topProductsTitle.textContent = `Top 5 ${label} Products in Progress`;
                    }
                    if (topRequestersTitle) {
                        topRequestersTitle.textContent = `Top 5 ${label} Requesters`;
                    }

                    if (topProductsList) {
                        if (topData.products && topData.products.length) {
                            topProductsList.replaceChildren(...topData.products.map((item, index) => makeProgressItem({
                                rank: index,
                                title: item.product_name,
                                subtitle: `No: ${item.no}`,
                                count: item.approved_count,
                                total: item.total_approvers,
                                colorClass: 'bg-primary',
                                label: 'approved'
                            })));
                        } else {
                            showEmptyState(topProductsList, 'There are no active requests in progress.', 'ti ti-box');
                        }
                    }

                    const requesters = topData.requesters?.list ?? [];
                    if (topRequestersList) {
                        if (requesters.length) {
                            topRequestersList.replaceChildren(...requesters.map((item, index) => makeProgressItem({
                                rank: index,
                                title: item.user_name,
                                subtitle: item.department || 'No department',
                                count: item.forms_count,
                                total: topData.requesters.max_count || item.forms_count,
                                colorClass: 'bg-warning',
                                label: 'requests'
                            })));
                        } else {
                            showEmptyState(topRequestersList, 'There is no requester data for this period.', 'ti ti-users');
                        }
                    }
                }

                function updateDashboard() {
                    const data = dashboardData;
                    document.getElementById('preMpdrOutstanding').textContent = data.metrics.preMpdrOutstanding;
                    document.getElementById('mpdrOutstanding').textContent = data.metrics.mpdrOutstanding;
                    document.getElementById('needsMyApproval').textContent = data.metrics.needsMyApproval;
                    document.getElementById('highPriority').textContent = data.metrics.highPriority;

                    const approvedPreMpdr = document.getElementById('approvedPreMpdr');
                    if (approvedPreMpdr) {
                        approvedPreMpdr.textContent = data.metrics.approvedPreMpdr;
                        document.getElementById('convertedPreMpdr').textContent = data.metrics.convertedPreMpdr;
                        document.getElementById('preMpdrConversionRate').textContent = data.metrics.preMpdrConversionRate;
                        const conversionProgress = document.getElementById('preMpdrConversionProgress');
                        conversionProgress.style.width = `${data.metrics.preMpdrConversionRate}%`;
                        conversionProgress.setAttribute('aria-valuenow', data.metrics.preMpdrConversionRate);
                    }

                    const chartCollection = data.chart?.[chartDataType.value] ?? {};
                    const chartData = chartCollection[yearFilter.value] ?? defaultSeries;
                    if (currentChart) {
                        currentChart.updateOptions(getChartOptions(chartData));
                    } else {
                        currentChart = new ApexCharts(
                            document.querySelector('#monthlyRequisitionChart'),
                            getChartOptions(chartData)
                        );
                        currentChart.render();
                    }

                    const sum = values => values.reduce((total, value) => total + value, 0);
                    document.getElementById('summaryCreated').textContent = sum(chartData.created);
                    document.getElementById('summaryApproved').textContent = sum(chartData.approved);
                    document.getElementById('summaryPending').textContent = sum(chartData.pending);
                    document.getElementById('summaryRejected').textContent = sum(chartData.rejected);
                    document.querySelectorAll('.summaryPeriod').forEach(element => {
                        element.textContent = yearFilter.value;
                    });

                    renderTopLists(data);
                    renderApprovalQueue(data);
                }

                function populateYears(data) {
                    const years = [...new Set(Object.values(data.chart ?? {}).flatMap(values => Object.keys(values)))]
                        .sort((first, second) => Number(second) - Number(first));
                    if (!years.length) {
                        years.push(String(new Date().getFullYear()));
                    }

                    yearFilter.replaceChildren(...years.map(year => new Option(`Year ${year}`, year)));
                }

                function loadDashboard() {
                    fetch('{{ route("dashboard.mpdr.data") }}', { headers: { Accept: 'application/json' } })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Dashboard request failed with status ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            dashboardData = data;
                            populateYears(data);
                            dashboardError?.classList.add('d-none');
                            updateDashboard();
                        })
                        .catch(error => {
                            console.error('Error fetching dashboard data:', error);
                            dashboardError?.classList.remove('d-none');
                            showEmptyState(document.getElementById('topProductsList'), 'Product data is temporarily unavailable.', 'ti ti-alert-circle');
                            showEmptyState(document.getElementById('topRequestersList'), 'Requester data is temporarily unavailable.', 'ti ti-alert-circle');
                            const queue = document.getElementById('approvalQueueList');
                            if (!queue) {
                                return;
                            }

                            const row = document.createElement('tr');
                            const cell = document.createElement('td');
                            cell.colSpan = 4;
                            cell.className = 'text-center text-muted py-5';
                            cell.innerHTML = `
                                <div class="py-3">
                                    <span class="d-inline-flex p-3 rounded-circle bg-light-danger text-danger mb-2">
                                        <i class="ti ti-alert-triangle fs-2"></i>
                                    </span>
                                    <div class="fw-bold text-dark fs-6">Data Unavailable</div>
                                    <small class="text-muted">Unable to load approval queue data at this time.</small>
                                </div>
                            `;
                            row.append(cell);
                            queue.replaceChildren(row);
                        });
                }

                yearFilter.addEventListener('change', () => dashboardData && updateDashboard());
                chartDataType.addEventListener('change', () => dashboardData && updateDashboard());

                loadDashboard();
            });
        </script>
    @endpush
</x-app-layout>
