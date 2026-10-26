<x-app-layout>
    @section('title')
        Report PREMPDR
    @endsection

    {{-- Menggunakan style tabel yang konsisten dari komponen --}}
    @include('components.mpdr-table-styles')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select/select2.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    {{-- Judul dan Breadcrumb --}}
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Pre-MPDR Report</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li>
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard') }}">
                        <i class="ph-duotone ph-house f-s-16"></i> Home
                    </a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Pre-MPDR Report</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- Filter --}}
            <div class="filter-action-bar">
                <div class="filter-group">
                    <span class="text-muted fw-bold text-nowrap filter-label"><i class="ph-bold ph-funnel me-1"></i>Filter by:</span>
                    <div class="select2-wrapper">
                        <select id="statusFilter" class="form-select select2">
                            <option value="all">All Statuses</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <button id="resetFilters" class="btn btn-secondary border flex-shrink-0" data-bs-toggle="tooltip" title="Reset Filters">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </button>
                </div>
            </div>

            {{-- Kontainer Tabel Baru (seperti list-prempdr) --}}
            <div class="main-table-container">
                <div class="table-header-enhanced">
                    <h4 class="table-title">
                        <i class="ph-duotone ph-file-text"></i>
                        Pre-MPDR Report List
                    </h4>
                    <p class="table-subtitle">View and print all completed or rejected Pre-MPDR submissions</p>
                </div>
                <div class="table-responsive">
                    <table class="w-100 display" id="prempdrTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Reg</th>
                                <th>Project Name</th>
                                <th>Submitted By</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
        <script script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        $(function () {
            // Inisialisasi Select2 untuk filter
            $('#statusFilter').select2({
                theme: 'bootstrap-5',
                minimumResultsForSearch: Infinity
            });

            // Inisialisasi DataTable
            const table = $('#prempdrTable').DataTable({
                processing: true,
                serverSide: false, // Data diambil sekali, lalu filter di client-side
                // order: [[ 3, 'desc' ]],
                ajax: {
                    url: "{{ route('prempdr.report.data') }}",
                    dataSrc: function(json) {
                        const statusFilter = $('#statusFilter').val();
                        if (statusFilter === 'all' || !statusFilter) {
                            return json;
                        }
                        // Filter data di sisi client berdasarkan pilihan status
                        return json.filter(item => item.status === statusFilter);
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '20px',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'no',
                        name: 'no',
                        render: data => `<span class="status-badge-lg bg-dark">${data}</span>`
                    },
                    {
                        data: 'project_name',
                        name: 'project_name',
                        render: data => `<div class="d-flex align-items-center">
                                            <i class="ph-bold ph-folder-simple me-2 text-primary fs-5"></i>
                                            <span>${data}</span>
                                        </div>`
                    },
                    {
                        data: 'submitted_by',
                        name: 'submitted_by',
                        render: function (data, type, row) {
                            let name = data;
                            if (!name || name === '-') {
                                const pos = (row.user && row.user.position) ? ` (${row.user.position.name})` : '';
                                name = row.user ? (row.user.name + pos) : '-';
                            }
                            return `<div class="d-flex align-items-center">
                                        <i class="ph-bold ph-user me-2 text-primary fs-5"></i>
                                        <span>${name}</span>
                                    </div>`;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            // Untuk sorting, kembalikan data mentah (timestamp)
                            if (type === 'sort') {
                                return data;
                            }

                            // Untuk display, kembalikan format yang cantik
                            if (!data) return '-';
                            const date = new Date(data);
                            const formattedDate = date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
                            return `<div class="d-flex align-items-center">
                                        <i class="ph ph-calendar-check me-2 text-primary fs-5"></i>
                                        <span>${formattedDate}</span>
                                    </div>`;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function (data) {
                            let statusClass = '';
                            switch(data) {
                                case 'Approved': statusClass = 'bg-success'; break;
                                case 'Rejected': statusClass = 'bg-danger'; break;
                            }
                            return `<span class="status-badge-lg ${statusClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'no',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data) {
                            const printRoute = "{{ route('prempdr.print', ':formId') }}".replace(':formId', data);
                            return `
                                <a href="${printRoute}" target="_blank" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="View & Print Report">
                                    <i class="ph-bold ph-printer"></i>
                                </a>`;
                        }
                    }
                ],
                "drawCallback": function( settings ) {
                    $('.tooltip').remove();
                    $('[data-bs-toggle="tooltip"]').each(function() {
                        const inst = bootstrap.Tooltip.getInstance(this);
                        if (inst) {
                            inst.dispose();
                        }
                        new bootstrap.Tooltip(this, {
                            trigger: 'hover',
                            animation: false,
                            boundary: 'window'
                        });
                    });
                }
            });

            // Event listener untuk filter status
            $('#statusFilter').on('change', function () {
                table.ajax.reload(); // Cukup reload ajax, filtering ditangani di dataSrc
            });

            // Event listener untuk reset filter
            $('#resetFilters').on('click', function() {
                $('#statusFilter').val('all').trigger('change');
            });

            // Logika untuk Debounce Search (membuat pencarian lebih efisien)
            let searchInput = $('#prempdrTable_filter input');
            searchInput.unbind();
            let debounceTimer;
            searchInput.bind('keyup', function (e) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    table.search(searchInput.val()).draw();
                }, 500); // Tunda pencarian 500ms setelah user berhenti mengetik
            });

            // Menambahkan placeholder dan class pada input search DataTables
            $('#prempdrTable_filter input').attr({
                'placeholder': '🔍 Search report...',
                'class': 'form-control'
            });
        });
        </script>
    @endpush
</x-app-layout>
