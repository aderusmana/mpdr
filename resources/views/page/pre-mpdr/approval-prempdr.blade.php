<x-app-layout>
    @section('title')
        List Approval Pre-MPDR
    @endsection

    {{-- Menggunakan style tabel yang konsisten --}}
    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

        {{-- CSS KUSTOM UNTUK MODAL BARU --}}
        <style>
            /* Mengadopsi gaya Card dari list-prempdr */
            .view-modal-card {
                border: 1px solid #e9ecef;
                border-radius: 0.75rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
                margin-bottom: 1.5rem;
            }

            .view-modal-card-header {
                background-color: #f8f9fa;
                border-bottom: 1px solid #e9ecef;
                padding: 0.75rem 1.25rem;
                display: flex;
                align-items: center;
                font-weight: 600;
                color: var(--bs-primary);
            }

            .view-modal-card-header i {
                font-size: 1.2rem;
                margin-right: 0.5rem;
            }

            /* Style untuk menampilkan data radio/check (bukan input) */
            .readonly-data-box {
                background-color: #f9fbfd;
                border: 1px solid #e9ecef;
                border-radius: 0.375rem;
                padding: 0.75rem 1rem;
            }

            .readonly-data-box strong {
                display: block;
                color: #2a3547;
            }

            .readonly-data-box span {
                color: #5a6a85;
                font-style: italic;
            }

            /* Header modal yang disesuaikan */
            #approvalModal .modal-header {
                background-color: #2a3547;
                color: white;
                border-bottom: 2px solid #3a4659;
            }

            #approvalModal .modal-header .btn-close {
                filter: invert(1) grayscale(100) brightness(200%);
            }

            #approvalModal .modal-body {
                background-color: #f9fbfd;
            }
        </style>
    @endpush

    {{-- Judul dan Breadcrumb --}}
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Pre-MPDR Approval List</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard') }}">
                        <i class="ph-duotone ph-house f-s-16"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Approval List</a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Kontainer Tabel Utama --}}
    <div class="row">
        <div class="col-12">
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-check-square-offset"></i>
                        Approval List
                    </h4>
                    <p class="table-subtitle">Review and process Pre-MPDR forms awaiting your approval</p>
                </div>
                <div class="table-responsive">
                    <table class="w-100 display table table-striped" id="prempdrTable">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%;">No.</th>
                                <th class="text-center" style="width: 15%;">No. Reg</th>
                                <th style="width: 25%;">Project Name</th>
                                <th style="width: 25%;">Route To</th>
                                <th class="text-center" style="width: 15%;">Status</th>
                                <th class="text-center" style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data diisi oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- =============================================================== -->
    <!--            MODAL APPROVAL FORM (DESAIN BARU DENGAN CARD)        -->
    <!-- =============================================================== -->
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approvalModalLabel">
                        <i class="ph-duotone ph-check-square-offset me-2"></i>
                        Form Approval Pre-MPDR: <span id="modal_no_reg" class="fw-bold"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Loader -->
                    <div id="modal-loader" class="text-center p-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="mt-3">Memuat data form...</h5>
                    </div>

                    <!-- Konten Form (Wrapper) -->
                    <div id="modal-content-wrapper" style="display: none;">

                        <!-- 1. EXECUTIVE HEADER BANNER -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
                            style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                            <div class="card-body p-4 text-white">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            class="p-3 bg-white bg-opacity-10 rounded-3 border border-white border-opacity-10">
                                            <i class="ph-bold ph-receipt text-info" style="font-size: 2.2rem;"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                <span
                                                    class="badge bg-info text-dark px-3 py-1 font-monospace fw-bold fs-6"
                                                    id="banner_no_reg">-</span>
                                                <span class="badge bg-secondary px-2 py-1 font-monospace"
                                                    id="banner_revision">REV 0</span>
                                                <span class="badge bg-warning text-dark px-3 py-1 fs-6"
                                                    id="banner_status">In Approval</span>
                                            </div>
                                            <h4 class="fw-bold mb-0 text-white" id="banner_project_name">-</h4>
                                            <div class="text-slate-300 small mt-1">Proposed Brand: <strong
                                                    class="text-white" id="banner_brand_name">-</strong></div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-slate-400 small mb-1">Priority Level</div>
                                        <span class="badge bg-danger px-3 py-2 fs-6 fw-bold" id="banner_priority"><i
                                                class="ph-bold ph-fire me-1"></i> Normal</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. QUICK METRIC KPI CARDS -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div
                                    class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-primary h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i
                                            class="ph-bold ph-tag me-1 text-primary"></i> Category</div>
                                    <div class="fw-bold fs-6 text-dark" id="kpi_category">-</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div
                                    class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-info h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i
                                            class="ph-bold ph-globe me-1 text-info"></i> Channel</div>
                                    <div class="fw-bold fs-6 text-dark" id="kpi_channel">-</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div
                                    class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-warning h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i
                                            class="ph-bold ph-calendar me-1 text-warning"></i> Target Launch</div>
                                    <div class="fw-bold fs-6 text-dark" id="kpi_target_launch">-</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div
                                    class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-success h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i
                                            class="ph-bold ph-shield-check me-1 text-success"></i> Certification</div>
                                    <div class="fw-bold fs-6 text-dark" id="kpi_certification">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. RATIONAL FOR DEVELOPMENT -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-target text-primary fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Rational For Development</h6>
                            </div>
                            <div class="card-body bg-light p-3">
                                <div class="p-3 bg-white rounded-3 border-start border-4 border-primary shadow-sm text-secondary fs-6 lh-base"
                                    id="rational_view">
                                    No rational provided.
                                </div>
                            </div>
                        </div>

                        <!-- 4. PRODUCT SPECIFICATIONS & DESCRIPTION -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-cube text-info fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">General Product Description & Function</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border h-100">
                                            <div class="fw-bold small text-muted text-uppercase mb-2"><i
                                                    class="ph-bold ph-note-pencil me-1"></i> Product Description
                                                (Form/Color/Flavor)</div>
                                            <div class="text-dark fs-6" id="product_desc_view">N/A</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border h-100">
                                            <div class="fw-bold small text-muted text-uppercase mb-2"><i
                                                    class="ph-bold ph-users me-1"></i> Usage Description (Application
                                                in customer)</div>
                                            <div class="text-dark fs-6" id="usage_desc_view">N/A</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-white border rounded-3 gap-3">
                                            <div class="p-2 bg-info bg-opacity-10 text-info rounded-circle"><i
                                                    class="ph-bold ph-thermometer-cold fs-4"></i></div>
                                            <div>
                                                <div class="small text-muted fw-bold">Storage Temperature</div>
                                                <div class="fw-bold text-dark fs-6" id="storage_temp_view">N/A</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-white border rounded-3 gap-3">
                                            <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle"><i
                                                    class="ph-bold ph-truck fs-4"></i></div>
                                            <div>
                                                <div class="small text-muted fw-bold">Delivery Temperature</div>
                                                <div class="fw-bold text-dark fs-6" id="delivery_temp_view">N/A</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. COMPETITOR PRODUCTS TABLE -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-trophy text-warning fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Competitor's Product to Match or to Beat</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="competitorProduct">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">#</th>
                                                <th>Product Name</th>
                                                <th>Size</th>
                                                <th>Packaging</th>
                                                <th class="pe-4">Price Indication</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- 6. PACKAGING & MARKET STRATEGY CARDS -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    <div
                                        class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="ph-bold ph-package text-primary fs-5"></i>
                                        <h6 class="fw-bold mb-0 text-dark">Packaging Details</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold mb-1">Weight of Product:</div>
                                            <div class="p-2 bg-light rounded border fw-bold text-dark"
                                                id="weight_view">-</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold mb-1">Packaging Category & Details:
                                            </div>
                                            <div class="p-2 bg-light rounded border text-dark" id="packaging_view">-
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-muted fw-bold mb-1">Product Variation List:</div>
                                            <div class="p-2 bg-light rounded border text-dark"
                                                id="product_variation_view">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    <div
                                        class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="ph-bold ph-chart-line-up text-success fs-5"></i>
                                        <h6 class="fw-bold mb-0 text-dark">Market Update & Pricing Strategy</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold mb-1">Potential Volume (Mt/Annually):
                                            </div>
                                            <div class="p-2 bg-light rounded border fw-bold text-dark fs-6"
                                                id="potential_volume_view">-</div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="p-2 bg-light rounded border">
                                                    <div class="small text-muted fw-bold">Expected Margin</div>
                                                    <div class="fw-bold text-success fs-6" id="expected_margin_view">-
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 bg-light rounded border">
                                                    <div class="small text-muted fw-bold">Price Estimate</div>
                                                    <div class="fw-bold text-primary fs-6" id="price_estimate_view">-
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 7. RIWAYAT APPROVAL -->
                        <div class="card border border-2 border-primary border-opacity-25 shadow-sm rounded-3 mb-4">
                            <div
                                class="card-header bg-primary bg-opacity-10 py-3 border-bottom border-primary border-opacity-25 d-flex align-items-center gap-2">
                                <i class="ph-bold ph-check-square-offset text-primary fs-5"></i>
                                <h6 class="fw-bold mb-0 text-primary">Approval History & Status</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="approver"
                                        class="table table-bordered table-striped align-middle mb-0 w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Approver</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Date</th>
                                                <th class="pe-3">Comments / Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Diisi oleh JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- 8. FORM ACTION APPROVAL (Paling Bawah) -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div
                                class="card-header bg-primary text-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-pencil-simple-line fs-5"></i>
                                <h6 class="fw-bold mb-0 text-white">Your Action</h6>
                            </div>
                            <div class="card-body p-4 text-center">
                                <form id="approvalActionForm" method="POST">
                                    @csrf
                                    <div class="mb-3 text-start">
                                        <label for="comment" class="form-label fw-bold">Comments / Notes</label>
                                        <textarea name="comment" id="comment" class="form-control rounded-3" rows="3"
                                            placeholder="Enter a comment... (Required for 'Approve with Review' or 'Not Approve')"></textarea>
                                        <div id="textarea-warning" class="d-none text-danger mt-1 small fw-bold">A
                                            comment is required for this action.</div>
                                    </div>
                                    <div class="d-flex justify-content-center flex-wrap gap-3">
                                        <button type="button" class="btn btn-success btn-lg px-4 fw-bold"
                                            onclick="submitApproval('approve')">
                                            <i class="ph-bold ph-check-circle me-1"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-warning btn-lg px-4 fw-bold text-dark"
                                            onclick="validateAndSubmit('approve with review')">
                                            <i class="ph-bold ph-warning-circle me-1"></i> Approve with Review
                                        </button>
                                        <button type="button" class="btn btn-danger btn-lg px-4 fw-bold"
                                            onclick="validateAndSubmit('not approve')">
                                            <i class="ph-bold ph-x-circle me-1"></i> Not Approve
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div> <!-- End of modal-content-wrapper -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            let table;

            $(function() {
                // ===============================================================
                //                      1. INISIALISASI DATATABLES
                // ===============================================================
                table = $('#prempdrTable').DataTable({
                    processing: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('prempdr.approval.list.data') }}",
                        type: 'GET',
                        dataSrc: 'data'
                    },
                    columns: [{
                            data: null,
                            searchable: false,
                            orderable: false,
                            width: '5%',
                            className: 'text-center align-middle',
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        {
                            data: 'no',
                            name: 'no',
                            width: '15%',
                            className: 'text-center align-middle text-nowrap',
                            render: data => `<span class="status-badge-lg bg-dark">${data}</span>`
                        },
                        {
                            data: 'project_name',
                            name: 'project_name',
                            width: '25%',
                            className: 'align-middle',
                            render: data =>
                                `<div class="d-flex align-items-center"><i class="ph-bold ph-folder-simple me-2 text-primary fs-5"></i><span class="fw-bold">${data}</span></div>`
                        },
                        {
                            data: 'route_to',
                            name: 'route_to',
                            width: '25%',
                            className: 'align-middle',
                            render: data => data ?
                                `<span class="status-badge-lg bg-warning d-flex align-items-center text-nowrap"><i class="ph-bold ph-user-circle me-1 fs-6"></i>${data}</span>` :
                                '<span class="text-muted fst-italic">-</span>'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            width: '15%',
                            className: 'text-center align-middle text-nowrap',
                            render: function(data, type, row) {
                                let statusClass = {
                                    'Rejected': 'bg-danger',
                                    'Approved': 'bg-success',
                                    'In Approval': 'bg-info',
                                    'Draft': 'bg-warning'
                                } [data] || 'bg-secondary';
                                return `<span class="status-badge-lg ${statusClass}">${data}</span>`;
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            width: '15%',
                            className: 'text-center align-middle text-nowrap',
                            render: function(data, type, row) {
                                let resendButton = '';
                                @can('view resend pre')
                                    if (row.status === 'In Approval') {
                                        resendButton = `
                                            <button class="btn btn-sm btn-warning resend-email-btn me-1"
                                                data-id="${row.no}" data-approver="${row.route_to || ''}"
                                                data-bs-toggle="tooltip" title="Resend Email">
                                                <i class="ph-bold ph-paper-plane-tilt"></i> Resend
                                            </button>
                                        `;
                                    }
                                @endcan

                                return `
                                    <button class="btn btn-sm btn-info review-btn me-1" data-id="${row.no}" data-bs-toggle="tooltip" title="Review Form">
                                        <i class="ph-bold ph-note-pencil"></i> Review
                                    </button>
                                    ${resendButton}
                                `;
                            }
                        }
                    ],
                    drawCallback: function() {
                        $('.tooltip').remove();
                        $('[data-bs-toggle="tooltip"]').each(function() {
                            const inst = bootstrap.Tooltip.getInstance(this);
                            if (inst) {
                                inst.dispose();
                            }
                            new bootstrap.Tooltip(this, {
                                trigger: 'hover',
                                animation: false,
                                boundary: 'window'
                            });
                        });
                    }
                });

                // Adjust columns on window resize (maximize/minimize/restore)
                let resizeTimer;
                $(window).on('resize', function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function() {
                        if ($.fn.dataTable.isDataTable('#prempdrTable')) {
                            table.columns.adjust();
                        }
                    }, 100);
                });

                // ===============================================================
                //               2. EVENT LISTENER TOMBOL REVIEW
                // ===============================================================
                $('#prempdrTable tbody').on('click', '.review-btn', function() {
                    const no_reg = $(this).data('id');

                    // Reset modal state
                    $('#approvalActionForm')[0].reset();
                    $('#textarea-warning').addClass('d-none');
                    $('#modal-loader').show();
                    $('#modal-content-wrapper').hide();

                    const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
                    modal.show();

                    loadApprovalData(no_reg);
                });

                $('#prempdrTable tbody').on('click', '.resend-email-btn', function() {
                    const button = $(this);
                    const formNo = button.data('id');
                    const approverName = button.data('approver') || 'the pending approver';

                    Swal.fire({
                        title: 'Resend Approval Email?',
                        text: `Resend the Pre-MPDR approval email for ${formNo} to ${approverName}? The previous email link will expire.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Resend Email',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            return;
                        }

                        button.prop('disabled', true);
                        $.ajax({
                            url: `{{ url('prempdr/resend-email') }}/${encodeURIComponent(formNo)}`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: response.status === 'success' ?
                                        'success' : 'error',
                                    title: response.status === 'success' ?
                                        'Email Scheduled' : 'Failed',
                                    text: response.message
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed to Resend Email',
                                    text: xhr.responseJSON?.message ||
                                        'A server error occurred.'
                                });
                            },
                            complete: function() {
                                button.prop('disabled', false);
                            }
                        });
                    });
                });

                // ===============================================================
                //          3. SUBMIT FORM ACTION DENGAN AJAX
                // ===============================================================
                $('#approvalActionForm').on('submit', function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const url = form.attr('action');
                    let formData = new FormData(this);
                    const actionValue = form.data('actionValue');
                    if (actionValue) formData.append('action', actionValue);

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#approvalModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON?.message || 'Error occurred.'
                            });
                        }
                    });
                });
            });

            // ===============================================================
            //                 4. FUNGSI UNTUK MENGAMBIL & MENGISI DATA
            // ===============================================================
            function loadApprovalData(no_reg) {
                $.ajax({
                    url: "{{ route('prempdr.form.data') }}",
                    method: 'GET',
                    data: {
                        no_reg: no_reg
                    },
                    success: function(response) {
                        if (!response) return;

                        // Executive Banner
                        $('#modal_no_reg').text(no_reg);
                        $('#banner_no_reg').text(no_reg);
                        const revNo = response.revision ? (response.revision.revision ?? response.revision.no ??
                            '0') : '0';
                        $('#banner_revision').text('REV ' + revNo);
                        $('#banner_status').text(response.status || 'In Approval');
                        $('#banner_project_name').text(response.project_name || '-');
                        $('#banner_brand_name').text(response.brand_name || '-');
                        $('#banner_priority').html(
                            `<i class="ph-bold ph-fire me-1"></i> ${response.level_priority || 'Normal'}`);

                        // KPIs
                        const catStr = response.category ? response.category.category : '-';
                        const catOther = (response.category && response.category.other) ?
                            ` (${response.category.other})` : '';
                        $('#kpi_category').text(catStr + catOther);

                        const chanStr = response.channel ? response.channel.category : '-';
                        const chanCountry = (response.channel && response.channel.country) ?
                            ` (${response.channel.country})` : '';
                        $('#kpi_channel').text(chanStr + chanCountry);

                        $('#kpi_target_launch').text(response.detail ? (response.detail.target_launch || '-') :
                        '-');

                        const certStr = response.certification ? response.certification.category : '-';
                        const certOther = (response.certification && response.certification.other) ?
                            ` (${response.certification.other})` : '';
                        $('#kpi_certification').text(certStr + certOther);

                        // Rational
                        $('#rational_view').text(response.detail ? (response.detail.rational_for_development ||
                            'No rational provided.') : 'No rational provided.');

                        // Product Description & Specs
                        if (response.description) {
                            $('#product_desc_view').text(response.description.product_description || 'N/A');
                            $('#usage_desc_view').text(response.description.usage_description || 'N/A');
                            $('#storage_temp_view').text(response.description.storage_temperature || 'N/A');
                            $('#delivery_temp_view').text(response.description.delivery_temperature || 'N/A');
                        } else {
                            $('#product_desc_view').text('N/A');
                            $('#usage_desc_view').text('N/A');
                            $('#storage_temp_view').text('N/A');
                            $('#delivery_temp_view').text('N/A');
                        }

                        // Competitor Table
                        const competitorTbody = $('#competitorProduct tbody');
                        competitorTbody.empty();
                        if (response.competitor && Array.isArray(response.competitor) && response.competitor
                            .length > 0) {
                            response.competitor.forEach((c, idx) => {
                                competitorTbody.append(`
                                    <tr>
                                        <td class="ps-4 fw-bold">${idx + 1}</td>
                                        <td class="fw-semibold">${c.name || '-'}</td>
                                        <td>${c.size || '-'}</td>
                                        <td>${c.packaging || '-'}</td>
                                        <td class="pe-4">${c.price || '-'}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            competitorTbody.append(
                                '<tr><td colspan="5" class="text-center text-muted py-3">No competitor data available.</td></tr>'
                                );
                        }

                        // Packaging Details
                        if (response.packaging) {
                            $('#weight_view').text(response.packaging.weight || '-');
                            const packCategory = response.packaging.category || '-';
                            const packDetail = response.packaging.detail ? ` - ${response.packaging.detail}` : '';
                            $('#packaging_view').text(packCategory + packDetail);
                            $('#product_variation_view').text(response.packaging.product_variation || '-');
                        } else {
                            $('#weight_view').text('-');
                            $('#packaging_view').text('-');
                            $('#product_variation_view').text('-');
                        }

                        // Market Update
                        if (response.market) {
                            $('#potential_volume_view').text(response.market.potential_volume || '-');
                            $('#expected_margin_view').text(response.market.expected_margin || '-');
                            $('#price_estimate_view').text(response.market.price_estimate || '-');
                        } else {
                            $('#potential_volume_view').text('-');
                            $('#expected_margin_view').text('-');
                            $('#price_estimate_view').text('-');
                        }

                        // Approver Table
                        const approverTbody = $('#approver tbody');
                        approverTbody.empty();
                        const details = response.approved_detail || response.approved_detail_ordered || [];
                        if (details && details.length > 0) {
                            details.forEach(detail => {
                                const rawStatus = (detail.status || '').toString().trim();
                                const lowerStatus = rawStatus.toLowerCase();
                                let statusBadge = 'bg-secondary';
                                let displayStatus = 'Pending';

                                if (lowerStatus === 'approve' || lowerStatus === 'approved') {
                                    displayStatus = 'Approved';
                                    statusBadge = 'bg-success';
                                } else if (lowerStatus === 'approve with review' || lowerStatus === 'approved with review') {
                                    displayStatus = 'Approved with Review';
                                    statusBadge = 'bg-warning text-dark';
                                } else if (lowerStatus === 'not approve' || lowerStatus === 'not approved') {
                                    displayStatus = 'Not Approved';
                                    statusBadge = 'bg-danger';
                                } else if (lowerStatus === 'reject' || lowerStatus === 'rejected') {
                                    displayStatus = 'Rejected';
                                    statusBadge = 'bg-danger';
                                } else if (lowerStatus === 'vacant') {
                                    displayStatus = 'Vacant';
                                    statusBadge = 'bg-dark text-white';
                                } else if (lowerStatus === 'pending') {
                                    displayStatus = 'Pending';
                                    statusBadge = 'bg-info text-dark';
                                } else if (rawStatus) {
                                    displayStatus = rawStatus.replace(/\b\w/g, c => c.toUpperCase());
                                }

                                const commentHtml = detail.comment ?
                                    `<div style="max-height: 120px; overflow-y: auto; word-break: break-word; white-space: pre-wrap; line-height: 1.45;" class="p-2 bg-light rounded border-start border-3 border-info small text-dark">${detail.comment}</div>` :
                                    '<span class="text-muted">-</span>';
                                approverTbody.append(`
                                    <tr>
                                        <td class="ps-3 align-middle fw-semibold">${detail.approver_position || (detail.user && detail.user.position ? detail.user.position.name : '') || detail.name || detail.approver || detail.approver_name || '-'}</td>
                                        <td class="align-middle text-center"><span class="badge ${statusBadge} px-3 py-1">${displayStatus}</span></td>
                                        <td class="align-middle text-center text-muted small">${detail.approved_date || '-'}</td>
                                        <td class="pe-3 align-middle">${commentHtml}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            approverTbody.append(
                                '<tr><td colspan="4" class="text-center text-muted py-3">No approval history available.</td></tr>'
                                );
                        }

                        // Action Form Action URL
                        const actionUrl = "{{ route('prempdr.form.approve', ['no_reg' => ':no_reg']) }}".replace(
                            ':no_reg', no_reg);
                        $('#approvalActionForm').attr('action', actionUrl);

                        // Sembunyikan loader dan tampilkan konten
                        $('#modal-loader').hide();
                        $('#modal-content-wrapper').show();
                    },
                    error: function(xhr) {
                        $('#modal-loader').html(
                            '<p class="text-danger fw-bold fs-5">Failed to load data. Close the dialog and try again.</p>'
                            );
                    }
                });
            }

            // ===============================================================
            //                  5. FUNGSI SUBMIT & VALIDASI
            // ===============================================================
            window.submitApproval = function(action) {
                Swal.fire({
                    title: `Confirm Action`,
                    text: `Are you sure you want to "${action}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745', // Hijau
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Continue!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#approvalActionForm');
                        form.data('actionValue', action); // Simpan action di data attribute
                        form.submit(); // Trigger event submit yang sudah kita override
                    }
                });
            }

            window.validateAndSubmit = function(action) {
                const comment = $('#comment').val().trim();
                const warning = $('#textarea-warning');

                // Komentar hanya wajib jika BUKAN 'approve'
                if (action !== 'approve' && comment === '') {
                    warning.removeClass('d-none');
                    $('#comment').focus();
                } else {
                    warning.addClass('d-none');
                    submitApproval(action);
                }
            }

            // ===============================================================
            //            6. FUNGSI UNTUK MERESET/MEMBERSIHKAN MODAL
            // ===============================================================
            function resetModalForm() {
                const form = $('#approvalActionForm');
                form[0].reset();
                form.data('actionValue', null); // Hapus data action sebelumnya
                $('#textarea-warning').addClass('d-none');
                $('#modal_no_reg').text('');
                $('#revision-no, #revision-count, #revision-date').text('');
                $('#approvalModal input[type="text"], #approvalModal textarea').val('');
                $('#productCategory, #channel, #certification, #packaging').html('');
                $('#competitorProduct tbody, #approver tbody').empty();
            }
        </script>
    @endpush
</x-app-layout>
