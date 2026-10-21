<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>MPDR Report - {{ $form->no }}</title>
    <style>
        /* Pengaturan Font dan Halaman Global */
        @page {
            margin: 25px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }

        /* Kontainer Utama dengan Border Hitam */
        .container {
            border: 1px solid #000000;
            border-radius: 6px;
            width: 100%;
        }

        /* Header Dokumen */
        .header-table {
            width: 100%;
            border-bottom: 1px solid #000000;
        }
        .header-table td {
            padding: 15px;
            vertical-align: middle;
        }
        .logo {
            width: 130px;
        }
        .doc-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        .doc-title span {
            display: block;
            font-size: 14px;
            font-weight: normal;
            color: #2f2c2cff;
            margin-top: 4px;
        }
        .doc-info {
            font-size: 9px;
            text-align: right;
        }
        .doc-info b {
            display: inline-block;
            width: 80px;
            color: #000;
        }
        .doc-info div {
            padding-bottom: 3px;
        }

        /* Konten Utama */
        .main-content {
            padding: 5px 20px 20px 20px;
        }

        /* Judul Seksi */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 2px;
            margin-bottom: 2px;
            padding: 8px 12px;
            background-color: #004a99;
            color: #ffffff;
            border-radius: 4px;
        }
        .section-title:first-child {
            margin-top: 15px;
        }

        /* Tabel Info Multi-kolom */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
            page-break-inside: avoid;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            /* border-bottom: 1px solid #f0f0f0; */
        }
        .info-label {
            font-size: 9px;
            font-weight: bold;
            color: #333;
            margin-bottom: 2px;
            display: block;
        }

        .info-value {
             font-size: 11px;
             color: #000;
             padding: 8px 10px;
             background-color: #f5f5f5; /* Background abu-abu terang */
             border: 1px solid #eeeeee; /* Border tipis sekeliling */
             border-left: 3px solid #004a99; /* Garis pembatas biru di kiri */
             border-radius: 3px;
        }

        /* Tabel Detail (Kompetitor & Approval) */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table th, .detail-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        .detail-table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
            text-align: center;
        }
        .detail-table td {
            text-align: center;
            font-size: 10px;
        }
        .detail-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .detail-table tbody tr {
             page-break-inside: avoid;
        }
        .notes {
            text-align: left;
            font-style: italic;
            font-size: 9px;
        }

        /* Kontainer Approval untuk halaman baru */
        .approval-container {
            page-break-before: always;
        }

        /* Footer Halaman */
        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 9px;
            color: #777;
        }
        .page-number:before {
            content: "Page " counter(page);
        }
    </style>
</head>
@php
    $logoPath = public_path('assets/images/logo/sinarmeadow.png');
    $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
