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
                    <table class="w-100 display table table-striped" id="prempdrTable">
                        <thead class="header-item">
                            <th>No</th>
                            <th>Form</th>
                            <th>Action</th>
                            <th>Description</th>
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
                $('#prempdrTable').DataTable({
                    order: [0, 'desc'],
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ route('logs.data') }}",
                        type: 'GET',
                        data: { form: 'mpdr' },
                        dataSrc: ''
                    },
                    columns: [
                        { data: null, name: 'no', render: (d,t,r,meta) => meta.row + 1 },
                        { data: 'properties.no', name: 'form' },
                        { data: 'event', name: 'action' },
                        { data: 'description', name: 'description' }
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
