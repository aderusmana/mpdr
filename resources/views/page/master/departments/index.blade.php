<x-app-layout>
    @section('title')
        Departments
    @endsection

    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Departments Management</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard') }}">
                        <i class="ph-duotone ph-house f-s-16"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Departments</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Tombol Add User --}}
            <div class="d-flex justify-content-end align-items-center mb-4">
                <button type="button" class="btn new-mpdr-btn" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                    <i class="ph-bold ph-plus"></i>
                    <span>Add Departments</span>
                </button>
            </div>

            {{-- Kontainer Tabel --}}
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-users"></i>
                        Departments List
                    </h4>
                    <p class="table-subtitle">Manage all registered users, their roles, and permissions.</p>
                </div>
                <div class="table-responsive">
                    <table class="w-100 display table table-striped" id="prempdrTable">
                        <thead class="header-item">
                            <th>No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Department -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary text-white">
                    <h5 class="modal-title text-white">Add Department</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf
                        <div class="box">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="tb-name"
                                                    placeholder="Enter Name here" name="name">
                                                <label for="tb-name">Name</label>
                                            </div>
                                            <span class="validation-text text-danger"></span>
                                        </div>
                                    </div>
                                    {{-- dep_head input removed per request --}}
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex gap-6 m-0">
                        <button type="submit" class="btn btn-success">Add</button>
                        </form>
                        <button class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal"> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit modals will be generated dynamically -->
    <div id="editModalDiv"></div>

    @push('scripts')
    <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var departments;
            // Initialize DataTable with AJAX source
            var table = $('#prempdrTable').DataTable({
                    ajax: {
                    url: "{{ route('departments.index') }}",
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    dataSrc: function(response) {
                        // Yajra returns data in `response.data`
                        departments = response.data || [];
                        return response.data || [];
                    },
                    error: function(xhr, status, error) {
                        console.error('Departments.data AJAX error:', xhr.status, error);
                        console.error('Response text:', xhr.responseText);
                        var msg = 'Invalid JSON response from server.';
                        try {
                            var json = JSON.parse(xhr.responseText);
                            if (json.message) msg = json.message;
                        } catch (e) {
                            // not JSON
                        }
                        Swal.fire('Error', msg + ' See console for details.', 'error');
                    }
                },
                columns: [
                    { data: null, render: function(data, type, row, meta) { return meta.row + 1; } },
                    { data: 'name', name: 'name' },
                    { data: null, orderable: false, searchable: false, render: function(data, type, row, meta) {
                        var id = row.id;
                        var deleteUrl = "{{ route('departments.destroy', ':id') }}".replace(':id', id);
                        return `
                            <div class="d-flex action-btn">
                                <a href="javascript:void(0)" class="text-primary edit" onClick="openEditModal(${meta.row})">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                <form id="delete-form-${id}" action="${deleteUrl}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a href="javascript:void(0)" class="text-dark delete ms-2" data-department-id="${id}" onClick="confirmDepartmentDelete(this)">
                                        <i class="ti ti-trash fs-5"></i>
                                    </a>
                                </form>
                            </div>
                        `;
                    }}
                ]
            });

            // expose table for reload
            window.departmentTable = table;

            // Add Department via AJAX
            $(document).on('submit', '#addDepartmentModal form', function(e) {
                e.preventDefault();
                var $form = $(this);
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(resp) {
                        if (resp.status === 'success') {
                            $('#addDepartmentModal').modal('hide');
                            Swal.fire('Success', resp.message, 'success');
                            if (window.departmentTable) window.departmentTable.ajax.reload(null, false);
                            $form[0].reset();
                        } else {
                            var msg = resp.message || 'Failed to create department';
                            Swal.fire('Error', msg, 'error');
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Server error';
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(function(v){ return v.join('\n'); }).join('\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', msg, 'error');
                    }
                });
            });

            // Re-init listeners when table draws (not strictly necessary because action buttons call global functions)
            table.on('draw', function() {
                // nothing required here — actions are handled by global handlers
            });
        });

        // Open edit modal dynamically
        function openEditModal(index) {
            var table = window.departmentTable;
            var json = table.ajax.json() || {};
            var data = json.data || json.departments || departments || [];
            var department = data[index];
            if (!department) {
                console.error('Edit modal: department not found for index', index, 'json:', json);
                Swal.fire('Error', 'Failed to load department data for edit. See console.', 'error');
                return;
            }
            var modalDiv = document.getElementById('editModalDiv');
            var id = department.id;
            var updateUrl = "{{ route('departments.update', ':id') }}".replace(':id', id);
            var newModal = `
                <div class="modal fade" id="editDepartmentModal${id}" tabindex="-1" role="dialog" aria-labelledby="editDepartmentModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header modal-colored-header bg-primary text-white">
                                <h5 class="modal-title text-white">Edit Department</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="${updateUrl}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="add-department-box">
                                        <div class="add-department-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3 department-name">
                                                        <input type="text" name="name" class="form-control" placeholder="Name" value="${department.name}">
                                                        <span class="validation-text text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="d-flex gap-6 m-0">
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            // remove existing modal if any
            modalDiv.innerHTML = '';
            modalDiv.innerHTML = newModal;
            // attach submit handler
            $('#editDepartmentModal' + id).find('form').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(resp) {
                        if (resp.status === 'success') {
                            $('#editDepartmentModal' + id).modal('hide');
                            Swal.fire('Success', resp.message, 'success');
                            if (window.departmentTable) window.departmentTable.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', resp.message || 'Failed to update department', 'error');
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Server error';
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(function(v){ return v.join('\n'); }).join('\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', msg, 'error');
                    }
                });
            });
            var modal = document.getElementById('editDepartmentModal' + id);
            var bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }

        // Confirm and perform AJAX delete
        function confirmDepartmentDelete(element) {
            var departmentId = element.getAttribute('data-department-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.getElementById('delete-form-' + departmentId);
                    if (!form) return;
                    var action = form.action;
                    var tokenInput = form.querySelector('input[name="_token"]');
                    var token = tokenInput ? tokenInput.value : '';
                    $.ajax({
                        url: action,
                        method: 'POST',
                        data: { _token: token, _method: 'DELETE' },
                        success: function(resp) {
                            if (resp.status === 'success') {
                                Swal.fire('Deleted!', resp.message, 'success');
                                if (window.departmentTable) window.departmentTable.ajax.reload(null, false);
                            } else {
                                Swal.fire('Error', resp.message || 'Failed to delete', 'error');
                            }
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Server error';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
</x-app-layout>
