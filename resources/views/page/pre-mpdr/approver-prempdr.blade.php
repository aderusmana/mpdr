<x-app-layout>
    @section('title')
        Approver Pre-MPDR
    @endsection

    {{-- Menggunakan style tabel yang konsisten --}}
    @include('components.mpdr-table-styles')

    @push('css')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <style>
            .sortable-item {
                padding: 10px 15px; /* Sedikit penyesuaian padding */
                border: 1px solid #dee2e6;
                margin-bottom: 8px; /* Sedikit penyesuaian margin */
                background: #ffffff;
                cursor: grab; /* Mengubah cursor menjadi grab */
                border-radius: 8px !important; /* Memastikan border radius diterapkan */
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }
            .sortable-item:active {
                cursor: grabbing;
            }
        </style>
    @endpush

    {{-- Judul dan Breadcrumb - Mengadopsi style baru --}}
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
                    <a class="f-s-14 f-w-500" href="#">Manage Approvers</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Kontainer Baru untuk membungkus konten --}}
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-users-three"></i>
                        Approver Configuration
                    </h4>
                    <p class="table-subtitle">Add, remove, and reorder the approval workflow by dragging the items</p>
                </div>

                {{-- Konten asli halaman (form dan list) dimasukkan ke sini --}}
                <div class="p-4">
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
                        <select class="form-select fw-semibold" id="approver-list" style="width: 250px;">
                            <option value="" selected disabled>Select Approver</option>
                        </select>
                        <select class="form-select fw-semibold" id="approver-level" style="width: 150px;">
                            <option value="" selected disabled>Select Level</option>
                        </select>
                        <button class="btn btn-success" onClick="addItem()">
                            <i class="ph-bold ph-plus me-1"></i>
                            Add
                        </button>
                        <div class="ms-auto">
                            <button id="saveList" class="btn btn-primary">
                                <i class="ph-bold ph-floppy-disk me-1"></i>
                                Save Order
                            </button>
                        </div>
                    </div>

                    <ul id="sortable" class="list-unstyled">
                        {{-- Item approver akan ditambahkan di sini oleh JavaScript --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        {{-- Script asli tidak diubah, hanya memastikan berjalan dengan baik --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
        <script>
            // SEMUA KODE JAVASCRIPT ASLI ANDA DARI FILE approver-prempdr.blade.php
            // TIDAK PERLU DIUBAH SAMA SEKALI. CUKUP PASTE DI SINI.
            const DELETE_URL_TEMPLATE = "{{ route('prempdr.approver.destroy', ['nik' => '__NIK__']) }}";
            // Fungsi untuk menambahkan item approver ke list
            function addItem(status) {
                var sortableList = document.getElementById('sortable');
                var approverSelect = document.getElementById('approver-list');
                var levelSelect = document.getElementById('approver-level');

                var selectedApproverIndex = approverSelect.selectedIndex;
                var selectedApproverValue = approverSelect.value;
                var selectedNik = approverSelect.options[selectedApproverIndex].getAttribute('data-nik');

                var selectedLevelIndex = levelSelect.selectedIndex;
                var selectedLevel = levelSelect.value;

                // Validasi: pastikan approver dan level sudah dipilih
                if (!selectedApproverValue || !selectedLevel) {
                    Swal.fire('Incomplete', 'Please select both an approver and a level.', 'warning');
                    return;
                }

                var defaultAvatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(selectedApproverValue)}&color=7F9CF5&background=EBF4FF`;
                var userAvatarUrl = `{{ asset('storage/uploads/user_avatar') }}/${selectedNik}.jpg`;

                var newItem = `
                    <li data-nik="${selectedNik}" data-name="${selectedApproverValue}" data-level="${selectedLevel}" class="row align-items-center sortable-item">
                        <div class="col-md-6 d-flex align-items-center">
                            <img src="${defaultAvatarUrl}" class="rounded-circle me-3 user-avatar" width="40" height="40" alt="avatar" data-user-avatar="${userAvatarUrl}">
                            <div>
                                <h6 class="mb-0 fw-bold">${selectedApproverValue}</h6>
                                <span class="badge bg-info-subtle text-info-emphasis rounded-pill">Level ${selectedLevel}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-end ms-auto gap-2">
                                <select class="form-select form-select-sm approver-status ${status == 'Vacant' ? 'text-danger' : 'text-success'}" onChange="changeSelectColor(this)" style="width: 120px;">
                                    <option value="Active" class="text-success" ${status == 'Active' ? 'selected' : ''}>Active</option>
                                    <option value="Vacant" class="text-danger" ${status == 'Vacant' ? 'selected' : ''}>Vacant</option>
                                </select>
                                <button aria-label="Remove Approver" class="btn btn-sm btn-danger" onClick="deleteItem(this, ${selectedApproverIndex})">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                `;

                $('#sortable').append(newItem);
                checkAndLoadUserAvatar(selectedNik, $('#sortable li:last-child .user-avatar'));

                // Sembunyikan approver dan level yang sudah dipilih dari dropdown
                approverSelect.options[selectedApproverIndex].classList.add('d-none');
                levelSelect.remove(selectedLevelIndex);

                // Reset pilihan dropdown
                approverSelect.selectedIndex = 0;
                levelSelect.selectedIndex = 0;
            }

            // Fungsi untuk menghapus item dari list
            function deleteItem(button, approverIndex) {
                var listItem = $(button).closest('li');
                var nikToDelete = listItem.data('nik');
                var approverName = listItem.data('name');
                var level = listItem.data('level');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to remove "${approverName}" from the approver list?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let finalUrl = DELETE_URL_TEMPLATE.replace('__NIK__', nikToDelete);
                        $.ajax({
                            url: finalUrl, // URL ke route destroy
                            type: 'POST', // Gunakan POST untuk 'menipu' method DELETE
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE' // Ini adalah method spoofing yang dipahami Laravel
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    // 1. Kembalikan approver ke dropdown
                                    var approverSelect = document.getElementById('approver-list');
                                    approverSelect.options[approverIndex].classList.remove('d-none');

                                    // 2. Kembalikan level ke dropdown dan urutkan
                                    var levelSelect = document.getElementById('approver-level');
                                    $('#approver-level').append($('<option>', {
                                        value: level,
                                        text: level
                                    }));
                                    sortSelectOptions(levelSelect);

                                    // 3. Hapus item dari tampilan
                                    listItem.remove();

                                    // 4. Tampilkan notifikasi sukses
                                    Swal.fire('Deleted!', `"${approverName}" has been successfully removed.`, 'success');
                                } else {
                                    Swal.fire('Failed!', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', 'An error occurred while communicating with the server.', 'error');
                            }
                        });
                    }
                });
            }

            function sortSelectOptions(selectElement) {
                var options = $(selectElement).find('option');
                options.sort(function(a, b) {
                    if (a.value === "") return -1;
                    if (b.value === "") return 1;
                    return +a.text - +b.text;
                });
                $(selectElement).html(options);
                selectElement.selectedIndex = 0;
            }

            window.addEventListener('load', function() {
                var el = document.getElementById('sortable');
                var sortable = new Sortable(el, {
                    animation: 150
                });

                $.ajax({
                    url: '{{ route('prempdr.approver.available.levels') }}',
                    method: 'GET',
                    success: function(levels) {
                        var levelSelect = $('#approver-level');
                        levels.forEach(level => {
                            levelSelect.append($('<option>', {
                                value: level,
                                text: level
                            }));
                        });
                    },
                    error: function() {
                        console.error('Error fetching available levels.');
                    }
                });

                var existingApprovers = [];
                $.ajax({
                    url: '{{ route('prempdr.selected.approver.list') }}',
                    method: 'GET',
                    async: false,
                    success: function(response) {
                        existingApprovers = response;
                    },
                    error: function() {
                        console.error('Error fetching selected approvers.');
                    }
                });

                $.ajax({
                    url: '{{ route('prempdr.approver.list.data') }}',
                    method: 'GET',
                    success: function(response) {
                        var approverSelect = $('#approver-list');
                        response.forEach(item => {
                            approverSelect.append($('<option>', {
                                value: item.name,
                                'data-nik': item.nik,
                                text: item.name
                            }));
                        });

                        var approverSelectEl = document.getElementById('approver-list');
                        var levelSelectEl = document.getElementById('approver-level');

                        existingApprovers.sort((a, b) => a.order - b.order).forEach(approver => {
                            for (let i = 0; i < approverSelectEl.options.length; i++) {
                                if (approverSelectEl.options[i].getAttribute('data-nik') === approver.approver_nik) {
                                    approverSelectEl.selectedIndex = i;
                                    break;
                                }
                            }

                            $('#approver-level').append($('<option>', {
                                value: approver.level,
                                text: approver.level,
                                selected: true
                            }));

                            addItem(approver.approver_status);
                        });
                    },
                    error: function() {
                        console.error('Error fetching all approver list.');
                    }
                });
            });

            const saveButton = document.getElementById('saveList');
            saveButton.addEventListener('click', () => {
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
                                title: 'Success',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        var response = xhr.responseJSON;
                        if (response && response.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while processing your request.'
                            });
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
