<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-MPDR Approval Comment - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .05);
        }

        .card-header.main-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 16px 16px 0 0 !important;
        }

        .card-header.main-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card-header.main-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 28px 0 16px;
            padding: 12px 14px;
            border-left: 4px solid #2c3e50;
            border-radius: 8px;
            background: #f3f6fa;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .info-label {
            color: #8a96a3;
            font-size: .85em;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .info-value {
            color: #212529;
            font-weight: 600;
            font-size: 1rem;
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        .detail-grid > div {
            padding: 12px;
            border: 1px solid #e9eef5;
            border-radius: 8px;
            background: #fff;
        }

        .detail-grid .info-label {
            text-transform: uppercase;
            letter-spacing: .04em;
            font-size: .75rem;
        }

        .history-table th {
            color: #475569;
            background: #f3f6fa;
            white-space: nowrap;
        }

        .history-comment {
            min-width: 180px;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .requester-note {
            border: 1px solid #dbe4ef;
            border-radius: 8px;
            background: #f8fafc;
            color: #475569;
            padding: 12px 14px;
            margin-bottom: 18px;
        }

        .form-label {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 62, 80, 0.3);
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
        }

        .processing-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, .9);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .processing-overlay .spinner-border {
            color: #2c3e50 !important;
            width: 3rem;
            height: 3rem;
        }

        .comment-type-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .badge-review {
            background-color: #ffc107;
            color: #000;
        }

        .badge-reject {
            background-color: #dc3545;
            color: white;
        }

        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .main-container {
            display: grid;
            grid-template-columns: 2.5fr 1fr;
            gap: 30px;
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .action-card {
            position: sticky;
            top: 40px;
        }

        .left-column > .card {
            margin-bottom: 30px;
        }

        .status-badge {
            font-size: 0.9em;
            padding: 0.5em 0.7em;
            font-weight: 700;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }

        @media (max-width:1024px) {
            .main-container {
                grid-template-columns: 1fr;
            }
            .action-card {
                position: relative;
                top: 0;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="left-column">
            <div class="card">
                <div class="card-header main-header">
                    <h4 class="mb-0">Pre-MPDR Approval Review</h4>
                    <p class="mb-0 opacity-75">Registration No. {{ $form_no }} &nbsp;|&nbsp; Revision {{ $form->revision->revision ?? 'N/A' }}</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Form Information</h5>
                    <div class="row g-3 mb-4 detail-grid">
                        <div class="col-md-4">
                            <div class="info-label">Registration Number</div>
                            <div class="info-value">{{ $form->no }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Project Name</div>
                            <div class="info-value">{{ $form->project_name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Brand Name</div>
                            <div class="info-value">{{ $form->brand_name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Level Priority</div>
                            <div class="info-value">
                                @if($form->level_priority == 'low')
                                    <span class="badge bg-success">Low</span>
                                @elseif($form->level_priority == 'medium')
                                    <span class="badge bg-warning text-dark">Medium</span>
                                @elseif($form->level_priority == 'high')
                                    <span class="badge bg-danger">High</span>
                                @else
                                    <span class="badge bg-secondary">{{ $form->level_priority ?? 'N/A' }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Requester</div>
                            <div class="info-value">{{ $form->user->name ?? 'N/A' }}{{ !empty($form->user?->position?->name) ? ' (' . $form->user->position->name . ')' : '' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Created Date</div>
                            <div class="info-value">{{ $form->created_at?->format('d M Y') ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Last Updated</div>
                            <div class="info-value">{{ $form->updated_at?->format('d M Y, H:i') ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Revision</div>
                            <div class="info-value">{{ $form->revision->revision ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-box"></i> Product Overview</h5>
                    <div class="row g-3 mb-4 detail-grid">
                        <div class="col-md-6">
                            <div class="info-label">Product Category</div>
                            <div class="info-value">{{ $form->category->category ?? 'N/A' }}@if($form->category?->other) ({{ $form->category->other }})@endif</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Target Launch Date</div>
                            <div class="info-value">{{ $form->detail->target_launch ?? 'N/A' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="info-label">Development Rational</div>
                            <div class="info-value">{{ $form->detail->rational_for_development ?? 'N/A' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="info-label">Sales Channel</div>
                            <div class="info-value">{{ $form->channel->category ?? 'N/A' }}@if($form->channel?->country) ({{ $form->channel->country }})@endif</div>
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-align-left"></i> Product Description and Usage</h5>
                    <div class="row g-3 mb-4 detail-grid">
                        <div class="col-md-6">
                            <div class="info-label">Product Description (Form, Color, or Flavor)</div>
                            <div class="info-value">{{ $form->description->product_description ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Intended Use / Customer Application</div>
                            <div class="info-value">{{ $form->description->usage_description ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Storage Temperature</div>
                            <div class="info-value">{{ $form->description->storage_temperature ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Delivery Temperature</div>
                            <div class="info-value">{{ $form->description->delivery_temperature ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-certificate"></i> Certification Requirements</h5>
                    <div class="row g-3 mb-4 detail-grid">
                        <div class="col-md-6">
                            <div class="info-label">Certification</div>
                            <div class="info-value">{{ $form->certification->category ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Additional Certification Details</div>
                            <div class="info-value">{{ $form->certification->other ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-box-open"></i> Packaging Details</h5>
                    <div class="row g-3 mb-4 detail-grid">
                        <div class="col-md-6">
                            <div class="info-label">Packaging Type</div>
                            <div class="info-value">{{ $form->packaging->category ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Packaging Weight</div>
                            <div class="info-value">{{ $form->packaging->weight ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Packaging Details</div>
                            <div class="info-value">{{ $form->packaging->detail ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Product Variation</div>
                            <div class="info-value">{{ $form->packaging->product_variation ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-chart-pie"></i> Market Estimates</h5>
                    <div class="row g-3 mb-4 detail-grid">
                        <div class="col-md-4">
                            <div class="info-label">Potential Volume</div>
                            <div class="info-value">{{ $form->market->potential_volume ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Expected Margin</div>
                            <div class="info-value">{{ $form->market->expected_margin ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Estimated Price</div>
                            <div class="info-value">{{ \App\Helpers\PriceHelper::format($form->market->price_estimate ?? null) }}</div>
                        </div>
                    </div>

                    @if($form->competitor && $form->competitor->count() > 0)
                    <h5 class="section-title"><i class="fas fa-chart-line"></i> Competitor Products</h5>
                    <p class="text-muted small">Competitor information provided with this request.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm history-table">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>Size</th>
                                    <th>Packaging</th>
                                    <th>Price Indication</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($form->competitor as $index => $comp)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $comp->name ?? '-' }}</td>
                                    <td>{{ $comp->size ?? '-' }}</td>
                                    <td>{{ $comp->packaging ?? '-' }}</td>
                                    <td>{{ \App\Helpers\PriceHelper::format($comp->price ?? null, '-') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <h5 class="section-title"><i class="fas fa-chart-line"></i> Competitor Products</h5>
                    <div class="requester-note">No competitor products were provided with this request.</div>
                    @endif

                    <h5 class="section-title"><i class="fas fa-tasks"></i> Workflow Status</h5>
                    <div class="row g-3 detail-grid">
                        <div class="col-md-12">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                @if($form->status == 'Draft')
                                    <span class="badge status-badge bg-secondary">Draft</span>
                                @elseif($form->status == 'In Approval')
                                    <span class="badge status-badge bg-info text-white">In Approval</span>
                                @elseif($form->status == 'Approved')
                                    <span class="badge status-badge bg-success">Approved</span>
                                @elseif($form->status == 'Rejected')
                                    <span class="badge status-badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge status-badge bg-secondary">{{ $form->status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-list-check"></i> Approval Workflow</h5>
                    <div class="table-responsive">
                            <table class="table table-bordered table-sm history-table align-middle">
                                <thead>
                                    <tr><th>Approver</th><th>Role</th><th>Status</th><th>Decision Date</th><th>Comment</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($form->approvedDetail->sortBy([['level', 'asc'], ['id', 'asc']]) as $approval)
                                        @php
                                            $approvalStatus = strtolower(trim($approval->status ?? 'pending'));
                                            $approvalLabel = match($approvalStatus) {
                                                'approve', 'approved' => 'Approved',
                                                'approve with review' => 'Approved with review',
                                                'not approve', 'rejected' => 'Not approved',
                                                'vacant' => 'Vacant',
                                                default => 'Pending',
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ $approval->name ?? $approval->approver ?? 'N/A' }}</td>
                                            <td>{{ $approval->approver_position ?? 'Level ' . ($approval->level ?? 'N/A') }}</td>
                                            <td>{{ $approvalLabel }}</td>
                                            <td>{{ $approval->approved_date ?? '—' }}</td>
                                            <td class="history-comment">{{ $approval->comment ?: '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted">No approval history is available.</td></tr>
                                    @endforelse
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="card action-card">
                <div class="card-body p-4">
                    <h5 class="section-title">
                        <i class="fas fa-comment-dots"></i> 
                        @if($route_name == 'approveWithReview')
                            Approve with Review
                        @else
                            Rejection Comment
                        @endif
                    </h5>
                    <div class="requester-note">
                        <div><strong>Reviewer:</strong> {{ $approver->name ?? 'N/A' }}</div>
                        <div><strong>Request:</strong> {{ $form->no }}</div>
                    </div>

                    <div class="mb-3">
                        @if($route_name == 'approveWithReview')
                        <span class="comment-type-badge badge-review w-100 d-block text-center">
                            <i class="fas fa-check-circle"></i> Approve with Review
                        </span>
                        @elseif($route_name == 'notApprove')
                        <span class="comment-type-badge badge-reject w-100 d-block text-center">
                            <i class="fas fa-times-circle"></i> Reject Request
                        </span>
                        @endif
                    </div>

                    <div class="info-box mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Important:</strong> Your comment will be sent to the requester and recorded in the approval history.
                    </div>

                    <form action="{{ route('prempdr.comment', ['form_no' => $form_no, 'approver_nik' => $approver_nik, 'status' => $status, 'token' => $token]) }}" method="POST" id="commentForm">
                        @csrf
                        <div class="mb-4">
                            <label for="comment" class="form-label">
                                Comment <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                class="form-control" 
                                name="comment" 
                                id="comment" 
                                rows="10" 
                                required
                                placeholder="Please provide your detailed comment here..."></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Minimum 10 characters required
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i> Submit Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="processing-overlay" id="processingOverlay">
        <div class="spinner-border" role="status"></div>
        <p class="mt-3 fw-semibold">Processing your comment...</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('commentForm');
            const overlay = document.getElementById('processingOverlay');
            const submitBtn = document.getElementById('submitBtn');
            const commentTextarea = document.getElementById('comment');

            if (form && submitBtn) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const commentValue = commentTextarea.value.trim();
                    
                    if (commentValue.length < 10) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Comment Too Short',
                            text: 'Please provide at least 10 characters for your comment.'
                        });
                        return;
                    }

                    if (!/[a-zA-Z]/.test(commentValue)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Comment',
                            text: 'Please provide valid comment with at least some text.'
                        });
                        return;
                    }

                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`;

                    Swal.fire({
                        title: 'Confirm Submission',
                        text: 'Are you sure you want to submit this comment?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2c3e50',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Submit',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            overlay.style.display = 'flex';
                            const formData = new FormData(form);
                            fetch(form.action, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Server returned ' + response.status);
                                }
                                return response.text();
                            })
                            .then(html => {
                                document.open();
                                document.write(html);
                                document.close();
                            })
                            .catch(error => {
                                console.error('Submission error:', error);
                                overlay.style.display = 'none';
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Submission Error',
                                    text: 'Could not submit your comment. Please try again.'
                                });
                            });
                        } else {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
