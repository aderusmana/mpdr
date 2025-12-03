<x-app-layout>
    @section('title')
        Log Pre-MPDR
    @endsection

    {{-- Menggunakan style tabel yang konsisten --}}
    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    {{-- Judul dan Breadcrumb - Mengadopsi style baru --}}
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Pre-MPDR Log</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard') }}">
                        <i class="ph-duotone ph-house f-s-16"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Pre-MPDR Log</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Kontainer Tabel Baru --}}
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-timer"></i>
                        Pre-MPDR Activity Log
                    </h4>
                    <p class="table-subtitle">Track all activities and changes related to Pre-MPDR forms</p>
                </div>
                <div class="table-responsive">
                    <table class="w-100 display table table-striped" id="prempdrTable"> {{-- Menambahkan class table-striped --}}
                        <thead>
                            <tr> {{-- Mengganti thead asli dengan style yang lebih konsisten --}}
                                <th>No</th>
                                <th>Form</th>
                                <th>Action</th>
                                <th>Description</th>
                            </thead>
                        <tbody>
                            {{-- Data diisi oleh DataTables --}}
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
                const table = $('#prempdrTable').DataTable({
                    order: [0, 'desc'],
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ route('logs.data') }}",
                        type: 'GET',
                        data: { form: 'prempdr' },
                        dataSrc: function(response) { return response; }
                    },
                    columns: [
                        { data: null, name: 'no', orderable: false, searchable: false,
                          render: function(data, type, row, meta) { return meta.row + 1; }
                        },
                        { data: null, name: 'form',
                          render: function(data, type, row) { return row.properties.no; }
                        },
                        { data: 'event', name: 'action' },
                        { data: 'description', name: 'description' }
                    ],
                });

                // [PERBAIKAN] Menambahkan placeholder dan class pada input search
                $('#prempdrTable_filter input').attr({
                    'placeholder': '🔍 Search logs...',
                    'class': 'form-control'
                });
            });
        </script>
    @endpush
</x-app-layout>
