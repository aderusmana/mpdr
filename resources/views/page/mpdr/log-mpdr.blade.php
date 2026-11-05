<x-app-layout>
    @section('title')
        Log MPDR
    @endsection

    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">MPDR Activity Log</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li><a class="f-s-14 f-w-500" href="{{ route('dashboard') }}"><i class="ph-duotone ph-house f-s-16"></i> Home</a></li>
                <li class="active"><a class="f-s-14 f-w-500" href="#">MPDR Log</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title"><i class="ph-duotone ph-timer"></i> MPDR Log</h4>
                    <p class="table-subtitle">Track all activities and changes related to MPDR forms.</p>
                </div>
                <div class="table-responsive">
                    <table class="w-100 display table table-striped" id="mpdrTable" data-mobile-title="Form">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Form</th>
                                <th>Action</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(function() {
                $('#mpdrTable').DataTable({
                    order: [0, 'desc'],
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ route('logs.data') }}",
                        type: 'GET',
                        data: { form: 'mpdr' },
                        dataSrc: function(response) { return Array.isArray(response) ? response : []; }
                    },
                    columns: [
                        { 
                            data: null, 
                            name: 'no', 
                            orderable: false, 
                            searchable: false,
                            defaultContent: '-',
                            createdCell: function(td) {
                                $(td).attr('data-label', 'No');
                            },
                            render: function(d, t, r, meta) { return meta.row + 1; }
                        },
                        { 
                            data: 'properties.no', 
                            name: 'form',
                            defaultContent: '-',
                            createdCell: function(td) {
                                $(td).attr('data-label', 'Form');
                            },
                            render: function(data, type, row) {
                                if (row && row.properties && row.properties.no) {
                                    return '<span class="badge bg-dark fw-bold">' + row.properties.no + '</span>';
                                }
                                if (row && row.subject_id) {
                                    return '<span class="badge bg-secondary">#' + row.subject_id + '</span>';
                                }
                                return '-';
                            }
                        },
                        { 
                            data: 'event', 
                            name: 'action',
                            defaultContent: '-',
                            createdCell: function(td) {
                                $(td).attr('data-label', 'Action');
                            },
                            render: function(data, type, row) {
                                var action = data || (row && row.properties && row.properties.action) || 'Activity';
                                var badgeClass = 'bg-primary';
                                var actLower = String(action).toLowerCase();
                                if (actLower.indexOf('create') !== -1) badgeClass = 'bg-success';
                                else if (actLower.indexOf('approve') !== -1) badgeClass = 'bg-info';
                                else if (actLower.indexOf('reject') !== -1) badgeClass = 'bg-danger';
                                else if (actLower.indexOf('update') !== -1) badgeClass = 'bg-warning text-dark';
                                return '<span class="badge ' + badgeClass + '">' + action + '</span>';
                            }
                        },
                        { 
                            data: 'description', 
                            name: 'description',
                            defaultContent: '-',
                            createdCell: function(td) {
                                $(td).attr('data-label', 'Description');
                            },
                            render: function(data, type, row) {
                                if (data && typeof data === 'string' && data.trim().length > 0) {
                                    return data;
                                }
                                var causer = (row && row.causer && (row.causer.name || row.causer.email)) ? (row.causer.name || row.causer.email) : 'System';
                                var eventName = (row && row.event) ? row.event : ((row && row.properties && row.properties.action) ? row.properties.action : 'Activity');
                                var formNo = (row && row.properties && row.properties.no) ? row.properties.no : (row && row.subject_id ? ('#' + row.subject_id) : '');
                                return eventName + (formNo ? (' MPDR ' + formNo) : '') + ' by ' + causer;
                            }
                        }
                    ],
                    drawCallback: function() {
                        if (typeof window.syncAllTableDataLabels === 'function') {
                            window.syncAllTableDataLabels();
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
