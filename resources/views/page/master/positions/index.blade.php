<x-app-layout>
    @section('title')
        Positions
    @endsection

    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Positions Management</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard') }}">
                        <i class="ph-duotone ph-house f-s-16"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Positions</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Tombol Add Positions --}}
            <div class="d-flex justify-content-end align-items-center mb-4">
                <button type="button" class="btn new-mpdr-btn" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                    <i class="ph-bold ph-plus"></i>
                    <span>Add Positions</span>
                </button>
            </div>

            {{-- Kontainer Tabel --}}
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-users"></i>
                        Positions List
                    </h4>
                    <p class="table-subtitle">Manage all registered users, their roles, and permissions.</p>
                </div>
                <div class="table-responsive">
                    <table class="w-100 display table table-striped" id="prempdrTable">
                        <thead class="header-item"> {{-- Header tabel asli dipertahankan --}}
                            <th>No</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Level</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Position -->
    <div class="modal fade" id="addPositionModal" tabindex="-1" role="dialog" aria-labelledby="addPositionModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary text-white">
                    <h5 class="modal-title text-white">Add Position</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('positions.store') }}" method="POST">
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
                                        <div class="mb-3">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="department_id" name="department_id">
                                                    <option value="">Select Department</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="department_id">Department</label>
                                            </div>
                                            <span class="validation-text text-danger"></span>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="level_id" name="level_id">
                                                    <option value="">Select Level</option>
                                                    @foreach ($levels as $level)
                                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="level_id">Level</label>
                                            </div>
                                            <span class="validation-text text-danger"></span>
                                        </div>
                                    </div>
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
            var positions;
            var table = $('#prempdrTable').DataTable({
                ajax: {
                    url: "{{ route('positions.index') }}",
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    dataSrc: function(response) {
                        positions = response.data || [];
                        return response.data || [];
                    },
                    error: function(xhr, status, error) {
                        console.error('Positions.data AJAX error:', xhr.status, error);
                        console.error('Response text:', xhr.responseText);
                        Swal.fire('Error', 'Invalid JSON response from server. See console.', 'error');
                    }
                },
                columns: [
                    { data: null, render: function(data, type, row, meta) { return meta.row + 1; } },
                    { data: 'name', name: 'name' },
                    { data: 'department.name', name: 'department' },
                    { data: 'level.name', name: 'level' },
                    { data: null, orderable: false, searchable: false, render: function(data, type, row, meta) {
                        var id = row.id;
                        var deleteUrl = "{{ route('positions.destroy', ':id') }}".replace(':id', id);
                        return `
                            <div class="d-flex action-btn">
                                <a href="javascript:void(0)" class="text-primary edit" onClick="openEditModal(${meta.row})">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                <form id="delete-form-${id}" action="${deleteUrl}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a href="javascript:void(0)" class="text-dark delete ms-2" data-position-id="${id}" onClick="confirmPositionDelete(this)">
                                        <i class="ti ti-trash fs-5"></i>
                                    </a>
                                </form>
                            </div>
                        `;
                    }}
                ]
            });

            window.positionTable = table;

            // Add Position via AJAX
            $(document).on('submit', '#addPositionModal form', function(e) {
                e.preventDefault();
                var $form = $(this);
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(resp) {
                        if (resp.status === 'success') {
                            $('#addPositionModal').modal('hide');
                            Swal.fire('Success', resp.message, 'success');
                            if (window.positionTable) window.positionTable.ajax.reload(null, false);
                            $form[0].reset();
                        } else {
                            Swal.fire('Error', resp.message || 'Failed to create position', 'error');
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
        });

        // Open edit modal dynamically
        function openEditModal(index) {
            var table = window.positionTable;
            var json = table.ajax.json() || {};
            var data = json.data || positions || [];
            var position = data[index];
            if (!position) {
                console.error('Edit modal: position not found for index', index, 'json:', json);
                Swal.fire('Error', 'Failed to load position data for edit. See console.', 'error');
                return;
            }
            var modalDiv = document.getElementById('editModalDiv');
            var id = position.id;
            var updateUrl = "{{ route('positions.update', ':id') }}".replace(':id', id);
            var newModal = `
                <div class="modal fade" id="editPositionModal${id}" tabindex="-1" role="dialog" aria-labelledby="editPositionModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header modal-colored-header bg-primary text-white">
                                <h5 class="modal-title text-white">Edit Position</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="${updateUrl}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="add-position-box">
                                        <div class="add-position-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3 position-name">
                                                        <input type="text" name="name" class="form-control" placeholder="Name" value="${position.name}">
                                                        <span class="validation-text text-danger"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-floating mb-3">
                                                            <select class="form-select" id="department_id" name="department_id">
                                                                <option value="">Select Department</option>
                                                                @foreach ($departments as $department)
                                                                    <option value="{{ $department->id }}" ${position.department_id == {{ $department->id }} ? 'selected' : ''}>{{ $department->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <label for="department_id">Department</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-floating mb-3">
                                                            <select class="form-select" id="level_id" name="level_id">
                                                                <option value="">Select Level</option>
                                                                @foreach ($levels as $level)
                                                                    <option value="{{ $level->id }}" ${position.level_id == {{ $level->id }} ? 'selected' : ''}>{{ $level->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <label for="level_id">Level</label>
                                                        </div>
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
            modalDiv.innerHTML = '';
            modalDiv.innerHTML = newModal;
            $('#editPositionModal' + id).find('form').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(resp) {
                        if (resp.status === 'success') {
                            $('#editPositionModal' + id).modal('hide');
                            Swal.fire('Success', resp.message, 'success');
                            if (window.positionTable) window.positionTable.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', resp.message || 'Failed to update position', 'error');
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
            var modal = document.getElementById('editPositionModal' + id);
            var bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }

        function confirmPositionDelete(element) {
            var positionId = element.getAttribute('data-position-id');
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
                    var form = document.getElementById('delete-form-' + positionId);
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
                                if (window.positionTable) window.positionTable.ajax.reload(null, false);
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
