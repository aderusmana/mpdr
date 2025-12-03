<x-app-layout>
    @section('title')
        Approver MPDR
    @endsection

    @include('components.mpdr-table-styles')

    @push('css')
        {{-- Style untuk item yang bisa di-drag --}}
        <style>
            .sortable-item {
                padding: 10px 15px;
                border: 1px solid #dee2e6;
                margin-bottom: 8px;
                background: #ffffff;
                cursor: grab;
                border-radius: 8px !important;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }
            .sortable-item:active {
                cursor: grabbing;
            }
        </style>
    @endpush

    {{-- Judul dan Breadcrumb --}}
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Manage MPDR Approvers</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li><a class="f-s-14 f-w-500" href="{{ route('dashboard') }}"><i class="ph-duotone ph-house f-s-16"></i> Home</a></li>
                <li class="active"><a class="f-s-14 f-w-500" href="#">Manage Approvers</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title"><i class="ph-duotone ph-users-three"></i> Approver Configuration</h4>
                    <p class="table-subtitle">Add, remove, and reorder the approval workflow by dragging the items.</p>
                </div>
                <div class="p-4">
                    {{-- Form input disederhanakan, tanpa Level --}}
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
                        <select class="form-select fw-semibold" id="approver-list" style="width: 250px;">
                            <option value="" selected disabled>Select Approver</option>
                        </select>
                        <button class="btn btn-success" id="btn-add-approver">
                            <i class="ph-bold ph-plus me-1"></i> Add
                        </button>
                        <div class="ms-auto">
                            <button id="saveList" class="btn btn-primary">
                                <i class="ph-bold ph-floppy-disk me-1"></i> Save Order
                            </button>
                        </div>
                    </div>
                    <ul id="sortable" class="list-unstyled"></ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // --- FUNGSI-FUNGSI ---

            /** Menambahkan item approver ke dalam list UI */
            function addApproverToList(nik, name, status = 'Active') {
                const defaultAvatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&color=7F9CF5&background=EBF4FF`;

                // Template HTML untuk setiap item approver (tanpa Level)
                const newItemHTML = `
                    <li data-nik="${nik}" data-name="${name}" class="row align-items-center sortable-item">
                        <div class="col-md-7 d-flex align-items-center">
                            <img src="${defaultAvatarUrl}" class="rounded-circle me-3 user-avatar" width="40" height="40" alt="avatar">
                            <h6 class="mb-0 fw-bold">${name}</h6>
                        </div>
                        <div class="col-md-5">
                            <div class="d-flex align-items-center justify-content-end ms-auto gap-2">
                                <select class="form-select form-select-sm approver-status ${status === 'Vacant' ? 'text-danger' : 'text-success'}" onChange="changeSelectColor(this)" style="width: 120px;">
                                    <option value="Active" class="text-success" ${status === 'Active' ? 'selected' : ''}>Active</option>
                                    <option value="Vacant" class="text-danger" ${status === 'Vacant' ? 'selected' : ''}>Vacant</option>
                                </select>
                                <button aria-label="Remove Approver" class="btn btn-sm btn-danger btn-remove">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                `;
                $('#sortable').append(newItemHTML);
            }

            /** Mengubah warna teks dropdown status */
            function changeSelectColor(select) {
                select.classList.toggle('text-success', select.value === 'Active');
                select.classList.toggle('text-danger', select.value === 'Vacant');
            }

            // --- INISIALISASI HALAMAN ---

            $(document).ready(function() {
                const el = document.getElementById('sortable');
                const sortable = new Sortable(el, { animation: 150 });

                // Ambil semua data yang diperlukan secara bersamaan
                Promise.all([
                    $.get("{{ route('mpdr.selected.approver.list') }}"),
                    $.get("{{ route('mpdr.approver.list.data') }}")
                ]).then(([existingApprovers, allApprovers]) => {

                    const approverSelect = $('#approver-list');
                    const existingNiks = existingApprovers.map(a => a.approver_nik);

                    // 1. Isi dropdown "Select Approver"
                    allApprovers.forEach(approver => {
                        const option = new Option(approver.name, approver.nik);
                        // Sembunyikan jika sudah ada di list
                        if (existingNiks.includes(approver.nik)) {
                            option.classList.add('d-none');
                        }
                        approverSelect.append(option);
                    });

                    // 2. Tampilkan approver yang sudah ada ke dalam list
                    if (existingApprovers && existingApprovers.length > 0) {
                        existingApprovers.forEach(approver => {
                            addApproverToList(approver.approver_nik, approver.approver_name, approver.approver_status);
                        });
                    }

                }).catch(error => {
                    console.error("Failed to load initial approver data:", error);
                    Swal.fire('Error', 'Could not load approver data from the server.', 'error');
                });

                // --- EVENT LISTENERS ---

                // Tombol "Add"
                $('#btn-add-approver').on('click', function() {
                    const approverSelect = $('#approver-list');
                    const selectedNik = approverSelect.val();
                    const selectedName = approverSelect.find('option:selected').text();

                    if (!selectedNik) {
                        Swal.fire('Incomplete', 'Please select an approver from the list.', 'warning');
                        return;
                    }

                    addApproverToList(selectedNik, selectedName);

                    // Sembunyikan opsi yang baru ditambahkan dari dropdown
                    approverSelect.find('option:selected').addClass('d-none');
                    approverSelect.val(''); // Reset dropdown
                });

                // Tombol "Remove" (Trash Icon)
                $('#sortable').on('click', '.btn-remove', function() {
                    const listItem = $(this).closest('li');
                    const nik = listItem.data('nik');
                    const name = listItem.data('name');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to remove "${name}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, remove it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Tampilkan kembali di dropdown
                            $(`#approver-list option[value="${nik}"]`).removeClass('d-none');
                            listItem.remove();
                            Swal.fire('Removed!', `"${name}" has been removed.`, 'success');
                        }
                    });
                });

                // Tombol "Save Order"
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
                                Swal.fire({ icon: 'success', title: 'Success!', text: response.message, timer: 2000, showConfirmButton: false });
                            } else {
                                Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                            }
                        },
                        error: () => {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred.' });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
