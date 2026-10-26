<x-app-layout>
    @section('title')
        List Approval MPDR
    @endsection

    {{-- Menggunakan style tabel yang konsisten seperti di list-mpdr --}}
    @include('components.mpdr-table-styles')
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    {{-- Judul dan Breadcrumb --}}
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">MPDR Approval List</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li><a class="f-s-14 f-w-500" href="{{ route('dashboard') }}"><i class="ph-duotone ph-house f-s-16"></i> Home</a></li>
                <li class="active"><a class="f-s-14 f-w-500" href="#">MPDR Approval List</a></li>
            </ul>
        </div>
    </div>

    {{-- Kontainer Tabel Utama --}}
    <div class="main-table-container">
        <div class="table-header-enhanced">
            <h4 class="table-title"><i class="ph-duotone ph-list-checks"></i> MPDR Waiting for Approval</h4>
            <p class="table-subtitle">Select a form to review and take action.</p>
        </div>
        <div class="table-responsive">
            <table class="w-100 display table table-striped" id="prempdrTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 15%;">No. Reg</th>
                        <th style="width: 25%;">Product Name</th>
                        <th class="text-center" style="width: 15%;">Request Date</th>
                        <th style="width: 20%;">Waiting Approval</th>
                        <th class="text-center" style="width: 15%;">Status</th>
                        <th class="text-center" style="width: 10%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data akan diisi oleh DataTables --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- =================== MODAL UNTUK APPROVAL FORM =================== --}}
    {{-- =================================================================== --}}
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="loading-overlay" style="display: none;">
                    <div class="spinner-border text-white" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="mt-3 fw-bold">Loading Form...</h5>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="approvalModalLabel"><i class="ph-bold ph-file-text me-2"></i>Review MPDR Form</h5>
                    <button type="button" class="btn-close btn-close-white m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background-color: #f9fbfd;">

                    <div id="approval-form-detail-view">

                        <!-- 1. EXECUTIVE HEADER BANNER -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                            <div class="card-body p-4 text-white">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-3 bg-white bg-opacity-10 rounded-3 border border-white border-opacity-10">
                                            <i class="ph-bold ph-file-text text-info" style="font-size: 2.2rem;"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                <span class="badge bg-info text-dark px-3 py-1 font-monospace fw-bold fs-6" id="no_reg_badge">-</span>
                                                <span class="badge bg-secondary px-2 py-1 font-monospace" id="revision_badge">REV 0</span>
                                                <span class="badge bg-warning text-dark px-3 py-1 fs-6" id="status_badge">In Approval</span>
                                            </div>
                                            <h4 class="fw-bold mb-0 text-white" id="banner_product_name">-</h4>
                                            <div class="text-slate-300 small mt-1">Initiator: <strong class="text-white" id="banner_initiator">-</strong></div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-slate-400 small mb-1">Priority Level</div>
                                        <span class="badge bg-danger px-3 py-2 fs-6 fw-bold" id="banner_priority"><i class="ph-bold ph-fire me-1"></i> Normal</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. QUICK METRIC KPI CARDS -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm rounded-3 p-3 bg-light border-start border-4 border-primary h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i class="ph-bold ph-tag me-1 text-white"></i> Category</div>
                                    <div class="fw-bold fs-6 text-dark" id="kpi_category">-</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm rounded-3 p-3 bg-light border-start border-4 border-info h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i class="ph-bold ph-globe me-1 text-info"></i> Channel</div>
                                    <div class="fw-bold fs-6 text-dark" id="kpi_channel">-</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm rounded-3 p-3 bg-light border-start border-4 border-warning h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i class="ph-bold ph-calendar me-1 text-warning"></i> Target Launch</div>
                                    <div class="fw-bold fs-6 text-dark" id="kpi_target_launch">-</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm rounded-3 p-3 bg-light border-start border-4 border-success h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i class="ph-bold ph-shield-check me-1 text-success"></i> Certification</div>
                                    <div class="fw-bold fs-6 text-dark" id="kpi_cert">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. RATIONAL FOR DEVELOPMENT -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-target text-white fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Rational For Development</h6>
                            </div>
                            <div class="card-body bg-light p-3">
                                <div class="p-3 bg-white rounded-3 border-start border-4 border-primary shadow-sm text-secondary fs-6 lh-base" id="rationalForDevelopment_view">
                                    No rational provided.
                                </div>
                            </div>
                        </div>

                        <!-- 4. PRODUCT SPECIFICATIONS & DESCRIPTION -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-cube text-info fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Product Specifications & Description</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border h-100">
                                            <div class="fw-bold small text-muted text-uppercase mb-2"><i class="ph-bold ph-note-pencil me-1"></i> Product Description (Form/Color/Flavor)</div>
                                            <div class="text-dark fs-6" id="productDescription_view">N/A</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border h-100">
                                            <div class="fw-bold small text-muted text-uppercase mb-2"><i class="ph-bold ph-users me-1"></i> Usage Description (Customer Application)</div>
                                            <div class="text-dark fs-6" id="usageDescription_view">N/A</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-white border rounded-3 gap-3">
                                            <div class="p-2 bg-info bg-opacity-10 text-info rounded-circle"><i class="ph-bold ph-thermometer-cold fs-4"></i></div>
                                            <div>
                                                <div class="small text-muted fw-bold">Storage Temperature</div>
                                                <div class="fw-bold text-dark fs-6" id="storageTemperature_view">N/A</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-white border rounded-3 gap-3">
                                            <div class="p-2 bg-primary bg-opacity-10 text-white rounded-circle"><i class="ph-bold ph-truck fs-4"></i></div>
                                            <div>
                                                <div class="small text-muted fw-bold">Delivery Temperature</div>
                                                <div class="fw-bold text-dark fs-6" id="deliveryTemperature_view">N/A</div>
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
                                <h6 class="fw-bold mb-0 text-dark">Competitor's Product to Match or Beat</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">#</th>
                                                <th>PRODUCT NAME</th>
                                                <th>SIZE</th>
                                                <th>PACKAGING</th>
                                                <th class="pe-3">PRICE INDICATION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="ps-3 fw-bold">1</td>
                                                <td id="productName1_view" class="fw-semibold">-</td>
                                                <td id="size1_view">-</td>
                                                <td id="packaging1_view">-</td>
                                                <td id="priceIndication1_view" class="pe-3">-</td>
                                            </tr>
                                            <tr>
                                                <td class="ps-3 fw-bold">2</td>
                                                <td id="productName2_view" class="fw-semibold">-</td>
                                                <td id="size2_view">-</td>
                                                <td id="packaging2_view">-</td>
                                                <td id="priceIndication2_view" class="pe-3">-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- 6. PACKAGING & MARKET UPDATE -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="ph-bold ph-package text-success fs-5"></i>
                                        <h6 class="fw-bold mb-0 text-dark">Detailed Packaging Required</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold">Weight of Product (kg):</div>
                                            <div class="fw-bold text-dark fs-6" id="weightProduct_view">-</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold">Packaging Type:</div>
                                            <div class="fw-bold text-dark fs-6" id="packagingType_view">-</div>
                                        </div>
                                        <div>
                                            <div class="small text-muted fw-bold">Product Variation List:</div>
                                            <div class="fw-bold text-dark fs-6" id="productVariation_view">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="ph-bold ph-chart-line-up text-white fs-5"></i>
                                        <h6 class="fw-bold mb-0 text-dark">Market Update & Strategy</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold">Potential Volume (Mt/Annually):</div>
                                            <div class="fw-bold text-dark fs-6" id="potentialVolume_view">-</div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="small text-muted fw-bold">Expected Margin (%):</div>
                                                <div class="fw-bold text-dark fs-6" id="expectedMargin_view">-</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="small text-muted fw-bold">Price Estimate:</div>
                                                <div class="fw-bold text-dark fs-6" id="priceEstimate_view">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 7. APPROVAL STATUS CARD WITH BORDER -->
                        <div class="card border border-2 border-primary border-opacity-25 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-primary bg-opacity-10 py-3 border-bottom border-primary border-opacity-25 d-flex align-items-center gap-2">
                                <i class="ph-bold ph-check-square-offset text-white fs-5"></i>
                                <h6 class="fw-bold mb-2 text-white">Approval History & Status</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="approverTable" class="table table-bordered table-striped align-middle mb-0 w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Approved by</th>
                                                <th class="text-center">Approved</th>
                                                <th class="text-center">Approved with Review</th>
                                                <th class="text-center">Not Approved</th>
                                                <th class="text-center pe-3">Notes / Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <form id="approvalActionForm" method="POST" class="w-100">
                        @csrf
                        <div class="d-flex flex-column align-items-center">
                            <textarea name="comment" id="comment" class="form-control rounded-lg w-100 border border-2 mb-2" style="max-width:550px; resize: none;" rows="4" placeholder="Enter a comment if needed..."></textarea>
                            <div id="textarea-warning" class="d-none fw-bold text-danger mb-2">A comment is required for this action.</div>
                            <div class="d-flex justify-content-center flex-wrap">
                                <input type="hidden" name="action" id="action" value="">
                                <button type="button" class="m-2 px-4 py-2 btn btn-success" onclick="submitForm('approve')"><i class="ph-bold ph-check-circle me-1"></i>Approve</button>
                                <button type="button" class="m-2 px-4 py-2 btn btn-warning" onclick="validateForm('approve with review')"><i class="ph-bold ph-warning-circle me-1"></i>Approve with Review</button>
                                <button type="button" class="m-2 px-4 py-2 btn btn-danger" onclick="validateForm('not approve')"><i class="ph-bold ph-x-circle me-1"></i>Not Approve</button>
                                <button type="button" class="m-2 px-4 py-2 btn btn-secondary" data-bs-dismiss="modal"><i class="ph-bold ph-x me-1"></i>Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // === SEMUA KODE JS TETAP SAMA, TIDAK ADA PERUBAHAN LOGIC ===
        $(function() {
            const mainTable = $('#prempdrTable').DataTable({
                processing: true,
                serverSide: false,
                autoWidth: false,
                ajax: { url: "{{ route('mpdr.approval.list.data') }}", dataSrc: '' },
                columns: [
                    { data: 'no', name: 'no', width: '15%', className: 'text-center align-middle text-nowrap', render: data => `<span class="status-badge-lg bg-dark">${data}</span>` },
                    { data: 'product_name', name: 'product_name', width: '25%', className: 'align-middle', render: data => `<div class="d-flex align-items-center"><i class="ph-bold ph-folder-simple me-2 text-primary fs-5"></i><span>${data}</span></div>` },
                    { data: 'created_at', name: 'created_at', width: '15%', className: 'text-center align-middle text-nowrap', render: function(data) {
                        if (!data) return '-';
                        const date = new Date(data);
                        return `<div class="d-flex align-items-center justify-content-center"><i class="ph ph-calendar-check me-2 text-primary fs-5"></i><span>${date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })}</span></div>`;
                    }},
                    { data: 'approver_name', name: 'approver_name', width: '20%', className: 'align-middle', render: function(data, type, row) {
                        if (!data) return '<span class="text-muted fst-italic">-</span>';
                        return `<span class="badge bg-warning text-dark px-2 py-1 fs-6 text-nowrap"><i class="ph-bold ph-user-circle me-1"></i>${data}</span>`;
                    }},
                    { data: 'status', name: 'status', width: '15%', className: 'text-center align-middle text-nowrap', render: function(data, type, row) {
                        let statusClass = { 'Rejected': 'bg-danger', 'Approved': 'bg-success', 'In Approval': 'bg-info', 'Draft': 'bg-warning' }[data] || 'bg-secondary';
                        let progress = (data === 'In Approval' && row.total_approvers > 0) ? ` (${row.approved_count}/${row.total_approvers})` : '';
                        return `<span class="status-badge-lg ${statusClass}">${data}${progress}</span>`;
                    }},
                    { data: null, name: 'action', orderable: false, searchable: false, width: '10%', className: 'text-center align-middle text-nowrap',
                        render: function(data, type, row) {
                            let resendBtn = '';
                            @can('view resend mpdr')
                            resendBtn = `<button type="button" class="btn btn-sm btn-warning resend-line-btn ms-1" data-form-no="${row.no}" data-approver-nik="${row.approver_nik}" data-approver-name="${row.approver_name}" data-bs-toggle="tooltip" title="Resend Email to ${row.approver_name}"><i class="ph-bold ph-paper-plane-tilt"></i></button>`;
                            @endcan

                            return `<div class="d-flex gap-1 justify-content-center align-items-center">
                                <button type="button" class="btn btn-sm btn-primary review-approval-btn" data-form-no="${row.no}" data-bs-toggle="modal" data-bs-target="#approvalModal" title="Review Approval"><i class="ph-bold ph-eye"></i></button>
                                ${resendBtn}
                            </div>`;
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
                        mainTable.columns.adjust();
                    }
                }, 100);
            });

            // Event listener untuk Kirim Ulang Email Notifikasi MPDR per line user (tanpa modal select)
            $(document).on('click', '.resend-line-btn, .resend-email-btn', function() {
                const formNo = $(this).data('form-no');
                const approverNik = $(this).data('approver-nik');
                const approverName = $(this).data('approver-name') || 'approver';
                const btn = $(this);

                Swal.fire({
                    title: `Resend Email (${formNo})`,
                    text: `Resend the approval notification to ${approverName}? The previous email link will expire.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#eab308',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="ph-bold ph-paper-plane-tilt me-1"></i> Resend Email',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.prop('disabled', true);
                        $.ajax({
                            url: "{{ url('mpdr/resend-email') }}/" + formNo,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                approver_nik: approverNik
                            },
                            success: function(res) {
                                if (res.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Email Terkirim!',
                                        text: res.message
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed',
                                        text: res.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'A server error occurred.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: msg
                                });
                            },
                            complete: function() {
                                btn.prop('disabled', false);
                            }
                        });
                    }
                });
            });

            const competitorTable = $('#competitorProduct').DataTable({ responsive: true, ordering: false, paging: false, searching: false, info: false });
            const approverTable = $('#approverTable').DataTable({ responsive: true, ordering: false, paging: false, searching: false, info: false, columnDefs: [ { targets: [1,2,3,4], className: 'text-center' } ] });

            $('#prempdrTable tbody').on('click', '.review-approval-btn', function () {
                const no_reg = $(this).data('form-no');
                loadFormData(no_reg);
            });

            function loadFormData(no_reg) {
                const modal = $('#approvalModal');
                const overlay = modal.find('.loading-overlay');
                overlay.show();

                modal.find('#approvalModalLabel').html(`<i class="ph-bold ph-file-text me-2"></i>Review MPDR Form: ${no_reg}`);
                const formActionUrl = "{{ route('mpdr.form.approve', ['no_reg' => ':no_reg']) }}".replace(':no_reg', no_reg);
                $('#approvalActionForm').attr('action', formActionUrl);

                const detailUrl = "{{ route('mpdr.form.detail', ['formId' => ':formId']) }}".replace(':formId', no_reg);

                $.ajax({
                    url: detailUrl, // <-- MENGGUNAKAN URL YANG SUDAH BENAR
                    method: 'GET',
                    // 'data' tidak lagi diperlukan karena ID sudah ada di URL
                    success: function(response) {
                        populateModal(response, no_reg);
                        overlay.hide();
                    },
                    error: function() {
                        overlay.hide();
                        Swal.fire('Error', 'Failed to load form data. Please try again.', 'error');
                        modal.modal('hide');
                    }
                });
            }

            function formatPriceJs(val, fallback = '-') {
                if (val === null || val === undefined || val === '' || val === 'N/A' || val === '-') {
                    return fallback;
                }
                let str = String(val).trim();
                if (!str || str === '-' || str.toLowerCase() === 'n/a') {
                    return fallback;
                }
                let prefix = '';
                const match = str.match(/^(rp\.?|idr)\s*/i);
                if (match) {
                    prefix = match[0];
                    str = str.substring(prefix.length).trim();
                }
                if (/^\d{1,3}(\.\d{3})+(,\d+)?$/.test(str)) {
                    return prefix + str;
                }
                if (/^\d{1,3}(,\d{3})+(\.\d+)?$/.test(str)) {
                    let parts = str.split('.');
                    let intPart = parts[0].replace(/,/g, '.');
                    let decPart = parts[1] && parts[1].length > 0 ? (',' + parts[1]) : '';
                    return prefix + intPart + decPart;
                }
                if (str.includes(',') && !str.includes('.')) {
                    let parts = str.split(',');
                    let num = parseFloat(parts[0]);
                    if (!isNaN(num)) {
                        let intFormatted = num.toLocaleString('id-ID');
                        let decFormatted = parts[1] ? (',' + parts[1]) : '';
                        return prefix + intFormatted + decFormatted;
                    }
                }
                let num = Number(str);
                if (!isNaN(num)) {
                    let parts = str.split('.');
                    let intFormatted = Math.floor(Math.abs(num)).toLocaleString('id-ID');
                    if (num < 0) intFormatted = '-' + intFormatted;
                    let decFormatted = '';
                    if (parts[1]) {
                        let dec = parts[1].replace(/0+$/, '');
                        if (dec.length > 0) decFormatted = ',' + dec;
                    }
                    return prefix + intFormatted + decFormatted;
                }
                return prefix + str;
            }

            function populateModal(data, no_reg) {
                // Executive Banner & KPI Updates
                $('#no_reg_text, #no_reg_badge').text(no_reg);
                $('#revision_badge').text('REV ' + (data.revision?.no || '0'));
                $('#status_badge').text(data.status || 'In Approval');
                $('#banner_product_name').text(data.product_name || '-');
                $('#banner_initiator').text(data.initiator || '-');
                $('#banner_priority').html(`<i class="ph-bold ph-fire me-1"></i> ${data.level_priority || 'Normal'}`);

                const catName = data.category?.category || '-';
                const catOther = data.category?.other ? ` (${data.category.other})` : '';
                $('#kpi_category').text(catName + catOther);

                const chName = data.channel?.category || '-';
                const chCountry = data.channel?.country ? ` (${data.channel.country})` : '';
                $('#kpi_channel').text(chName + chCountry);

                $('#kpi_target_launch').text(data.detail?.target_launch || '-');

                const certName = data.certification?.category || '-';
                const certOther = data.certification?.other ? ` (${data.certification.other})` : '';
                $('#kpi_cert').text(certName + certOther);

                $('#rationalForDevelopment_view').text(data.detail?.rational_for_development || 'No rational provided.');

                $('#productDescription_view').html(data.description?.product_description || 'N/A');
                $('#usageDescription_view').text(data.description?.usage_description || 'N/A');
                $('#storageTemperature_view').text(data.description?.storage_temperature || 'N/A');
                $('#deliveryTemperature_view').text(data.description?.delivery_temperature || 'N/A');

                // Competitor Product Table
                $('#productName1_view').text(data.competitor?.[0]?.name || '-');
                $('#size1_view').text(data.competitor?.[0]?.size || '-');
                $('#packaging1_view').text(data.competitor?.[0]?.packaging || '-');
                $('#priceIndication1_view').text(formatPriceJs(data.competitor?.[0]?.price, '-'));

                $('#productName2_view').text(data.competitor?.[1]?.name || '-');
                $('#size2_view').text(data.competitor?.[1]?.size || '-');
                $('#packaging2_view').text(data.competitor?.[1]?.packaging || '-');
                $('#priceIndication2_view').text(formatPriceJs(data.competitor?.[1]?.price, '-'));

                // Packaging & Market
                $('#weightProduct_view').text(data.packaging?.weight || '-');
                const pkgCat = data.packaging?.category || '-';
                const pkgDetail = data.packaging?.detail ? ` - ${data.packaging.detail}` : '';
                $('#packagingType_view').text(pkgCat + pkgDetail);
                $('#productVariation_view').text(data.packaging?.product_variation || '-');

                $('#potentialVolume_view').text(data.market?.potential_volume || '-');
                $('#expectedMargin_view').text(data.market?.expected_margin || '-');
                $('#priceEstimate_view').text(formatPriceJs(data.market?.price_estimate, '-'));

                // Approver Table History
                approverTable.clear();
                data.approved_detail.forEach(function(detail) {
                    let approvedCell = '', approvedWithReviewCell = '', notApprovedCell = '', commentsCell = detail.comment || '-';
                    if (detail.status !== 'pending' && detail.status !== 'vacant' && detail.status !== 'Vacant') {
                        const rawSt = (detail.status || '').toString().trim().toLowerCase();
                        let displaySt = detail.status;
                        if (rawSt === 'approve' || rawSt === 'approved') displaySt = 'Approved';
                        else if (rawSt === 'approve with review' || rawSt === 'approved with review') displaySt = 'Approved with Review';
                        else if (rawSt === 'not approve' || rawSt === 'not approved') displaySt = 'Not Approved';
                        else if (rawSt === 'reject' || rawSt === 'rejected') displaySt = 'Rejected';

                        const newDiv = `<div class="d-flex flex-column"><div>${displaySt}</div><div>${detail.approved_date || ''}</div></div>`;
                        if (rawSt === 'approve' || rawSt === 'approved') approvedCell = newDiv;
                        else if (rawSt === 'approve with review' || rawSt === 'approved with review') {
                            approvedWithReviewCell = newDiv;
                        } else if (rawSt === 'not approve' || rawSt === 'not approved' || rawSt === 'reject' || rawSt === 'rejected') {
                            notApprovedCell = newDiv;
                        }
                    }
                    approverTable.row.add([detail.approver_position || detail.position || detail.approver_name, approvedCell, approvedWithReviewCell, notApprovedCell, commentsCell]);
                });
                approverTable.draw();
            }

            $('#approvalModal').on('hidden.bs.modal', function(){
                $('#approvalActionForm')[0].reset();
                $('#textarea-warning').addClass('d-none');
                approverTable.clear().draw();
            });
        });

        function submitForm(actionValue) {
            Swal.fire({
                title: "Confirm Action",
                text: `Are you sure you want to '${actionValue}' this form?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Proceed!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $("#approvalActionForm");
                    const modal = $('#approvalModal');
                    const overlay = modal.find('.loading-overlay');
                    
                    overlay.find('h5').text('Processing Action...');
                    overlay.show();

                    // [PERBAIKAN UTAMA] Mengirim form via AJAX
                    $.ajax({
                        url: form.attr('action'), // URL sudah benar dari fungsi loadFormData
                        method: 'POST',
                        data: {
                            _token: form.find('input[name="_token"]').val(),
                            action: actionValue,
                            comment: $('#comment').val()
                        },
                        success: function(response) {
                            overlay.hide();
                            modal.modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message
                            });
                            $('#prempdrTable').DataTable().ajax.reload(); // Muat ulang tabel
                        },
                        error: function(xhr) {
                            overlay.hide();
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message || 'Something went wrong!'
                            });
                        }
                    });
                }
            });
        }

        function validateForm(type) {
            const comment = $('#comment').val().trim();
            const warning = $('#textarea-warning');
            if (comment === '') {
                warning.removeClass('d-none');
            } else {
                warning.addClass('d-none');
                submitForm(type);
            }
        }
    </script>
@endpush
</x-app-layout>