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
            {{-- Status Tabs (Pending & Complete) + Create Button --}}
            <div class="mpdr-status-tabs-container">
                <div class="mpdr-nav-pills" role="tablist">
                    <button class="mpdr-tab-link active" id="tabPendingBtn" data-status-tab="pending" type="button"
                        role="tab" aria-selected="true">
                        <i class="ph-bold ph-clock-countdown fs-5"></i>
                        <span>Pending</span>
                        <span class="mpdr-tab-badge mpdr-tab-badge-pending" id="pendingCount">0</span>
                    </button>
                    <button class="mpdr-tab-link" id="tabCompleteBtn" data-status-tab="complete" type="button"
                        role="tab" aria-selected="false">
                        <i class="ph-bold ph-check-circle fs-5"></i>
                        <span>Complete</span>
                        <span class="mpdr-tab-badge mpdr-tab-badge-complete" id="completeCount">0</span>
                    </button>
                </div>

                <div class="action-btn-wrapper">
                    @can('view create mpdr')
                        <button class="btn new-mpdr-btn" type="button" data-bs-toggle="modal" data-bs-target="#mpdrModal"
                            id="btn-create-mpdr">
                            <i class="ph-bold ph-plus"></i>
                            <span>New MPDR</span>
                        </button>
                    @endcan
                </div>
            </div>

            {{-- Filter dan Subtitle Hint --}}
            <div class="filter-action-bar">
                <div class="filter-group">
                    <span class="text-muted fw-bold text-nowrap filter-label"><i
                            class="ph-bold ph-funnel me-1"></i>Filter by:</span>
                    <div class="select2-wrapper">
                        <select id="statusFilter" class="form-select select2">
                            <option value="all">All Pending Statuses</option>
                            <option value="Draft">Draft</option>
                            <option value="In Approval">In Approval</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <button id="resetFilters" class="btn btn-secondary border flex-shrink-0" data-bs-toggle="tooltip"
                        title="Reset Filters">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </button>
                </div>
                <div class="tab-info-hint" id="tabDescription">
                    <i class="ph-bold ph-hourglass-medium text-warning"></i>
                    <span>Showing MPDR submissions pending approval or draft.</span>
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
                                <th class="text-center" style="width: 5%;">No.</th>
                                <th class="text-center" style="width: 12%;">No. Reg</th>
                                <th style="width: 18%;">Product Name</th>
                                <th class="text-center" style="width: 12%;">Request Date</th>
                                <th style="width: 31%;">Route To</th>
                                <th class="text-center" style="width: 12%;">Status</th>
                                <th class="text-center" style="width: 10%;">Action</th>
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
                                                        required checked>
                                                    <label class="form-check-label" for="typeNew">New Product</label>
                                                </div>
                                                <div class="form-check" id="existingProductRequestOption"
                                                    style="display: none;">
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
                                                    <input class="form-check-input me-2" type="checkbox"
                                                        name="channel[]" id="channel1" value="International">
                                                    <label class="form-check-label me-2" for="channel1">
                                                        International
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="country" id="country" placeholder="Country" readonly
                                                        style="width: 150px;">

                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="channel[]"
                                                        id="channel2" value="Regional">
                                                    <label class="form-check-label" for="channel2">
                                                        Regional
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="channel[]"
                                                        id="channel3" value="Industrial">
                                                    <label class="form-check-label" for="channel3">
                                                        Industrial
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="channel[]"
                                                        id="channel4" value="FSBC-Direct">
                                                    <label class="form-check-label" for="channel4">
                                                        FSBC-Direct
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="channel[]"
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
                                                    <input class="form-check-input" type="checkbox"
                                                        name="certification[]" id="certification1" value="BPOM">
                                                    <label class="form-check-label" for="certification1">
                                                        BPOM
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="certification[]" id="certification2" value="HALAL">
                                                    <label class="form-check-label" for="certification2">
                                                        HALAL
                                                    </label>
                                                </div>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-2" type="checkbox"
                                                        name="certification[]" id="certification3" value="Others">
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
                                                <div class="form-check">
                                                    <label for="potentialVolume" class="form-label">Potential Volume
                                                        (Mt/Annually):</label>
                                                    <input type="text" class="form-control" name="potentialVolume"
                                                        id="potentialVolume" required>
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

    {{-- MODAL KHUSUS VIEW DETAIL MPDR (EXECUTIVE SUMMARY REPORT REDESIGN) --}}
    <div class="modal fade" id="mpdrViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark bg-gradient text-white border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2"
                        id="mpdrViewModalLabel">
                        <i class="ph-bold ph-file-text text-info fs-4"></i>
                        <span>MPDR Executive Summary</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background-color: #f8fafc;">
                    <!-- Loader Spinner inside Modal -->
                    <div id="v-mpdr-loader" class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h6 class="mt-3 fw-bold text-muted">Fetching MPDR Details...</h6>
                    </div>

                    <!-- Main View Content Container -->
                    <div id="v-mpdr-content-wrapper" style="display: none;">
                        <!-- 1. EXECUTIVE HEADER BANNER -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden"
                            style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                            <div class="card-body p-4 text-white">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            class="p-3 bg-white bg-opacity-10 rounded-3 border border-white border-opacity-10">
                                            <i class="ph-bold ph-file-pdf text-info" style="font-size: 2.2rem;"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                <span id="vm-no"
                                                    class="badge bg-info text-dark px-3 py-1 font-monospace fw-bold fs-6"></span>
                                                <span id="vm-revision"
                                                    class="badge bg-secondary px-2 py-1 font-monospace">REV 0</span>
                                                <span id="vm-type-request" class="badge bg-primary px-3 py-1"></span>
                                                <span id="vm-status" class="badge bg-success px-3 py-1 fs-6"></span>
                                            </div>
                                            <h4 id="vm-product-name" class="fw-bold mb-0 text-white"></h4>
                                            <div class="text-slate-300 small mt-1">Proposed Brand: <strong
                                                    id="vm-brand-name" class="text-white"></strong></div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-slate-400 small mb-1">Priority Level</div>
                                        <span id="vm-priority" class="badge bg-danger px-3 py-2 fs-6 fw-bold"></span>
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
                                    <div id="vm-category" class="fw-bold fs-6 text-dark"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div
                                    class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-info h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i
                                            class="ph-bold ph-globe me-1 text-info"></i> Channel</div>
                                    <div id="vm-channel" class="fw-bold fs-6 text-dark"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div
                                    class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-warning h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i
                                            class="ph-bold ph-calendar me-1 text-warning"></i> Target Launch</div>
                                    <div id="vm-target-launch" class="fw-bold fs-6 text-dark"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div
                                    class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-success h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i
                                            class="ph-bold ph-shield-check me-1 text-success"></i> Certification</div>
                                    <div id="vm-certification" class="fw-bold fs-6 text-dark"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. INITIATOR & REQUESTER CARD & RATIONAL FOR DEVELOPMENT -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    <div
                                        class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="ph-bold ph-user-circle text-primary fs-5"></i>
                                        <h6 class="fw-bold mb-0 text-dark">Initiator & Requester</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold mb-1">Requester:</div>
                                            <div id="vm-requester"
                                                class="p-2 bg-light rounded border fw-semibold text-dark"></div>
                                        </div>
                                        <div>
                                            <div class="small text-muted fw-bold mb-1">Initiator:</div>
                                            <div id="vm-initiator" class="p-2 bg-light rounded border text-dark">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    <div
                                        class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="ph-bold ph-target text-primary fs-5"></i>
                                        <h6 class="fw-bold mb-0 text-dark">Rational For Development</h6>
                                    </div>
                                    <div class="card-body bg-light p-3">
                                        <div id="vm-rational"
                                            class="p-3 bg-white rounded-3 border-start border-4 border-primary shadow-sm text-secondary fs-6 lh-base h-100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. PRODUCT SPECIFICATIONS CARD -->
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
                                            <div id="vm-product-desc" class="text-dark fs-6"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border h-100">
                                            <div class="fw-bold small text-muted text-uppercase mb-2"><i
                                                    class="ph-bold ph-users me-1"></i> Usage Description (Application
                                                in customer)</div>
                                            <div id="vm-usage-desc" class="text-dark fs-6"></div>
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
                                                <div id="vm-storage-temp" class="fw-bold text-dark fs-6"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-white border rounded-3 gap-3">
                                            <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle"><i
                                                    class="ph-bold ph-truck fs-4"></i></div>
                                            <div>
                                                <div class="small text-muted fw-bold">Delivery Temperature</div>
                                                <div id="vm-delivery-temp" class="fw-bold text-dark fs-6"></div>
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
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">#</th>
                                                <th>Product Name</th>
                                                <th>Size</th>
                                                <th>Packaging</th>
                                                <th class="pe-4">Price Indication</th>
                                            </tr>
                                        </thead>
                                        <tbody id="vm-competitor-tbody"></tbody>
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
                                            <div id="vm-weight" class="p-2 bg-light rounded border fw-bold text-dark">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold mb-1">Packaging Category & Details:
                                            </div>
                                            <div id="vm-packaging-detail" class="p-2 bg-light rounded border"></div>
                                        </div>
                                        <div>
                                            <div class="small text-muted fw-bold mb-1">Product Variation List:</div>
                                            <div id="vm-product-variation"
                                                class="p-2 bg-light rounded border text-dark"></div>
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
                                            <div id="vm-potential-volume"
                                                class="p-2 bg-light rounded border fw-bold text-dark fs-6"></div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="p-2 bg-light rounded border">
                                                    <div class="small text-muted fw-bold">Expected Margin</div>
                                                    <div id="vm-expected-margin" class="fw-bold text-success fs-6">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 bg-light rounded border">
                                                    <div class="small text-muted fw-bold">Price Estimate</div>
                                                    <div id="vm-price-estimate" class="fw-bold text-primary fs-6">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 7. APPROVAL HISTORY TABLE -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-check-square text-primary fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Approval History</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4" style="width: 30%;">Approved By / Role</th>
                                                <th style="width: 15%;">Status</th>
                                                <th style="width: 20%;">Date</th>
                                                <th class="pe-4" style="width: 35%;">Notes / Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody id="vm-approver-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- 8. ACTIVITY HISTORY LOG -->
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-clock-counter-clockwise text-primary fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Activity History Log</h6>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-group list-group-flush" id="vm-history-log-container"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        @if (auth()->user()->can('view create mpdr') || auth()->user()->can('view edit mpdr'))
                            <button type="button" class="btn btn-warning fw-bold" id="btn-resubmit-mpdr-view" style="display: none;">
                                <i class="ph-bold ph-arrow-counter-clockwise me-1"></i>Resubmit Form
                            </button>
                        @endif
                    </div>
                    <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">
                        <i class="ph-bold ph-x me-1"></i>Close
                    </button>
                </div>
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

                let isUpdatingFilter = false;
                const urlParams = new URLSearchParams(window.location.search);
                let currentTab = (urlParams.get('tab') === 'complete' || window.location.hash === '#complete') ?
                    'complete' : 'pending';

                function updateTabCounts(data) {
                    const pendingCount = (data || []).filter(item => item.status !== 'Approved').length;
                    const completeCount = (data || []).filter(item => item.status === 'Approved').length;
                    $('#pendingCount').text(pendingCount);
                    $('#completeCount').text(completeCount);
                }

                function syncFilterOptions(tab) {
                    isUpdatingFilter = true;
                    const select = $('#statusFilter');
                    select.empty();
                    if (tab === 'complete') {
                        select.append(new Option('All Complete (Approved)', 'all', true, true));
                        select.append(new Option('Approved', 'Approved'));
                        $('#tabDescription').html(
                            '<i class="ph-bold ph-check-circle text-success"></i> <span>Showing completed MPDR submissions (Approved).</span>'
                            );
                        $('.table-subtitle').text('View, manage and track all completed & approved submissions');
                    } else {
                        select.append(new Option('All Pending Statuses', 'all', true, true));
                        select.append(new Option('In Approval', 'In Approval'));
                        select.append(new Option('Draft', 'Draft'));
                        select.append(new Option('Rejected', 'Rejected'));
                        $('#tabDescription').html(
                            '<i class="ph-bold ph-hourglass-medium text-warning"></i> <span>Showing MPDR submissions pending approval or draft.</span>'
                            );
                        $('.table-subtitle').text('View, manage and track all pending submissions');
                    }
                    select.trigger('change.select2');
                    isUpdatingFilter = false;
                }

                function filterDataByTabAndStatus(json) {
                    if (!json || !Array.isArray(json)) return [];

                    // 1. Filter by Tab
                    let filtered = [];
                    if (currentTab === 'complete') {
                        filtered = json.filter(item => item.status === 'Approved');
                    } else {
                        filtered = json.filter(item => item.status !== 'Approved');
                    }

                    // 2. Filter by status dropdown
                    const statusFilter = $('#statusFilter').val();
                    if (statusFilter && statusFilter !== 'all') {
                        filtered = filtered.filter(item => item.status === statusFilter);
                    }

                    return filtered;
                }

                // Inisialisasi status filter berdasarkan tab awal
                if (currentTab === 'complete') {
                    $('#tabPendingBtn').removeClass('active').attr('aria-selected', 'false');
                    $('#tabCompleteBtn').addClass('active').attr('aria-selected', 'true');
                    syncFilterOptions('complete');
                } else {
                    $('#tabCompleteBtn').removeClass('active').attr('aria-selected', 'false');
                    $('#tabPendingBtn').addClass('active').attr('aria-selected', 'true');
                    syncFilterOptions('pending');
                }

                const table = $('#prempdrTable').DataTable({
                    processing: true,
                    serverSide: false,
                    autoWidth: false,
                    dom: '<"d-flex align-items-center justify-content-between mb-3"Bf>rt<"d-flex align-items-center justify-content-between mt-3"ip>',
                    buttons: [{
                        extend: 'searchBuilder',
                        config: {
                            depthLimit: 2
                        }
                    }],
                    searchBuilder: {
                        columns: [1, 2, 3, 4, 5, 6]
                    },
                    ajax: {
                        url: "{{ route('mpdr.form.list') }}",
                        dataSrc: function(json) {
                            updateTabCounts(json);
                            return filterDataByTabAndStatus(json);
                        }
                    },
                    columns: [{
                            data: null,
                            searchable: false,
                            orderable: false,
                            width: '5%',
                            className: 'text-center align-middle',
                            render: (d, t, r, meta) => meta.row + 1
                        },
                        {
                            data: 'no',
                            width: '12%',
                            className: 'text-center align-middle text-nowrap',
                            render: data => `<span class="status-badge-lg bg-dark">${data}</span>`
                        },
                        {
                            data: 'product_name',
                            width: '18%',
                            className: 'align-middle',
                            render: data =>
                                `<div class="d-flex align-items-center"><i class="ph-bold ph-folder-simple me-2 text-primary fs-5"></i><span>${data}</span></div>`
                        },
                        {
                            data: 'created_at',
                            width: '12%',
                            className: 'text-center align-middle text-nowrap',
                            render: function(data) {
                                if (!data) return '-';
                                const date = new Date(data);
                                return `<div class="d-flex align-items-center justify-content-center"><i class="ph ph-calendar-check me-2 text-primary fs-5"></i><span>${date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })}</span></div>`;
                            }
                        },
                        {
                            data: 'route_to',
                            width: '31%',
                            className: 'align-middle',
                            render: function(data) {
                                if (!data) return '<span class="text-muted fst-italic">-</span>';
                                const names = data.split(', ');
                                const badges = names.map(name =>
                                    `<span class="badge bg-warning text-dark me-1 mb-1 text-nowrap"><i class="ph-bold ph-user-circle me-1"></i>${name.trim()}</span>`
                                    ).join('');
                                return `<div class="d-flex flex-wrap gap-1 align-items-center">${badges}</div>`;
                            }
                        },
                        {
                            data: 'status',
                            width: '12%',
                            className: 'text-center align-middle text-nowrap',
                            render: function(data, type, row) {
                                let statusClass = {
                                    'Rejected': 'bg-danger',
                                    'Approved': 'bg-success',
                                    'In Approval': 'bg-info',
                                    'Draft': 'bg-warning'
                                } [data] || 'bg-secondary';

                                let progress = '';
                                if (data === 'In Approval' && row.total_approvers > 0) {
                                    progress = ` (${row.approved_count}/${row.total_approvers})`;
                                }

                                return `<span class="status-badge-lg ${statusClass}">${data}${progress}</span>`;
                            }
                        },
                        {
                            data: null,
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            width: '10%',
                            className: 'text-center align-middle text-nowrap',
                            render: function(data, type, row) {
                                // [LOGIKA AKSI YANG BENAR]
                                if (row.status === 'Draft') {
                                    const deleteRoute = "{{ route('mpdr.destroy', ':formId') }}"
                                        .replace(':formId', row.no);
                                    return `
                                    <div class="d-flex gap-1 justify-content-center align-items-center">
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

                                    if (row.status === 'Rejected') {
                                        buttons += `
                                        @if (auth()->user()->can('view create mpdr') || auth()->user()->can('view edit mpdr'))
                                            <button type="button" class="btn btn-sm btn-warning resubmit-form-btn" data-form-id="${row.no}" data-bs-toggle="tooltip" title="Resubmit Form"><i class="ph-bold ph-arrow-counter-clockwise"></i></button>
                                        @endif`;
                                    }

                                    @if (auth()->user()->can('view direct gm mpdr') ||
                                            auth()->user()->hasRole(['super-admin', 'Super-Admin']))
                                        if (row.status === 'In Approval' && row.can_direct_to_gm) {
                                            const sendGmRoute =
                                                "{{ route('mpdr.sendMailToGMUrgent', ':formId') }}"
                                                .replace(':formId', row.no);
                                            buttons += `
                                        <form id="send-form-${row.no}" action="${sendGmRoute}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            <button type="button" class="btn btn-sm text-white send-gm-btn" style="background-color: #4f46e5; border-color: #4f46e5;" send-form-no="${row.no}" onclick="confirmEmail(this)" data-bs-toggle="tooltip" title="Direct to GM">
                                                <i class="ph-bold ph-paper-plane-tilt"></i> GM
                                            </button>
                                        </form>`;
                                        }
                                    @endif

                                    return `<div class="d-flex gap-1 align-items-center justify-content-center">${buttons}</div>`;
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

                window.mpdrTable = table;

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

                // --- EVENT LISTENERS ---
                let currentDetailData = null;

                function setRequesterDisplay(user) {
                    $('#requester_name_display').val(user?.name || '');
                }

                // Tab switching event
                $('.mpdr-tab-link').on('click', function() {
                    const tab = $(this).data('status-tab');
                    if (currentTab === tab) return;

                    currentTab = tab;
                    $('.mpdr-tab-link').removeClass('active').attr('aria-selected', 'false');
                    $(this).addClass('active').attr('aria-selected', 'true');

                    syncFilterOptions(tab);
                    table.ajax.reload(null, false);
                });

                $('#statusFilter').on('change', () => {
                    if (!isUpdatingFilter) {
                        table.ajax.reload(null, false);
                    }
                });

                $('#resetFilters').on('click', () => {
                    isUpdatingFilter = true;
                    $('#statusFilter').val('all').trigger('change.select2');
                    isUpdatingFilter = false;
                    table.search('').ajax.reload();
                });

                // Event listener untuk Kirim Ulang Email Notifikasi MPDR dengan Pilihan Approver Pending
                $(document).on('click', '.resend-email-btn', function() {
                    const formNo = $(this).data('form-no');
                    const btn = $(this);

                    btn.prop('disabled', true);

                    const detailUrl = "{{ route('mpdr.form.detail', ['formId' => ':formId']) }}".replace(
                        ':formId', formNo);
                    $.ajax({
                        url: detailUrl,
                        type: 'GET',
                        success: function(res) {
                            btn.prop('disabled', false);

                            let pendingApprovers = [];
                            if (res && res.approved_detail_ordered) {
                                pendingApprovers = res.approved_detail_ordered.filter(a => a
                                    .status === 'pending');
                            }

                            if (pendingApprovers.length === 0) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'No Pending Approvers',
                                    text: 'All approvers have responded to this form or are inactive.'
                                });
                                return;
                            }

                            let selectHtml = '';
                            if (pendingApprovers.length === 1) {
                                const app = pendingApprovers[0];
                                selectHtml = `<p class="mb-2 text-muted">The approval notification will be resent to:</p>
                                    <div class="p-3 bg-light border rounded text-start">
                                        <strong>${app.approver_name}</strong> <span class="badge bg-secondary ms-1">${app.approver_position || 'Approver'}</span>
                                        <div class="text-muted small">NIK: ${app.approver_nik}</div>
                                    </div>
                                    <input type="hidden" id="swal-approver-nik" value="${app.approver_nik}">`;
                            } else {
                                let options = pendingApprovers.map(a =>
                                    `<option value="${a.approver_nik}">${a.approver_name} (${a.approver_position || 'Approver'}) - NIK: ${a.approver_nik}</option>`
                                    ).join('');
                                selectHtml = `<p class="mb-2 text-muted">Select the pending approver who should receive the resent approval email:</p>
                                    <select id="swal-approver-nik" class="form-select border border-2">
                                        ${options}
                                    </select>`;
                            }

                            Swal.fire({
                                title: `Resend Email (${formNo})`,
                                html: selectHtml +
                                    `<div class="alert alert-warning mt-3 p-2 text-start small"><i class="ph-bold ph-warning-circle me-1"></i> The selected approver's previous email link will <strong>expire</strong>.</div>`,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#eab308',
                                cancelButtonColor: '#64748b',
                                confirmButtonText: '<i class="ph-bold ph-paper-plane-tilt me-1"></i> Resend Email',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const selectedNik = $('#swal-approver-nik').val();
                                    btn.prop('disabled', true);
                                    $.ajax({
                                        url: "{{ url('mpdr/resend-email') }}/" +
                                            formNo,
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            approver_nik: selectedNik
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
                                            const msg = xhr.responseJSON ? xhr
                                                .responseJSON.message :
                                                'A server error occurred.';
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
                        },
                        error: function() {
                            btn.prop('disabled', false);
                            Swal.fire('Error', 'Failed to load the pending approver list.',
                            'error');
                        }
                    });
                });

                // --- LOGIKA MODAL ---
                function clearValidationErrors() {
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                }

                function resetForm() {
                    $('#mpdrForm')[0].reset();
                    $('#mpdrForm :input').prop('disabled', false);
                    $('#existingProductRequestOption').hide();
                    $('#typeNew').prop('checked', true);
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
                    // Reset checkboxes
                    $('input[name="channel[]"]').prop('checked', false);
                    $('input[name="certification[]"]').prop('checked', false);
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
                        currentDetailData = null;
                        populateForm(data);
                        if (data.user) {
                            setRequesterDisplay(data.user);
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

                        // Refresh submit button state after all fields & requester have been populated
                        try {
                            toggleSubmitButtons();
                        } catch (e) {}
                    }).fail(function() {
                        // Tambahan: Memberi tahu jika ada error
                        alert('Failed to load Pre-MPDR details. Check the console for errors.');
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
                    const isFromPreMpdr = !data.product_name && !!data.project_name;
                    let initiatorNik = '';
                    if (data.requester && data.requester.nik) {
                        initiatorNik = data.requester.nik;
                    } else if (data.user && data.user.nik) {
                        initiatorNik = data.user.nik;
                    } else if (data.initiator_detail && data.initiator_detail.initiator_nik) {
                        initiatorNik = data.initiator_detail.initiator_nik;
                    } else if (data.initiator) {
                        initiatorNik = data.initiator;
                    }

                    const productName = data.product_name || data.project_name || '';
                    const brandName = data.brand_name || (data.pre_mpdr ? data.pre_mpdr.brand_name : (data.preMpdr ?
                        data.preMpdr.brand_name : ''));

                    $('input[name="initiator"]').val(initiatorNik);
                    $('input[name="productName"]').val(productName || '');
                    $('select[name="levelPriority"]').val(data.level_priority || '');
                    $('input[name="brandName"]').val(brandName || '');

                    if (data.type_of_request) {
                        $('#existingProductRequestOption').toggle(data.type_of_request === 'Existing Product');
                        $(`input[name="typeOfRequest"][value="${data.type_of_request}"]`).prop('checked', true);
                    } else {
                        $('#existingProductRequestOption').hide();
                        $('#typeNew').prop('checked', true);
                    }

                    const preNo = data.prempdr_no || (data.pre_mpdr ? data.pre_mpdr.no : (data.preMpdr ? data.preMpdr.no : (isFromPreMpdr ? data.no : '')));
                    if (preNo) {
                        if (!$('#no_reg_select option[value="' + preNo + '"]').length) {
                            $('#no_reg_select').append(new Option(preNo, preNo, true, true));
                        }
                        $('#no_reg_select').val(preNo);
                    }

                    if (data.detail) {
                        $('textarea[name="rationalForDevelopment"]').val(data.detail.rational_for_development || '');
                    }

                    // If MPDR record provides requester relation, populate requester display
                    if (data.requester) {
                        setRequesterDisplay(data.requester);
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
                    // --- Channel ---
                    $('input[name="channel[]"]').prop('checked', false);
                    if (data.channel) {
                        const channelVal = data.channel.category || (typeof data.channel === 'string' ? data.channel :
                            '');
                        const channelArr = channelVal.split(',').map(v => v.trim()).filter(Boolean);
                        channelArr.forEach(value => {
                            $(`input[name="channel[]"][value="${value}"]`).prop('checked', true);
                        });
                        if (channelArr.includes('International')) {
                            $('#country').val(data.channel.country || '').prop('readonly', false);
                        } else {
                            $('#country').val('').prop('readonly', true);
                        }
                    }
                    if (data.description) {
                        $('textarea[name="productDescription"]').val(data.description.product_description);
                        $('input[name="usageDescription"]').val(data.description.usage_description);
                        $('input[name="storageTemperature"]').val(data.description.storage_temperature);
                        $('input[name="deliveryTemperature"]').val(data.description.delivery_temperature);
                    }
                    // --- Certification ---
                    $('input[name="certification[]"]').prop('checked', false);
                    if (data.certification) {
                        const certVal = data.certification.category || (typeof data.certification === 'string' ? data
                            .certification : '');
                        const certArr = certVal.split(',').map(v => v.trim()).filter(Boolean);
                        certArr.forEach(value => {
                            $(`input[name="certification[]"][value="${value}"]`).prop('checked', true);
                        });
                        if (certArr.includes('Others')) {
                            $('#certificationText').val(data.certification.other || '').prop('readonly', false);
                        } else {
                            $('#certificationText').val('').prop('readonly', true);
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
                    // Product Category: only enable the text input if Others is checked
                    const isPcOtherChecked = ($('#productCategory input[name="productCategory"]:checked').val() === 'Others');
                    if (isPcOtherChecked) {
                        $('#productCategoryText').prop('readonly', false);
                    } else {
                        $('#productCategoryText').prop('readonly', true).val('');
                    }

                    // Channel: enable country input if International is checked
                    const isIntlChecked = $('input[name="channel[]"][value="International"]').is(':checked');
                    if (isIntlChecked) {
                        $('#country').prop('readonly', false);
                    } else {
                        $('#country').prop('readonly', true).val('');
                    }

                    // Certification: enable text input if Others is checked
                    const isCertOtherChecked = $('input[name="certification[]"][value="Others"]').is(':checked');
                    if (isCertOtherChecked) {
                        $('#certificationText').prop('readonly', false);
                    } else {
                        $('#certificationText').prop('readonly', true).val('');
                    }

                    // Packaging: enable only the textarea belonging to the checked packaging radio
                    const pkgVal = $('input[name="packaging"]:checked').val();
                    if (pkgVal === 'Existing') {
                        $('#ExistingPackagingText').prop('readonly', false);
                        $('#NewPackagingText').prop('readonly', true).val('');
                    } else if (pkgVal === 'New') {
                        $('#NewPackagingText').prop('readonly', false);
                        $('#ExistingPackagingText').prop('readonly', true).val('');
                    } else {
                        $('#ExistingPackagingText, #NewPackagingText').prop('readonly', true).val('');
                    }
                }

                // Client-side validation for required MPDR fields (Competitor's Product is optional / can be empty)
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
                    if ($('input[name="productCategory"]:checked').val() === 'Others' && !hasVal('#productCategoryText')) return false;
                    if ($('input[name="channel[]"]:checked').length === 0) return false;
                    if ($('input[name="channel[]"][value="International"]').is(':checked') && !hasVal('#country')) return false;
                    if (!hasVal('textarea[name="productDescription"]')) return false;
                    if (!hasVal('input[name="usageDescription"]')) return false;
                    if (!hasVal('input[name="storageTemperature"]')) return false;
                    if (!hasVal('input[name="deliveryTemperature"]')) return false;
                    if ($('input[name="certification[]"]:checked').length === 0) return false;
                    if ($('input[name="certification[]"][value="Others"]').is(':checked') && !hasVal('#certificationText')) return false;
                    if (!hasVal('input[name="weightProduct"]')) return false;
                    if ($('input[name="packaging"]:checked').length === 0) return false;
                    if (!hasVal('input[name="productVariation"]')) return false;
                    if (!hasVal('input[name="potentialVolume"]')) return false;
                    if (!hasVal('input[name="expectedMargin"]')) return false;
                    if (!hasVal('input[name="priceEstimate"]')) return false;
                    if (!hasVal('input[name="targetLaunchText"]')) return false;

                    // Competitor's Product is intentionally NOT required (bisa kosong)
                    return true;
                }

                function toggleSubmitButtons() {
                    const ok = isMpdrFormValid();
                    // Disable primary Submit button if not all required fields are filled; Draft remains enabled
                    $('.btn-submit[value="Submit"]').prop('disabled', !ok);
                }

                // listen to changes on all required fields and toggle submit accordingly
                $(document).on('input change',
                    'input[name="productName"], input[name="brandName"], select[name="levelPriority"], input[name="typeOfRequest"], #requester_id, #requester_name_display, textarea[name="rationalForDevelopment"], input[name="usageDescription"], input[name="storageTemperature"], input[name="deliveryTemperature"], textarea[name="productDescription"], input[name="targetLaunchText"], input[name="productCategory"], input[name="channel[]"], input[name="certification[]"], #country, #certificationText, #productCategoryText, input[name="weightProduct"], input[name="packaging"], input[name="productVariation"], input[name="potentialVolume"], input[name="expectedMargin"], input[name="priceEstimate"], #ExistingPackagingText, #NewPackagingText',
                    toggleSubmitButtons);

                // delegate change events so it works with dynamic content and programmatic triggers
                $(document).on('change', 'input[name="productCategory"]', applyScopedToggles);
                $(document).on('change', 'input[name="channel[]"]', applyScopedToggles);
                $(document).on('change', 'input[name="certification[]"]', applyScopedToggles);
                $(document).on('change', 'input[name="packaging"]', applyScopedToggles);

                // ensure proper initial state
                applyScopedToggles();


                // Handler tombol VIEW MPDR (Executive Summary Modal)
                $('#prempdrTable tbody').on('click', '.view-form-btn', function() {
                    const btn = $(this);
                    const originalHtml = btn.html();
                    btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm"></span>`);

                    const formId = $(this).data('form-id');
                    const url = `{{ url('mpdr/detail') }}/${formId}`;

                    $('#v-mpdr-loader').show();
                    $('#v-mpdr-content-wrapper').hide();
                    $('#mpdrViewModal').modal('show');

                    $.get(url, function(data) {
                        populateMpdrViewModal(data);
                        $('#v-mpdr-loader').hide();
                        $('#v-mpdr-content-wrapper').fadeIn(200);
                    }).fail(function() {
                        $('#mpdrViewModal').modal('hide');
                        Swal.fire('Error', 'Failed to fetch MPDR details.', 'error');
                    }).always(function() {
                        btn.prop('disabled', false).html(originalHtml);
                    });
                });

                // Handler tombol EDIT MPDR (Form Modal)
                $('#prempdrTable tbody').on('click', '.edit-form-btn', function() {
                    const btn = $(this);
                    const originalHtml = btn.html();
                    btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm"></span>`);

                    const formId = $(this).data('form-id');
                    const url = `{{ url('mpdr/detail') }}/${formId}`;

                    resetForm();

                    $('#mpdrModalLabel').html(
                        `<i class="ph-bold ph-pencil-simple me-2"></i>Edit MPDR: ${formId}`
                    );
                    $('.btn-submit').show();
                    $('#preMpdrSelector').hide();
                    $('#form-fieldset').prop('disabled', false);

                    $('#mpdrForm').attr('action', `{{ url('mpdr/update') }}/${formId}`);
                    $('#formMethod').val('PUT');
                    $('#requester_name_display').prop('readonly', true);
                    $('input[name="typeOfRequest"]').prop('disabled', true);

                    $.get(url, function(data) {
                        currentDetailData = data;
                        populateForm(data);
                        if (data.requester) {
                            setRequesterDisplay(data.requester);
                            $('#requester_id').val(data.requester.id);
                        } else if (data.user) {
                            setRequesterDisplay(data.user);
                            $('#requester_id').val(data.user.id);
                        } else if (data.initiator) {
                            $('#requester_name_display').val(data.initiator);
                        }
                        $('#requester_name_display').prop('readonly', true);
                        $('input[name="typeOfRequest"]').prop('disabled', true);
                        $('#no_reg_text').text(data.no);
                        $('#no_reg').val(data.no);

                        try {
                            toggleSubmitButtons();
                        } catch (e) {}

                        $('#mpdrModal').modal('show');

                    }).fail(() => {
                        Swal.fire('Error', 'Failed to fetch details.', 'error');
                    }).always(() => {
                        btn.prop('disabled', false).html(originalHtml);
                    });
                });

                // Handler untuk Resubmit MPDR jika status Rejected
                function openResubmitMpdr(formId, triggerBtn) {
                    let originalHtml = '';
                    if (triggerBtn) {
                        originalHtml = $(triggerBtn).html();
                        $(triggerBtn).prop('disabled', true).html(`<span class="spinner-border spinner-border-sm"></span>`);
                    }

                    const detailUrl = `{{ url('mpdr/detail') }}/${formId}`;
                    const noRegUrl = "{{ route('mpdr.noReg') }}";

                    $.when(
                        $.get(detailUrl),
                        $.get(noRegUrl)
                    ).done(function(detailRes, noRegRes) {
                        const detailData = detailRes[0];
                        const newNoReg = noRegRes[0].no_reg;

                        resetForm();

                        $('#mpdrModalLabel').html(
                            `<i class="ph-bold ph-arrow-counter-clockwise me-2 text-warning"></i>Resubmit MPDR: <strong>${newNoReg}</strong> <span class="badge bg-danger ms-2" style="font-size:0.75rem">Rejected source: ${formId}</span>`
                        );
                        $('.btn-submit').show();
                        $('#mpdrForm').attr('action', "{{ route('mpdr.store') }}");
                        $('#formMethod').val('');

                        // Enable form fieldset so all fields can be edited
                        $('#form-fieldset').prop('disabled', false);
                        $('#form-fieldset :input').prop('disabled', false);

                        currentDetailData = detailData;
                        populateForm(detailData);

                        // Ensure Pre-MPDR relation is set & option exists
                        const preNo = detailData.prempdr_no || (detailData.pre_mpdr ? detailData.pre_mpdr.no : (detailData.preMpdr ? detailData.preMpdr.no : ''));
                        if (preNo) {
                            if (!$('#no_reg_select option[value="' + preNo + '"]').length) {
                                $('#no_reg_select').append(new Option(preNo, preNo, true, true));
                            }
                            $('#no_reg_select').val(preNo);
                        }
                        $('#preMpdrSelector').show();
                        $('#no_reg_select').prop('disabled', true);

                        // Override registration number with newly generated number
                        $('#no_reg_text').text(newNoReg);
                        $('#no_reg').val(newNoReg);

                        // Requester setup
                        if (detailData.requester) {
                            setRequesterDisplay(detailData.requester);
                            $('#requester_id').val(detailData.requester.id);
                        } else if (detailData.user) {
                            setRequesterDisplay(detailData.user);
                            $('#requester_id').val(detailData.user.id);
                        } else if (detailData.initiator) {
                            $('#requester_name_display').val(detailData.initiator);
                        }
                        $('#requester_name_display').prop('readonly', true).prop('disabled', true);

                        applyScopedToggles();
                        try {
                            toggleSubmitButtons();
                        } catch (e) {}

                        $('#mpdrModal').modal('show');
                    }).fail(function() {
                        Swal.fire('Error', 'Failed to fetch MPDR details or generate a new registration number.', 'error');
                    }).always(function() {
                        if (triggerBtn) {
                            $(triggerBtn).prop('disabled', false).html(originalHtml);
                        }
                    });
                }

                // Table Resubmit Button Handler
                $('#prempdrTable tbody').on('click', '.resubmit-form-btn', function() {
                    const formId = $(this).data('form-id');
                    openResubmitMpdr(formId, this);
                });

                // Detail View Modal Resubmit Button Handler
                $('#btn-resubmit-mpdr-view').on('click', function() {
                    const formId = $(this).data('form-id');
                    $('#mpdrViewModal').modal('hide');
                    openResubmitMpdr(formId, null);
                });

                function populateMpdrViewModal(data) {
                    if (!data) return;

                    $('#vm-no').text(data.no || '-');
                    $('#vm-revision').text('REV ' + (data.revision ? (data.revision.revision ?? '0') : '0'));

                    if (data.type_of_request) {
                        $('#vm-type-request').text(data.type_of_request).show();
                    } else {
                        $('#vm-type-request').hide();
                    }

                    let statusClass = 'bg-secondary';
                    if (data.status === 'Approved') statusClass = 'bg-success';
                    else if (data.status === 'Rejected') statusClass = 'bg-danger';
                    else if (data.status === 'In Approval') statusClass = 'bg-info';
                    else if (data.status === 'Draft') statusClass = 'bg-warning text-dark';

                    $('#vm-status').removeClass().addClass('badge px-3 py-1 fs-6 ' + statusClass).text(data.status ||
                        'Draft');

                    if (data.status === 'Rejected') {
                        $('#btn-resubmit-mpdr-view').data('form-id', data.no).show();
                    } else {
                        $('#btn-resubmit-mpdr-view').hide();
                    }

                    $('#vm-product-name').text(data.product_name || '-');

                    const brandName = data.brand_name || (data.pre_mpdr ? data.pre_mpdr.brand_name : (data.preMpdr ?
                        data.preMpdr.brand_name : 'N/A'));
                    $('#vm-brand-name').text(brandName);
                    $('#vm-priority').html('<i class="ph-bold ph-fire me-1"></i> Priority ' + (data.level_priority ||
                        'Normal'));

                    const category = (data.category && data.category.category) ? data.category.category : '-';
                    const categoryOther = (data.category && data.category.other) ? ` (${data.category.other})` : '';
                    $('#vm-category').text(category + categoryOther);

                    const channel = (data.channel && data.channel.category) ? data.channel.category : '-';
                    const country = (data.channel && data.channel.country) ? ` (${data.channel.country})` : '';
                    $('#vm-channel').text(channel + country);

                    $('#vm-target-launch').text(data.detail ? (data.detail.target_launch || 'N/A') : 'N/A');

                    const cert = (data.certification && data.certification.category) ? data.certification.category :
                    '-';
                    const certOther = (data.certification && data.certification.other) ?
                        ` (${data.certification.other})` : '';
                    $('#vm-certification').text(cert + certOther);

                    const requesterName = data.requester?.name || (data.user?.name || data.initiator || '-');
                    $('#vm-requester').text(requesterName);

                    const initiatorName = data.initiator || (data.initiator_detail ? data.initiator_detail
                        .initiator_name : '-');
                    $('#vm-initiator').text(initiatorName);

                    $('#vm-rational').text(data.detail ? (data.detail.rational_for_development ||
                        'No rational provided.') : 'No rational provided.');

                    $('#vm-product-desc').html(data.description ? (data.description.product_description || 'N/A') :
                        'N/A');
                    $('#vm-usage-desc').html(data.description ? (data.description.usage_description || 'N/A') : 'N/A');
                    $('#vm-storage-temp').text(data.description ? (data.description.storage_temperature || 'N/A') :
                        'N/A');
                    $('#vm-delivery-temp').text(data.description ? (data.description.delivery_temperature || 'N/A') :
                        'N/A');

                    // Competitors Table
                    let compHtml = '';
                    if (data.competitor && Array.isArray(data.competitor) && data.competitor.length > 0) {
                        data.competitor.forEach((item, index) => {
                            compHtml += `<tr>
                                <td class="ps-4 fw-bold text-muted">${index + 1}</td>
                                <td class="fw-semibold text-dark">${item.name || '-'}</td>
                                <td><span class="badge bg-light text-dark border">${item.size || '-'}</span></td>
                                <td>${item.packaging || '-'}</td>
                                <td class="pe-4 fw-bold text-success">${item.price || '-'}</td>
                            </tr>`;
                        });
                    } else {
                        compHtml =
                            '<tr><td colspan="5" class="text-center py-4 text-muted">No competitor product specified.</td></tr>';
                    }
                    $('#vm-competitor-tbody').html(compHtml);

                    // Packaging & Market
                    $('#vm-weight').text(data.packaging ? (data.packaging.weight || 'N/A') : 'N/A');
                    const packType = data.packaging ? (data.packaging.category || '-') : '-';
                    const packDetail = data.packaging && data.packaging.detail ? data.packaging.detail : '';
                    $('#vm-packaging-detail').html(
                        `<span class="badge bg-primary me-2">${packType}</span><span class="text-dark">${packDetail || 'No extra details'}</span>`
                        );
                    $('#vm-product-variation').text(data.packaging ? (data.packaging.product_variation || 'N/A') :
                        'N/A');

                    $('#vm-potential-volume').text(data.market ? (data.market.potential_volume || 'N/A') : 'N/A');
                    $('#vm-expected-margin').text(data.market ? (data.market.expected_margin || 'N/A') : 'N/A');
                    $('#vm-price-estimate').text(data.market ? (data.market.price_estimate || 'N/A') : 'N/A');

                    // Approval History Table
                    let appHtml = '';
                    const details = data.approved_detail_ordered || data.approved_detail || [];
                    if (details && details.length > 0) {
                        details.forEach(d => {
                            let badgeClass = 'bg-secondary';
                            if (d.status === 'approve' || d.status === 'Approved') badgeClass = 'bg-success';
                            else if (d.status === 'approve with review') badgeClass = 'bg-warning text-dark';
                            else if (d.status === 'rejected' || d.status === 'Rejected') badgeClass =
                                'bg-danger';

                            const commentBox = d.comment ?
                                `<div style="max-height: 120px; overflow-y: auto; word-break: break-word; white-space: pre-wrap; line-height: 1.45;" class="p-2 bg-light rounded border-start border-3 border-info small text-dark">${d.comment}</div>` :
                                '<span class="text-muted">-</span>';

                            appHtml += `<tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">${d.approver_position || d.approver_name || d.name || '-'}</div>
                                </td>
                                <td><span class="badge ${badgeClass}">${d.status}</span></td>
                                <td class="text-muted small">${d.approved_date || '-'}</td>
                                <td class="pe-4">${commentBox}</td>
                            </tr>`;
                        });
                    } else {
                        appHtml =
                            '<tr><td colspan="4" class="text-center py-4 text-muted">No approval history data found.</td></tr>';
                    }
                    $('#vm-approver-tbody').html(appHtml);

                    // History Log
                    const history = data.history || [];
                    const $list = $('#vm-history-log-container');
                    $list.empty();

                    if (!history.length) {
                        $list.append('<li class="list-group-item text-muted border-0 ps-0">No history available.</li>');
                    } else {
                        history.forEach(function(item) {
                            const actor = item.causer || item.actor || 'System';
                            const event = item.description || item.event || '';
                            const time = item.created_at || item.time || '';
                            $list.append(`
                                <li class="list-group-item px-0 py-2 border-bottom border-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div><strong class="text-dark">${actor}</strong> <span class="text-muted">— ${event}</span></div>
                                        <div class="text-muted small font-monospace">${time}</div>
                                    </div>
                                </li>
                            `);
                        });
                    }
                }

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
                            const disabledInputs = $(form).find(':disabled');
                            disabledInputs.prop('disabled', false);
                            const formData = new FormData(form);
                            disabledInputs.prop('disabled', true);
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
                                        $('#statusFilter').val('all').trigger('change');
                                        table.search('').draw();
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

            });

            function confirmDelete(button) {
                var formNo = button.getAttribute('data-form-no');
                var form = document.getElementById('delete-form-' + formNo);
                if (!form) return;
                var action = form.action;
                var tokenInput = form.querySelector('input[name="_token"]');
                var token = tokenInput ? tokenInput.value : '';

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This MPDR request will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: action,
                            type: 'POST',
                            data: {
                                _token: token,
                                _method: 'DELETE'
                            },
                            success: function(resp) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    text: resp.message ||
                                        'The MPDR request was deleted successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                var table = $('#prempdrTable').DataTable();
                                if (table) table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON
                                    .message : 'Failed to delete the MPDR request.';
                                Swal.fire('Error', msg, 'error');
                            }
                        });
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
                    text: 'The email will be sent directly to the GM if no approval has been rejected. Pending or vacant approvals will not change until the GM approves.',
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
                                    title: 'Email Scheduled',
                                    text: response.message || 'Email has been sent successfully.',
                                });
                                if (window.mpdrTable) {
                                    window.mpdrTable.ajax.reload(null, false);
                                }
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
