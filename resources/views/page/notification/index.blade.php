<x-app-layout>
    @section('title')
        Notifications
    @endsection

    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    {{-- Judul dan Breadcrumb --}}
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Notification Center</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard') }}">
                        <i class="ph-duotone ph-house f-s-16"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Notifications</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Header Actions --}}
            <div class="d-flex justify-content-end align-items-center gap-2 mb-4">
                <button type="button" id="btn-mark-all-read-page" class="btn btn-outline-success btn-sm d-flex align-items-center gap-1">
                    <i class="fa fa-check-double"></i> {{ __('Mark All as Read') }}
                </button>
                <button type="button" id="btn-clear-all-page" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
                    <i class="fa fa-trash-alt"></i> {{ __('Clear All Notifications') }}
                </button>
            </div>

            {{-- Notification Table --}}
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-bell"></i>
                        Notification List
                    </h4>
                    <p class="table-subtitle">View and manage your system and approval notifications.</p>
                </div>
                <div class="table-responsive">
                    <table id="notifications-table" class="w-100 display table table-striped align-middle">
                        <thead class="header-item">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 110px;">Status</th>
                                <th style="width: 130px;">Type</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th style="width: 170px;">Date</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document.body).ready(function() {
            var table = $('#notifications-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('notifications.index') }}",
                    type: "GET"
                },
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
                    columns: [1, 2, 3, 4, 5]
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'status', name: 'read_at', orderable: true, searchable: true },
                    { data: 'type_label', name: 'type', orderable: true, searchable: true },
                    { data: 'title', name: 'data', orderable: true, searchable: true },
                    { data: 'message', name: 'data', orderable: true, searchable: true },
                    { data: 'created_at_formatted', name: 'created_at', orderable: true, searchable: true },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']]
            });

            // Mark single notification as read (per line)
            $(document).on('click', '.mark-single-read', function() {
                var notificationId = $(this).data('id');
                $.ajax({
                    url: "{{ route('notifications.markAsRead') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        notification_id: notificationId
                    },
                    success: function(res) {
                        table.ajax.reload(null, false);
                        if (typeof fetchNotifications === 'function') {
                            fetchNotifications();
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error', 'Failed to mark the notification as read.', 'error');
                    }
                });
            });

            // Delete single notification
            $(document).on('click', '.delete-single-notification', function() {
                var notificationId = $(this).data('id');
                Swal.fire({
                    title: 'Delete Notification?',
                    text: 'This notification will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/notifications/" + notificationId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                table.ajax.reload(null, false);
                                if (typeof fetchNotifications === 'function') {
                                    fetchNotifications();
                                }
                            },
                            error: function(err) {
                                Swal.fire('Error', 'Failed to delete the notification.', 'error');
                            }
                        });
                    }
                });
            });

            // Mark all as read button
            $('#btn-mark-all-read-page').on('click', function() {
                $.ajax({
                    url: "{{ route('notifications.markAllAsRead') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        table.ajax.reload(null, false);
                        if (typeof fetchNotifications === 'function') {
                            fetchNotifications();
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'All notifications have been marked as read.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                });
            });

            // Clear all notifications button
            $('#btn-clear-all-page').on('click', function() {
                Swal.fire({
                    title: 'Clear All Notifications?',
                    text: 'All your notifications will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Yes, Clear All'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('notifications.clear') }}",
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                table.ajax.reload(null, false);
                                if (typeof fetchNotifications === 'function') {
                                    fetchNotifications();
                                }
                                Swal.fire('Success', 'All notifications have been deleted.', 'success');
                            }
                        });
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
