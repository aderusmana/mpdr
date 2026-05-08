<x-app-layout>
    @section('title')
        List MPDR
    @endsection

    {{-- Menggunakan style tabel yang konsisten --}}
    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    {{-- Judul dan Breadcrumb --}}
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">MPDR List</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li><a class="f-s-14 f-w-500" href="{{ route('dashboard') }}"><i class="ph-duotone ph-house f-s-16"></i>
                        Home</a></li>
                <li class="active"><a class="f-s-14 f-w-500" href="#">MPDR List</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Filter dan Tombol Create --}}
            <div
                class="d-flex flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div class="d-flex flex-md-row align-items-start align-items-md-center gap-2 w-48">
                    <span class="text-muted fw-bold">Filter by:</span>
                    <select id="statusFilter" class="form-select select2" style="width:100%; max-width:200px;">
                        <option value="all">All Statuses</option>
                        <option value="Draft">Draft</option>
                        <option value="In Approval">In Approval</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                    <button id="resetFilters" class="btn btn-secondary border mb-2 mb-md-0" data-bs-toggle="tooltip"
                        title="Reset Filters">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </button>
                </div>
                <div class="mt-md-0 ms-0 ms-md-2 w-36 w-md-auto">
                    @can('view create mpdr')
                        {{-- Tombol ini sekarang akan membuka modal --}}
                        <button class="btn new-mpdr-btn w-48 w-md-auto" type="button" data-bs-toggle="modal"
                            data-bs-target="#mpdrModal" id="btn-create-mpdr">
                            <i class="ph-bold ph-plus"></i>
                            <span>New MPDR</span>
                        </button>
                    @endcan
                </div>
            </div>

            {{-- Kontainer Tabel --}}
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title"><i class="ph-duotone ph-list"></i> MPDR List</h4>
                    <p class="table-subtitle">View, manage and track all MPDR submissions</p>
                </div>
                <div class="table-responsive">
                    <table class="w-100 display table table-striped" id="prempdrTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Reg</th>
                                <th>Product Name</th>
                                <th>Request Date</th>
                                {{-- <th>Route To</th> --}}
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

    {{-- MODAL UNTUK CREATE/EDIT/VIEW MPDR --}}
    <div class="modal fade" id="mpdrModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
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
                    <h5 class="modal-title text-white" id="mpdrModalLabel"><i
                            class="ph-bold ph-note-pencil me-2"></i>Create New MPDR</h5>
                    <button type="button" class="btn-close btn-close-white m-0 fs-5" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="mpdrForm" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-body p-4" style="background-color: #f9fbfd;">
                        {{-- Hidden method for edit --}}
                        <input type="hidden" name="_method" id="formMethod">
                        <input type="hidden" name="initiator" id="initiator_nik">

                        {{-- Pre-MPDR Selector for Create Mode --}}
                        <div id="preMpdrSelector" class="mb-4">
                            <label for="no_pre" class="form-label fw-bold">Select from approved Pre-MPDR:</label>
                            <select name="no_pre" id="no_reg_select" class="form-select select2"
                                aria-label="Select Pre-MPDR Form"></select>
                        </div>

                        {{-- Form Content --}}
                        <fieldset id="form-fieldset" disabled>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="text-primary fw-bolder">MPDR Details</h4>
                                <div class="text-end">
                                    <label class="form-label mb-0">No Reg: <span id="no_reg_text"
                                            class="fw-bold"></span></label>
                                    <input type="hidden" id="no_reg" name="no_reg" readonly>
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
                                            <label for="productName" class="form-label">Product Name<i
                                                    class="text-danger">*</i></label>
                                            <input type="text" class="form-control" name="productName"
                                                placeholder="Enter Product Name" required>
                                            <div class="invalid-feedback" id="productName_error"></div>
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
                                        <div class="col-md-4">
                                            <label class="form-label">Type of Request<i
                                                    class="text-danger">*</i></label>
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="typeOfRequest" id="typeNew" value="New Product"
                                                        required>
                                                    <label class="form-check-label" for="typeNew">New Product</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="typeOfRequest" id="typeExisting"
                                                        value="Existing Product">
                                                    <label class="form-check-label" for="typeExisting">Existing
                                                        Product</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Requester</label>
                                            <div class="input-group">
                                                <input type="text" id="requester_name_display"
                                                    class="form-control" name="requester_name_display"
                                                    placeholder="Requester name" readonly>
                                                <input type="hidden" id="requester_id" name="requester_id"
                                                    value="">
                                            </div>
                                        </div>
                                        <input type="hidden" id="initiator_name_display"
                                            name="initiator_name_display" value="">
                                        <input type="hidden" id="initiator_nik" name="initiator" value="">
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
                                    <h6 class="fw-bold text-primary mb-0"><i class="ph-bold ph-tag me-2"></i>Category
                                        & Specifications</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6" id="productCategory">
                                            <label class="form-label fw-bold">Product Category<i
                                                    class="text-danger">*</i></label>
                                            <div class="">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="productCategory" id="productCategory1"
                                                        value="Margarine" required>
                                                    <label class="form-check-label" for="productCategory1">
                                                        Margarine
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="productCategory" id="productCategory2"
                                                        value="Frying Fats">
                                                    <label class="form-check-label" for="productCategory2">
                                                        Frying Fats
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="productCategory" id="productCategory3"
                                                        value="Shortening">
                                                    <label class="form-check-label" for="productCategory3">
                                                        Shortening
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="productCategory" id="productCategory4" value="Pastry">
                                                    <label class="form-check-label" for="productCategory4">
                                                        Pastry
                                                    </label>
                                                </div>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-2" type="radio"
                                                        name="productCategory" id="productCategory5" value="Others">
                                                    <label class="form-check-label me-2" for="productCategory5">
                                                        Others
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="productCategoryText" name="productCategoryText"
                                                        placeholder="(Specify)" readonly style="width: 150px;">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="channel">
                                            <label class="form-label fw-bold">Channel<i
                                                    class="text-danger">*</i></label>
                                            <div class="">
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-2" type="radio"
                                                        name="channel" id="channel1" value="International" required>
                                                    <label class="form-check-label me-2" for="channel1">
                                                        International
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="country" id="country" placeholder="Country" readonly
                                                        style="width: 150px;">

                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="channel"
                                                        id="channel2" value="Regional">
                                                    <label class="form-check-label" for="channel2">
                                                        Regional
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="channel"
                                                        id="channel3" value="Industrial">
                                                    <label class="form-check-label" for="channel3">
                                                        Industrial
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="channel"
                                                        id="channel4" value="FSBC-Direct">
                                                    <label class="form-check-label" for="channel4">
                                                        FSBC-Direct
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="channel"
                                                        id="channel5" value="FSBC-Distributor">
                                                    <label class="form-check-label" for="channel5">
                                                        FSBC-Distributor
                                                    </label>
                                                </div>
                                            </div>
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
                                            <div class="">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="certification" id="certification1" value="BPOM"
                                                        required>
                                                    <label class="form-check-label" for="certification1">
                                                        BPOM
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="certification" id="certification2" value="HALAL">
                                                    <label class="form-check-label" for="certification2">
                                                        HALAL
                                                    </label>
                                                </div>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-2" type="radio"
                                                        name="certification" id="certification3" value="Others">
                                                    <label class="form-check-label me-2" for="certification3">
                                                        Others
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="certificationText" id="certificationText"
                                                        placeholder="(Specify)" readonly style="width: 150px;">

                                                </div>
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
                                            <h5 class="fw-bold">Competitor's Product:</h5>
                                            <button type="button" class="btn btn-sm btn-success"
                                                id="add-competitor-btn-mpdr">
                                                <i class="ph-bold ph-plus me-1"></i> Add Row
                                            </button>
                                        </div>
                                        <table class="table table-bordered table-sm mt-2" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 5%;" class="text-center">#</th>
                                                    <th>PRODUCT NAME</th>
                                                    <th>SIZE</th>
                                                    <th>PACKAGING</th>
                                                    <th>Price Indication</th>
                                                    <th style="width: 5%;" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="competitor-tbody-mpdr">
                                                {{-- Baris dinamis akan ditambahkan di sini --}}
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
                                                        <input class="form-check-input" type="radio"
                                                            name="packaging" id="existingPackaging" value="Existing"
                                                            required>
                                                        <label class="form-check-label" for="existingPackaging">
                                                            Existing
                                                        </label>
                                                        <textarea class="form-control no-resize" name="ExistingPackagingText" id="ExistingPackagingText"
                                                            placeholder="Details" rows="2" readonly></textarea>
                                                    </div>
                                                    <div class="form-check col">
                                                        <input class="form-check-input" type="radio"
                                                            name="packaging" id="newPackaging" value="New">
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
                                                        <input type="text" class="form-control"
                                                            name="expectedMargin" required>
                                                    </div>
                                                    <div class="form-check col">
                                                        <label for="priceEstimate" class="form-label">Price
                                                            Estimate:</label>
                                                        <input type="text" class="form-control"
                                                            name="priceEstimate" required>
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

                        </fieldset>

                        {{-- CARD: APPROVAL HISTORY (table view for MPDR) --}}
                        <div class="card view-modal-card mt-3">
                            <div class="card-header view-modal-card-header">
                                <h6 class="fw-bold text-primary mb-0"><i class="ph-bold ph-checks me-2"></i>Approval
                                    History</h6>
                            </div>
                            <div class="card-body">
                                <div class="approval-container">
                                    <div class="section-title" style="margin-top: 0;">Approval History</div>
                                    <table class="detail-table table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%; text-align: left;">Approved by</th>
                                                <th style="width: 15%;">Status</th>
                                                <th style="width: 15%;">Date</th>
                                                <th style="width: 40%;">Notes / Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody id="mpdr-approval-history-body">
                                            <tr>
                                                <td colspan="4">No approval data found.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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
            // [PERBAIKAN] Seluruh kode JS berada dalam satu blok $(function(){...})
            $(function() {
                // Inisialisasi
                $('#statusFilter').select2({
                    theme: 'bootstrap-5'
                });
                $('#no_reg_select').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#mpdrModal')
                });

                const table = $('#prempdrTable').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ route('mpdr.form.list') }}",
                        dataSrc: function(json) {
                            const statusFilter = $('#statusFilter').val();
                            return (statusFilter === 'all') ? json : json.filter(item => item.status ===
                                statusFilter);
                        }
                    },
                    columns: [{
                            data: null,
                            searchable: false,
                            orderable: false,
                            className: 'text-center',
                            render: (d, t, r, meta) => meta.row + 1
                        },
                        {
                            data: 'no',
                            render: data => `<span class="status-badge-lg bg-dark">${data}</span>`
                        },
                        {
                            data: 'product_name',
                            render: data =>
                                `<div class="d-flex align-items-center"><i class="ph-bold ph-folder-simple me-2 text-primary fs-5"></i><span>${data}</span></div>`
                        },
                        {
                            data: 'created_at',
                            render: function(data) {
                                if (!data) return '-';
                                const date = new Date(data);
                                return `<div class="d-flex align-items-center"><i class="ph ph-calendar-check me-2 text-primary fs-5"></i><span>${date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })}</span></div>`;
                            }
                        },
                        // { data: 'route_to', render: data => data ? `<span class="status-badge-lg bg-warning d-flex align-items-center"><i class="ph-bold ph-user-circle me-1 fs-6"></i>${data}</span>` : '<span class="text-muted fst-italic">-</span>' },
                        {
                            data: 'status',
                            render: function(data, type, row) {
                                let statusClass = {
                                    'Rejected': 'bg-danger',
                                    'Approved': 'bg-success',
                                    'In Approval': 'bg-info',
                                    'Draft': 'bg-warning'
                                } [data] || 'bg-secondary';

                                let approvedCount = 0;
                                if (row.approved_detail && Array.isArray(row.approved_detail)) {
                                    approvedCount = row.approved_detail.filter(detail => {
                                        if (!detail.status) return false;
                                        const status = detail.status.toLowerCase();
                                        return status === 'approve' || status ===
                                            'approve with review';
                                    }).length;
                                }

                                let progress = '';
                                if (data === 'In Approval' && row.total_approvers > 0) {
                                    progress = ` (${approvedCount}/${row.total_approvers})`;
                                }

                                return `<span class="status-badge-lg ${statusClass}">${data}${progress}</span>`;
                            }
                        },
                        {
                            data: null,
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                // [LOGIKA AKSI YANG BENAR]
                                if (row.status === 'Draft') {
                                    const deleteRoute = "{{ route('mpdr.destroy', ':formId') }}"
                                        .replace(':formId', row.no);
                                    return `
                                    <div class="d-flex gap-1">
                                        @can('view edit mpdr')
                                        <button type="button" class="btn btn-sm btn-primary edit-form-btn" data-form-id="${row.no}" data-bs-toggle="tooltip" title="Edit Form"><i class="ph-bold ph-pencil-simple"></i></button>
                                        @endcan
                                        @can('view delete mpdr')
                                        <form id="delete-form-${row.no}" action="${deleteRoute}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" data-form-no="${row.no}" onclick="confirmDelete(this)" data-bs-toggle="tooltip" title="Delete Form"><i class="ph-bold ph-trash"></i></button>
                                        </form>
                                        @endcan
                                    </div>`;
                                } else {
                                    let buttons =
                                        `<button type="button" class="btn btn-sm btn-info view-form-btn" data-form-id="${row.no}" data-bs-toggle="tooltip" title="View Details"><i class="ph-bold ph-eye"></i></button>`;

                                    @if (auth()->user()->hasRole('super-admin'))
                                        if (row.status === 'In Approval') {
                                            const sendEmailUrl =
                                                "{{ route('mpdr.send.mail.gm', ':formId') }}".replace(
                                                    ':formId', row.no);
                                            buttons += `
                                        <form id="send-form-${row.no}" action="${sendEmailUrl}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-warning ms-1" send-form-no="${row.no}" onclick="confirmEmail(this)" data-bs-toggle="tooltip" title="Send Urgent Email to GM">
                                                <i class="ph-bold ph-paper-plane-tilt"></i>
                                            </button>
                                        </form>`;
                                        }
                                    @endif

                                    return `<div class="d-flex gap-1">${buttons}</div>`;
                                }
                            }
                        }
                    ],
                    "drawCallback": function(settings) { // Re-initialize tooltips
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                            '[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    }
                });

                // --- EVENT LISTENERS ---
                // store latest fetched detail data so renderer can use it on modal show
                let currentDetailData = null;

                $('#statusFilter').on('change', () => table.ajax.reload());
                $('#resetFilters').on('click', () => $('#statusFilter').val('all').trigger('change'));

                // --- LOGIKA MODAL ---
                function clearValidationErrors() {
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                }

                function resetForm() {
                    $('#mpdrForm')[0].reset();
                    $('#mpdrForm :input').prop('disabled', false);
                    // Ensure the interactive fieldset is disabled until a Pre-MPDR is chosen
                    $('#form-fieldset').prop('disabled', true);
                    // Ensure Type of Request radios are enabled by default for create mode
                    $('input[name="typeOfRequest"]').prop('disabled', false);
                    clearValidationErrors();
                    $('#formMethod').val('');
                    $('#mpdrForm').attr('action', '');
                    $('#no_reg_select').val(null).trigger('change');

                    $('#competitor-tbody-mpdr').empty();
                    competitorRowIndexMpdr = 0;
                    // Reset dependent text inputs (Others / International) to readonly/empty
                    $('#productCategoryText').prop('readonly', true).val('');
                    $('#country').prop('readonly', true).val('');
                    $('#certificationText').prop('readonly', true).val('');
                    // Disable primary Submit button until form is valid (Draft may remain enabled)
                    $('.btn-submit[value="Submit"]').prop('disabled', true);
                }

                $('#btn-create-mpdr').on('click', function() {
                    resetForm();
                    $('#mpdrModalLabel').html('<i class="ph-bold ph-note-pencil me-2"></i>Create New MPDR');
                    $('.btn-submit').show();
                    $('#preMpdrSelector').show();
                    $('#mpdrForm').attr('action', "{{ route('mpdr.store') }}");
                    $('#formMethod').val('POST');

                    // Create mode: show Type of Request and Requester even before selecting Pre-MPDR
                    // Enable the fieldset so these controls are visible, then disable all inputs
                    // except the Pre-MPDR selector, Type of Request radios and requester display.
                    $('#form-fieldset').prop('disabled', false);
                    // Disable all inputs first
                    $('#form-fieldset :input').prop('disabled', true);
                    // Enable only the specific controls we want visible/editable in create mode
                    $('#no_reg_select').prop('disabled', false);
                    $('input[name="typeOfRequest"]').prop('disabled', false);
                    $('#requester_name_display').prop('readonly', true).prop('disabled', false);
                    $('#requester_id').prop('disabled', false);

                    $.get("{{ route('mpdr.prempdr.list') }}", function(data) {
                        const select = $('#no_reg_select');
                        select.empty().append(
                            '<option selected disabled value="">--- Select Pre-MPDR Form ---</option>'
                        );
                        data.forEach(item => select.append(new Option(item, item)));
                    });
                });

                $('#no_reg_select').on('change', function() {
                    const preMpdrNo = $(this).val();
                    if (!preMpdrNo) {
                        $('#form-fieldset').prop('disabled', true);
                        return;
                    }
                    $('#form-fieldset').prop('disabled', false);

                    // PANGGILAN DENGAN URL YANG SUDAH DIPERBAIKI
                    const url = `{{ url('prempdr/detail') }}/${preMpdrNo}`; // <--- BARIS INI DIPERBAIKI

                    $.get(url, function(data) {
                        // populate form and set requester from pre-mpdr's user relation
                        // When selecting a Pre-MPDR for creating a new MPDR, do NOT show
                        // the approval history from the Pre-MPDR. Approval history in
                        // the modal should come from the MPDR detail endpoint only
                        // (when viewing/editing an existing MPDR). Clear any stored
                        // MPDR detail data and clear the history table here.
                        currentDetailData = null;
                        populateForm(data);
                        // After populating from Pre-MPDR, refresh submit button state
                        // (we only enable the main Submit when all required fields are present)
                        try {
                            toggleSubmitButtons();
                        } catch (e) {}
                        renderMpdrApprovalHistory({});
                        if (data.user) {
                            $('#requester_name_display').val(data.user.name);
                            $('#requester_id').val(data.user.id);
                            // set initiator display and hidden nik to requester (as per backend)
                            $('#initiator_name_display').val(data.user.name);
                            $('#initiator_nik').val(data.user.nik);
                        }
                        $.get("{{ route('mpdr.noReg') }}", function(mpdrData) {
                            $('#no_reg_text').text(mpdrData.no_reg);
                            $('#no_reg').val(mpdrData.no_reg);
                        });

                        // After selecting Pre-MPDR: enable all form inputs so user can fill details,
                        // but keep requester display non-editable and ensure hidden requester_id is enabled for submit.
                        // If we're editing (formMethod == 'PUT'), keep Type of Request radios disabled.
                        $('#form-fieldset :input').prop('disabled', false);
                        // requester visible but not editable; disable to prevent editing but keep hidden id enabled
                        $('#requester_name_display').prop('readonly', true).prop('disabled', true);
                        $('#requester_id').prop('disabled', false);

                        if ($('#formMethod').val() === 'PUT') {
                            // Editing existing MPDR: Type of Request must remain readonly
                            $('input[name="typeOfRequest"]').prop('disabled', true);
                        } else {
                            // Creating new MPDR: allow choosing Type of Request
                            $('input[name="typeOfRequest"]').prop('disabled', false);
                        }
                    }).fail(function() {
                        // Tambahan: Memberi tahu jika ada error
                        alert('Gagal mengambil detail Pre-MPDR. Periksa console untuk error.');
                        $('#form-fieldset').prop('disabled', true);
                    });
                });

                let competitorRowIndexMpdr = 0;

                function addMpdrCompetitorRow(data = {}) {
                    competitorRowIndexMpdr++;
                    const newRow = `
                            <tr class="competitor-row">
                                <td class="text-center fw-bold">${competitorRowIndexMpdr}</td>
                                <td><input type="text" class="form-control" name="competitor_names[]" placeholder="Product Name" value="${data.name || ''}"></td>
                                <td><input type="text" class="form-control" name="competitor_sizes[]" placeholder="Size" value="${data.size || ''}"></td>
                                <td><input type="text" class="form-control" name="competitor_packagings[]" placeholder="Packaging" value="${data.packaging || ''}"></td>
                                <td><input type="text" class="form-control" name="competitor_prices[]" placeholder="Price" value="${data.price || ''}"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-competitor-btn"><i class="ph-bold ph-trash"></i></button>
                                </td>
                            </tr>
                        `;
                    $('#competitor-tbody-mpdr').append(newRow);
                }

                $('#add-competitor-btn-mpdr').on('click', function() {
                    addMpdrCompetitorRow();
                });

                $('#competitor-tbody-mpdr').on('click', '.remove-competitor-btn', function() {
                    $(this).closest('tr').remove();
                    $('#competitor-tbody-mpdr tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                        competitorRowIndexMpdr = index + 1;
                    });
                });

                function populateForm(data) {
                    const isFromPreMpdr = !!data.user;
                    const initiatorNik = isFromPreMpdr ? data.user.nik : data.initiator_detail.initiator_nik;
                    const productName = isFromPreMpdr ? data.project_name : data.product_name;

                    $('#initiator_nik').val(initiatorNik);
                    $('input[name="productName"]').val(productName);
                    $('select[name="levelPriority"]').val(data.level_priority);
                    $('input[name="brandName"]').val(data
                        .brand_name); // brand_name ada di Pre-MPDR, akan kosong jika di MPDR
                    $('textarea[name="rationalForDevelopment"]').val(data.detail.rational_for_development);

                    // If MPDR record provides requester relation, populate requester display
                    if (data.requester) {
                        $('#requester_name_display').val(data.requester.name);
                        $('#requester_id').val(data.requester.id);
                        // ensure initiator display follows requester when present
                        $('#initiator_name_display').val(data.requester.name);
                        $('#initiator_nik').val(data.requester.nik);
                    }

                    // --- Category & Specifications ---
                    if (data.category) {
                        $(`input[name="productCategory"][value="${data.category.category}"]`).prop('checked', true)
                            .trigger('change');
                        if (data.category.category === 'Others') {
                            $('#productCategoryText').val(data.category.other);
                        }
                    }
                    if (data.channel) {
                        $(`input[name="channel"][value="${data.channel.category}"]`).prop('checked', true).trigger(
                            'change');
                        if (data.channel.category === 'International') {
                            $('#country').val(data.channel.country);
                        }
                    }
                    if (data.description) {
                        $('textarea[name="productDescription"]').val(data.description.product_description);
                        $('input[name="usageDescription"]').val(data.description.usage_description);
                        $('input[name="storageTemperature"]').val(data.description.storage_temperature);
                        $('input[name="deliveryTemperature"]').val(data.description.delivery_temperature);
                    }
                    if (data.certification) {
                        $(`input[name="certification"][value="${data.certification.category}"]`).prop('checked', true)
                            .trigger('change');
                        if (data.certification.category === 'Others') {
                            $('#certificationText').val(data.certification.other);
                        }
                    }

                    // --- Packaging, Market & Target ---
                    if (data.packaging) {
                        $('input[name="weightProduct"]').val(data.packaging.weight);
                        $(`input[name="packaging"][value="${data.packaging.category}"]`).prop('checked', true).trigger(
                            'change');
                        if (data.packaging.category === 'Existing') {
                            $('#ExistingPackagingText').val(data.packaging.detail);
                        } else if (data.packaging.category === 'New') {
                            $('#NewPackagingText').val(data.packaging.detail);
                        }
                        $('input[name="productVariation"]').val(data.packaging.product_variation);
                    }
                    if (data.market) {
                        $('input[name="potentialVolume"]').val(data.market.potential_volume);
                        $('input[name="expectedMargin"]').val(data.market.expected_margin);
                        $('input[name="priceEstimate"]').val(data.market.price_estimate);
                    }
                    if (data.detail) {
                        $('input[name="targetLaunchText"]').val(data.detail.target_launch);
                    }

                    // --- Mengisi data produk kompetitor ---
                    $('#competitor-tbody-mpdr').empty();
                    competitorRowIndexMpdr = 0;

                    if (data.competitor && data.competitor.length > 0) {
                        data.competitor.forEach(item => {
                            addMpdrCompetitorRow(item);
                        });
                    } else {
                        // Jika tidak ada data, tambahkan satu baris kosong
                        addMpdrCompetitorRow();
                    }
                }

                // Scoped toggles: enable only the text input that belongs to the selected radio
                function applyScopedToggles() {
                    // Product Category: only enable the text input next to the checked radio
                    $('#productCategory .form-check').each(function() {
                        const $text = $(this).find('input[type="text"]');
                        if ($text.length) {
                            $text.prop('readonly', true).val('');
                        }
                    });
                    const $pcChecked = $('#productCategory input[name="productCategory"]:checked');
                    if ($pcChecked.length) {
                        const $input = $pcChecked.closest('.form-check').find('input[type="text"]');
                        if ($input.length) $input.prop('readonly', false);
                    }

                    // Channel: only enable the country input adjacent to the checked radio
                    $('#channel .form-check').each(function() {
                        const $t = $(this).find('input[type="text"]');
                        if ($t.length) {
                            $t.prop('readonly', true).val('');
                        }
                    });
                    const $chChecked = $('#channel input[name="channel"]:checked');
                    if ($chChecked.length) {
                        const $input = $chChecked.closest('.form-check').find('input[type="text"]');
                        if ($input.length) $input.prop('readonly', false);
                    }

                    // Certification: only enable the text input next to the checked radio
                    $('#certification .form-check').each(function() {
                        const $text = $(this).find('input[type="text"]');
                        if ($text.length) {
                            $text.prop('readonly', true).val('');
                        }
                    });
                    const $certChecked = $('#certification input[name="certification"]:checked');
                    if ($certChecked.length) {
                        const $input = $certChecked.closest('.form-check').find('input[type="text"]');
                        if ($input.length) $input.prop('readonly', false);
                    }

                    // Packaging: enable only the textarea belonging to the checked packaging radio
                    $('#detailedPackaging .form-check').each(function() {
                        const $ta = $(this).find('textarea');
                        if ($ta.length) {
                            $ta.prop('readonly', true).val('');
                        }
                    });
                    const $pkgChecked = $('input[name="packaging"]:checked');
                    if ($pkgChecked.length) {
                        const $ta = $pkgChecked.closest('.form-check').find('textarea');
                        if ($ta.length) $ta.prop('readonly', false);
                    }
                }

                // Client-side validation for required MPDR fields
                function isMpdrFormValid() {
                    const hasVal = sel => $.trim($(sel).val() || '') !== '';

                    if (!hasVal('input[name="productName"]')) return false;
                    if (!hasVal('select[name="levelPriority"]')) return false;
                    if (!hasVal('input[name="brandName"]')) return false;
                    if ($('input[name="typeOfRequest"]:checked').length === 0) return false;
                    // requester can be provided via requester_id (hidden) or visible display
                    if (!hasVal('#requester_id') && !hasVal('#requester_name_display')) return false;
                    if (!hasVal('textarea[name="rationalForDevelopment"]')) return false;
                    if ($('input[name="productCategory"]:checked').length === 0) return false;
                    if ($('input[name="channel"]:checked').length === 0) return false;
                    if (!hasVal('textarea[name="productDescription"]')) return false;
                    if (!hasVal('input[name="usageDescription"]')) return false;
                    if (!hasVal('input[name="storageTemperature"]')) return false;
                    if (!hasVal('input[name="deliveryTemperature"]')) return false;
                    if ($('input[name="certification"]:checked').length === 0) return false;
                    if (!hasVal('input[name="targetLaunchText"]')) return false;

                    return true;
                }

                function toggleSubmitButtons() {
                    const ok = isMpdrFormValid();
                    // Only control the main Submit button; allow Draft to be used anytime
                    $('.btn-submit[value="Submit"]').prop('disabled', !ok);
                }

                // listen to changes on the required fields and toggle submit accordingly
                $(document).on('input change',
                    'input[name="productName"], input[name="brandName"], select[name="levelPriority"], input[name="typeOfRequest"], #requester_id, #requester_name_display, textarea[name="rationalForDevelopment"], input[name="usageDescription"], input[name="storageTemperature"], input[name="deliveryTemperature"], textarea[name="productDescription"], input[name="targetLaunchText"], input[name="productCategory"], input[name="channel"], input[name="certification"]',
                    toggleSubmitButtons);

                // delegate change events so it works with dynamic content and programmatic triggers
                $(document).on('change', 'input[name="productCategory"]', applyScopedToggles);
                $(document).on('change', 'input[name="channel"]', applyScopedToggles);
                $(document).on('change', 'input[name="certification"]', applyScopedToggles);
                $(document).on('change', 'input[name="packaging"]', applyScopedToggles);

                // ensure proper initial state
                applyScopedToggles();

                // Render approval history table for MPDR modal
                function renderMpdrApprovalHistory(data) {
                    const tbody = $('#mpdr-approval-history-body');
                    if (!tbody.length) return; // table not present
                    tbody.empty();

                    const details = data.approved_detail_ordered || data.approved_detail || [];

                    if (!details || details.length === 0) {
                        tbody.append('<tr><td colspan="4">No approval data found.</td></tr>');
                        return;
                    }

                    details.forEach(detail => {
                        const approverName = detail.approver_name || detail.name || '-';
                        const approverPosition = detail.approver_position || detail.position || '';

                        let statusLabel = '<span style="color: #888;">Pending</span>';
                        if (detail.status) {
                            const st = ('' + detail.status).toLowerCase();
                            if (st === 'approve' || st === 'approved') statusLabel =
                                '<b style="color: green;">Approved</b>';
                            else if (st === 'approve with review' || st === 'approved with review')
                                statusLabel = '<b style="color: #f5a623;">Approved with Review</b>';
                            else if (st === 'not approve' || st === 'rejected' || st === 'not approved')
                                statusLabel = '<b style="color: red;">Not Approved</b>';
                        }

                        const dateText = detail.approved_date ? (new Date(detail.approved_date))
                            .toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            }) : '-';
                        const comment = detail.comment ? $('<div>').text(detail.comment).html() : '-';

                        const row = `<tr>
                            <td style="text-align: left;"><b>${approverName}</b>${approverPosition ? `<br><small style="color: #666;">${$('<div>').text(approverPosition).html()}</small>` : ''}</td>
                            <td>${statusLabel}</td>
                            <td>${dateText}</td>
                            <td class="notes">${comment}</td>
                        </tr>`;

                        tbody.append(row);
                    });
                }

                // Ganti seluruh blok $('#prempdrTable tbody').on('click', ...) dengan yang ini
                $('#prempdrTable tbody').on('click', '.edit-form-btn, .view-form-btn', function() {
                    const btn = $(this);
                    const originalHtml = btn.html();

                    // Langsung ubah tombol menjadi loading state
                    btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm"></span>`);

                    const formId = $(this).data('form-id');
                    const isView = $(this).hasClass('view-form-btn');
                    const url = `{{ url('mpdr/detail') }}/${formId}`;

                    resetForm();

                    $('#mpdrModalLabel').html(
                        `<i class="ph-bold ph-${isView ? 'eye' : 'pencil-simple'} me-2"></i>${isView ? 'View' : 'Edit'} MPDR: ${formId}`
                    );
                    $('.btn-submit').toggle(!isView);
                    $('#preMpdrSelector').hide();
                    $('#form-fieldset').prop('disabled', false); // Aktifkan fieldset untuk diisi

                    if (!isView) {
                        $('#mpdrForm').attr('action',
                            `{{ url('mpdr/update') }}/${formId}`); // Sesuaikan dengan route update Anda
                        $('#formMethod').val('PUT');
                        // when editing an existing MPDR, requester should be read-only
                        $('#requester_name_display').prop('readonly', true);
                        // when editing an existing MPDR, Type of Request must not be changed
                        $('input[name="typeOfRequest"]').prop('disabled', true);
                    }

                    $.get(url, function(data) {
                        // 1. Isi form dulu
                        currentDetailData = data;
                        populateForm(data);
                        // After populating, update submit button state
                        toggleSubmitButtons();
                        // Render approval history table for MPDR (view/edit)
                        renderMpdrApprovalHistory(currentDetailData);
                        // ensure requester display is readonly in edit mode
                        if (!isView) {
                            if (data.requester) {
                                $('#requester_name_display').val(data.requester.name);
                                $('#requester_id').val(data.requester.id);
                            }
                            $('#requester_name_display').prop('readonly', true);
                            // Disable Type of Request radios so they cannot be changed during edit
                            $('input[name="typeOfRequest"]').prop('disabled', true);
                        }
                        $('#no_reg_text').text(data.no);
                        $('#no_reg').val(data.no);

                        if (isView) {
                            $('#mpdrModal .modal-body :input').prop('disabled', true);
                        }

                        // 2. BARU tampilkan modal setelah semuanya siap
                        $('#mpdrModal').modal('show');

                    }).fail(() => {
                        Swal.fire('Error',
                            'Failed to fetch details. Please check the console for errors.', 'error'
                        );
                    }).always(() => {
                        // 3. Selalu kembalikan tombol ke kondisi semula
                        btn.prop('disabled', false).html(originalHtml);
                    });
                });

                $('#mpdrForm').on('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    const status = $(document.activeElement).val();

                    // If user intends to Submit (not Save Draft), validate required fields first
                    if (status === 'Submit' && !isMpdrFormValid()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Form',
                            text: 'Please complete all required fields before submitting.'
                        });
                        return;
                    }

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

                            const overlay = $('#mpdrModal .loading-overlay');
                            // disable both buttons while processing
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
                                        $('#mpdrModal').modal('hide');
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: res.message
                                        });
                                        $('#prempdrTable').DataTable().ajax
                                            .reload(); // Pastikan ID tabel benar
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
                                    // sync submit button with current validation state
                                    toggleSubmitButtons();
                                }
                            });
                        }
                    });
                });

                $('#mpdrModal').on('hidden.bs.modal', () => resetForm());
                // When modal is shown, ensure approval history renders using latest fetched data
                $('#mpdrModal').on('shown.bs.modal', function() {
                    try {
                        renderMpdrApprovalHistory(currentDetailData || {});
                    } catch (e) {
                        console.warn('Failed to render approval history:', e);
                    }
                });
            });

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

            function confirmEmail(button) {
                var formNo = button.getAttribute('send-form-no');
                var form = document.getElementById('send-form-' + formNo);
                var url = form.getAttribute('action');
                var token = form.querySelector('input[name="_token"]').value;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "An email will be sent to the GM for urgent approval!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // [PERBAIKAN UTAMA] Kirim request via AJAX
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: token,
                            },
                            beforeSend: function() {
                                // Tampilkan loading state
                                $(button).prop('disabled', true).html(
                                    '<span class="spinner-border spinner-border-sm"></span>');
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message || 'Email has been sent successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON.message || 'Something went wrong.'
                                });
                            },
                            complete: function() {
                                // Kembalikan tombol ke state semula
                                $(button).prop('disabled', false).html(
                                    '<i class="ph-bold ph-paper-plane-tilt"></i>');
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
