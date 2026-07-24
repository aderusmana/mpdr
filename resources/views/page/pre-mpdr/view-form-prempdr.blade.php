@php
    $category = $formData->category->category ?? '-';
    $categoryOther = $formData->category->other ?? '';
    $fullCategory = $category . ($categoryOther ? " ($categoryOther)" : '');

    $channelCategory = $formData->channel->category ?? '-';
    $countryName = $formData->channel->country ?? '';
    $fullChannel = $channelCategory . ($countryName ? " ($countryName)" : '');

    $certCategory = $formData->certification->category ?? '-';
    $certOtherText = $formData->certification->other ?? '';
    $fullCert = $certCategory . ($certOtherText ? " ($certOtherText)" : '');

    $packagingType = $formData->packaging->category ?? '-';
    $packagingDesc = $formData->packaging->detail ?? '';
@endphp

<div class="prempdr-detail-container p-1">

    <!-- 1. EXECUTIVE HEADER BANNER (SOFT CORPORATE PALETTE) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-top: 4px solid #0284c7 !important;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">
                        <i class="ph-bold ph-file-text" style="font-size: 2.2rem;"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 font-monospace fw-bold fs-6 border border-primary border-opacity-25">{{ $formData->no }}</span>
                            <span class="badge bg-light text-dark px-2 py-1 font-monospace border">REV {{ optional($formData->revision)->no ?? '0' }}</span>
                            @php
                                $statusClass = match($formData->status) {
                                    'Approved' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                    'Rejected' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                    'In Approval' => 'bg-warning bg-opacity-15 text-dark border border-warning border-opacity-25',
                                    default => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} px-3 py-1 fs-6 fw-bold">{{ $formData->status }}</span>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $formData->project_name }}</h4>
                        <div class="text-secondary small mt-1">Proposed Brand: <strong class="text-dark">{{ $formData->brand_name ?? 'N/A' }}</strong></div>
                    </div>
                </div>
                <div class="text-end">
                    <div class="text-secondary small mb-1 fw-semibold">Priority Level</div>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 fs-6 fw-bold"><i class="ph-bold ph-fire me-1"></i> {{ $formData->level_priority ?? 'Normal' }}</span>
                    <div class="text-muted small mt-2">Updated: {{ $formData->updated_at ? $formData->updated_at->diffForHumans() : '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. QUICK METRIC KPI CARDS (SOFT PASTEL FILLS) -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 border-start border-4 border-primary h-100" style="background-color: #f0f7ff; border-color: #dbeafe;">
                <div class="text-secondary small fw-bold text-uppercase mb-1"><i class="ph-bold ph-tag me-1 text-primary"></i> Category</div>
                <div class="fw-bold fs-6 text-dark">{{ $fullCategory }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 border-start border-4 border-info h-100" style="background-color: #f0fdfa; border-color: #ccfbf1;">
                <div class="text-secondary small fw-bold text-uppercase mb-1"><i class="ph-bold ph-globe me-1 text-info"></i> Channel</div>
                <div class="fw-bold fs-6 text-dark">{{ $fullChannel }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 border-start border-4 border-warning h-100" style="background-color: #fffbeb; border-color: #fef3c7;">
                <div class="text-secondary small fw-bold text-uppercase mb-1"><i class="ph-bold ph-calendar me-1 text-warning"></i> Target Launch</div>
                <div class="fw-bold fs-6 text-dark">{{ $formData->detail->target_launch ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 border-start border-4 border-success h-100" style="background-color: #f0fdf4; border-color: #dcfce7;">
                <div class="text-secondary small fw-bold text-uppercase mb-1"><i class="ph-bold ph-shield-check me-1 text-success"></i> Certification</div>
                <div class="fw-bold fs-6 text-dark">{{ $fullCert }}</div>
            </div>
        </div>
    </div>

    <!-- 3. RATIONAL FOR DEVELOPMENT CARD -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="ph-bold ph-target text-primary fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark">Rational For Development</h6>
        </div>
        <div class="card-body bg-slate-50 p-3">
            <div class="p-3 bg-white rounded-3 border-start border-4 border-primary shadow-sm text-secondary fs-6 lh-base">
                {{ $formData->detail->rational_for_development ?? 'No rational provided.' }}
            </div>
        </div>
    </div>

    <!-- 4. PRODUCT SPECIFICATIONS CARD -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="ph-bold ph-cube text-info fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark">General Product Description & Function</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <div class="fw-bold small text-muted text-uppercase mb-2"><i class="ph-bold ph-note-pencil me-1"></i> Product Description (Form/Color/Flavor)</div>
                        <div class="text-dark fs-6">{!! $formData->description->product_description ?? 'N/A' !!}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <div class="fw-bold small text-muted text-uppercase mb-2"><i class="ph-bold ph-users me-1"></i> Usage Description (Application in customer)</div>
                        <div class="text-dark fs-6">{!! $formData->description->usage_description ?? 'N/A' !!}</div>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 bg-white border rounded-3 gap-3">
                        <div class="p-2 bg-info bg-opacity-10 text-info rounded-circle"><i class="ph-bold ph-thermometer-cold fs-4"></i></div>
                        <div>
                            <div class="small text-muted fw-bold">Storage Temperature</div>
                            <div class="fw-bold text-dark fs-6">{{ $formData->description->storage_temperature ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 bg-white border rounded-3 gap-3">
                        <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle"><i class="ph-bold ph-truck fs-4"></i></div>
                        <div>
                            <div class="small text-muted fw-bold">Delivery Temperature</div>
                            <div class="fw-bold text-dark fs-6">{{ $formData->description->delivery_temperature ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. COMPETITOR PRODUCTS TABLE -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="ph-bold ph-trophy text-warning fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark">Competitor's Product to Match or to Beat</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Product Name</th>
                            <th>Size</th>
                            <th>Packaging</th>
                            <th class="pe-4">Price Indication</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($formData->competitor as $item)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $item->name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item->size }}</span></td>
                            <td>{{ $item->packaging }}</td>
                            <td class="pe-4 fw-bold text-success">{{ $item->price }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No competitor product specified.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 6. PACKAGING & MARKET STRATEGY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="ph-bold ph-package text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">Packaging Details</h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <div class="small text-muted fw-bold mb-1">Weight of Product:</div>
                        <div class="p-2 bg-light rounded border fw-bold text-dark">{{ $formData->packaging->weight ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted fw-bold mb-1">Packaging Category & Details:</div>
                        <div class="p-2 bg-light rounded border">
                            <span class="badge bg-primary me-2">{{ $packagingType }}</span>
                            <span class="text-dark">{{ $packagingDesc ?: 'No extra details' }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="small text-muted fw-bold mb-1">Product Variation List:</div>
                        <div class="p-2 bg-light rounded border text-dark">{{ $formData->packaging->product_variation ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="ph-bold ph-chart-line-up text-success fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">Market Update & Pricing Strategy</h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <div class="small text-muted fw-bold mb-1">Potential Volume (Mt/Annually):</div>
                        <div class="p-2 bg-light rounded border fw-bold text-dark fs-6">{{ $formData->market->potential_volume ?? 'N/A' }}</div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded border">
                                <div class="small text-muted fw-bold">Expected Margin</div>
                                <div class="fw-bold text-success fs-6">{{ $formData->market->expected_margin ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded border">
                                <div class="small text-muted fw-bold">Price Estimate</div>
                                <div class="fw-bold text-primary fs-6">{{ $formData->market->price_estimate ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. APPROVAL WORKFLOW STATUS -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="ph-bold ph-checks text-success fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark">Approval Workflow Progress</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Approver Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Date Approved</th>
                            <th class="pe-4">Notes / Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($approver as $item)
                        @php
                            $appStatusBadge = match($item->status) {
                                'approve' => 'bg-success',
                                'approve with review' => 'bg-warning text-dark',
                                'not approve', 'not approved' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">{{ $item->name }}</td>
                            <td class="text-center">
                                <span class="badge {{ $appStatusBadge }} px-3 py-1">{{ ucfirst($item->status ?? 'pending') }}</span>
                            </td>
                            <td class="text-center text-muted small">
                                {{ $item->approved_date ? \Carbon\Carbon::parse($item->approved_date)->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="pe-4 text-muted small fst-italic">
                                {{ $item->comment ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No approval details found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
