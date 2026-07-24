<x-app-layout>
    @section('title')
        Approver Pre-MPDR
    @endsection

    @include('components.mpdr-table-styles')

    @push('css')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <style>
            .approver-card-wrapper {
                background: #ffffff;
                border-radius: 16px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            }

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
            <h4 class="main-title">Manage Pre-MPDR Approvers</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard') }}">
                        <i class="ph-duotone ph-house f-s-16"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Manage Pre-MPDR Approvers</a>
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
                        Pre-MPDR Approval Workflow Configuration
                    </h4>
                    <p class="table-subtitle">Tambah, hapus, dan atur urutan alur persetujuan Pre-MPDR dengan menggeser (*drag & drop*) item di bawah ini.</p>
                </div>

                <div class="p-4">
                    {{-- Form Control Top Bar --}}
                    <div class="p-3 mb-4 rounded-3 border bg-light d-flex flex-wrap gap-3 align-items-center justify-content-between">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="position-relative" style="min-width: 260px;">
                                <select class="form-select fw-semibold" id="approver-list">
                                    <option value="" selected disabled>Select Approver...</option>
                                </select>
                            </div>
                            <div class="position-relative" style="min-width: 160px;">
                                <select class="form-select fw-semibold" id="approver-level">
                                    <option value="" selected disabled>Select Level...</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-success fw-bold d-flex align-items-center gap-2 px-3" onClick="addItem()">
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
                        <p class="text-muted small mb-0">Silakan pilih **Approver** dan **Level** pada form di atas lalu klik **"+ Add Approver"**.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
        <script>
            // Helper function to render a sortable item in the list
            function addApproverItem(nik, name, level, status) {
                status = status || 'Active';
                var defaultAvatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&color=2563eb&background=eff6ff`;
                var userAvatarUrl = `{{ asset('storage/uploads/user_avatar') }}/${nik}.jpg`;

                var newItem = `
                    <li data-nik="${nik}" data-name="${name}" data-level="${level}" class="sortable-item">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <i class="ph-bold ph-dots-six-vertical drag-handle me-3" title="Drag to reorder"></i>
                                <img src="${defaultAvatarUrl}" class="rounded-circle me-3 approver-avatar" alt="avatar" data-user-avatar="${userAvatarUrl}">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="mb-0 fw-bold text-dark">${name}</h6>
                                        <span class="badge bg-light text-secondary border font-monospace">NIK: ${nik}</span>
                                    </div>
                                    <span class="badge-step d-inline-flex align-items-center gap-1">
                                        <i class="ph-bold ph-git-commit"></i> Level ${level}
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 ms-auto">
                                <select class="form-select form-select-sm approver-status ${status == 'Vacant' ? 'text-danger fw-bold' : 'text-success fw-bold'}" onChange="changeSelectColor(this)" style="width: 120px;">
                                    <option value="Active" class="text-success fw-bold" ${status == 'Active' ? 'selected' : ''}>🟢 Active</option>
                                    <option value="Vacant" class="text-danger fw-bold" ${status == 'Vacant' ? 'selected' : ''}>🔴 Vacant</option>
                                </select>
                                <button type="button" aria-label="Remove Approver" class="btn btn-sm btn-outline-danger border-0 btn-remove-item px-2 py-1" title="Remove Approver">
                                    <i class="ph-bold ph-trash fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                `;

                $('#sortable').append(newItem);
                checkAndLoadUserAvatar(nik, $('#sortable li:last-child .approver-avatar'));

                // Hide selected approver & remove level from dropdown
                $(`#approver-list option[data-nik="${nik}"]`).addClass('d-none');
                $(`#approver-level option[value="${level}"]`).remove();

                updateEmptyState();
            }

            // Called when clicking "+ Add Approver" button
            function addItem() {
                var approverSelect = $('#approver-list');
                var levelSelect = $('#approver-level');

                var selectedOption = approverSelect.find('option:selected');
                var selectedNik = selectedOption.attr('data-nik');
                var selectedName = selectedOption.attr('data-name') || approverSelect.val();
                var selectedLevel = levelSelect.val();

                if (!selectedNik || !selectedName || !selectedLevel) {
                    Swal.fire('Incomplete', 'Please select both an approver and a level.', 'warning');
                    return;
                }

                addApproverItem(selectedNik, selectedName, selectedLevel, 'Active');

                // Reset dropdown selections
                approverSelect.val('');
                levelSelect.val('');
            }

            // Remove item from sortable list
            $(document).on('click', '.btn-remove-item', function() {
                var listItem = $(this).closest('li');
                var nik = listItem.data('nik');
                var name = listItem.data('name');
                var level = listItem.data('level');

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
                        $(`#approver-list option[data-nik="${nik}"]`).removeClass('d-none');
                        if (level) {
                            $('#approver-level').append($('<option>', { value: level, text: level }));
                            sortSelectOptions(document.getElementById('approver-level'));
                        }
                        listItem.remove();
                        updateEmptyState();
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

            function updateEmptyState() {
                if ($('#sortable li').length === 0) {
                    $('#empty-state').removeClass('d-none');
                } else {
                    $('#empty-state').addClass('d-none');
                }
            }

            function sortSelectOptions(selectElement) {
                if (!selectElement) return;
                var options = $(selectElement).find('option');
                options.sort(function(a, b) {
                    if (a.value === "") return -1;
                    if (b.value === "") return 1;
                    return +a.text - +b.text;
                });
                $(selectElement).html(options);
                selectElement.selectedIndex = 0;
            }

            $(document).ready(function() {
                var el = document.getElementById('sortable');
                if (el && typeof Sortable !== 'undefined') {
                    new Sortable(el, { animation: 150 });
                }

                Promise.all([
                    $.get("{{ route('prempdr.approver.available.levels') }}"),
                    $.get("{{ route('prempdr.selected.approver.list') }}"),
                    $.get("{{ route('prempdr.approver.list.data') }}")
                ]).then(([availableLevels, existingApprovers, allApprovers]) => {
                    // 1. Populate Available Levels
                    var levelSelect = $('#approver-level');
                    if (Array.isArray(availableLevels)) {
                        availableLevels.forEach(level => {
                            levelSelect.append($('<option>', { value: level, text: level }));
                        });
                    }

                    // 2. Populate All Approvers Dropdown
                    var approverSelect = $('#approver-list');
                    if (Array.isArray(allApprovers)) {
                        allApprovers.forEach(item => {
                            approverSelect.append($('<option>', {
                                value: item.name,
                                'data-nik': item.nik,
                                'data-name': item.name,
                                text: item.name + (item.nik ? ' (' + item.nik + ')' : '')
                            }));
                        });
                    }

                    // 3. Render Existing Approvers
                    if (Array.isArray(existingApprovers) && existingApprovers.length > 0) {
                        existingApprovers.forEach(approver => {
                            addApproverItem(approver.approver_nik, approver.approver_name, approver.level, approver.approver_status);
                        });
                    }
                    updateEmptyState();
                }).catch(err => {
                    console.error('Error initializing approver data:', err);
                    updateEmptyState();
                });
            });

            $(document).on('click', '#saveList', function() {
                var orderedNik = [];
                var orderedNames = [];
                var orderedStatuses = [];
                var orderedLevels = [];

                $("#sortable li").each(function() {
                    orderedNik.push($(this).data('nik'));
                    orderedNames.push($(this).data('name'));
                    orderedLevels.push($(this).data('level'));
                    orderedStatuses.push($(this).find('.approver-status').val());
                });

                $.ajax({
                    url: '{{ route('prempdr.approver.update.order') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nik: orderedNik,
                        name: orderedNames,
                        status: orderedStatuses,
                        level: orderedLevels
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Disimpan!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        var response = xhr.responseJSON;
                        if (response && response.message) {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat menyimpan alur.' });
                        }
                    }
                });
            });

            function checkAndLoadUserAvatar(nik, imgElement) {
                var tempImg = new Image();
                tempImg.onload = function() {
                    imgElement.attr('src', this.src);
                };
                tempImg.src = `{{ asset('storage/uploads/user_avatar') }}/${nik}.jpg`;
            }

            function changeSelectColor(select) {
                select.classList.toggle('text-success', select.value === 'Active');
                select.classList.toggle('text-danger', select.value === 'Vacant');
            }
        </script>
    @endpush
</x-app-layout>
