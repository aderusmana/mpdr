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
                        <th>No. Reg</th>
                        <th>Product Name</th>
                        <th>Action</th>
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
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="mt-3 fw-bold">Loading Form...</h5>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="approvalModalLabel"><i class="ph-bold ph-file-text me-2"></i>Review MPDR Form</h5>
                    <button type="button" class="btn-close btn-close-white m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background-color: #f9fbfd;">

                    <fieldset id="approval-form-fieldset" disabled>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="text-primary fw-bolder">MPDR Details</h4>
                            <div class="text-end">
                                <label class="form-label mb-0">No Reg: <span id="no_reg_text" class="fw-bold"></span></label>
                            </div>
                        </div>

                        {{-- CARD 1: INFORMASI DASAR --}}
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <h6 class="fw-bold text-primary mb-0"><i class="ph-bold ph-identification-card me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Product Name:</label>
                                        <input type="text" class="form-control" id="productName">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Level Priority:</label>
                                        <input type="text" class="form-control" id="levelPriority">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Initiator:</label>
                                        <input type="text" class="form-control" id="initiator">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Rational For Development:</label>
                                        <textarea class="form-control no-resize" id="rationalForDevelopment" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD 2: KATEGORI & SPESIFIKASI --}}
                        <div class="card view-modal-card">
                             <div class="card-header view-modal-card-header">
                                <h6 class="fw-bold text-primary mb-0"><i class="ph-bold ph-tag me-2"></i>Category & Specifications</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6" id="productCategory">
                                        <label class="form-label fw-bold">Product Category:</label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="productCategory" id="Margarine"><label class="form-check-label" for="Margarine">Margarine</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="productCategory" id="Frying Fats"><label class="form-check-label" for="Frying Fats">Frying Fats</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="productCategory" id="Shortening"><label class="form-check-label" for="Shortening">Shortening</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="productCategory" id="Pastry"><label class="form-check-label" for="Pastry">Pastry</label></div>
                                            <div class="form-check d-flex align-items-center"><input class="form-check-input me-2" type="radio" name="productCategory" id="Others"><label class="form-check-label me-2" for="Others">Others</label><input type="text" class="form-control w-50" id="productCategoryText"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="channel">
                                        <label class="form-label fw-bold">Channel:</label>
                                        <div>
                                            <div class="form-check d-flex align-items-center"><input class="form-check-input me-2" type="radio" name="channel" id="International"><label class="form-check-label me-2" for="International">International</label><input type="text" class="form-control w-50" id="country"></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="channel" id="Regional"><label class="form-check-label" for="Regional">Regional</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="channel" id="Industrial"><label class="form-check-label" for="Industrial">Industrial</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="channel" id="FSBC-Direct"><label class="form-check-label" for="FSBC-Direct">FSBC-Direct</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="channel" id="FSBC-Distributor"><label class="form-check-label" for="FSBC-Distributor">FSBC-Distributor</label></div>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Product Description <span class="fw-normal">(Form/Color/Flavor brief)</span>:</label>
                                        <div id="productDescription" class="form-control" style="min-height: 80px; background-color: #e9ecef;"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Usage Description <span class="fw-normal">(Application in customer)</span>:</label>
                                        <input type="text" class="form-control" id="usageDescription">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Storage Temperature:</label><input type="text" class="form-control" id="storageTemperature">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Delivery Temperature:</label><input type="text" class="form-control" id="deliveryTemperature">
                                    </div>
                                     <div class="col-md-6" id="certification">
                                        <label class="form-label fw-bold">Certification Requirement:</label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="certification" id="BPOM"><label class="form-check-label" for="BPOM">BPOM</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="certification" id="HALAL"><label class="form-check-label" for="HALAL">HALAL</label></div>
                                            <div class="form-check d-flex align-items-center"><input class="form-check-input me-2" type="radio" name="certification" id="certificationOthers"><label class="form-check-label me-2" for="certification3">Others</label><input type="text" class="form-control w-50" id="certificationText"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD 3: COMPETITOR --}}
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <h6 class="fw-bold text-primary mb-0"><i class="ph-bold ph-users-three me-2"></i>Competitor's Product</h6>
                            </div>
                            <div class="card-body">
                                <p class="card-subtitle mb-3">Product to match or to beat.</p>
                                <div class="table-responsive">
                                    <table id="competitorProduct" class="table table-striped nowrap w-100">
                                        <thead>
                                            <tr><th>#</th><th>PRODUCT NAME</th><th>SIZE</th><th>PACKAGING</th><th>Price Indication</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>1</td><td><input type="text" class="form-control" id="productName1"></td><td><input type="text" class="form-control" id="size1"></td><td><input type="text" class="form-control" id="packaging1"></td><td><input type="text" class="form-control" id="priceIndication1"></td></tr>
                                            <tr><td>2</td><td><input type="text" class="form-control" id="productName2"></td><td><input type="text" class="form-control" id="size2"></td><td><input type="text" class="form-control" id="packaging2"></td><td><input type="text" class="form-control" id="priceIndication2"></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- CARD 4: KEMASAN, PASAR, & TARGET --}}
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <h6 class="fw-bold text-primary mb-0"><i class="ph-bold ph-package me-2"></i>Packaging, Market & Target</h6>
                            </div>
                            <div class="card-body">
                                <div id="detailedPackaging">
                                    <h5 class="fw-bolder">Detailed Packaging Required:</h5>
                                    <ol class="ps-4">
                                        <li class="mb-3">
                                            <label class="form-label">Weight of Product (kg/carton):</label>
                                            <input type="text" class="form-control" id="weightProduct">
                                        </li>
                                        <li class="mb-3">
                                            <label class="form-label">Packaging:</label>
                                            <div class="row">
                                                <div class="col form-check"><input class="form-check-input" type="radio" name="packaging" id="ExistingPackaging"><label class="form-check-label" for="ExistingPackaging">Existing</label><textarea class="form-control no-resize" id="ExistingPackagingText" rows="2"></textarea></div>
                                                <div class="col form-check"><input class="form-check-input" type="radio" name="packaging" id="NewPackaging"><label class="form-check-label" for="NewPackaging">New</label><textarea class="form-control no-resize" id="NewPackagingText" rows="2"></textarea></div>
                                            </div>
                                        </li>
                                        <li>
                                            <label class="form-label">Product Variation List:</label>
                                            <input type="text" class="form-control" id="productVariation">
                                        </li>
                                    </ol>
                                </div>
                                <hr class="my-4">
                                <div id="potential">
                                    <h5 class="fw-bolder">MARKET UPDATE</h5>
                                    <ol class="ps-4">
                                        <li class="mb-3">
                                            <label class="form-label">Potential Volume (Mt/Annually):</label>
                                            <input type="text" class="form-control" id="potentialVolume">
                                        </li>
                                        <li>
                                            <label class="form-label">Pricing Strategy</label>
                                            <div class="row">
                                                <div class="col form-check"><label class="form-label">Expected Margin (%):</label><input type="text" class="form-control" id="expectedMargin"></div>
                                                <div class="col form-check"><label class="form-label">Price Estimate:</label><input type="text" class="form-control" id="priceEstimate"></div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                                <hr class="my-4">
                                <div>
                                    <h5 class="fw-bolder">TARGET LAUNCH (as Initiator request):</h5>
                                    <input type="text" class="form-control" id="targetLaunch">
                                </div>
                            </div>
                        </div>

                        {{-- CARD 5: APPROVAL STATUS --}}
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <h6 class="fw-bold text-primary mb-0"><i class="ph-bold ph-check-square-offset me-2"></i>Approval Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="approverTable" class="table table-striped nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Approved by,</th>
                                                <th class="text-center">Approved</th>
                                                <th class="text-center">Approved with Review</th>
                                                <th class="text-center">Not Approved</th>
                                                <th class="text-center">Notes/Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <div class="modal-footer">
                    <form id="approvalActionForm" method="POST" class="w-100">
                        @csrf
                        <div class="d-flex flex-column align-items-center">
                            <textarea name="comment" id="comment" class="form-control rounded-lg w-100 border border-2 mb-2" style="max-width:550px; resize: none;" rows="4" placeholder="Tulis komentar disini jika diperlukan..."></textarea>
                            <div id="textarea-warning" class="d-none fw-bold text-danger mb-2">Kolom komentar wajib diisi untuk aksi ini.</div>
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
                processing: true, serverSide: false,
                ajax: { url: "{{ route('mpdr.approval.list.data') }}", dataSrc: '' },
                columns: [
                    { data: 'no', name: 'no', render: data => `<span class="status-badge-lg bg-dark">${data}</span>` },
                    { data: 'product_name', name: 'product_name', render: data => `<div class="d-flex align-items-center"><i class="ph-bold ph-folder-simple me-2 text-primary fs-5"></i><span>${data}</span></div>` },
                    { data: null, name: 'action', orderable: false, searchable: false,
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-primary review-approval-btn" data-form-no="${row.no}" data-bs-toggle="modal" data-bs-target="#approvalModal"><i class="ph-bold ph-eye me-1"></i> Review Approval</button>`;
                        }
                    }
                ]
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
                        Swal.fire('Error', 'Gagal memuat data form. Silakan coba lagi.', 'error');
                        modal.modal('hide');
                    }
                });
            }

            function populateModal(data, no_reg) {
                 // Set fieldset menjadi disabled dulu, lalu enable
                $('#approval-form-fieldset').prop('disabled', false);

                $('#no_reg_text').text(no_reg);
                $('#productName').val(data.product_name);
                $('#levelPriority').val(data.level_priority);
                $('#initiator').val(data.initiator);
                $('#rationalForDevelopment').val(data.detail.rational_for_development);
                $('#targetLaunch').val(data.detail.target_launch);

                $('input[type="radio"]').prop('checked', false);

                $(`input[name="productCategory"][id="${data.category.category}"]`).prop('checked', true);
                $('#productCategoryText').val(data.category.other);
                $(`input[name="channel"][id="${data.channel.category}"]`).prop('checked', true);
                $('#country').val(data.channel.country);

                $('#productDescription').html(data.description.product_description);
                $('#usageDescription').val(data.description.usage_description);
                $('#storageTemperature').val(data.description.storage_temperature);
                $('#deliveryTemperature').val(data.description.delivery_temperature);

                const certCategory = data.certification.category === 'Others' ? 'certificationOthers' : data.certification.category;
                $(`input[name="certification"][id="${certCategory}"]`).prop('checked', true);
                $('#certificationText').val(data.certification.other);

                $('#productName1').val(data.competitor[0]?.name || '');
                $('#size1').val(data.competitor[0]?.size || '');
                $('#packaging1').val(data.competitor[0]?.packaging || '');
                $('#priceIndication1').val(data.competitor[0]?.price || '');
                $('#productName2').val(data.competitor[1]?.name || '');
                $('#size2').val(data.competitor[1]?.size || '');
                $('#packaging2').val(data.competitor[1]?.packaging || '');
                $('#priceIndication2').val(data.competitor[1]?.price || '');

                $('#weightProduct').val(data.packaging.weight);
                $(`input[name="packaging"][id="${data.packaging.category}Packaging"]`).prop('checked', true);
                $(`#ExistingPackagingText`).val(data.packaging.category === 'Existing' ? data.packaging.detail : '');
                $(`#NewPackagingText`).val(data.packaging.category === 'New' ? data.packaging.detail : '');
                $('#productVariation').val(data.packaging.product_variation);

                $('#potentialVolume').val(data.market.potential_volume);
                $('#expectedMargin').val(data.market.expected_margin);
                $('#priceEstimate').val(data.market.price_estimate);

                approverTable.clear();
                data.approved_detail.forEach(function(detail) {
                    let approvedCell = '', approvedWithReviewCell = '', notApprovedCell = '', commentsCell = '';
                    if (detail.status !== 'pending' && detail.status !== 'vacant') {
                        const newDiv = `<div class="d-flex flex-column"><div>${detail.status}</div><div>${detail.approved_date}</div></div>`;
                        if (detail.status === 'approve') approvedCell = newDiv;
                        else if (detail.status === 'approve with review') {
                            approvedWithReviewCell = newDiv;
                            commentsCell = detail.comment;
                        } else if (detail.status === 'not approve') {
                            notApprovedCell = newDiv;
                            commentsCell = detail.comment;
                        }
                    }
                    approverTable.row.add([detail.approver_name, approvedCell, approvedWithReviewCell, notApprovedCell, commentsCell]);
                });
                approverTable.draw();

                // Terakhir, set fieldset menjadi disabled
                $('#approval-form-fieldset').prop('disabled', true);
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