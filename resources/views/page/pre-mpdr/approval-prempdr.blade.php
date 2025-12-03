<x-app-layout>
    @section('title')
        List Approval Pre-MPDR
    @endsection

    {{-- Menggunakan style tabel yang konsisten --}}
    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

        {{-- CSS KUSTOM UNTUK MODAL BARU --}}
        <style>
            /* Mengadopsi gaya Card dari list-prempdr */
            .view-modal-card {
                border: 1px solid #e9ecef;
                border-radius: 0.75rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.04);
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
                                <th style="width: 5%;">No.</th>
                                <th>No. Reg</th>
                                <th>Project Name</th>
                                <th>Route To</th>
                                <th>Status</th>
                                <th style="width: 15%;">Action</th>
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

                        <!-- CARD 1: INFORMASI UTAMA -->
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <i class="ph-bold ph-identification-card"></i>
                                Informasi Utama
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <label class="form-label">Project Name:</label>
                                        <input type="text" class="form-control" id="projectName" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Level Prioritas:</label>
                                        <input type="text" class="form-control" id="levelPriority" readonly>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Proposed BRAND Name:</label>
                                    <input type="text" class="form-control" id="brandName" readonly>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Rational For Development:</label>
                                    <textarea class="form-control" id="rationalForDevelopment" rows="3" readonly></textarea>
                                </div>
                                <hr class="my-3">
                                <div class="row text-center">
                                    <div class="col">
                                        <small class="text-muted d-block">No</small>
                                        <span id="revision-no" class="fw-bold"></span>
                                    </div>
                                    <div class="col">
                                        <small class="text-muted d-block">Revisi</small>
                                        <span id="revision-count" class="fw-bold"></span>
                                    </div>
                                    <div class="col">
                                        <small class="text-muted d-block">Tanggal</small>
                                        <span id="revision-date" class="fw-bold"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 2: SPESIFIKASI PRODUK -->
                        <div class="card view-modal-card">
                             <div class="card-header view-modal-card-header">
                                <i class="ph-bold ph-atom"></i>
                                Deskripsi & Fungsi Produk
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Kategori Produk</label>
                                        <div id="productCategory" class="readonly-data-box"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Channel</label>
                                         <div id="channel" class="readonly-data-box"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Produk (Bentuk/Warna/Rasa):</label>
                                    <textarea class="form-control" id="productDescription" rows="2" readonly></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Penggunaan (Aplikasi di customer):</label>
                                    <input type="text" class="form-control" id="usageDescription" readonly>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Suhu Penyimpanan:</label>
                                        <input type="text" class="form-control" id="storageTemperature" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Suhu Pengiriman:</label>
                                        <input type="text" class="form-control" id="deliveryTemperature" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label fw-bold">Sertifikasi yang Dibutuhkan</label>
                                    <div id="certification" class="readonly-data-box"></div>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 3: KEMASAN & KOMPETITOR -->
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <i class="ph-bold ph-package"></i>
                                Kemasan & Kompetitor
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold">Produk Kompetitor</h6>
                                <table id="competitorProduct" class="table table-sm table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr><th>Nama Produk</th><th>Ukuran</th><th>Kemasan</th><th>Indikasi Harga</th></tr>
                                    </thead>
                                    <tbody>
                                        <!-- Diisi oleh JS -->
                                    </tbody>
                                </table>
                                <h6 class="fw-bold mt-4">Detail Kemasan</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Berat Produk (kg/karton):</label>
                                        <input type="text" class="form-control" id="weightProduct" readonly>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Jenis Kemasan:</label>
                                        <div id="packaging" class="readonly-data-box"></div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">List Variasi Produk:</label>
                                    <input type="text" class="form-control" id="productVariation" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 4: PASAR & TARGET -->
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <i class="ph-bold ph-chart-line-up"></i>
                                Update Pasar & Target
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Potensi Volume (Mt/Tahun):</label>
                                        <input type="text" class="form-control" id="potentialVolume" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Margin yang Diharapkan (%):</label>
                                        <input type="text" class="form-control" id="expectedMargin" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Estimasi Harga:</label>
                                        <input type="text" class="form-control" id="priceEstimate" readonly>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Target Launch (Sesuai permintaan initiator):</label>
                                    <textarea class="form-control" id="targetLaunch" rows="2" readonly></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 5: RIWAYAT APPROVAL -->
                        <div class="card view-modal-card">
                            <div class="card-header view-modal-card-header">
                                <i class="ph-bold ph-steps"></i>
                                Riwayat Approval
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="approver" class="table table-striped table-hover mb-0">
                                        <thead class="table-light">
                                            <tr><th>Approver</th><th>Status</th><th>Tanggal</th><th>Komentar</th></tr>
                                        </thead>
                                        <tbody>
                                            <!-- Diisi oleh JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 6: FORM ACTION APPROVAL (Paling Bawah) -->
                        <div class="card view-modal-card border-primary">
                            <div class="card-header view-modal-card-header bg-primary bg-opacity-10">
                                <i class="ph-bold ph-pencil-simple-line"></i>
                                Tindakan Anda
                            </div>
                            <div class="card-body text-center">
                                {{-- Form di-nest di dalam card, tapi action di-set oleh JS --}}
                                <form id="approvalActionForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="comment" class="form-label fw-bold">Komentar / Catatan</label>
                                        <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Tulis komentar Anda di sini... (Wajib jika 'Review' or 'Not Approve')"></textarea>
                                        <div id="textarea-warning" class="d-none text-danger mt-1 small">Komentar tidak boleh kosong.</div>
                                    </div>
                                    <div class="d-flex justify-content-center flex-wrap gap-2">
                                        <button type="button" class="btn btn-success btn-lg" onclick="submitApproval('approve')">
                                            <i class="ph ph-check-circle me-1"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-warning btn-lg" onclick="validateAndSubmit('approve with review')">
                                            <i class="ph ph-warning-circle me-1"></i> Approve with Review
                                        </button>
                                        <button type="button" class="btn btn-danger btn-lg" onclick="validateAndSubmit('not approve')">
                                            <i class="ph ph-x-circle me-1"></i> Not Approve
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div> <!-- End of modal-content-wrapper -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Variabel table dibuat global agar bisa diakses oleh fungsi AJAX
            let table;

            $(function() {
                // ===============================================================
                //                      1. INISIALISASI DATATABLES
                // ===============================================================
                table = $('#prempdrTable').DataTable({
                    processing: true,
                    ajax: {
                        url: "{{ route('prempdr.approval.list.data') }}",
                        type: 'GET',
                        dataSrc: 'data'
                    },
                    columns: [
                        {
                            data: null, searchable: false, orderable: false, className: 'text-center',
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        { data: 'no', name: 'no', render: data => `<span class="status-badge-lg bg-dark">${data}</span>` },
                        { data: 'project_name', name: 'project_name', render: data => `<div class="d-flex align-items-center"><i class="ph-bold ph-folder-simple me-2 text-primary fs-5"></i><span class="fw-bold">${data}</span></div>` },
                        {
                            data: 'route_to', name: 'route_to',
                            render: data => data ? `<span class="status-badge-lg bg-warning d-flex align-items-center"><i class="ph-bold ph-user-circle me-1 fs-6"></i>${data}</span>` : '<span class="text-muted fst-italic">-</span>'
                        },
                        {
                            data: 'status', name: 'status',
                            render: function (data, type, row) {
                                let statusClass = 'bg-info'; // Default untuk "In Approval"
                                let approvedCount = 0;
                                let totalApprovers = 0;
                                if (row.approved_detail) {
                                    totalApprovers = row.approved_detail.length;
                                    // Hitung yang sudah memberi status (bukan null atau pending)
                                    approvedCount = row.approved_detail.filter(d => d.status !== null && d.status !== 'pending').length;
                                }
                                let progress = totalApprovers > 0 ? `(${approvedCount}/${totalApprovers})` : '';
                                return `<span class="status-badge-lg ${statusClass}">${data} ${progress}</span>`;
                            }
                        },
                        {
                            data: null, name: 'action', orderable: false, searchable: false,
                            render: function(data, type, row) {
                                return `<button class="btn btn-primary open-approval-modal" data-noreg="${row.no}" 
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Open Approval Form">
                                            <i class="ph ph-pencil-simple-line me-1"></i>
                                        </button>`;
                            }
                        }
                    ],
                    "drawCallback": function( settings ) {
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    }
                });

                // [PERBAIKAN] Menambahkan placeholder dan class pada input search
                $('#prempdrTable_filter input').attr({
                    'placeholder': '🔍 Search approvals...',
                    'class': 'form-control'
                });

                // ===============================================================
                //          (PERBAIKAN) 2. LOGIKA SAAT MODAL DIBUKA
                // ===============================================================
                // Event listener ini DIKEMBALIKAN lagi
                $('#prempdrTable tbody').on('click', '.open-approval-modal', function() {
                    const noReg = $(this).data('noreg');
                    const modal = new bootstrap.Modal(document.getElementById('approvalModal'));

                    resetModalForm();
                    $('#modal_no_reg').text(noReg);
                    $('#modal-loader').show();
                    $('#modal-content-wrapper').hide();

                    const formActionUrl = "{{ route('prempdr.form.approve', ['no_reg' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', noReg);
                    $('#approvalActionForm').attr('action', formActionUrl);

                    loadApprovalData(noReg);
                    modal.show();
                });

                // ===============================================================
                //          (BARU) 3. SUBMIT FORM ACTION DENGAN AJAX
                // ===============================================================
                $('#approvalActionForm').on('submit', function(e) {
                    e.preventDefault(); // Mencegah form submit dan reload halaman

                    const form = $(this);
                    const url = form.attr('action');
                    let formData = new FormData(this);

                    const actionValue = form.data('actionValue');
                    if (actionValue) {
                        formData.append('action', actionValue);
                    }

                    const submitButtons = form.find('button');
                    submitButtons.prop('disabled', true);

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Pastikan Anda punya meta tag CSRF
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
                            table.ajax.reload(); // Muat ulang data tabel
                        },
                        error: function(xhr) {
                            let errorMsg = 'An unexpected error occurred. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorMsg
                            });
                        },
                        complete: function() {
                            submitButtons.prop('disabled', false); // Aktifkan kembali tombol
                        }
                    });
                });
            }); // <-- AKHIR DARI $(function() { ... })

            // =Details of functions moved outside document.ready for clarity
            // ===============================================================
            //                 4. FUNGSI UNTUK MENGAMBIL & MENGISI DATA
            // ===============================================================
            function loadApprovalData(no_reg) {
                $.ajax({
                    url: "{{ route('prempdr.form.data') }}",
                    method: 'GET',
                    data: { no_reg: no_reg },
                    success: function(response) {
                        // Isi semua field di modal
                        $('#revision-no').text(response.revision.no);
                        $('#revision-count').text(response.revision.revision);
                        $('#revision-date').text(response.revision.date);
                        $('#projectName').val(response.project_name);
                        $('#levelPriority').val(response.level_priority);
                        $('#brandName').val(response.brand_name);
                        $('#rationalForDevelopment').val(response.detail.rational_for_development);

                        // Kategori, Channel, Sertifikasi (Read-only view)
                        $('#productCategory').html(`<strong>${response.category.category}</strong> ${response.category.other ? `<span>(${response.category.other})</span>` : ''}`);
                        $('#channel').html(`<strong>${response.channel.category}</strong> ${response.channel.country ? `<span>(${response.channel.country})</span>` : ''}`);
                        $('#certification').html(`<strong>${response.certification.category}</strong> ${response.certification.other ? `<span>(${response.certification.other})</span>` : ''}`);

                        $('#productDescription').val(response.description.product_description);
                        $('#usageDescription').val(response.description.usage_description);
                        $('#storageTemperature').val(response.description.storage_temperature);
                        $('#deliveryTemperature').val(response.description.delivery_temperature);

                        // Tabel Kompetitor
                        const competitorTbody = $('#competitorProduct tbody');
                        competitorTbody.empty();
                        if(response.competitor && response.competitor.length > 0) {
                            response.competitor.forEach(c => {
                                competitorTbody.append(`<tr><td>${c.name || '-'}</td><td>${c.size || '-'}</td><td>${c.packaging || '-'}</td><td>${c.price || '-'}</td></tr>`);
                            });
                        } else {
                            competitorTbody.append('<tr><td colspan="4" class="text-center">Tidak ada data kompetitor.</td></tr>');
                        }

                        // Detail Kemasan
                        $('#weightProduct').val(response.packaging.weight);
                        $('#packaging').html(`<strong>${response.packaging.category}</strong>: <span>${response.packaging.detail || '-'}</span>`);
                        $('#productVariation').val(response.packaging.product_variation);

                        // Update Pasar
                        $('#potentialVolume').val(response.market.potential_volume);
                        $('#expectedMargin').val(response.market.expected_margin);
                        $('#priceEstimate').val(response.market.price_estimate);
                        $('#targetLaunch').val(response.detail.target_launch);

                        // Riwayat Approval
                        const approverTbody = $('#approver tbody');
                        approverTbody.empty();
                        if(response.approved_detail && response.approved_detail.length > 0) {
                            response.approved_detail.forEach(detail => {
                                let statusBadge = '';
                                if(detail.status === 'approve') {
                                    statusBadge = 'bg-success';
                                } else if(detail.status === 'approve with review') {
                                    statusBadge = 'bg-warning text-dark';
                                } else if(detail.status) {
                                    statusBadge = 'bg-danger';
                                }

                                if(detail.status) { // Hanya tampilkan yang sudah ada status
                                    approverTbody.append(`
                                        <tr>
                                            <td>${detail.name}</td>
                                            <td><span class="badge ${statusBadge}">${detail.status}</span></td>
                                            <td>${detail.approved_date}</td>
                                            <td>${detail.comment || '-'}</td>
                                        </tr>
                                    `);
                                }
                            });
                        } else {
                            approverTbody.append('<tr><td colspan="4" class="text-center">Belum ada riwayat approval.</td></tr>');
                        }

                        // Sembunyikan loader dan tampilkan konten
                        $('#modal-loader').hide();
                        $('#modal-content-wrapper').show();
                    },
                    error: function() {
                        $('#modal-loader').html('<p class="text-danger fw-bold fs-5">Gagal memuat data. Silakan tutup dan coba lagi.</p>');
                    }
                });
            }

            // ===============================================================
            //                  5. FUNGSI SUBMIT & VALIDASI
            // ===============================================================
            window.submitApproval = function(action) {
                Swal.fire({
                    title: `Konfirmasi Tindakan`,
                    text: `Apakah Anda yakin ingin melakukan "${action}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745', // Hijau
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal'
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
