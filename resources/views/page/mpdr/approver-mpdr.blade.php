<x-app-layout>
    @section('title')
        Approver MPDR
    @endsection

    @include('components.mpdr-table-styles')

    @push('css')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <style>
            .sortable-item {
                padding: 14px 20px;
                border: 1px solid #e2e8f0;
                border-left: 4px solid #3b82f6 !important;
                margin-bottom: 12px;
                background: #ffffff;
                border-radius: 12px !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
                transition: all 0.25s ease;
                cursor: grab;
            }

            .sortable-item:hover {
                box-shadow: 0 6px 16px rgba(59, 130, 246, 0.08);
                transform: translateY(-2px);
                border-color: #cbd5e1;
            }

            .sortable-item:active {
                cursor: grabbing;
                background: #f8fafc;
            }

            .drag-handle {
                cursor: grab;
                color: #94a3b8;
                font-size: 1.25rem;
                transition: color 0.2s;
            }

            .sortable-item:hover .drag-handle {
                color: #3b82f6;
            }

            .approver-avatar {
                width: 44px;
                height: 44px;
                object-fit: cover;
                border: 2px solid #e2e8f0;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }

            .badge-step {
                background-color: #eff6ff;
                color: #1d4ed8;
                border: 1px solid #bfdbfe;
                font-weight: 600;
                font-size: 0.85rem;
                padding: 6px 12px;
                border-radius: 20px;
            }

            .empty-state-box {
                border: 2px dashed #cbd5e1;
                border-radius: 16px;
                background: #f8fafc;
            }
        </style>
    @endpush

    {{-- Header & Breadcrumbs --}}
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Manage MPDR Approvers</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard') }}">
                        <i class="ph-duotone ph-house f-s-16"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Manage MPDR Approvers</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-users-three"></i>
                        MPDR Approval Workflow Configuration
                    </h4>
                    <p class="table-subtitle">Tambah, hapus, dan atur urutan alur persetujuan MPDR dengan menggeser (*drag & drop*) item di bawah ini.</p>
                </div>

                <div class="p-4">
                    {{-- Form Control Top Bar --}}
                    <div class="p-3 mb-4 rounded-3 border bg-light d-flex flex-wrap gap-3 align-items-center justify-content-between">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="position-relative" style="min-width: 280px;">
                                <select class="form-select fw-semibold" id="approver-list">
                                    <option value="" selected disabled>Select Approver...</option>
                                </select>
                            </div>
                            <button type="button" id="btn-add-approver" class="btn btn-success fw-bold d-flex align-items-center gap-2 px-3">
                                <i class="ph-bold ph-plus"></i>
                                <span>Add Approver</span>
                            </button>
                        </div>
                        <div>
                            <button type="button" id="saveList" class="btn btn-primary fw-bold d-flex align-items-center gap-2 px-4">
                                <i class="ph-bold ph-floppy-disk"></i>
                                <span>Save Approval Order</span>
                            </button>
                        </div>
                    </div>

                    {{-- Dynamic Sortable Approver Workflow List --}}
                    <ul id="sortable" class="list-unstyled mb-0"></ul>

                    {{-- Empty State Indicator --}}
                    <div id="empty-state" class="empty-state-box text-center py-5 my-3 d-none">
                        <i class="ph-duotone ph-tree-structure text-primary display-4 mb-3 d-block"></i>
                        <h5 class="fw-bold text-dark mb-1">Belum Ada Approver Terpasang</h5>
                        <p class="text-muted small mb-0">Silakan pilih **Approver** pada dropdown di atas lalu klik **"+ Add Approver"**.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Add approver item to list UI
            function addApproverToList(nik, name, status = 'Active') {
                const defaultAvatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&color=2563eb&background=eff6ff`;
                const userAvatarUrl = `{{ asset('storage/uploads/user_avatar') }}/${nik}.jpg`;

                const newItemHTML = `
                    <li data-nik="${nik}" data-name="${name}" class="sortable-item">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <i class="ph-bold ph-dots-six-vertical drag-handle me-3" title="Drag to reorder"></i>
                                <img src="${defaultAvatarUrl}" class="rounded-circle me-3 approver-avatar" alt="avatar" data-user-avatar="${userAvatarUrl}">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="mb-0 fw-bold text-dark">${name}</h6>
                                        <span class="badge bg-light text-secondary border font-monospace">NIK: ${nik}</span>
                                    </div>
                                    <span class="badge-step d-inline-flex align-items-center gap-1 step-badge">
                                        <i class="ph-bold ph-git-commit"></i> Step <span class="step-num"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 ms-auto">
                                <select class="form-select form-select-sm approver-status ${status === 'Vacant' ? 'text-danger fw-bold' : 'text-success fw-bold'}" onChange="changeSelectColor(this)" style="width: 120px;">
                                    <option value="Active" class="text-success fw-bold" ${status === 'Active' ? 'selected' : ''}>🟢 Active</option>
                                    <option value="Vacant" class="text-danger fw-bold" ${status === 'Vacant' ? 'selected' : ''}>🔴 Vacant</option>
                                </select>
                                <button type="button" aria-label="Remove Approver" class="btn btn-sm btn-outline-danger border-0 btn-remove px-2 py-1" title="Remove Approver">
                                    <i class="ph-bold ph-trash fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                `;

                $('#sortable').append(newItemHTML);
                checkAndLoadUserAvatar(nik, $('#sortable li:last-child .approver-avatar'));

                // Hide selected option from dropdown
                $(`#approver-list option[value="${nik}"]`).addClass('d-none');
                updateStepBadges();
            }

            function updateStepBadges() {
                $('#sortable li').each(function(index) {
                    $(this).find('.step-num').text(index + 1);
                });
                if ($('#sortable li').length === 0) {
                    $('#empty-state').removeClass('d-none');
                } else {
                    $('#empty-state').addClass('d-none');
                }
            }

            function changeSelectColor(select) {
                select.classList.toggle('text-success', select.value === 'Active');
                select.classList.toggle('text-danger', select.value === 'Vacant');
            }

            function checkAndLoadUserAvatar(nik, imgElement) {
                var tempImg = new Image();
                tempImg.onload = function() {
                    imgElement.attr('src', this.src);
                };
                tempImg.src = `{{ asset('storage/uploads/user_avatar') }}/${nik}.jpg`;
            }

            $(document).ready(function() {
                const el = document.getElementById('sortable');
                if (el && typeof Sortable !== 'undefined') {
                    new Sortable(el, {
                        animation: 150,
                        onEnd: function() {
                            updateStepBadges();
                        }
                    });
                }

                // Load existing & available approvers
                Promise.all([
                    $.get("{{ route('mpdr.selected.approver.list') }}"),
                    $.get("{{ route('mpdr.approver.list.data') }}")
                ]).then(([existingApprovers, allApprovers]) => {
                    const approverSelect = $('#approver-list');

                    if (Array.isArray(allApprovers)) {
                        allApprovers.forEach(approver => {
                            approverSelect.append($('<option>', {
                                value: approver.nik,
                                text: `${approver.name} (${approver.nik})`
                            }));
                        });
                    }

                    if (Array.isArray(existingApprovers) && existingApprovers.length > 0) {
                        existingApprovers.forEach(approver => {
                            addApproverToList(approver.approver_nik, approver.approver_name, approver.approver_status);
                        });
                    }
                    updateStepBadges();
                }).catch(err => {
                    console.error('Error initializing MPDR approver data:', err);
                    updateStepBadges();
                });

                // Add button handler
                $('#btn-add-approver').on('click', function() {
                    const approverSelect = $('#approver-list');
                    const selectedNik = approverSelect.val();
                    const selectedName = approverSelect.find('option:selected').text().replace(/\s*\([^)]*\)/, '');

                    if (!selectedNik) {
                        Swal.fire('Incomplete', 'Please select an approver from the list.', 'warning');
                        return;
                    }

                    addApproverToList(selectedNik, selectedName);
                    approverSelect.val('');
                });

                // Remove button handler
                $('#sortable').on('click', '.btn-remove', function() {
                    const listItem = $(this).closest('li');
                    const nik = listItem.data('nik');
                    const name = listItem.data('name');

                    Swal.fire({
                        title: 'Remove Approver?',
                        text: `Apakah Anda yakin ingin menghapus "${name}" dari alur persetujuan?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(`#approver-list option[value="${nik}"]`).removeClass('d-none');
                            listItem.remove();
                            updateStepBadges();
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: `"${name}" telah dihapus dari alur.`,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    });
                });

                // Save Order button handler
                $('#saveList').on('click', function() {
                    let orderedData = [];
                    $("#sortable li").each(function(index) {
                        orderedData.push({
                            nik: $(this).data('nik'),
                            name: $(this).data('name'),
                            status: $(this).find('.approver-status').val(),
                            order: index + 1
                        });
                    });

                    $.ajax({
                        url: "{{ route('mpdr.approver.update.order') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            approvers: orderedData
                        },
                        success: (response) => {
                            if (response.status === 'success') {
                                Swal.fire({ icon: 'success', title: 'Berhasil Disimpan!', text: response.message });
                            } else {
                                Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                            }
                        },
                        error: () => {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat menyimpan alur.' });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
