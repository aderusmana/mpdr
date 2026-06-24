<x-app-layout>
    @section('title')
        Pre-MPDR
    @endsection

    {{-- Menggunakan style yang baru --}}
    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    {{-- Judul dan Breadcrumb --}}
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Pre-MPDR List</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="#">
                        <i class="ph-duotone ph ph-note-pencil f-s-16"></i> Forms
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Pre-MPDR</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Filter dan Tombol Create --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted fw-bold">Filter by:</span>
                    <select id="statusFilter" class="form-select select2" style="width: 200px;">
                        <option value="all">All Statuses</option>
                        <option value="Draft">Draft</option>
                        <option value="In Approval">In Approval</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                    <button id="resetFilters" class="btn btn-secondary border" data-bs-toggle="tooltip"
                        title="Reset Filters">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </button>
                </div>
                <div>
                    @can('view create pre')
                        {{-- Tombol ini sekarang akan membuka modal --}}
                        <button class="btn new-mpdr-btn" type="button" data-bs-toggle="modal"
                            data-bs-target="#prempdrModal" id="btn-create-prempdr">
                            <i class="ph-bold ph-plus"></i>
                            <span>New Pre-MPDR</span>
                        </button>
                    @endcan


                </div>
            </div>

            {{-- Kontainer Tabel Baru --}}
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-list"></i>
                        Pre-MPDR List
                    </h4>
                    <p class="table-subtitle">View, manage and track all Pre-MPDR submissions</p>
                </div>
                <div class="table-responsive">
                    <table class="w-100 display" id="prempdrTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Reg</th>
                                <th>Project Name</th>
                                <th>Request Date</th>
                                <th>Route To</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UNTUK CREATE/EDIT PRE-MPDR --}}
    <div class="modal fade" id="prempdrModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="loading-overlay" style="display: none;">
                    <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="mt-3 fw-bold">Processing...</h5>
                </div>

                <div class="modal-header">
                    <h5 class="modal-title text-white" id="prempdrModalLabel"><i
                            class="ph-bold ph-note-pencil me-2"></i>Create New Pre-MPDR</h5>
                    <button type="button" class="btn-close btn-close-white m-0 fs-5" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="prempdrForm" enctype="multipart/form-data" action="{{ route('prempdr.store') }}"
                    method="POST">
                    @csrf
                    <div class="modal-body p-4" style="background-color: #f9fbfd;">
                        {{-- Bagian Header Form --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="text-primary fw-bolder">Details</h4>
                            <div class="text-end">
                                <label class="form-label mb-0">No Reg: <span id="no_reg_text"
                                        class="fw-bold"></span></label>
                                <input type="hidden" id="no_reg" name="no_reg" value="" readonly>
                            </div>
                        </div>

                        {{-- CARD 1: INFORMASI DASAR --}}
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <h6 class="fw-bold text-primary mb-0"><i
                                        class="ph-bold ph-identification-card me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label for="projectName" class="form-label">Project Name<i
                                                class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="projectName"
                                            placeholder="Enter project name" required>
                                        <div class="invalid-feedback" id="projectName_error"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="levelPriority" class="form-label">Priority<i
                                                class="text-danger">*</i></label>
                                        <select class="form-select" name="levelPriority" required>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                        </select>
                                        <div class="invalid-feedback" id="levelPriority_error"></div>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="brandName" class="form-label">Proposed Brand Name<i
                                                class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="brandName"
                                            placeholder="Enter brand name" required>
                                        <div class="invalid-feedback" id="brandName_error"></div>
                                    </div>
                                    <div class="col-12">
                                        <label for="rationalForDevelopment" class="form-label">Rational For
                                            Development<i class="text-danger">*</i></label>
                                        <textarea class="form-control no-resize" name="rationalForDevelopment" rows="2"
                                            placeholder="Explain the reason for this development" required></textarea>
                                        <div class="invalid-feedback" id="rationalForDevelopment_error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD 2: KATEGORI & SPESIFIKASI --}}
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <h6 class="fw-bold text-primary mb-0"><i class="ph-bold ph-tag me-2"></i>Category &
                                    Specifications</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    {{-- Product Category --}}
                                    <div class="col-md-6" id="productCategory">
                                        <label class="form-label fw-bold">Product Category<i
                                                class="text-danger">*</i></label>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="productCategory" id="productCategory1" value="Margarine"
                                                required><label class="form-check-label"
                                                for="productCategory1">Margarine</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="productCategory" id="productCategory2"
                                                value="Frying Fats"><label class="form-check-label"
                                                for="productCategory2">Frying Fats</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="productCategory" id="productCategory3"
                                                value="Shortening"><label class="form-check-label"
                                                for="productCategory3">Shortening</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="productCategory" id="productCategory4" value="Pastry"><label
                                                class="form-check-label" for="productCategory4">Pastry</label></div>
                                        {{-- [PERBAIKAN STRUKTUR HTML] --}}
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input class="form-check-input" type="radio" name="productCategory"
                                                id="productCategory5" value="Others">
                                            <label class="form-check-label" for="productCategory5">Others</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="productCategoryText" name="productCategoryText"
                                                placeholder="(Specify)" readonly style="width: 150px;">
                                        </div>
                                    </div>

                                    {{-- Channel --}}
                                    <div class="col-md-6" id="channel">
                                        <label class="form-label fw-bold">Channel<i class="text-danger">*</i></label>
                                        {{-- [PERBAIKAN STRUKTUR HTML] --}}
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input class="form-check-input" type="radio" name="channel"
                                                id="channel1" value="International" required>
                                            <label class="form-check-label" for="channel1">International</label>
                                            <input type="text" class="form-control form-control-sm" name="country"
                                                id="country" placeholder="Country" readonly style="width: 150px;">
                                        </div>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="channel" id="channel2" value="Regional"><label
                                                class="form-check-label" for="channel2">Regional</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="channel" id="channel3" value="Industrial"><label
                                                class="form-check-label" for="channel3">Industrial</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="channel" id="channel4" value="FSBC-Direct"><label
                                                class="form-check-label" for="channel4">FSBC-Direct</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="channel" id="channel5" value="FSBC-Distributor"><label
                                                class="form-check-label" for="channel5">FSBC-Distributor</label></div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Product Description <span
                                                class="fw-normal">(Form/Color/Flavor brief)</span><i
                                                class="text-danger">*</i></label>
                                        <textarea class="form-control no-resize" name="productDescription" rows="3"
                                            placeholder="Describe the product form, color, and flavor" required></textarea>
                                        <div class="invalid-feedback" id="productDescription_error"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Usage Description <span
                                                class="fw-normal">(Application in customer)</span><i
                                                class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="usageDescription"
                                            placeholder="Describe how the customer will use this product" required>
                                        <div class="invalid-feedback" id="usageDescription_error"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Storage Temperature<i
                                                class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="storageTemperature"
                                            placeholder="e.g., 25°C" required>
                                        <div class="invalid-feedback" id="storageTemperature_error"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Delivery Temperature<i
                                                class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="deliveryTemperature"
                                            placeholder="e.g., Ambient" required>
                                        <div class="invalid-feedback" id="deliveryTemperature_error"></div>
                                    </div>
                                    <div class="col-md-6" id="certification">
                                        <label class="form-label fw-bold">Certification Requirement<i
                                                class="text-danger">*</i></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="certification"
                                                id="certification1" value="BPOM" required>
                                            <label class="form-check-label" for="certification1">BPOM</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="certification"
                                                id="certification2" value="HALAL">
                                            <label class="form-check-label" for="certification2">HALAL</label>
                                        </div>
                                        {{-- [PERBAIKAN STRUKTUR HTML] --}}
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input class="form-check-input" type="radio" name="certification"
                                                id="certification3" value="Others">
                                            <label class="form-check-label" for="certification3">Others</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="certificationText" id="certificationText"
                                                placeholder="(Specify)" readonly style="width: 150px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD 3: KEMASAN, PASAR, & TARGET --}}
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <h6 class="fw-bold text-primary mb-0"><i
                                        class="ph-bold ph-package me-2"></i>Packaging, Market & Target</h6>
                            </div>
                            <div class="card-body">
                                <div id="competitor">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold mb-2">Competitor's Product to Match or to Beat:</h5>
                                        <button type="button" class="btn btn-sm btn-success"
                                            id="add-competitor-btn">
                                            <i class="ph-bold ph-plus me-1"></i> Add Row
                                        </button>
                                    </div>
                                    <table class="table table-bordered table-sm mt-2" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 5%;" class="text-center">No</th>
                                                <th>PRODUCT NAME</th>
                                                <th>SIZE</th>
                                                <th>PACKAGING</th>
                                                <th>Price Indication</th>
                                                <th style="width: 5%;" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="competitor-tbody">
                                            {{-- Baris dinamis akan ditambahkan di sini oleh JavaScript --}}
                                        </tbody>
                                    </table>
                                </div>
                                <hr class="my-4">
                                <div id="detailedPackaging">
                                    <h5>Detailed Packaging Required:</h5>
                                    <ol>
                                        <li>
                                            <div class="">
                                                <label for="weightProduct" class="form-label">Weight of Product
                                                    (kg/carton):</label>
                                                <input type="number" class="form-control" name="weightProduct"
                                                    required>
                                            </div>
                                        </li>
                                        <li>
                                            <label class="form-label">Packaging:</label>
                                            <div class="row">
                                                <div class="form-check col">
                                                    <input class="form-check-input" type="radio" name="packaging"
                                                        id="existingPackaging" value="Existing" required>
                                                    <label class="form-check-label" for="existingPackaging">
                                                        Existing
                                                    </label>
                                                    <textarea class="form-control no-resize" name="ExistingPackagingText" id="ExistingPackagingText"
                                                        placeholder="Details" rows="2" readonly></textarea>
                                                </div>
                                                <div class="form-check col">
                                                    <input class="form-check-input" type="radio" name="packaging"
                                                        id="newPackaging" value="New">
                                                    <label class="form-check-label" for="newPackaging">
                                                        New
                                                    </label>
                                                    <textarea class="form-control no-resize" name="NewPackagingText" id="NewPackagingText" placeholder="Details"
                                                        rows="2" readonly></textarea>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <label class="form-label">Product Variation List:</label>
                                                <div>
                                                    <input type="text" class="form-control"
                                                        name="productVariation" id="" required>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                                <hr class="my-4">
                                <div id="potential">
                                    <h5>MARKET UPDATE</h5>
                                    <ol>
                                        <li>
                                            <div class="form-chcek">
                                                <label for="potentialVolume" class="form-label">Potential Volume
                                                    (Mt/Annually):</label>
                                                <input type="number" class="form-control" name="potentialVolume"
                                                    required>
                                            </div>
                                        </li>
                                        <li>
                                            <label class="form-label">Pricing Strategy</label>
                                            <div class="row">
                                                <div class="form-check col">
                                                    <label for="expectedMargin" class="form-label">Expected Margin
                                                        (%):</label>
                                                    <input type="text" class="form-control" name="expectedMargin"
                                                        required>
                                                </div>
                                                <div class="form-check col">
                                                    <label for="priceEstimate" class="form-label">Price
                                                        Estimate:</label>
                                                    <input type="text" class="form-control" name="priceEstimate"
                                                        required>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                                <hr class="my-4">
                                <div>
                                    <h5 class="fw-bold">TARGET LAUNCH (as Initiator request)<i
                                            class="text-danger">*</i></h5>
                                    <input type="text" class="form-control" name="targetLaunchText"
                                        placeholder="e.g., End wk of January - ETD Shipment (for first trial 1 FCL-2 FCL)"
                                        required>
                                    <div class="invalid-feedback" id="targetLaunchText_error"></div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD 4: APPROVAL TRACKING (MOVED TO BOTTOM) --}}
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <h5 class="fw-bold text-primary mb-3"><i class="ph-bold ph-path me-2"></i> Approval &
                                    Process Tracking</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <span class="fw-bold me-3">Current Status:</span>
                                    <div id="view_status_badge"></div>
                                </div>
                                <div class="tracker-container" id="approval-tracker-container">
                                    <div class="tracker-line">
                                        <div class="tracker-line-progress" id="tracker-progress"></div>
                                    </div>
                                    <div class="tracker-step" data-step-name="Maker">
                                        <div class="tracker-icon"><i class="ph-bold ph-file-arrow-up fs-6"></i></div>
                                        <div class="tracker-label">Submitted</div>
                                        <div class="tracker-details"></div>
                                    </div>
                                    <div class="tracker-step" data-step-name="Atasan">
                                        <div class="tracker-icon"><i class="ph-bold ph-user-plus fs-6"></i></div>
                                        <div class="tracker-label">Atasan</div>
                                        <div class="tracker-details"></div>
                                    </div>
                                    <div class="tracker-step" data-step-name="Asisten Manager Marketing">
                                        <div class="tracker-icon"><i class="ph-bold ph-users fs-6"></i></div>
                                        <div class="tracker-label">Asisten Manager Marketing</div>
                                        <div class="tracker-details"></div>
                                    </div>
                                    <div class="tracker-step" data-step-name="Manager Marketing">
                                        <div class="tracker-icon"><i class="ph-bold ph-user-list fs-6"></i></div>
                                        <div class="tracker-label">Manager Marketing</div>
                                        <div class="tracker-details"></div>
                                    </div>
                                    <div class="tracker-step" data-step-name="Dephead S&M">
                                        <div class="tracker-icon"><i class="ph-bold ph-user-gear fs-6"></i></div>
                                        <div class="tracker-label">DepHead S&M</div>
                                        <div class="tracker-details"></div>
                                    </div>
                                    <div class="tracker-step" data-step-name="Completed">
                                        <div class="tracker-icon"><i class="ph-bold ph-check-circle fs-6"></i></div>
                                        <div class="tracker-label">Completed</div>
                                        <div class="tracker-details"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <h5 class="fw-bold text-primary mb-3"><i
                                        class="ph-bold ph-clock-counter-clockwise me-2"></i> Pre MPDR History</h5>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-group list-group-flush" id="history-log-container">
                                    {{-- History akan diisi oleh JavaScript di sini --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="form_status" value="Submit"
                            class="btn btn-success btn-submit"><i
                                class="ph-bold ph-paper-plane-tilt me-1"></i>Submit</button>
                        <button type="submit" name="form_status" value="Draft"
                            class="btn btn-primary btn-submit"><i class="ph-bold ph-floppy-disk me-1"></i>Save as
                            Draft</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="ph-bold ph-x me-1"></i>Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(function() {
                // Inisialisasi Select2 untuk filter
                $('#statusFilter').select2({
                    theme: 'bootstrap-5',
                    minimumResultsForSearch: Infinity
                });

                // Inisialisasi DataTable dengan AJAX
                const table = $('#prempdrTable').DataTable({
                    processing: true,
                    serverSide: false, // Ini sudah benar
                    // order: [[ 3, 'desc' ]],
                    ajax: {
                        url: "{{ route('prempdr.form.list') }}",
                        dataSrc: function(json) {
                            const statusFilter = $('#statusFilter').val();
                            if (statusFilter === 'all') {
                                return json;
                            }
                            return json.filter(item => item.status === statusFilter);
                        }
                    },
                    columns: [{
                            data: null, // Data null untuk penomoran otomatis
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            width: '20px',
                            className: 'text-center',
                            // [PERBAIKAN UTAMA] Ini akan membuat nomor urut 1, 2, 3, ...
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'no',
                            name: 'no',
                            // [Tampilan Baru] Menambahkan badge pada No. Registrasi
                            render: function(data) {
                                return `<span class="status-badge-lg bg-dark">${data}</span>`;
                            }
                        },
                        {
                            data: 'project_name',
                            name: 'project_name',
                            // [Tampilan Baru] Menambahkan ikon dan membuat teks tebal
                            render: function(data) {
                                return `<div class="d-flex align-items-center">
                                    <i class="ph-bold ph-folder-simple me-2 text-primary fs-5"></i>
                                    <span>${data}</span>
                                </div>`;
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data) {
                                if (!data) return '-';
                                // Format tanggal menjadi: 23 Oct 2025
                                const date = new Date(data);
                                const formattedDate = date.toLocaleDateString('en-GB', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric'
                                });

                                // Gabungkan ikon dengan teks
                                return `<div class="d-flex align-items-center">
                                    <i class="ph ph-calendar-check me-2 text-primary fs-5"></i>
                                    <span>${formattedDate}</span>
                                </div>`;
                            }
                        },
                        {
                            data: 'route_to',
                            name: 'route_to',
                            // [Tampilan Baru] Menambahkan badge dan ikon user
                            render: function(data) {
                                if (!data) return '<span class="text-muted fst-italic">-</span>';
                                return `<span class="status-badge-lg bg-warning d-flex align-items-center">
                                    <i class="ph-bold ph-user-circle me-1 fs-6"></i>
                                    ${data}
                                </span>`;
                            }
                        },
                        {

                            data: 'status',
                            name: 'status',
                            render: function(data, type, row) {
                                let statusClass = '';
                                switch (data) {
                                    case 'Rejected':
                                        statusClass = 'bg-danger';
                                        break;
                                    case 'Approved':
                                        statusClass = 'bg-success';
                                        break;
                                    case 'In Approval':
                                        statusClass = 'bg-info';
                                        break;
                                    case 'Draft':
                                        statusClass = 'bg-warning';
                                        break;
                                }
                                let progress = '';
                                if (data === 'In Approval' && row.approved_detail && row
                                    .approved_detail[0]) {
                                    progress =
                                        `(${row.approved_detail[0].approved_count}/${row.approved_detail[0].total})`;
                                }
                                return `<span class="status-badge-lg ${statusClass}">${data} ${progress}</span>`;
                            }
                        },
                        {
                            data: null,
                            name: 'action',
                            className: 'text-center',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                if (row.status === 'Draft') {
                                    const deleteRoute = "{{ route('prempdr.destroy', ':formId') }}"
                                        .replace(':formId', row.no);
                                    return `
                            <div class="d-flex gap-1">
                                @can('view edit pre')
                                    <button type="button" class="btn btn-sm btn-primary edit-form-btn" data-form-id="${row.no}" data-bs-toggle="tooltip" title="Edit Form"><i class="ph-bold ph-pencil-simple"></i></button>
                                @endcan

                                @can('view delete pre')
                                    <form id="delete-form-${row.no}" action="${deleteRoute}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" data-form-no="${row.no}" onclick="confirmDelete(this)" data-bs-toggle="tooltip" title="Delete Form"><i class="ph-bold ph-trash"></i></button>
                                    </form>
                                @endcan
                            </div>`;
                                } else {
                                    return `<button type="button" class="btn btn-sm btn-info view-form-btn" data-form-id="${row.no}" data-bs-toggle="tooltip" title="View Details"><i class="ph-bold ph-eye"></i></button>`;
                                }
                            }
                        }
                    ],
                    "drawCallback": function(settings) {
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                            '[data-bs-toggle="tooltip"]'));
                        tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    }
                });

                // Event listener untuk filter status
                $('#statusFilter').on('change', function() {
                    table.ajax.reload();
                });

                // Event listener untuk reset filter
                $('#resetFilters').on('click', function() {
                    $('#statusFilter').val('all').trigger('change');
                });

                function clearValidationErrors() {
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                }

                // Fungsi untuk mereset form di dalam modal
                function resetForm() {
                    $('#prempdrForm')[0].reset();
                    $('#prempdrForm :input').prop('disabled', false);
                    $('.invalid-feedback').text('');
                    $('.form-control, .form-select').removeClass('is-invalid');
                }

                function fetchNewRegNumber() {
                    $.get("{{ route('prempdr.noReg') }}", function(data) {
                        $('#no_reg_text').text(data.no_reg);
                        $('#no_reg').val(data.no_reg);
                    });
                }

                $('#btn-create-prempdr').on('click', function() {
                    resetForm();
                    $('#prempdrModalLabel').html(
                        '<i class="ph-bold ph-note-pencil me-2"></i>Create New Pre-MPDR');
                    $('.btn-submit').show();
                    $('#prempdrForm').attr('action', "{{ route('prempdr.store') }}");

                    $.get("{{ route('prempdr.noReg') }}", function(data) {
                        $('#no_reg_text').text(data.no_reg);
                        $('#no_reg').val(data.no_reg);
                    });

                    $('#competitor-tbody').empty();
                    competitorRowIndex = 0;
                    addCompetitorRow();
                });

                $('#prempdrTable tbody').on('click', '.view-form-btn, .edit-form-btn', function() {
                    const btn = $(this); // 1. Simpan referensi tombol yang di-klik
                    const originalHtml = btn.html(); // 2. Simpan konten asli tombol (ikonnya)

                    // 3. Langsung ubah tombol menjadi loading state & nonaktifkan
                    btn.prop('disabled', true).html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`
                    );

                    var formId = $(this).data('form-id');
                    var isView = $(this).hasClass('view-form-btn');
                    var url = "{{ url('prempdr/detail') }}/" + formId;

                    resetForm();

                    if (isView) {
                        $('#prempdrModalLabel').html(
                            `<i class="ph-bold ph-eye me-2"></i>View Details: ${formId}`);
                        $('.btn-submit').hide();
                    } else {
                        $('#prempdrModalLabel').html(
                            `<i class="ph-bold ph-pencil-simple me-2"></i>Edit Pre-MPDR: ${formId}`);
                        $('.btn-submit').show();
                        $('#prempdrForm').attr('action', "{{ url('prempdr/update') }}/" + formId);
                        if (!$('#prempdrForm').find('input[name="_method"]').length) {
                            $('#prempdrForm').append('<input type="hidden" name="_method" value="PATCH">');
                        } else {
                            $('#prempdrForm').find('input[name="_method"]').val('PATCH');
                        }
                    }

                    $.get(url, function(data) {
                        console.log(data); // <-- TAMBAHKAN BARIS INI
                        populateForm(data); // Isi form dengan data
                        // Render approval tracker and history if available
                        try {
                            renderApprovalTracker(data);
                            renderHistory(data);
                        } catch (err) {
                            console.warn('Tracker/History render error:', err);
                        }
                        if (isView) {
                            $('#prempdrModal .modal-body :input').prop('disabled', true);
                        }

                        $('#prempdrModal').modal('show'); // Tampilkan modal

                    }).fail(() => {
                        Swal.fire('Error', 'Failed to fetch details.', 'error');
                    }).always(() => {
                        // 4. Kembalikan tombol ke kondisi semula setelah semua selesai
                        btn.prop('disabled', false).html(originalHtml);
                    });
                });

                document.addEventListener('DOMContentLoaded', function() {
                    // Mengambil no_reg yang baru
                    fetch("{{ route('prempdr.noReg') }}")
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('no_reg_text').innerHTML = data.no_reg;
                            document.getElementById('no_reg').value = data.no_reg;
                        })
                        .catch(error => {
                            console.error('Error fetching no_reg:', error);
                        });


                    // mengambil list approver
                    $.ajax({
                        url: "{{ route('prempdr.approver.list.data') }}",
                        method: 'GET',
                        success: function(response) {
                            response.forEach(item => {
                                $('#initiator').append($('<option>', {
                                    value: item.nik,
                                    text: item.name
                                }));
                            });
                        },
                        error: function() {
                            // Jika gagal, tampilkan pesan error
                            console.log('Error ketika mengambil approver list');
                            // $('#formData').html('<p>There was an error fetching the data.</p>');
                        }
                    });
                });

                function handleRadioTextToggle(radioName, radioValue, textInputId) {
                    $('input[name="' + radioName + '"]').on('change', function() {
                        const textInput = $('#' + textInputId);
                        if ($(this).val() === radioValue && $(this).is(':checked')) {
                            textInput.prop('readonly', false).focus();
                        } else {
                            textInput.prop('readonly', true).val('');
                        }
                    });
                }

                /**
                 * Render approval tracker into the modal
                 * Expects `data.approval_tracker` with `steps` and `progress_percent`
                 */
                function renderApprovalTracker(data) {
                    const tracker = data.approval_tracker || {
                        steps: [],
                        progress_percent: 0,
                        current_status: data.status || ''
                    };
                    const steps = tracker.steps || [];

                    // Current status badge
                    const status = tracker.current_status || data.status || '';
                    let badgeClass = 'bg-secondary';
                    if (status === 'Approved') badgeClass = 'bg-success';
                    else if (status === 'Rejected') badgeClass = 'bg-danger';
                    else if (status === 'In Approval') badgeClass = 'bg-info';
                    else if (status === 'Draft') badgeClass = 'bg-warning';

                    $('#view_status_badge').html(`<span class="status-badge-lg ${badgeClass}">${status}</span>`);

                    // Build tracker HTML dynamically
                    const $container = $('#approval-tracker-container');
                    $container.empty();

                    const progressPercent = tracker.progress_percent || 0;
                    const $line = $(
                        `<div class="tracker-line"><div class="tracker-line-progress" id="tracker-progress" style="width: ${progressPercent}%;"></div></div>`
                        );
                    $container.append($line);

                    // For each step, create a tracker-step node
                    steps.forEach(function(step) {
                        const stepStatus = (step.status || '').toLowerCase();
                        let stepClass = '';
                        if (stepStatus === 'completed' || stepStatus === 'approve with review' || (
                                stepStatus !== 'pending' && stepStatus !== 'vacant' && stepStatus !== ''))
                            stepClass = 'completed';
                        if (stepStatus === 'rejected' || stepStatus === 'not approve') stepClass = 'rejected';

                        const label = step.label || step.name || '';
                        const date = step.date ? `<div class="text-muted small">${step.date}</div>` : '';
                        const comment = step.comment ?
                            `<div class="text-muted small fst-italic">${step.comment}</div>` : '';
                            
                        let iconClass = 'ph-check-circle';
                        let lbl = label.toLowerCase();
                        if(lbl.includes('submitted') || lbl.includes('maker')) iconClass = 'ph-file-arrow-up';
                        else if(lbl.includes('asisten manager') || lbl.includes('assistant')) iconClass = 'ph-users';
                        else if(lbl.includes('manager')) iconClass = 'ph-user-list';
                        else if(lbl.includes('atasan') || lbl.includes('supervisor') || lbl.includes('spv')) iconClass = 'ph-user-plus';
                        else if(lbl.includes('dephead') || lbl.includes('dept') || lbl.includes('s&m')) iconClass = 'ph-user-gear';

                        const $step = $(`
                    <div class="tracker-step ${stepClass}" data-step-name="${label}">
                        <div class="tracker-icon"><i class="ph-bold ${iconClass} fs-6"></i></div>
                        <div class="tracker-label">${label}</div>
                        <div class="tracker-details">
                            <div class="d-flex align-items-start gap-2">
                                <div class="comment-avatar" style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#06b6d4,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;">
                                    <i class="ph ph-user-circle fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    ${step.date ? '<div class="comment-date small text-muted mb-1">' + step.date + '</div>' : ''}
                                    ${step.comment ? '<div class="comment-bubble" style="padding:10px 12px;background:linear-gradient(90deg,rgba(99,102,241,0.06),rgba(99,102,241,0.02));border-radius:10px;border-left:4px solid rgba(99,102,241,0.6);box-shadow:0 6px 18px rgba(15,23,42,0.04);font-style:italic;color:#0f172a;">' + step.comment + '</div>' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                        $container.append($step);
                    });
                }

                /**
                 * Render activity history into `#history-log-container`.
                 * Expects `data.history` to be an array of {actor,event,time,properties}
                 */
                function renderHistory(data) {
                    const history = data.history || [];
                    const $list = $('#history-log-container');
                    $list.empty();

                    if (!history.length) {
                        $list.append('<li class="list-group-item text-muted">No history available.</li>');
                        return;
                    }

                    history.forEach(function(item) {
                        const actor = item.actor || 'System';
                        const event = item.event || '';
                        const time = item.time || '';
                        let propsHtml = '';
                        try {
                            if (item.properties && Object.keys(item.properties).length) {
                                let props = item.properties;
                                if (typeof props === 'string') {
                                    try {
                                        props = JSON.parse(props);
                                    } catch (e) {
                                        /* keep as string */ }
                                }

                                if (props && typeof props === 'object' && Object.keys(props).length) {
                                    const parts = [];
                                    Object.keys(props).forEach(key => {
                                        let val = props[key];
                                        if (val === null || val === undefined) val = '-';
                                        else if (typeof val === 'object') val = JSON.stringify(val);
                                        // Friendly label: replace underscores and capitalize words
                                        const label = key.replace(/_/g, ' ').replace(/\b\w/g, ch => ch
                                            .toUpperCase());
                                        parts.push(
                                            `<div><strong>${label}:</strong> ${String(val)}</div>`);
                                    });
                                    propsHtml = `<div class="small text-muted mt-2">${parts.join('')}</div>`;
                                } else {
                                    propsHtml = `<div class="small text-muted">No additional properties.</div>`;
                                }
                            }
                        } catch (e) {
                            propsHtml = '';
                        }

                        const $li = $(`
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div><strong>${actor}</strong> — ${event}</div>
                            <div class="text-muted small">${time}</div>
                        </div>
                        ${propsHtml}
                    </li>
                `);

                        $list.append($li);
                    });
                }

                // Terapkan fungsi ke semua grup radio button
                handleRadioTextToggle('productCategory', 'Others', 'productCategoryText');
                handleRadioTextToggle('channel', 'International', 'country');
                handleRadioTextToggle('certification', 'Others', 'certificationText');
                handleRadioTextToggle('packaging', 'Existing', 'ExistingPackagingText');
                handleRadioTextToggle('packaging', 'New', 'NewPackagingText');

                // mengatur readonly text pada input certification
                const certificationRadios = document.querySelectorAll('input[name="certification"]');
                const certificationText = document.getElementById('certificationText');
                certificationRadios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        if (radio.checked && radio.value == 'Others') {
                            certificationText.removeAttribute('readonly');
                        } else {
                            certificationText.setAttribute('readonly', true);
                            certificationText.value = '';
                        }
                    });
                });

                // mengatur readonly text pada input packaging
                const packagingRadios = document.querySelectorAll('input[name="packaging"]');
                packagingRadios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        var existingPackagingText = document.getElementById('ExistingPackagingText');
                        var newPackagingText = document.getElementById('NewPackagingText');
                        if (radio.checked && radio.value == 'Existing') {
                            existingPackagingText.removeAttribute('readonly');
                            newPackagingText.setAttribute('readonly', true);
                            newPackagingText.value = '';
                        } else {
                            newPackagingText.removeAttribute('readonly');
                            existingPackagingText.setAttribute('readonly', true);
                            existingPackagingText.value = '';
                        }
                    });
                });

                let competitorRowIndex = 0;

                // Fungsi untuk menambah baris baru
                function addCompetitorRow(data = {}) {
                    competitorRowIndex++;
                    const newRow = `
                <tr class="competitor-row">
                    <td class="text-center fw-bold">${competitorRowIndex}</td>
                    <td><input type="text" class="form-control" name="competitor_names[]" placeholder="Product Name" value="${data.name || ''}"></td>
                    <td><input type="text" class="form-control" name="competitor_sizes[]" placeholder="Size" value="${data.size || ''}"></td>
                    <td><input type="text" class="form-control" name="competitor_packagings[]" placeholder="Packaging" value="${data.packaging || ''}"></td>
                    <td><input type="text" class="form-control" name="competitor_prices[]" placeholder="Price" value="${data.price || ''}"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-competitor-btn"><i class="ph-bold ph-trash"></i></button>
                    </td>
                </tr>
            `;
                    $('#competitor-tbody').append(newRow);
                }

                // Event listener untuk tombol "Add Row"
                $('#add-competitor-btn').on('click', function() {
                    addCompetitorRow();
                });

                // Event listener untuk tombol "Remove" pada setiap baris
                $('#competitor-tbody').on('click', '.remove-competitor-btn', function() {
                    $(this).closest('tr').remove();
                    // Update nomor urut setelah menghapus
                    $('#competitor-tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                        competitorRowIndex = index + 1;
                    });
                });

                function populateForm(data) {
                    // 1. Mengisi input teks, textarea, dan select sederhana
                    $('#prempdrForm [name="projectName"]').val(data.project_name);
                    $('#prempdrForm [name="levelPriority"]').val(data.level_priority);
                    $('#prempdrForm [name="brandName"]').val(data.brand_name);

                    // Menggunakan data dari relasi 'detail'
                    $('#prempdrForm [name="rationalForDevelopment"]').val(data.detail.rational_for_development);
                    $('#prempdrForm [name="targetLaunchText"]').val(data.detail.target_launch);

                    // Menggunakan data dari relasi 'description'
                    $('#prempdrForm [name="productDescription"]').val(data.description.product_description);
                    $('#prempdrForm [name="usageDescription"]').val(data.description.usage_description);
                    $('#prempdrForm [name="storageTemperature"]').val(data.description.storage_temperature);
                    $('#prempdrForm [name="deliveryTemperature"]').val(data.description.delivery_temperature);

                    // Menggunakan data dari relasi 'packaging'
                    $('#prempdrForm [name="weightProduct"]').val(data.packaging.weight);
                    $('#prempdrForm [name="productVariation"]').val(data.packaging.product_variation);

                    // Menggunakan data dari relasi 'market'
                    $('#prempdrForm [name="potentialVolume"]').val(data.market.potential_volume);
                    $('#prempdrForm [name="expectedMargin"]').val(data.market.expected_margin);
                    $('#prempdrForm [name="priceEstimate"]').val(data.market.price_estimate);

                    // 2. Mengisi radio button
                    // Product Category
                    $(`input[name="productCategory"][value="${data.category.category}"]`).prop('checked', true);
                    if (data.category.category === 'Others') {
                        $('#productCategoryText').val(data.category.other).prop('readonly', false);
                    }

                    // Channel
                    $(`input[name="channel"][value="${data.channel.category}"]`).prop('checked', true);
                    if (data.channel.category === 'International') {
                        $('#country').val(data.channel.country).prop('readonly', false);
                    }

                    // Certification
                    $(`input[name="certification"][value="${data.certification.category}"]`).prop('checked', true);
                    if (data.certification.category === 'Others') {
                        $('#certificationText').val(data.certification.other).prop('readonly', false);
                    }

                    // Packaging
                    $(`input[name="packaging"][value="${data.packaging.category}"]`).prop('checked', true);
                    if (data.packaging.category === 'Existing') {
                        $('#ExistingPackagingText').val(data.packaging.detail).prop('readonly', false);
                        $('#NewPackagingText').prop('readonly', true).val('');
                    } else if (data.packaging.category === 'New') {
                        $('#NewPackagingText').val(data.packaging.detail).prop('readonly', false);
                        $('#ExistingPackagingText').prop('readonly', true).val('');
                    }

                    // 3. Mengisi No Registrasi
                    $('#no_reg_text').text(data.no);
                    $('#no_reg').val(data.no);

                    // Legacy static inputs removed; competitor rows below are populated dynamically

                    // 4. Mengisi data produk kompetitor (JIKA ADA)
                    $('#competitor-tbody').empty();
                    competitorRowIndex = 0;

                    if (data.competitor && data.competitor.length > 0) {
                        data.competitor.forEach(item => {
                            addCompetitorRow(item);
                        });
                    } else {
                        // Jika tidak ada data kompetitor, tambahkan satu baris kosong
                        addCompetitorRow();
                    }
                }

                // Handler untuk submit form via AJAX
                $('#prempdrForm').on('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    // Nilai 'status' sudah benar ditangkap di sini, sebelum SweetAlert muncul.
                    const status = $(document.activeElement).val();

                    // Konfigurasi pesan dinamis untuk SweetAlert
                    const confirmationTitle = (status === 'Submit') ? 'Submit for Approval?' : 'Save as Draft?';
                    const confirmationHtml = (status === 'Submit') ?
                        "This form will be sent to the next approver. <br>Please ensure all data is correct." :
                        "You can continue editing this form later from the list.";

                    Swal.fire({
                        title: confirmationTitle,
                        html: confirmationHtml,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData(form);

                            formData.append('form_status', status);

                            const overlay = $('#prempdrModal .loading-overlay');
                            $('.btn-submit').prop('disabled', true);
                            overlay.show();

                            $.ajax({
                                url: $(form).attr('action'),
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(res) {
                                    if (res.success) {
                                        $('#prempdrModal').modal('hide');
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: res.message
                                        });
                                        $('#prempdrTable').DataTable().ajax.reload();
                                    }
                                },
                                error: function(xhr) {
                                    clearValidationErrors();
                                    if (xhr.status === 422) {
                                        const errors = xhr.responseJSON.errors;
                                        let errorHtml = '<ul class="text-start">';
                                        for (const key in errors) {
                                            errorHtml += `<li>${errors[key][0]}</li>`;
                                        }
                                        errorHtml += '</ul>';
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Validation Failed',
                                            html: `Please correct the following issues:<br>${errorHtml}`
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'An Error Occurred',
                                            text: 'Something went wrong. Please try again.'
                                        });
                                    }
                                },
                                complete: function() {
                                    overlay.hide();
                                    $('.btn-submit').prop('disabled', false);
                                }
                            });
                        }
                    });
                });

                // Debounce search
                let searchInput = $('#prempdrTable_filter input');
                searchInput.unbind();
                let debounceTimer;
                searchInput.bind('keyup', function(e) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        table.search(searchInput.val()).draw();
                    }, 500);
                });

                $('#prempdrTable_filter input').attr({
                    'placeholder': '🔍 Search...',
                    'class': 'form-control'
                });

                $('#prempdrModal').on('hidden.bs.modal', () => resetForm());
            });

            // Fungsi konfirmasi hapus
            function confirmDelete(button) {
                var formNo = button.getAttribute('data-form-no');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + formNo).submit();
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
