<x-app-layout>
    @section('title')
        Users
    @endsection

    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">User Management</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard') }}">
                        <i class="ph-duotone ph-house f-s-16"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Users</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Tombol Add User --}}
            <div class="d-flex justify-content-end align-items-center mb-4">
                <button type="button" class="btn new-mpdr-btn" data-bs-toggle="modal" data-bs-target="#addContactModal">
                    <i class="ph-bold ph-plus"></i>
                    <span>Add User</span>
                </button>
            </div>

            {{-- Kontainer Tabel --}}
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-users"></i>
                        User List
                    </h4>
                    <p class="table-subtitle">Manage all registered users, their roles, and permissions.</p>
                </div>
                <div class="table-responsive">
                    <table class="w-100 display table table-striped" id="userTable">
                        <thead class="header-item">
                            <th>NIK</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add User -->
    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary text-white">
                    <h5 class="modal-title text-white">Add User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="box">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="tb-fname"
                                                    placeholder="Enter Name here" name="name" required>
                                                <label for="tb-fname">Name</label>
                                            </div>
                                            <span class="validation-text text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-floating mb-3">
                                                <input type="email" class="form-control" id="tb-email"
                                                    placeholder="Email" name="email" autocomplete="on" required>
                                                <label for="tb-email">Email</label>
                                            </div>
                                            <span class="validation-text text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="tb-nik"
                                                    placeholder="NIK" name="nik" autocomplete="on" maxlength="6" required>
                                                <label for="tb-nik">NIK</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Department</label>
                                            <select id="add_user_department" name="department_id" class="form-select select2" required>
                                                <option value="" disabled selected>Select Department</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Position</label>
                                            <select id="add_user_position" name="position_id" class="form-select select2" required>
                                                <option value="" disabled selected>Select Position</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Section</label>
                                            <select id="add_user_section" name="section_id" class="form-select select2" required>
                                                <option value="" disabled selected>Select Section</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Atasan</label>
                                            <select id="add_user_atasan" name="atasan_nik" class="form-select select2">
                                                <option value="" disabled selected>Select Atasan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Password" minlength="8" maxlength="20" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Role</label>
                                            <select id="add_user_role" name="roles[]" class="form-select select2" multiple required>
                                                <option value="" disabled>Select Role</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 contact-status">
                                            <label class="form-label fw-semibold">Status</label>
                                            <select name="status" class="form-select select2" required>
                                                <option value="" disabled selected>Select Status</option>
                                                @foreach (["Active", "Vacant"] as $status)
                                                    <option value="{{ $status }}">{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="d-flex gap-6 m-0">
                                <button type="submit" class="btn btn-success">Add</button>
                                <button class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal"> Close
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div id="editModalDiv">
    </div>

    @push('scripts')
    <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var departments;
        var positions;
        var sections;
        var roles;
        var users;
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable dengan ajax
            var table = $('#userTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: "{{ route('get.users.data') }}",
                    type: 'GET',
                    dataSrc: function(response) {
                        departments = response.departments;
                        positions = response.positions;
                        sections = response.sections;
                        roles = response.roles;
                        users = response.users;
                        response.departments.forEach(function(department) {
                            $('#add_user_department').append(
                                `<option value="${department.id}">${department.name}</option>`
                            );
                        });
                        response.positions.forEach(function(position) {
                            $('#add_user_position').append(
                                `<option value="${position.id}">${position.name}</option>`
                            );
                        });
                        response.roles.forEach(function(role) {
                            $('#add_user_role').append(
                                `<option value="${role}">${role}</option>`
                            );
                        });
                        // Re-initialize Select2 on the role select so new options are recognized
                        try {
                            $('#add_user_role').select2('destroy');
                        } catch (e) {
                            // ignore if not initialized yet
                        }
                        $('#add_user_role').select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            placeholder: 'Select Role'
                        });
                        response.sections.forEach(function(section) {
                            $('#add_user_section').append(
                                `<option value="${section.id}">${section.name}</option>`
                            );
                        });
                        // Populate Atasan (supervisor) select with users (value=nik, display=name)
                        response.users.forEach(function(u) {
                            $('#add_user_atasan').append(
                                `<option value="${u.nik}">${u.name}</option>`
                            );
                        });
                        // Initialize Select2 on all .select2 elements in add modal
                        $('#addContactModal .select2').select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            dropdownParent: $('#addContactModal')
                        });
                        
                        return response.users;
                    }
                },
                columns: [
                    { data: 'nik', name: 'nik' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'department.name', name: 'department' },
                    { data: 'position.name', name: 'position' },
                    { data: 'section.name', name: 'section' },
                    { data: 'status', name: 'status' },
                    { data: 'roles', name: 'role',
                        render: function(data, type, row) {
                            var roleHtml = '';
                            // Iterasi melalui data roles
                            data.forEach(function(role) {
                                roleHtml += '<h6 class="badge bg-primary">' + role.name + '</h6>';
                            });
                            return roleHtml;
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            var id = row.id;
                            var deleteUrl = "{{ route('users.destroy', ':id') }}".replace(':id', id);
                            return `
                            <div class="d-flex action-btn">
                                <a href="javascript:void(0)" class="text-primary edit" onClick="openEditModal(${meta.row})">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                <form id="delete-form-${id}" action="${deleteUrl}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a href="javascript:void(0)" class="text-dark delete ms-2"
                                        data-user-id="${id}" onClick="confirmUserDelete(this)">
                                        <i class="ti ti-trash fs-5"></i>
                                    </a>
                                </form>
                            </div>
                            `;
                        }
                    }
                ]
            });
            // expose datatable globally for reload after ajax operations
            window.userTable = table;
        });

        // Handle Add User form submission via AJAX
        $(document).on('submit', '#addContactModal form', function(e) {
            e.preventDefault();
            var $form = $(this);
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(resp) {
                    if (resp.status === 'success') {
                        $('#addContactModal').modal('hide');
                        Swal.fire('Success', resp.message, 'success');
                        if (window.userTable) window.userTable.ajax.reload(null, false);
                        // reset form fields
                        $form[0].reset();
                        // reset select2 selections
                        $form.find('.select2').val(null).trigger('change');
                    } else {
                        var msg = resp.message || 'Failed to create user';
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


        // Function to initialize modal event listeners
        function initializeModalListeners() {
            document.querySelectorAll('.delete').forEach(function(element) {
                element.addEventListener('click', function() {
                    var userId = this.getAttribute('data-user-id');
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
                            document.getElementById('delete-form-' + userId).submit();
                        }
                    });
                });
            });
        }

        // Function untuk buat/buka modal
        function openEditModal(id){
            var modal = document.getElementById(`editContactModal${id}`);
            if (modal){
                modalBootstrap = new bootstrap.Modal(modal);
                modalBootstrap.show();
                return;
            }
            var modalDiv = document.getElementById('editModalDiv');
            var newEditModal = '';
            var user = users[id];
                var updateUrl = "{{ route('users.update', ':id') }}".replace(':id', user.id);
                newEditModal += `
                    <div class="modal fade" id="editContactModal${id}" tabindex="-1" role="dialog"
                        aria-labelledby="editContactModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header modal-colored-header bg-primary text-white">
                                    <h5 class="modal-title text-white">Edit User</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="${updateUrl}" method="POST">
                                    @csrf
                                    @method('PUT')
                                        <div class="add-contact-box">
                                            <div class="add-contact-content">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-name">
                                                            <div class="form-floating">
                                                                <input type="text" name="name" class="form-control" id="edit-name-${id}"
                                                                    placeholder="Name" value="${user.name}">
                                                                <label for="edit-name-${id}">Name</label>
                                                            </div>
                                                            <span class="validation-text text-danger"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-email">
                                                            <div class="form-floating">
                                                                <input type="email" name="email" class="form-control" id="edit-email-${id}"
                                                                    placeholder="Email" value="${user.email}">
                                                                <label for="edit-email-${id}">Email</label>
                                                            </div>
                                                            <span class="validation-text text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-nik">
                                                            <div class="form-floating">
                                                                <input type="text" name="nik" class="form-control" id="edit-nik-${id}"
                                                                    placeholder="NIK" value="${user.nik}">
                                                                <label for="edit-nik-${id}">NIK</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-department">
                                                            <label class="form-label fw-semibold">Department</label>
                                                            <select name="department_id" class="form-control select2">
                                                                <option value="">Select Department</option>
                                                                `;
                                                                departments.forEach(function(department){
                                                                    newEditModal +=`
                                                                    <option value="${department.id}" ${user.department_id == department.id ? 'selected' : ''}>
                                                                        ${department.name}
                                                                    </option>
                                                                    `;
                                                                });
                                                                newEditModal += `
                                                            </select>
                                                        </div>
                                                    </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-position">
                                                            <label class="form-label fw-semibold">Position</label>
                                                            <select name="position_id" class="form-control select2">
                                                                <option value="">Select Position</option>
                                                                `;
                                                                positions.forEach(function(position){
                                                                    newEditModal +=`
                                                                    <option value="${position.id}" ${user.position_id == position.id ? 'selected' : ''}>
                                                                        ${position.name}
                                                                    </option>
                                                                    `;
                                                                });
                                                                newEditModal += `
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-section">
                                                            <label class="form-label fw-semibold">Section</label>
                                                            <select name="section_id" class="form-select select2">
                                                                <option value="">Select Section</option>
                                                                `;
                                                                sections.forEach(function(section){
                                                                    newEditModal +=`
                                                                    <option value="${section.id}" ${user.section_id == section.id ? 'selected' : ''}>
                                                                        ${section.name}
                                                                    </option>
                                                                    `;
                                                                });
                                                                newEditModal += `
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-supervisor">
                                                            <label class="form-label fw-semibold">Atasan</label>
                                                            <select name="atasan_nik" class="form-select select2">
                                                                <option value="">Select Atasan</option>
                                                                `;
                                                                users.forEach(function(u){
                                                                    newEditModal +=`
                                                                    <option value="${u.nik}" ${user.atasan_nik == u.nik ? 'selected' : ''}>
                                                                        ${u.name}
                                                                    </option>
                                                                    `;
                                                                });
                                                                newEditModal += `
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-password">
                                                            <label class="form-label fw-semibold">Password</label>
                                                            <input type="password" name="password" class="form-control"
                                                                placeholder="Leave blank if not changing">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-role">
                                                            <label class="form-label fw-semibold">Role</label>
                                                            <select name="roles[]" class="form-select select2" multiple required>
                                                                <option value="">Select Role</option>
                                                                `;
                                                                roles.forEach(function(role){
                                                                    const isSelected = user.roles.some(function(userRole) {
                                                                        return userRole.name === role; // Cek apakah nama role cocok dengan role user
                                                                    });
                                                                    newEditModal += `
                                                                        <option value="${role}" ${isSelected ? 'selected' : ''}>
                                                                            ${role}
                                                                        </option>
                                                                    `;
                                                                });
                                                                newEditModal += `
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 contact-status">
                                                            <label class="form-label fw-semibold">Status</label>
                                                            <select name="status" class="form-select select2">
                                                                <option value="" disabled selected>Select Status</option>
                                                                `;
                                                                ["Active", "Vacant"].forEach(function(status){
                                                                    newEditModal += `
                                                                        <option value="${status}" ${user.status == status ? 'selected' : ''}>
                                                                            ${status}
                                                                        </option>
                                                                    `;
                                                                });
                                                                newEditModal += `
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="d-flex gap-6 m-0">
                                                <button type="submit" class="btn btn-success">Update</button>
                                                <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal"> Close
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#editModalDiv').append(newEditModal);
                // Initialize Select2 only for selects inside the newly created modal
                try {
                    $('#editContactModal' + id).find('.select2').select2('destroy');
                } catch (e) {
                    // ignore if not initialized
                }
                $('#editContactModal' + id).find('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Select',
                    allowClear: true,
                    dropdownParent: $('#editContactModal' + id)
                });

                // AJAX submit handler for the edit modal form
                $('#editContactModal' + id).find('form').on('submit', function(e) {
                    e.preventDefault();
                    var $form = $(this);
                    $.ajax({
                        url: $form.attr('action'),
                        method: 'POST',
                        data: $form.serialize(),
                        success: function(resp) {
                            if (resp.status === 'success') {
                                $('#editContactModal' + id).modal('hide');
                                Swal.fire('Success', resp.message, 'success');
                                if (window.userTable) window.userTable.ajax.reload(null, false);
                            } else {
                                Swal.fire('Error', resp.message || 'Failed to update user', 'error');
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

                modal = document.getElementById(`editContactModal${id}`);
                modalBootstrap = new bootstrap.Modal(modal);
                modalBootstrap.show();
        }

        // Function untuk konfirmasi delete user
        function confirmUserDelete(button){
                    var userId = button.getAttribute('data-user-id');
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
                            var form = document.getElementById('delete-form-' + userId);
                            if (!form) return;
                            var action = form.action;
                            var tokenInput = form.querySelector('input[name="_token"]');
                            var token = tokenInput ? tokenInput.value : '';
                            $.ajax({
                                url: action,
                                method: 'POST',
                                data: {
                                    _token: token,
                                    _method: 'DELETE'
                                },
                                success: function(resp) {
                                    if (resp.status === 'success') {
                                        Swal.fire('Deleted!', resp.message, 'success');
                                        if (window.userTable) window.userTable.ajax.reload(null, false);
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
