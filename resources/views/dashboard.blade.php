<x-app-layout>
    {{-- Set Title --}}
    @section('title')
        Dashboard MPDR
    @endsection

    <div class="row mb-3 align-items-center">
        <div class="col-md-7">
            <h3 class="mb-0 fw-bold">MPDR Dashboard</h3>
            <small class="text-muted">Selamat datang kembali! Berikut adalah ringkasan data proses pengembangan produk.</small>
        </div>
        <div class="col-md-5 text-md-end mt-2 mt-md-0">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="ti ti-plus me-1"></i> Buat Pengajuan Baru
                </button>
                <ul class="dropdown-menu">
                    {{-- Note: Update these hrefs to the correct routes --}}
                    <li><a class="dropdown-item" href="{{ route('prempdr.index') }}">Pre-MPDR</a></li>
                    <li><a class="dropdown-item" href="{{ route('mpdr.index') }}">MPDR</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Metric Boxes --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="h-100 card hover-effect b-t-4-primary animate__animated animate__fadeInUp">
                <div class="card-body d-flex align-items-center">
                    <div class="metric-icon bg-light-primary text-primary"><i class="ti ti-lightbulb"></i></div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small mb-1">Pre-MPDR Outstanding</div>
                        <div class="metric-value" id="preMpdrOutstanding">0</div>
                        <div class="metric-change text-muted small">Total Draft & In Approval</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 hover-effect b-t-4-info animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="card-body d-flex align-items-center">
                    <div class="metric-icon bg-light-info text-info"><i class="ti ti-box"></i></div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small mb-1">MPDR Outstanding</div>
                        <div class="metric-value" id="mpdrOutstanding">0</div>
                        <div class="metric-change text-muted small">Total In Approval</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 hover-effect b-t-4-warning animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="card-body d-flex align-items-center">
                    <div class="metric-icon bg-light-warning text-warning"><i class="ti ti-user-check"></i></div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small mb-1">Butuh Persetujuan Saya</div>
                        <div class="metric-value" id="needsMyApproval">0</div>
                        <div class="metric-change text-warning small">Form menunggu tindakan Anda</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 hover-effect b-t-4-danger animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="card-body d-flex align-items-center">
                    <div class="metric-icon bg-light-danger text-danger"><i class="ti ti-alert-triangle"></i></div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small mb-1">Prioritas Tinggi (Urgent)</div>
                        <div class="metric-value" id="highPriority">0</div>
                        <div class="metric-change text-muted small">Total MPDR Urgent In Approval</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Chart and Summary --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
                    <h5 class="mb-0 card-title">Statistik Proses MPDR per Bulan</h5>
                    <div class="d-flex align-items-center">
                        <select class="form-select form-select-sm me-2" id="chartDataType" style="width: auto;">
                            <option value="prempdr" selected>Pre-MPDR</option>
                            <option value="mpdr">MPDR</option>
                        </select>
                        <select class="form-select form-select-sm" id="yearFilterSelect" style="width: auto;">
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
                                <p class="fs-6 text-muted mb-1">Total Created</p>
                                <h3 class="text-primary-dark mb-0" id="summaryCreated">0</h3>
                                <small class="text-muted">Current Period</small>
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
                                <small class="text-muted">Current Period</small>
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
                                <small class="text-muted">Current Period</small>
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
                                <small class="text-muted">Current Period</small>
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
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">Top 5 Produk dalam Proses</h5>
                </div>
                <div class="card-body pt-0 simplebar-scroll" style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group list-group-flush" id="topProductsList">
                        {{-- Content will be populated by JavaScript --}}
                        <p class="text-muted text-center p-4">Loading data...</p>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">Top 5 Requester / Inisiator</h5>
                </div>
                <div class="card-body pt-0 simplebar-scroll" style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group list-group-flush" id="topRequestersList">
                        {{-- Content will be populated by JavaScript --}}
                         <p class="text-muted text-center p-4">Loading data...</p>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activities & My Actions will be added in a future update if needed --}}


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const yearFilter = document.getElementById('yearFilterSelect');
                const chartDataType = document.getElementById('chartDataType');
                let currentChart;

                // Populate Year Filter
                const currentYear = new Date().getFullYear();
                for (let i = 0; i < 5; i++) {
                    const year = currentYear - i;
                    const option = new Option(`Tahun ${year}`, year);
                    yearFilter.add(option);
                }

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

                function updateDashboard(data) {
                    // Update Metric Boxes
                    document.getElementById('preMpdrOutstanding').textContent = data.metrics.preMpdrOutstanding;
                    document.getElementById('mpdrOutstanding').textContent = data.metrics.mpdrOutstanding;
                    document.getElementById('needsMyApproval').textContent = data.metrics.needsMyApproval;
                    document.getElementById('highPriority').textContent = data.metrics.highPriority;

                    // Update Chart & Summary Cards
                    const chartCollection = (data.chart && data.chart[chartDataType.value]) ? data.chart[chartDataType.value] : {};
                    const defaultSeries = { created: Array(12).fill(0), approved: Array(12).fill(0), pending: Array(12).fill(0), rejected: Array(12).fill(0) };
                    const chartData = chartCollection[yearFilter.value] ?? defaultSeries;

                    if (currentChart) {
                        currentChart.updateOptions(getChartOptions(chartData));
                    } else {
                        currentChart = new ApexCharts(document.querySelector("#monthlyRequisitionChart"), getChartOptions(chartData));
                        currentChart.render();
                    }

                    const sum = arr => arr.reduce((acc, val) => acc + val, 0);
                    document.getElementById('summaryCreated').textContent = sum(chartData.created);
                    document.getElementById('summaryApproved').textContent = sum(chartData.approved);
                    document.getElementById('summaryPending').textContent = sum(chartData.pending);
                    document.getElementById('summaryRejected').textContent = sum(chartData.rejected);

                    // Update Top 5 Products
                    const topProductsList = document.getElementById('topProductsList');
                    topProductsList.innerHTML = ''; // Clear list
                    if (data.top5.products.length > 0) {
                        data.top5.products.forEach(item => {
                            const progress = (item.approved_count / item.total_approvers) * 100;
                            const li = `
                                <li class="list-group-item px-0 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">${item.product_name}</div>
                                        <small class="text-muted">No: ${item.no}</small>
                                        <div class="progress mt-1" style="height: 5px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: ${progress}%;" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="ms-3 text-end">
                                        <span class="fw-bold fs-5">${item.approved_count}/${item.total_approvers}</span>
                                        <div class="small text-muted">approved</div>
                                    </div>
                                </li>`;
                            topProductsList.insertAdjacentHTML('beforeend', li);
                        });
                    } else {
                         topProductsList.innerHTML = '<p class="text-muted text-center p-4">Tidak ada data produk.</p>';
                    }


                    // Update Top 5 Requesters
                    const topRequestersList = document.getElementById('topRequestersList');
                    const requestersData = data.top5.requesters.list;
                    const maxRequesterForms = data.top5.requesters.max_count;
                    topRequestersList.innerHTML = ''; // Clear list

                    if (requestersData.length > 0) {
                        requestersData.forEach(item => {
                        const progress = maxRequesterForms > 0 ? (item.forms_count / maxRequesterForms) * 100 : 0;
                        const li = `
                            <li class="list-group-item px-0 d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">${item.user_name}</div>
                                    <small class="text-muted">${item.department || 'No Department'}</small>
                                    <div class="progress mt-1" style="height: 5px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: ${progress}%;" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="ms-3 text-end">
                                    <span class="fw-bold fs-5">${item.forms_count}</span>
                                    <div class="small text-muted">forms</div>
                                </div>
                            </li>`;
                        topRequestersList.insertAdjacentHTML('beforeend', li);
                        });
                     } else {
                         topRequestersList.innerHTML = '<p class="text-muted text-center p-4">Tidak ada data requester.</p>';
                     }
                }

                function fetchDataAndUpdate() {
                    // Fetch all dashboard data from a single endpoint
                    fetch('{{ route("dashboard.mpdr.data") }}')
                        .then(response => response.json())
                        .then(data => {
                            updateDashboard(data);
                        })
                        .catch(error => console.error('Error fetching dashboard data:', error));
                }

                // Event Listeners for filters
                yearFilter.addEventListener('change', fetchDataAndUpdate);
                chartDataType.addEventListener('change', fetchDataAndUpdate);

                // Initial load
                fetchDataAndUpdate();
            });
        </script>
    @endpush
</x-app-layout>