@endphp
<body>
    <div class="footer">
        MPDR Report - {{ $form->no }} | <span class="page-number"></span>
    </div>

    <div class="container">
        <header class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 20%;"><img src="{{ $logoBase64 }}" alt="Logo" class="logo" style="max-width: 140px; max-height: 55px;"></td>
                    <td class="doc-title" style="width: 55%;">Marketing Product Development Request
                        <span>PRODUCT DEVELOPMENT REQUEST FORM</span>
                    </td>
                    <td class="doc-info" style="width: 25%;">
                        <div><b>No. Reg</b>: {{ $form->no }}</div>
                        <div><b>Revision No</b>: {{ optional($form->revision)->no ?? 'N/A' }}</div>
                        <div><b>Date</b>: {{ $form->created_at->format('d-M-Y') }}</div>
                        <div><b>Submitted By</b>: {{ optional($form->user)->name ?? optional($form->requester)->name ?? $form->initiator ?? 'N/A' }}</div>
                    </td>
                </tr>
            </table>
        </header>

        <main class="main-content">
            <div class="section-title">1. Basic Information</div>
            <table class="info-table">
                <tr>
                    <td style="width: 30%;">
                        <div class="info-label">Product Name</div>
                        <div class="info-value">{{ $form->product_name }}</div>
                    </td>
                    <td style="width: 20%;">
                        <div class="info-label">Priority Level</div>
                        <div class="info-value">{{ $form->level_priority }}</div>
                    </td>
                    <td style="width: 25%;">
                        <div class="info-label">Initiator Name</div>
                        <div class="info-value">{{ $form->initiator ?? optional($form->requester)->name ?? 'N/A' }}</div>
                    </td>
                    <td style="width: 25%;">
                        <div class="info-label">Submitted By</div>
                        <div class="info-value">{{ optional($form->user)->name ?? optional($form->requester)->name ?? $form->initiator ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="info-label">Rational for Development</div>
                        <div class="info-value">{{ optional($form->detail)->rational_for_development }}</div>
                    </td>
                </tr>
            </table>

            <div class="section-title">2. Product Specifications</div>
            <table class="info-table">
                 <tr>
                    <td style="width: 50%;">
                        <div class="info-label">Product Category</div>
                        <div class="info-value">{{ optional($form->category)->category }}{{ optional($form->category)->category == 'Others' ? ': ' . optional($form->category)->other : '' }}</div>
                    </td>
                    <td style="width: 50%;">
                        <div class="info-label">Channel</div>
                        <div class="info-value">{{ optional($form->channel)->category }}{{ str_contains(optional($form->channel)->category ?? '', 'International') && optional($form->channel)->country ? ' (' . optional($form->channel)->country . ')' : '' }}</div>
                    </td>
                </tr>
                 <tr>
                    <td colspan="2">
                        <div class="info-label">Product Description (Form/Color/Flavor brief)</div>
                        <div class="info-value">{{ optional($form->description)->product_description }}</div>
                    </td>
                </tr>
                 <tr>
                    <td colspan="2">
                        <div class="info-label">Usage Description (Application in customer)</div>
                        <div class="info-value">{{ optional($form->description)->usage_description }}</div>
                    </td>
                </tr>
                 <tr>
                    <td style="width: 50%;">
                        <div class="info-label">Storage Temperature</div>
                        <div class="info-value">{{ optional($form->description)->storage_temperature }}</div>
                    </td>
                    <td style="width: 50%;">
                        <div class="info-label">Delivery Temperature</div>
                        <div class="info-value">{{ optional($form->description)->delivery_temperature }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="info-label">Certification Requirement</div>
                        <div class="info-value">{{ optional($form->certification)->category }}{{ str_contains(optional($form->certification)->category ?? '', 'Others') && optional($form->certification)->other ? ' (' . optional($form->certification)->other . ')' : '' }}</div>
                    </td>
                </tr>
            </table>

            <div class="section-title">3. Competitor's Product to Match or to Beat</div>
            <table class="detail-table">
                <thead><tr><th style="width:5%;">#</th><th>Product Name</th><th>Size</th><th>Packaging</th><th>Price Indication</th></tr></thead>
                <tbody>
                    @forelse($form->competitor as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="text-align: left;">{{ $item->name }}</td>
                        <td>{{ $item->size }}</td>
                        <td>{{ $item->packaging }}</td>
                        <td>{{ $item->price }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; font-style: italic; color: #888;">
                            No competitor product data provided.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="section-title">4. Packaging, Market & Target</div>
            <table class="info-table">
                <tr>
                    <td style="width: 50%;">
                        <div class="info-label">Weight of Product (kg/carton)</div>
                        <div class="info-value">{{ optional($form->packaging)->weight }}</div>
                    </td>
                     <td style="width: 50%;">
                        <div class="info-label">Packaging Type</div>
                        <div class="info-value">{{ optional($form->packaging)->category }}{{ optional($form->packaging)->detail ? ': ' . optional($form->packaging)->detail : '' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="info-label">Product Variation List</div>
                        <div class="info-value">{{ optional($form->packaging)->product_variation }}</div>
                    </td>
                </tr>
                 <tr>
                    <td style="width: 33%;">
                        <div class="info-label">Potential Volume (Mt/Annually)</div>
                        <div class="info-value">{{ optional($form->market)->potential_volume }}</div>
                    </td>
                    <td style="width: 33%;">
                        <div class="info-label">Expected Margin (%)</div>
                        <div class="info-value">{{ optional($form->market)->expected_margin }}%</div>
                    </td>
                    <td style="width: 34%;">
                        <div class="info-label">Price Estimate</div>
                        <div class="info-value">{{ optional($form->market)->price_estimate }}</div>
                    </td>
                </tr>
                 <tr>
                    <td colspan="3">
                        <div class="info-label">Target Launch Request</div>
                        <div class="info-value">{{ optional($form->detail)->target_launch }}</div>
                    </td>
                </tr>
            </table>
        </main>
    </div>

    <div class="approval-container">
        <div class="section-title" style="margin-top: 0;">Approval History</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Approved by</th>
                    <th style="width: 20%;">Status</th>
                    <th style="width: 15%;">Date</th>
                    <th style="width: 40%;">Notes / Comments</th>
                </tr>
            </thead>
            <tbody>
                @forelse($form->approvedDetail as $detail)
                @php
                    $approverJabatan = optional($detail->user?->position)->name
                        ?: \App\Models\User::with('position')->where('nik', $detail->approver_nik)->first()?->position?->name
                        ?: ($detail->level ? 'Level ' . $detail->level . ' Approver' : 'Approver');
                @endphp
                <tr>
                    <td style="text-align: left;">
                        <b>{{ $approverJabatan }}</b>
                        <br><small style="color: #666;">{{ $detail->level ? 'Level ' . $detail->level . ' Approver' : 'Approver' }}</small>
                    </td>
                    <td>
                        @if($detail->status == 'approve') <b style="color: green;">Approved</b>
                        @elseif($detail->status == 'approve with review') <b style="color: #f5a623;">Approved with Review</b>
                        @elseif($detail->status == 'not approve') <b style="color: red;">Not Approved</b>
                        @else <span style="color: #888;">Pending</span>
                        @endif
                    </td>
                    <td>{{ $detail->approved_date ? \Carbon\Carbon::parse($detail->approved_date)->format('d-M-Y') : '-' }}</td>
                    <td class="notes">{{ $detail->comment ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4">No approval data found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
