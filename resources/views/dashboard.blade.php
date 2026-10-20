<x-app-layout>
    {{-- Set Title --}}
    @section('title')
        Dashboard
    @endsection

    @push('css')
        <style>
            .dashboard-hero {
                position: relative;
                overflow: hidden;
                border: 0;
                border-radius: 20px;
                background: linear-gradient(120deg, #172554 0%, #273c9b 56%, #5264df 100%);
                color: #fff;
                box-shadow: 0 16px 36px rgba(39, 60, 155, .18);
            }
            .dashboard-hero::after {
                position: absolute;
                top: -110px;
                right: -50px;
                width: 330px;
                height: 330px;
                border: 1px solid rgba(255, 255, 255, .14);
                border-radius: 50%;
                box-shadow: 0 0 0 36px rgba(255, 255, 255, .04), 0 0 0 72px rgba(255, 255, 255, .03);
                content: "";
                pointer-events: none;
            }
            .dashboard-hero-content { position: relative; z-index: 1; }
            .dashboard-eyebrow {
                color: rgba(255, 255, 255, .72);
                font-size: .72rem;
                font-weight: 700;
                letter-spacing: .14em;
                text-transform: uppercase;
            }
            .workflow-card {
                height: 100%;
                border: 1px solid #e9edf5;
                border-radius: 16px;
                background: #fff;
                box-shadow: 0 5px 18px rgba(25, 42, 89, .04);
                transition: transform .2s ease, box-shadow .2s ease;
            }
            .workflow-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 24px rgba(25, 42, 89, .09);
            }
            .workflow-icon {
                display: inline-flex;
                width: 48px;
                height: 48px;
                align-items: center;
                justify-content: center;
                border-radius: 14px;
                font-size: 1.45rem;
            }
            .workflow-step {
                display: inline-block;
                padding: .3rem .6rem;
                border-radius: 20px;
                background: #f1f3fb;
                color: #5b6592;
                font-size: .72rem;
                font-weight: 700;
            }
            .dashboard-section-title { color: #1e293b; font-weight: 700; }
            .dashboard-card {
                height: 100%;
                border: 1px solid #edf0f5;
                border-radius: 16px;
                box-shadow: 0 5px 18px rgba(25, 42, 89, .04);
            }
            .dashboard-card .card-header { border-radius: 16px 16px 0 0; }
            .funnel-value { color: #1e293b; font-size: 1.7rem; font-weight: 700; }
            .queue-age { min-width: 92px; text-align: right; }
            @media (max-width: 575.98px) {
                .dashboard-hero { border-radius: 16px; }
                .dashboard-hero h1 { font-size: 1.55rem; }
                .dashboard-chart-filters { width: 100%; }
                .dashboard-chart-filters .form-select { flex: 1; }
            }
        </style>
    @endpush

    <div class="card dashboard-hero mb-4">
        <div class="card-body dashboard-hero-content p-4 p-lg-5">
            <div class="dashboard-eyebrow mb-2">Product development workspace</div>
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <h1 class="fw-bold mb-2 text-white">Product Development Dashboard</h1>
                    <p class="mb-0 text-white-50">Welcome back, {{ auth()->user()->name }}. Track the complete Pre-MPDR to MPDR workflow in one place.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <span class="badge rounded-pill bg-white text-primary px-3 py-2">
                        <i class="ti ti-layout-dashboard me-1"></i> PRE-MPDR + MPDR
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <h4 class="dashboard-section-title mb-1">Choose a Workflow</h4>
        <p class="text-muted mb-0">Manage early-stage development or continue with a product request.</p>
    </div>

    @php
        $canViewPreMpdr = auth()->user()->can('view pre');
        $canViewMpdr = auth()->user()->can('view mpdr');
    @endphp
    <div class="row g-3 mb-4">
        @can('view pre')
            <div class="col-12 {{ $canViewMpdr ? 'col-lg-6' : '' }}">
                <div class="workflow-card p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="workflow-icon bg-light-primary text-primary"><i class="ti ti-bulb"></i></div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <h5 class="mb-0 fw-bold">Pre-MPDR</h5>
                                <span class="workflow-step">01 - EARLY STAGE</span>
                            </div>
                            <p class="text-muted mb-3">Plan the product concept, market needs, and feasibility before submitting an MPDR request.</p>
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('prempdr.index') }}">
                                Open Pre-MPDR <i class="ti ti-arrow-up-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('view mpdr')
            <div class="col-12 {{ $canViewPreMpdr ? 'col-lg-6' : '' }}">
                <div class="workflow-card p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="workflow-icon bg-light-info text-info"><i class="ti ti-box"></i></div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <h5 class="mb-0 fw-bold">MPDR</h5>
                                <span class="workflow-step">02 - PRODUCT REQUEST</span>
                            </div>
                            <p class="text-muted mb-3">Turn an approved Pre-MPDR into a product request and track its approval progress.</p>
                            <a class="btn btn-outline-info btn-sm" href="{{ route('mpdr.index') }}">
                                Open MPDR <i class="ti ti-arrow-up-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    <div class="mb-3">
        <h4 class="dashboard-section-title mb-1">Workflow Overview</h4>
        <p class="text-muted mb-0">A summary of requests you can access.</p>
    </div>

    {{-- Metric Boxes --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="h-100 card dashboard-card hover-effect b-t-4-primary animate__animated animate__fadeInUp">
                <div class="card-body d-flex align-items-center">
                    <div class="metric-icon bg-light-primary text-primary"><i class="ti ti-lightbulb"></i></div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small mb-1">Pre-MPDR In Progress</div>
                        <div class="metric-value" id="preMpdrOutstanding">0</div>
                        <div class="metric-change text-muted small">Drafts and awaiting approval</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card hover-effect b-t-4-info animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="card-body d-flex align-items-center">
                    <div class="metric-icon bg-light-info text-info"><i class="ti ti-box"></i></div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small mb-1">MPDR In Progress</div>
                        <div class="metric-value" id="mpdrOutstanding">0</div>
                        <div class="metric-change text-muted small">Awaiting approval</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card hover-effect b-t-4-warning animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="card-body d-flex align-items-center">
                    <div class="metric-icon bg-light-warning text-warning"><i class="ti ti-user-check"></i></div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small mb-1">Awaiting My Approval</div>
                        <div class="metric-value" id="needsMyApproval">0</div>
                        <div class="metric-change text-warning small">Requests awaiting your action</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card hover-effect b-t-4-danger animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="card-body d-flex align-items-center">
                    <div class="metric-icon bg-light-danger text-danger"><i class="ti ti-alert-triangle"></i></div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small mb-1">High-Priority Requests</div>
                        <div class="metric-value" id="highPriority">0</div>
                        <div class="metric-change text-muted small">High-priority MPDRs awaiting approval</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($canViewPreMpdr && $canViewMpdr)
        <div class="mb-3">
            <h4 class="dashboard-section-title mb-1">Pre-MPDR to MPDR Conversion</h4>
            <p class="text-muted mb-0">Track approved Pre-MPDRs as they move into the MPDR workflow.</p>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Approved Pre-MPDRs</div>
                        <div class="funnel-value" id="approvedPreMpdr">0</div>
                        <small class="text-muted">Approved at the initial development stage</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Converted to MPDR</div>
                        <div class="funnel-value" id="convertedPreMpdr">0</div>
                        <small class="text-muted">Approved Pre-MPDRs linked to an MPDR</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Conversion Rate</div>
                        <div class="funnel-value"><span id="preMpdrConversionRate">0</span>%</div>
                        <div class="progress mt-2" style="height: 7px;">
                            <div class="progress-bar bg-primary" id="preMpdrConversionProgress" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">Converted Pre-MPDRs divided by approved Pre-MPDRs</small>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Approval Queue</h5>
                        <small class="text-muted">Requests are ordered by time since their last update.</small>
                    </div>
                    <span class="badge rounded-pill bg-light-danger text-danger">
                        <span id="staleApprovalCount">0</span> waiting 7+ days
                    </span>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Workflow</th>
                                    <th>Request</th>
                                    <th>Current Approver</th>
                                    <th class="text-end">Days Since Update</th>
                                </tr>
                            </thead>
                            <tbody id="approvalQueueList">
                                <tr><td colspan="4" class="text-center text-muted py-4">Loading approval queue...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Chart and Summary --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card h-100 dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
                    <div>
                        <h5 class="mb-1 card-title">Request Activity</h5>
                        <small class="text-muted">Monthly status breakdown</small>
                    </div>
                    <div class="d-flex align-items-center gap-2 dashboard-chart-filters">
                        <select class="form-select form-select-sm" id="chartDataType" style="width: auto;" aria-label="Select workflow">
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
                <div class="card-body">
                    <div id="monthlyRequisitionChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            {{-- Summary Cards --}}
             <div class="row g-3">
                <div class="col-sm-6">
                    <div class="card h-100 ticket-card bg-light-primary">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex-center bg-white mb-3" style="width: 50px; height: 50px; border-radius: 15px; flex-shrink: 0;">
                                <i class="ti ti-file-plus fs-3 text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="fs-6 text-muted mb-1">Total Requests</p>
                                <h3 class="text-primary-dark mb-0" id="summaryCreated">0</h3>
                                <small class="text-muted">Year <span class="summaryPeriod"></span></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card h-100 ticket-card bg-light-success">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex-center bg-white mb-3" style="width: 50px; height: 50px; border-radius: 15px; flex-shrink: 0;">
                                <i class="ti ti-check fs-3 text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="fs-6 text-muted mb-1">Approved</p>
                                <h3 class="text-success-dark mb-0" id="summaryApproved">0</h3>
                                <small class="text-muted">Year <span class="summaryPeriod"></span></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card h-100 ticket-card bg-light-warning">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex-center bg-white mb-3" style="width: 50px; height: 50px; border-radius: 15px; flex-shrink: 0;">
                                <i class="ti ti-clock fs-3 text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="fs-6 text-muted mb-1">Pending Approval</p>
                                <h3 class="text-warning-dark mb-0" id="summaryPending">0</h3>
                                <small class="text-muted">Year <span class="summaryPeriod"></span></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card h-100 ticket-card bg-light-danger">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex-center bg-white mb-3" style="width: 50px; height: 50px; border-radius: 15px; flex-shrink: 0;">
                                <i class="ti ti-x fs-3 text-danger"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="fs-6 text-muted mb-1">Rejected</p>
                                <h3 class="text-danger-dark mb-0" id="summaryRejected">0</h3>
                                <small class="text-muted">Year <span class="summaryPeriod"></span></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top 5 Lists --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card h-100 dashboard-card">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0" id="topProductsTitle">Top 5 Pre-MPDR Products in Progress</h5>
                </div>
                <div class="card-body pt-0 simplebar-scroll" style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group list-group-flush" id="topProductsList">
                        {{-- Content will be populated by JavaScript --}}
                        <li class="list-group-item text-muted text-center py-4">Loading data...</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 dashboard-card">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0" id="topRequestersTitle">Top 5 Pre-MPDR Requesters</h5>
                </div>
                <div class="card-body pt-0 simplebar-scroll" style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group list-group-flush" id="topRequestersList">
                        {{-- Content will be populated by JavaScript --}}
                         <li class="list-group-item text-muted text-center py-4">Loading data...</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-danger d-none" id="dashboardError" role="alert">
        Dashboard data could not be loaded. Refresh the page or try again later.
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
                    series: [{
                            name: 'Created',
                            type: 'column',
                            data: data.created,
                            color: '#0d6efd'
                        },
                        {
                            name: 'Approved',
                            type: 'line',
                            data: data.approved,
                            color: '#198754'
                        },
                        {
                            name: 'Pending',
                            type: 'line',
                            data: data.pending,
                            color: '#ffc107'
                        },
                        {
                            name: 'Rejected',
                            type: 'line',
                            data: data.rejected,
                            color: '#dc3545'
                        }
                    ],
                    chart: {
                        height: 350,
                        type: 'line',
                        stacked: false,
                        toolbar: { show: true }
                    },
                    stroke: {
                        width: [0, 3, 3, 3],
                        curve: 'smooth',
                        dashArray: [0, 0, 5, 5]
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
                    },
                    yaxis: { title: { text: 'Jumlah Form' } },
                    tooltip: { shared: true, intersect: false },
                    legend: { position: 'top', horizontalAlign: 'center' },
                    dataLabels: { enabled: false }
                });

                function showEmptyState(list, message) {
                    const item = document.createElement('li');
                    item.className = 'list-group-item text-center text-muted py-4';
                    item.textContent = message;
                    list.replaceChildren(item);
                }

                function renderApprovalQueue(data) {
                    const queue = document.getElementById('approvalQueueList');
                    const items = data.approvalQueue ?? [];
                    document.getElementById('staleApprovalCount').textContent = data.metrics.staleApprovals;

                    if (!items.length) {
                        const row = document.createElement('tr');
                        const cell = document.createElement('td');
                        cell.colSpan = 4;
                        cell.className = 'text-center text-muted py-4';
                        cell.textContent = 'There are no requests awaiting approval.';
                        row.append(cell);
                        queue.replaceChildren(row);
                        return;
                    }

                    queue.replaceChildren(...items.map(item => {
                        const row = document.createElement('tr');
                        const workflow = document.createElement('td');
                        const badge = document.createElement('span');
                        badge.className = `badge ${item.workflow === 'Pre-MPDR' ? 'bg-light-primary text-primary' : 'bg-light-info text-info'}`;
                        badge.textContent = item.workflow;
                        workflow.append(badge);

                        const request = document.createElement('td');
                        const requestName = document.createElement('div');
                        requestName.className = 'fw-semibold';
                        requestName.textContent = item.project_name || 'Untitled request';
                        const requestNumber = document.createElement('small');
                        requestNumber.className = 'text-muted';
                        requestNumber.textContent = item.form_no;
                        request.append(requestName, requestNumber);

                        const approver = document.createElement('td');
                        approver.textContent = item.stage || 'Approval in progress';

                        const age = document.createElement('td');
                        age.className = 'queue-age';
                        const ageBadge = document.createElement('span');
                        ageBadge.className = `badge ${item.days_since_update >= 7 ? 'bg-light-danger text-danger' : 'bg-light-secondary text-secondary'}`;
                        ageBadge.textContent = `${item.days_since_update} days`;
                        age.append(ageBadge);
                        row.append(workflow, request, approver, age);
                        return row;
                    }));
                }

                function makeProgressItem({ title, subtitle, count, total, color, label }) {
                    const item = document.createElement('li');
                    item.className = 'list-group-item px-0 d-flex align-items-center';

                    const details = document.createElement('div');
                    details.className = 'flex-grow-1';
                    const name = document.createElement('div');
                    name.className = 'fw-bold';
                    name.textContent = title || 'Unknown';
                    details.append(name);

                    if (subtitle) {
                        const caption = document.createElement('small');
                        caption.className = 'text-muted';
                        caption.textContent = subtitle;
                        details.append(caption);
                    }

                    const progress = document.createElement('div');
                    progress.className = 'progress mt-1';
                    progress.style.height = '5px';
                    const bar = document.createElement('div');
                    bar.className = `progress-bar ${color}`;
                    const percentage = total > 0 ? Math.min(100, Math.round((count / total) * 100)) : 0;
                    bar.style.width = `${percentage}%`;
                    bar.setAttribute('role', 'progressbar');
                    bar.setAttribute('aria-valuenow', percentage);
                    bar.setAttribute('aria-valuemin', 0);
                    bar.setAttribute('aria-valuemax', 100);
                    progress.append(bar);
                    details.append(progress);

                    const value = document.createElement('div');
                    value.className = 'ms-3 text-end';
                    const number = document.createElement('span');
                    number.className = 'fw-bold fs-5';
                    number.textContent = `${count}/${total}`;
                    const caption = document.createElement('div');
                    caption.className = 'small text-muted';
                    caption.textContent = label;
                    value.append(number, caption);
                    item.append(details, value);
                    return item;
                }

                function renderTopLists(data) {
                    const type = chartDataType.value;
                    const label = type === 'prempdr' ? 'Pre-MPDR' : 'MPDR';
                    const topData = data.top5?.[type] ?? { products: [], requesters: { list: [], max_count: 0 } };
                    const topProductsList = document.getElementById('topProductsList');
                    const topRequestersList = document.getElementById('topRequestersList');
                    document.getElementById('topProductsTitle').textContent = `Top 5 ${label} Products in Progress`;
                    document.getElementById('topRequestersTitle').textContent = `Top 5 ${label} Requesters`;

                    if (topData.products.length) {
                        topProductsList.replaceChildren(...topData.products.map(item => makeProgressItem({
                            title: item.product_name,
                            subtitle: `No: ${item.no}`,
                            count: item.approved_count,
                            total: item.total_approvers,
                            color: 'bg-primary',
                            label: 'approved'
                        })));
                    } else {
                        showEmptyState(topProductsList, 'There are no requests in progress.');
                    }

                    const requesters = topData.requesters.list;
                    if (requesters.length) {
                        topRequestersList.replaceChildren(...requesters.map(item => makeProgressItem({
                            title: item.user_name,
                            subtitle: item.department || 'No department',
                            count: item.forms_count,
                            total: topData.requesters.max_count,
                            color: 'bg-warning',
                            label: 'requests'
                        })));
                    } else {
                        showEmptyState(topRequestersList, 'There is no requester data.');
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
                            dashboardError.classList.add('d-none');
                            updateDashboard();
                        })
                        .catch(error => {
                            console.error('Error fetching dashboard data:', error);
                            dashboardError.classList.remove('d-none');
                            showEmptyState(document.getElementById('topProductsList'), 'Product data is unavailable.');
                            showEmptyState(document.getElementById('topRequestersList'), 'Requester data is unavailable.');
                            const queue = document.getElementById('approvalQueueList');
                            const row = document.createElement('tr');
                            const cell = document.createElement('td');
                            cell.colSpan = 4;
                            cell.className = 'text-center text-muted py-4';
                            cell.textContent = 'Approval queue data is unavailable.';
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
