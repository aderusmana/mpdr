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
        <style>
            .comment-bubble {
                max-height: 140px;
                overflow-y: auto;
                word-break: break-word;
                white-space: pre-wrap;
                line-height: 1.45;
                box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
            }
            .comment-bubble::-webkit-scrollbar {
                width: 4px;
            }
            .comment-bubble::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.03);
            }
            .comment-bubble::-webkit-scrollbar-thumb {
                background: rgba(99, 102, 241, 0.3);
                border-radius: 4px;
            }
            .comment-bubble::-webkit-scrollbar-thumb:hover {
                background: rgba(99, 102, 241, 0.6);
            }
        </style>
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
                    <a class="f-s-14 f-w-500" href="#">Pre-MPDR List
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Status Tabs (Pending & Complete) + Create Button --}}
            <div class="mpdr-status-tabs-container">
                <div class="mpdr-nav-pills" role="tablist">
                    <button class="mpdr-tab-link active" id="tabPendingBtn" data-status-tab="pending" type="button" role="tab" aria-selected="true">
                        <i class="ph-bold ph-clock-countdown fs-5"></i>
                        <span>Pending</span>
                        <span class="mpdr-tab-badge mpdr-tab-badge-pending" id="pendingCount">0</span>
                    </button>
                    <button class="mpdr-tab-link" id="tabCompleteBtn" data-status-tab="complete" type="button" role="tab" aria-selected="false">
                        <i class="ph-bold ph-check-circle fs-5"></i>
                        <span>Complete</span>
                        <span class="mpdr-tab-badge mpdr-tab-badge-complete" id="completeCount">0</span>
                    </button>
                </div>

                <div class="action-btn-wrapper">
                    @can('view create pre')
                        <button class="btn new-mpdr-btn" type="button" data-bs-toggle="modal"
                            data-bs-target="#prempdrModal" id="btn-create-prempdr">
                            <i class="ph-bold ph-plus"></i>
                            <span>New Pre-MPDR</span>
                        </button>
                    @endcan
                </div>
            </div>

            {{-- Filter dan Subtitle Hint --}}
            <div class="filter-action-bar">
                <div class="filter-group">
                    <span class="text-muted fw-bold text-nowrap filter-label"><i class="ph-bold ph-funnel me-1"></i>Filter by:</span>
                    <div class="select2-wrapper">
                        <select id="statusFilter" class="form-select select2">
                            <option value="all">All Pending Statuses</option>
                            <option value="Draft">Draft</option>
                            <option value="In Approval">In Approval</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <button id="resetFilters" class="btn btn-secondary border flex-shrink-0" data-bs-toggle="tooltip" title="Reset Filters">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </button>
                </div>
                <div class="tab-info-hint" id="tabDescription">
                    <i class="ph-bold ph-hourglass-medium text-warning"></i>
                    <span>Showing Pre-MPDR submissions pending approval or draft.</span>
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
                                <th class="text-center" style="width: 5%;">No.</th>
                                <th class="text-center" style="width: 12%;">No. Reg</th>
                                <th style="width: 18%;">Project Name</th>
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
                        <div id="prempdrFormContainer">
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
                                            <input class="form-check-input" type="checkbox" name="channel[]"
                                                id="channel1" value="International">
                                            <label class="form-check-label" for="channel1">International</label>
                                            <input type="text" class="form-control form-control-sm" name="country"
                                                id="country" placeholder="Country" readonly style="width: 150px;">
                                        </div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox"
                                                name="channel[]" id="channel2" value="Regional"><label
                                                class="form-check-label" for="channel2">Regional</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox"
                                                name="channel[]" id="channel3" value="Industrial"><label
                                                class="form-check-label" for="channel3">Industrial</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox"
                                                name="channel[]" id="channel4" value="FSBC-Direct"><label
                                                class="form-check-label" for="channel4">FSBC-Direct</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox"
                                                name="channel[]" id="channel5" value="FSBC-Distributor"><label
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
                                            <input class="form-check-input" type="checkbox" name="certification[]"
                                                id="certification1" value="BPOM">
                                            <label class="form-check-label" for="certification1">BPOM</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="certification[]"
                                                id="certification2" value="HALAL">
                                            <label class="form-check-label" for="certification2">HALAL</label>
                                        </div>
                                        {{-- [PERBAIKAN STRUKTUR HTML] --}}
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input class="form-check-input" type="checkbox" name="certification[]"
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
                                                <label for="weightProduct" class="form-label">Weight of Product (kg):</label>
                                                <input type="text" class="form-control" name="weightProduct"
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
                                                <input type="text" class="form-control" name="potentialVolume"
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
                                    <div class="tracker-step" data-step-name="Supervisor">
                                        <div class="tracker-icon"><i class="ph-bold ph-user-plus fs-6"></i></div>
                                        <div class="tracker-label">Supervisor</div>
                                        <div class="tracker-details"></div>
                                    </div>
                                    <div class="tracker-step" data-step-name="Asisten Manager Marketing">
                                        <div class="tracker-icon"><i class="ph-bold ph-users fs-6"></i></div>
                                        <div class="tracker-label">Assistant Marketing Manager</div>
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

    {{-- MODAL KHUSUS VIEW DETAIL PRE-MPDR (EXECUTIVE SUMMARY REPORT REDESIGN) --}}
    <div class="modal fade" id="prempdrViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark bg-gradient text-white border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="prempdrViewModalLabel">
                        <i class="ph-bold ph-file-text text-info fs-4"></i>
                        <span>Pre-MPDR Executive Summary</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background-color: #f8fafc;">
                    <!-- Loader Spinner inside Modal -->
                    <div id="v-modal-loader" class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h6 class="mt-3 fw-bold text-muted">Fetching Pre-MPDR Details...</h6>
                    </div>

                    <!-- Main View Content Container -->
                    <div id="v-modal-content-wrapper" style="display: none;">
                        <!-- 1. EXECUTIVE HEADER BANNER -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                            <div class="card-body p-4 text-white">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-3 bg-white bg-opacity-10 rounded-3 border border-white border-opacity-10">
                                            <i class="ph-bold ph-receipt text-info" style="font-size: 2.2rem;"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                <span id="v-no" class="badge bg-info text-dark px-3 py-1 font-monospace fw-bold fs-6"></span>
                                                <span id="v-revision" class="badge bg-secondary px-2 py-1 font-monospace">REV 0</span>
                                                <span id="v-status" class="badge bg-success px-3 py-1 fs-6"></span>
                                            </div>
                                            <h4 id="v-project-name" class="fw-bold mb-0 text-white"></h4>
                                            <div class="text-slate-300 small mt-1">Proposed Brand: <strong id="v-brand-name" class="text-white"></strong> <span class="text-white-50 mx-2">•</span> Submitted by: <strong id="v-submitted-by" class="text-white"></strong></div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-slate-400 small mb-1">Priority Level</div>
                                        <span id="v-priority" class="badge bg-danger px-3 py-2 fs-6 fw-bold"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. QUICK METRIC KPI CARDS -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-primary h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i class="ph-bold ph-tag me-1 text-primary"></i> Category</div>
                                    <div id="v-category" class="fw-bold fs-6 text-dark"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-info h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i class="ph-bold ph-globe me-1 text-info"></i> Channel</div>
                                    <div id="v-channel" class="fw-bold fs-6 text-dark"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-warning h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i class="ph-bold ph-calendar me-1 text-warning"></i> Target Launch</div>
                                    <div id="v-target-launch" class="fw-bold fs-6 text-dark"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-success h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1"><i class="ph-bold ph-shield-check me-1 text-success"></i> Certification</div>
                                    <div id="v-certification" class="fw-bold fs-6 text-dark"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. RATIONAL FOR DEVELOPMENT CARD -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-target text-primary fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Rational For Development</h6>
                            </div>
                            <div class="card-body bg-light p-3">
                                <div id="v-rational" class="p-3 bg-white rounded-3 border-start border-4 border-primary shadow-sm text-secondary fs-6 lh-base"></div>
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
                                            <div class="fw-bold small text-muted text-uppercase mb-2"><i class="ph-bold ph-note-pencil me-1"></i> Product Description (Form/Color/Flavor)</div>
                                            <div id="v-product-desc" class="text-dark fs-6"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border h-100">
                                            <div class="fw-bold small text-muted text-uppercase mb-2"><i class="ph-bold ph-users me-1"></i> Usage Description (Application in customer)</div>
                                            <div id="v-usage-desc" class="text-dark fs-6"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-white border rounded-3 gap-3">
                                            <div class="p-2 bg-info bg-opacity-10 text-info rounded-circle"><i class="ph-bold ph-thermometer-cold fs-4"></i></div>
                                            <div>
                                                <div class="small text-muted fw-bold">Storage Temperature</div>
                                                <div id="v-storage-temp" class="fw-bold text-dark fs-6"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-white border rounded-3 gap-3">
                                            <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle"><i class="ph-bold ph-truck fs-4"></i></div>
                                            <div>
                                                <div class="small text-muted fw-bold">Delivery Temperature</div>
                                                <div id="v-delivery-temp" class="fw-bold text-dark fs-6"></div>
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
                                        <tbody id="v-competitor-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- 6. PACKAGING & MARKET STRATEGY CARDS -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="ph-bold ph-package text-primary fs-5"></i>
                                        <h6 class="fw-bold mb-0 text-dark">Packaging Details</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold mb-1">Weight of Product:</div>
                                            <div id="v-weight" class="p-2 bg-light rounded border fw-bold text-dark"></div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold mb-1">Packaging Category & Details:</div>
                                            <div id="v-packaging-detail" class="p-2 bg-light rounded border"></div>
                                        </div>
                                        <div>
                                            <div class="small text-muted fw-bold mb-1">Product Variation List:</div>
                                            <div id="v-product-variation" class="p-2 bg-light rounded border text-dark"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="ph-bold ph-chart-line-up text-success fs-5"></i>
                                        <h6 class="fw-bold mb-0 text-dark">Market Update & Pricing Strategy</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <div class="small text-muted fw-bold mb-1">Potential Volume (Mt/Annually):</div>
                                            <div id="v-potential-volume" class="p-2 bg-light rounded border fw-bold text-dark fs-6"></div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="p-2 bg-light rounded border">
                                                    <div class="small text-muted fw-bold">Expected Margin</div>
                                                    <div id="v-expected-margin" class="fw-bold text-success fs-6"></div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 bg-light rounded border">
                                                    <div class="small text-muted fw-bold">Price Estimate</div>
                                                    <div id="v-price-estimate" class="fw-bold text-primary fs-6"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 7. APPROVAL TRACKING -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-path text-primary fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Approval & Process Tracking</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <span class="fw-bold me-3">Current Status:</span>
                                    <div id="v_status_badge"></div>
                                </div>
                                <div class="tracker-container" id="v-approval-tracker-container"></div>
                            </div>
                        </div>

                        <!-- 8. ACTIVITY HISTORY -->
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                <i class="ph-bold ph-clock-counter-clockwise text-primary fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Activity History Log</h6>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-group list-group-flush" id="v-history-log-container"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        @if (auth()->user()->can('view create pre') || auth()->user()->can('view edit pre'))
                            <button type="button" class="btn btn-warning fw-bold" id="btn-resubmit-prempdr-view" style="display: none;">
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
            $(function() {
                // Inisialisasi Select2 untuk filter
                $('#statusFilter').select2({
                    theme: 'bootstrap-5',
                    minimumResultsForSearch: Infinity
                });

                let isUpdatingFilter = false;
                const urlParams = new URLSearchParams(window.location.search);
                let currentTab = (urlParams.get('tab') === 'complete' || window.location.hash === '#complete') ? 'complete' : 'pending';

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
                        $('#tabDescription').html('<i class="ph-bold ph-check-circle text-success"></i> <span>Showing completed Pre-MPDR submissions (Approved).</span>');
                        $('.table-subtitle').text('View, manage and track all completed & approved submissions');
                    } else {
                        select.append(new Option('All Pending Statuses', 'all', true, true));
                        select.append(new Option('In Approval', 'In Approval'));
                        select.append(new Option('Draft', 'Draft'));
                        select.append(new Option('Rejected', 'Rejected'));
                        $('#tabDescription').html('<i class="ph-bold ph-hourglass-medium text-warning"></i> <span>Showing Pre-MPDR submissions pending approval or draft.</span>');
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

                // Inisialisasi DataTable dengan AJAX dan SearchBuilder bawaan DataTables Yajra
                const table = $('#prempdrTable').DataTable({
                    processing: true,
                    serverSide: false,
                    autoWidth: false,
                    dom: '<"d-flex align-items-center justify-content-between mb-3"Bf>rt<"d-flex align-items-center justify-content-between mt-3"ip>',
                    buttons: [
                        {
                            extend: 'searchBuilder',
                            config: {
                                depthLimit: 2
                            }
                        }
                    ],
                    searchBuilder: {
                        columns: [1, 2, 3, 4, 5, 6]
                    },
                    ajax: {
                        url: "{{ route('prempdr.form.list') }}",
                        dataSrc: function(json) {
                            updateTabCounts(json);
                            return filterDataByTabAndStatus(json);
                        }
                    },
                    columns: [{
                            data: null, // Data null untuk penomoran otomatis
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            width: '5%',
                            className: 'text-center align-middle',
                            // [PERBAIKAN UTAMA] Ini akan membuat nomor urut 1, 2, 3, ...
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'no',
                            name: 'no',
                            width: '12%',
                            className: 'text-center align-middle text-nowrap',
                            // [Tampilan Baru] Menambahkan badge pada No. Registrasi
                            render: function(data) {
                                return `<span class="status-badge-lg bg-dark">${data}</span>`;
                            }
                        },
                        {
                            data: 'project_name',
                            name: 'project_name',
                            width: '18%',
                            className: 'align-middle',
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
                            width: '12%',
                            className: 'text-center align-middle text-nowrap',
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
                                return `<div class="d-flex align-items-center justify-content-center">
                                    <i class="ph ph-calendar-check me-2 text-primary fs-5"></i>
                                    <span>${formattedDate}</span>
                                </div>`;
                            }
                        },
                        {
                            data: 'route_to',
                            name: 'route_to',
                            width: '31%',
                            className: 'align-middle',
                            // [Tampilan Baru] Menambahkan badge dan ikon user
                            render: function(data) {
                                if (!data) return '<span class="text-muted fst-italic">-</span>';
                                return `<span class="status-badge-lg bg-warning d-flex align-items-center text-nowrap">
                                    <i class="ph-bold ph-user-circle me-1 fs-6"></i>
                                    ${data}
                                </span>`;
                            }
                        },
                        {

                            data: 'status',
                            name: 'status',
                            width: '12%',
                            className: 'text-center align-middle text-nowrap',
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
                            width: '10%',
                            className: 'text-center align-middle text-nowrap',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                if (row.status === 'Draft') {
                                    const deleteRoute = "{{ route('prempdr.destroy', ':formId') }}"
                                        .replace(':formId', row.no);
                                    return `
                            <div class="d-flex gap-1 justify-content-center align-items-center">
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
                                } else if (row.status === 'Rejected') {
                                    return `<div class="d-flex gap-1 align-items-center justify-content-center">
                                        <button type="button" class="btn btn-sm btn-info view-form-btn" data-form-id="${row.no}" data-bs-toggle="tooltip" title="View Details"><i class="ph-bold ph-eye"></i></button>
                                        @if (auth()->user()->can('view create pre') || auth()->user()->can('view edit pre'))
                                            <button type="button" class="btn btn-sm btn-warning resubmit-form-btn" data-form-id="${row.no}" data-bs-toggle="tooltip" title="Resubmit Form"><i class="ph-bold ph-arrow-counter-clockwise"></i></button>
                                        @endif
                                    </div>`;
                                } else {
                                    let buttons = `<button type="button" class="btn btn-sm btn-info view-form-btn" data-form-id="${row.no}" data-bs-toggle="tooltip" title="View Details"><i class="ph-bold ph-eye"></i></button>`;

                                    @if (auth()->user()->can('view direct head snm pre') || auth()->user()->can('view direct gm mpdr') || auth()->user()->hasRole(['super-admin', 'Super-Admin', 'admin', 'Admin']))
                                        if (row.status === 'In Approval' && row.can_direct_to_last_approver) {
                                            const sendLastApproverRoute = "{{ route('prempdr.sendMailToLastApproverUrgent', ':formId') }}".replace(':formId', row.no);
                                            const label = row.last_approver_label || 'Head S&M';
                                            buttons += `
                                                <form id="send-last-approver-form-${row.no}" action="${sendLastApproverRoute}" method="POST" class="d-inline ms-1">
                                                    @csrf
                                                    <button type="button" class="btn btn-sm text-white send-last-approver-btn" style="background-color: #4f46e5; border-color: #4f46e5;" data-form-no="${row.no}" data-label="${label}" onclick="confirmDirectLastApprover(this)" data-bs-toggle="tooltip" title="Direct to ${label}">
                                                        <i class="ph-bold ph-paper-plane-tilt"></i> SnM
                                                    </button>
                                                </form>
                                            `;
                                        }
                                    @endif

                                    return `<div class="d-flex gap-1 align-items-center justify-content-center">${buttons}</div>`;
                                }
                            }
                        }
                    ],
                    "drawCallback": function(settings) {
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

                window.prempdrTable = table;

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

                // Event listener untuk filter status
                $('#statusFilter').on('change', function() {
                    if (!isUpdatingFilter) {
                        table.ajax.reload(null, false);
                    }
                });

                // Event listener untuk reset filter
                $('#resetFilters').on('click', function() {
                    isUpdatingFilter = true;
                    $('#statusFilter').val('all').trigger('change.select2');
                    isUpdatingFilter = false;
                    table.search('').ajax.reload();
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
                    $('#prempdrForm').find('input[name="_method"]').remove();
                    ['productCategory', 'channel[]', 'certification[]', 'packaging'].forEach(function(name) {
                        $('input[name="' + name + '"]').first().trigger('change');
                    });
                }

                function fetchNewRegNumber() {
                    $.get("{{ route('prempdr.noReg') }}", function(data) {
                        $('#no_reg_text').text(data.no_reg);
                        $('#no_reg').val(data.no_reg);
                    });
                }

                $('#btn-create-prempdr').on('click', function() {
                    resetForm();
                    $('#prempdrModalLabel').html('<i class="ph-bold ph-note-pencil me-2"></i>Create New Pre-MPDR');
                    $('.btn-submit').show();
                    $('#prempdrForm').attr('action', "{{ route('prempdr.store') }}");

                    $.get("{{ route('prempdr.noReg') }}", function(data) {
                        $('#no_reg_text').text(data.no_reg);
                        $('#no_reg').val(data.no_reg);
                    });

                    $('#competitor-tbody').empty();
                    competitorRowIndex = 0;
                    addCompetitorRow();
                    $('#approval-tracker-container').closest('.card').hide();
                    $('#history-log-container').closest('.card').hide();
                    $('#prempdrModal').modal('show');
                });

                // Handler untuk Tombol Edit Form (Modal Form Input)
                $('#prempdrTable tbody').on('click', '.edit-form-btn', function() {
                    const btn = $(this);
                    const originalHtml = btn.html();
                    btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm" role="status"></span>`);

                    var formId = $(this).data('form-id');
                    var url = "{{ url('prempdr/detail') }}/" + formId;

                    resetForm();
                    $('#prempdrModalLabel').html(`<i class="ph-bold ph-pencil-simple me-2"></i>Edit Pre-MPDR: ${formId}`);
                    $('.btn-submit').show();
                    $('#approval-tracker-container').closest('.card').hide();
                    $('#history-log-container').closest('.card').hide();
                    $('#prempdrForm').attr('action', "{{ url('prempdr/update') }}/" + formId);
                    if (!$('#prempdrForm').find('input[name="_method"]').length) {
                        $('#prempdrForm').append('<input type="hidden" name="_method" value="PATCH">');
                    } else {
                        $('#prempdrForm').find('input[name="_method"]').val('PATCH');
                    }

                    $.get(url, function(data) {
                        populateForm(data);
                        $('#prempdrModal').modal('show');
                    }).fail(() => {
                        Swal.fire('Error', 'Failed to fetch details.', 'error');
                    }).always(() => {
                        btn.prop('disabled', false).html(originalHtml);
                    });
                });

                // Handler untuk Resubmit Pre-MPDR jika Rejected
                function openResubmitPreMpdr(formId, triggerBtn) {
                    let originalHtml = '';
                    if (triggerBtn) {
                        originalHtml = $(triggerBtn).html();
                        $(triggerBtn).prop('disabled', true).html(`<span class="spinner-border spinner-border-sm" role="status"></span>`);
                    }

                    var detailUrl = "{{ url('prempdr/detail') }}/" + formId;
                    var noRegUrl = "{{ route('prempdr.noReg') }}";

                    $.when(
                        $.get(detailUrl),
                        $.get(noRegUrl)
                    ).done(function(detailRes, noRegRes) {
                        const data = detailRes[0];
                        const newNoReg = noRegRes[0].no_reg;

                        resetForm();

                        $('#prempdrModalLabel').html(
                            `<i class="ph-bold ph-arrow-counter-clockwise me-2 text-warning"></i>Resubmit Pre-MPDR: <strong>${newNoReg}</strong> <span class="badge bg-danger ms-2" style="font-size:0.75rem">Rejected source: ${formId}</span>`
                        );
                        $('.btn-submit').show();
                        $('#approval-tracker-container').closest('.card').hide();
                        $('#history-log-container').closest('.card').hide();

                        $('#prempdrForm').attr('action', "{{ route('prempdr.store') }}");
                        $('#prempdrForm').find('input[name="_method"]').remove();

                        // Populate form with rejected data
                        populateForm(data);

                        // Override registration number with newly generated number
                        $('#no_reg_text').text(newNoReg);
                        $('#no_reg').val(newNoReg);

                        // Ensure all inputs are enabled for editing
                        $('#prempdrForm :input').not('#no_reg').prop('disabled', false);

                        $('#prempdrModal').modal('show');
                    }).fail(function() {
                        Swal.fire('Error', 'Failed to fetch Pre-MPDR details or generate a new registration number.', 'error');
                    }).always(function() {
                        if (triggerBtn) {
                            $(triggerBtn).prop('disabled', false).html(originalHtml);
                        }
                    });
                }

                // Handler untuk Tombol Resubmit Form dari Tabel
                $('#prempdrTable tbody').on('click', '.resubmit-form-btn', function() {
                    var formId = $(this).data('form-id');
                    openResubmitPreMpdr(formId, this);
                });

                // Handler untuk Tombol Resubmit Form dari Modal View Details
                $('#btn-resubmit-prempdr-view').on('click', function() {
                    var formId = $(this).data('form-id');
                    $('#prempdrViewModal').modal('hide');
                    openResubmitPreMpdr(formId, null);
                });

                // Handler untuk Tombol View Details (Modal Khusus Executive Summary Report)
                $('#prempdrTable tbody').on('click', '.view-form-btn', function() {
                    const btn = $(this);
                    const originalHtml = btn.html();
                    btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm" role="status"></span>`);

                    var formId = $(this).data('form-id');
                    var url = "{{ url('prempdr/detail') }}/" + formId;

                    $('#prempdrViewModalLabel span').text('Pre-MPDR Executive Summary: ' + formId);
                    $('#v-modal-loader').show();
                    $('#v-modal-content-wrapper').hide();
                    $('#prempdrViewModal').modal('show');

                    $.get(url, function(data) {
                        try {
                            populateViewDetailModal(data);
                            renderViewApprovalTracker(data);
                            renderViewHistory(data);
                        } catch (err) {
                            console.error('Error populating view modal:', err);
                        } finally {
                            $('#v-modal-loader').hide();
                            $('#v-modal-content-wrapper').show();
                        }
                    }).fail((xhr) => {
                        console.error('AJAX fail detail:', xhr);
                        Swal.fire('Error', 'Failed to fetch details.', 'error');
                        $('#prempdrViewModal').modal('hide');
                    }).always(() => {
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
                        const selected = $('input[name="' + radioName + '"][value="' + radioValue + '"]').is(':checked');
                        if (selected) {
                            textInput.prop('readonly', false).prop('required', true);
                        } else {
                            textInput.prop('readonly', true).prop('required', false).val('');
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

                function formatPriceJs(val, fallback = 'N/A') {
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

                function populateViewDetailModal(data) {
                    if (!data) return;

                    $('#v-no').text(data.no || '');
                    $('#v-revision').text('REV ' + (data.revision ? (data.revision.revision ?? '0') : '0'));

                    let statusClass = 'bg-primary';
                    if (data.status === 'Approved') statusClass = 'bg-success';
                    else if (data.status === 'Rejected') statusClass = 'bg-danger';
                    else if (data.status === 'In Approval') statusClass = 'bg-warning text-dark';

                    $('#v-status').removeClass().addClass('badge px-3 py-1 fs-6 ' + statusClass).text(data.status || 'Draft');

                    if (data.status === 'Rejected') {
                        $('#btn-resubmit-prempdr-view').data('form-id', data.no).show();
                    } else {
                        $('#btn-resubmit-prempdr-view').hide();
                    }

                    $('#v-project-name').text(data.project_name || '-');
                    $('#v-brand-name').text(data.brand_name || 'N/A');
                    const userPos = (data.user && data.user.position) ? ` (${data.user.position.name})` : '';
                    const submitterName = data.user ? (data.user.name + userPos) : '-';
                    $('#v-submitted-by').text(submitterName);
                    $('#v-priority').html('<i class="ph-bold ph-fire me-1"></i> Priority ' + (data.level_priority || 'Normal'));

                    const category = (data.category && data.category.category) ? data.category.category : '-';
                    const categoryOther = (data.category && data.category.other) ? ` (${data.category.other})` : '';
                    $('#v-category').text(category + categoryOther);

                    const channel = (data.channel && data.channel.category) ? data.channel.category : '-';
                    const country = (data.channel && data.channel.country) ? ` (${data.channel.country})` : '';
                    $('#v-channel').text(channel + country);

                    $('#v-target-launch').text(data.detail ? (data.detail.target_launch || 'N/A') : 'N/A');

                    const cert = (data.certification && data.certification.category) ? data.certification.category : '-';
                    const certOther = (data.certification && data.certification.other) ? ` (${data.certification.other})` : '';
                    $('#v-certification').text(cert + certOther);

                    $('#v-rational').text(data.detail ? (data.detail.rational_for_development || 'No rational provided.') : 'No rational provided.');

                    $('#v-product-desc').html(data.description ? (data.description.product_description || 'N/A') : 'N/A');
                    $('#v-usage-desc').html(data.description ? (data.description.usage_description || 'N/A') : 'N/A');
                    $('#v-storage-temp').text(data.description ? (data.description.storage_temperature || 'N/A') : 'N/A');
                    $('#v-delivery-temp').text(data.description ? (data.description.delivery_temperature || 'N/A') : 'N/A');

                    let compHtml = '';
                    if (data.competitor && Array.isArray(data.competitor) && data.competitor.length > 0) {
                        data.competitor.forEach((item, index) => {
                            compHtml += `<tr>
                                <td class="ps-4 fw-bold text-muted">${index + 1}</td>
                                <td class="fw-semibold text-dark">${item.name || '-'}</td>
                                <td><span class="badge bg-light text-dark border">${item.size || '-'}</span></td>
                                <td>${item.packaging || '-'}</td>
                                <td class="pe-4 fw-bold text-success">${formatPriceJs(item.price, '-')}</td>
                            </tr>`;
                        });
                    } else {
                        compHtml = '<tr><td colspan="5" class="text-center py-4 text-muted">No competitor product specified.</td></tr>';
                    }
                    $('#v-competitor-tbody').html(compHtml);

                    $('#v-weight').text(data.packaging ? (data.packaging.weight || 'N/A') : 'N/A');
                    const packType = data.packaging ? (data.packaging.category || '-') : '-';
                    const packDetail = data.packaging && data.packaging.detail ? data.packaging.detail : '';
                    $('#v-packaging-detail').html(`<span class="badge bg-primary me-2">${packType}</span><span class="text-dark">${packDetail || 'No extra details'}</span>`);
                    $('#v-product-variation').text(data.packaging ? (data.packaging.product_variation || 'N/A') : 'N/A');

                    $('#v-potential-volume').text(data.market ? (data.market.potential_volume || 'N/A') : 'N/A');
                    $('#v-expected-margin').text(data.market ? (data.market.expected_margin || 'N/A') : 'N/A');
                    $('#v-price-estimate').text(data.market ? formatPriceJs(data.market.price_estimate, 'N/A') : 'N/A');
                }

                function renderViewApprovalTracker(data) {
                    const tracker = data.approval_tracker || { steps: [], progress_percent: 0, current_status: data.status || '' };
                    const steps = tracker.steps || [];
                    const status = tracker.current_status || data.status || '';
                    let badgeClass = 'bg-secondary';
                    if (status === 'Approved') badgeClass = 'bg-success';
                    else if (status === 'Rejected') badgeClass = 'bg-danger';
                    else if (status === 'In Approval') badgeClass = 'bg-info';
                    else if (status === 'Draft') badgeClass = 'bg-warning';

                    $('#v_status_badge').html(`<span class="badge px-3 py-1 fs-6 ${badgeClass}">${status}</span>`);

                    const $container = $('#v-approval-tracker-container');
                    $container.empty();

                    const progressPercent = tracker.progress_percent || 0;
                    $container.append(`<div class="tracker-line"><div class="tracker-line-progress" style="width: ${progressPercent}%;"></div></div>`);

                    steps.forEach(function(step) {
                        const stepStatus = (step.status || '').toLowerCase();
                        let stepClass = '';
                        if (stepStatus === 'completed' || stepStatus === 'approve with review' || (stepStatus !== 'pending' && stepStatus !== 'vacant' && stepStatus !== '')) stepClass = 'completed';
                        if (stepStatus === 'rejected' || stepStatus === 'not approve') stepClass = 'rejected';

                        const label = step.label || step.name || '';
                        let iconClass = 'ph-check-circle';
                        let lbl = label.toLowerCase();
                        if(lbl.includes('submitted') || lbl.includes('maker')) iconClass = 'ph-file-arrow-up';
                        else if(lbl.includes('asisten manager') || lbl.includes('assistant')) iconClass = 'ph-users';
                        else if(lbl.includes('manager')) iconClass = 'ph-user-list';
                        else if(lbl.includes('atasan') || lbl.includes('supervisor') || lbl.includes('spv')) iconClass = 'ph-user-plus';
                        else if(lbl.includes('dephead') || lbl.includes('dept') || lbl.includes('s&m')) iconClass = 'ph-user-gear';

                        $container.append(`
                            <div class="tracker-step ${stepClass}" data-step-name="${label}">
                                <div class="tracker-icon"><i class="ph-bold ${iconClass} fs-6"></i></div>
                                <div class="tracker-label">${label}</div>
                                <div class="tracker-details">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="comment-avatar" style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#06b6d4,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;">
                                            <i class="ph ph-user-circle fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            ${step.date ? '<div class="comment-date small text-muted mb-1">' + step.date + '</div>' : ''}
                                            ${step.comment ? '<div class="comment-bubble" style="padding:8px 12px;background:rgba(99,102,241,0.06);border-radius:8px;border-left:3px solid #6366f1;font-style:italic;color:#0f172a;font-size:0.85rem;">' + step.comment + '</div>' : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                }

                function renderViewHistory(data) {
                    const history = data.history || [];
                    const $list = $('#v-history-log-container');
                    $list.empty();

                    if (!history.length) {
                        $list.append('<li class="list-group-item text-muted border-0 ps-0">No history available.</li>');
                        return;
                    }

                    history.forEach(function(item) {
                        const actor = item.actor || 'System';
                        const event = item.event || '';
                        const time = item.time || '';
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

                // Terapkan fungsi ke semua grup radio button
                handleRadioTextToggle('productCategory', 'Others', 'productCategoryText');
                handleRadioTextToggle('channel[]', 'International', 'country');
                handleRadioTextToggle('certification[]', 'Others', 'certificationText');
                handleRadioTextToggle('packaging', 'Existing', 'ExistingPackagingText');
                handleRadioTextToggle('packaging', 'New', 'NewPackagingText');

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

                // Fungsi untuk update status tombol delete kompetitor
                function updateCompetitorButtons() {
                    const rows = $('#competitor-tbody tr');
                    if (rows.length <= 1) {
                        rows.find('.remove-competitor-btn').prop('disabled', true).addClass('opacity-50').attr('title', 'At least 1 row is required');
                    } else {
                        rows.find('.remove-competitor-btn').prop('disabled', false).removeClass('opacity-50').removeAttr('title');
                    }
                }

                // Fungsi untuk menambah baris baru
                function addCompetitorRow(data = {}) {
                    competitorRowIndex++;
                    const defaultPrice = (data.price !== undefined && data.price !== null && data.price !== '') ? data.price : '0';
                    const newRow = `
                <tr class="competitor-row">
                    <td class="text-center fw-bold">${competitorRowIndex}</td>
                    <td><input type="text" class="form-control" name="competitor_names[]" placeholder="Product Name" value="${data.name || ''}" required></td>
                    <td><input type="text" class="form-control" name="competitor_sizes[]" placeholder="Size" value="${data.size || ''}" required></td>
                    <td><input type="text" class="form-control" name="competitor_packagings[]" placeholder="Packaging" value="${data.packaging || ''}" required></td>
                    <td><input type="text" class="form-control competitor-price-input" name="competitor_prices[]" placeholder="Price" value="${defaultPrice}" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-competitor-btn"><i class="ph-bold ph-trash"></i></button>
                    </td>
                </tr>
            `;
                    $('#competitor-tbody').append(newRow);
                    updateCompetitorButtons();
                }

                // Event listener untuk tombol "Add Row"
                $('#add-competitor-btn').on('click', function() {
                    addCompetitorRow();
                });

                // Event listener untuk tombol "Remove" pada setiap baris
                $('#competitor-tbody').on('click', '.remove-competitor-btn', function() {
                    if ($('#competitor-tbody tr').length <= 1) {
                        return;
                    }
                    $(this).closest('tr').remove();
                    // Update nomor urut setelah menghapus
                    $('#competitor-tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                        competitorRowIndex = index + 1;
                    });
                    updateCompetitorButtons();
                });

                // Otomatis set price ke 0 jika dikosongkan
                $(document).on('blur', 'input[name="competitor_prices[]"]', function() {
                    if (!$(this).val() || !$(this).val().trim()) {
                        $(this).val('0');
                    }
                });

                function populateForm(data) {
                    if (!data) return;

                    // 1. Mengisi input teks, textarea, dan select sederhana
                    $('#prempdrForm [name="projectName"]').val(data.project_name || '');
                    $('#prempdrForm [name="levelPriority"]').val(data.level_priority || '');
                    $('#prempdrForm [name="brandName"]').val(data.brand_name || '');

                    // Menggunakan data dari relasi 'detail'
                    if (data.detail) {
                        $('#prempdrForm [name="rationalForDevelopment"]').val(data.detail.rational_for_development || '');
                        $('#prempdrForm [name="targetLaunchText"]').val(data.detail.target_launch || '');
                    }

                    // Menggunakan data dari relasi 'description'
                    if (data.description) {
                        $('#prempdrForm [name="productDescription"]').val(data.description.product_description || '');
                        $('#prempdrForm [name="usageDescription"]').val(data.description.usage_description || '');
                        $('#prempdrForm [name="storageTemperature"]').val(data.description.storage_temperature || '');
                        $('#prempdrForm [name="deliveryTemperature"]').val(data.description.delivery_temperature || '');
                    }

                    // Menggunakan data dari relasi 'packaging'
                    if (data.packaging) {
                        $('#prempdrForm [name="weightProduct"]').val(data.packaging.weight || '');
                        $('#prempdrForm [name="productVariation"]').val(data.packaging.product_variation || '');
                        $(`input[name="packaging"][value="${data.packaging.category}"]`).prop('checked', true);
                        if (data.packaging.category === 'Existing') {
                            $('#ExistingPackagingText').val(data.packaging.detail || '').prop('readonly', false);
                            $('#NewPackagingText').prop('readonly', true).val('');
                        } else if (data.packaging.category === 'New') {
                            $('#NewPackagingText').val(data.packaging.detail || '').prop('readonly', false);
                            $('#ExistingPackagingText').prop('readonly', true).val('');
                        }
                        $('input[name="packaging"]').first().trigger('change');
                    }

                    // Menggunakan data dari relasi 'market'
                    if (data.market) {
                        $('#prempdrForm [name="potentialVolume"]').val(data.market.potential_volume || '');
                        $('#prempdrForm [name="expectedMargin"]').val(data.market.expected_margin || '');
                        $('#prempdrForm [name="priceEstimate"]').val(data.market.price_estimate || '');
                    }

                    // 2. Mengisi radio button
                    if (data.category) {
                        $(`input[name="productCategory"][value="${data.category.category}"]`).prop('checked', true);
                        if (data.category.category === 'Others') {
                            $('#productCategoryText').val(data.category.other || '').prop('readonly', false);
                        }
                    }

                    if (data.channel) {
                        (data.channel.category || '').split(',').map(value => value.trim()).forEach(value => {
                            $(`input[name="channel[]"][value="${value}"]`).prop('checked', true);
                        });
                        $('#country').val(data.channel.country || '');
                        $('input[name="channel[]"]').first().trigger('change');
                    }

                    if (data.certification) {
                        (data.certification.category || '').split(',').map(value => value.trim()).forEach(value => {
                            $(`input[name="certification[]"][value="${value}"]`).prop('checked', true);
                        });
                        $('#certificationText').val(data.certification.other || '');
                        $('input[name="certification[]"]').first().trigger('change');
                    }

                    // 3. Mengisi No Registrasi
                    $('#no_reg_text').text(data.no || '');
                    $('#no_reg').val(data.no || '');

                    // 4. Mengisi data produk kompetitor (JIKA ADA)
                    $('#competitor-tbody').empty();
                    competitorRowIndex = 0;

                    if (data.competitor && Array.isArray(data.competitor) && data.competitor.length > 0) {
                        data.competitor.forEach(item => {
                            addCompetitorRow(item);
                        });
                    } else {
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
                                }
                            });
                        }
                    });
                });

                $('#prempdrModal').on('hidden.bs.modal', () => resetForm());
            });

            // Fungsi konfirmasi hapus via AJAX tanpa page reload
            function confirmDelete(button) {
                var formNo = button.getAttribute('data-form-no');
                var form = document.getElementById('delete-form-' + formNo);
                if (!form) return;
                var action = form.action;
                var tokenInput = form.querySelector('input[name="_token"]');
                var token = tokenInput ? tokenInput.value : '';

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This Pre-MPDR request will be permanently deleted.',
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
                                    text: resp.message || 'The Pre-MPDR request was deleted successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                var table = $('#prempdrTable').DataTable();
                                if (table) table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to delete the Pre-MPDR request.';
                                Swal.fire('Error', msg, 'error');
                            }
                        });
                    }
                });
            }

            // Fungsi konfirmasi direct to last approver (Head S&M) via AJAX
            function confirmDirectLastApprover(button) {
                var formNo = button.getAttribute('data-form-no');
                var label = button.getAttribute('data-label') || 'Head S&M';
                var form = document.getElementById('send-last-approver-form-' + formNo);
                if (!form) return;
                var url = form.getAttribute('action');
                var token = form.querySelector('input[name="_token"]').value;

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'The email will be sent directly to ' + label + '. Any pending or vacant prior approvals will be automatically handled upon approval.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: token,
                            },
                            beforeSend: function() {
                                $(button).prop('disabled', true).html(
                                    '<span class="spinner-border spinner-border-sm"></span>');
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Direct Approval Sent',
                                    text: response.message || 'Direct approval email has been sent successfully.',
                                });
                                var table = $('#prempdrTable').DataTable();
                                if (table) table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: msg
                                });
                            },
                            complete: function() {
                                $(button).prop('disabled', false).html(
                                    '<i class="ph-bold ph-paper-plane-tilt"></i> SnM');
                            }
                        });
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
