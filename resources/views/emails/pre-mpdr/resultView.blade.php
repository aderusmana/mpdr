<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-MPDR Approval Result - {{ $form_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 30px 15px;
            color: #1e293b;
        }

        .result-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            padding: 40px;
            text-align: center;
            max-width: 680px;
            width: 100%;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .result-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
        }

        .result-card.failed::before {
            background: linear-gradient(90deg, #dc2626, #ef4444);
        }

        .result-card.already::before {
            background: linear-gradient(90deg, #d97706, #f59e0b);
        }

        .result-icon {
            width: 84px;
            height: 84px;
            background: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            box-shadow: 0 10px 25px -5px rgba(22, 163, 74, 0.4);
        }

        .result-icon.failed {
            background: #dc2626;
            box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.4);
        }

        .result-icon.already {
            background: #d97706;
            box-shadow: 0 10px 25px -5px rgba(217, 119, 6, 0.4);
        }

        .result-icon i {
            color: white;
            font-size: 38px;
        }

        .result-title {
            color: #0f172a;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .result-message {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .section-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin: 18px 0;
            text-align: left;
        }

        .section-header {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        @media (max-width: 576px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
            .result-card {
                padding: 25px 18px;
            }
        }

        .detail-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
        }

        .detail-item .label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .detail-item .value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            word-break: break-word;
        }

        .comment-box {
            background: #fefce8;
            border: 1px solid #fef08a;
            border-left: 4px solid #ca8a04;
            border-radius: 6px;
            padding: 12px 14px;
            margin-top: 12px;
            font-size: 13px;
            color: #713f12;
            line-height: 1.5;
        }

        .status-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 14px 18px;
            margin: 20px 0;
            font-size: 13px;
            color: #1e40af;
            text-align: left;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .countdown-section {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .countdown-text {
            color: #475569;
            font-weight: 600;
            font-size: 13px;
        }

        .countdown-number {
            font-size: 16px;
            font-weight: 700;
            color: #dc2626;
            margin: 0 4px;
        }

        .btn-cancel {
            background: #e2e8f0;
            border: none;
            color: #475569;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #cbd5e1;
            color: #1e293b;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background: #2563eb;
            color: #ffffff;
            font-weight: 600;
            font-size: 13px;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            background: #1d4ed8;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-secondary-custom {
            background: #0f172a;
            color: #ffffff;
            font-weight: 600;
            font-size: 13px;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-secondary-custom:hover {
            background: #1e293b;
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="result-card {{ $result == 'Failed' ? 'failed' : ($result == 'Success' ? '' : 'already') }}">
        
        <!-- Result Icon -->
        <div class="result-icon {{ $result == 'Failed' ? 'failed' : ($result == 'Success' ? '' : 'already') }}">
            @if($result == 'Success')
                <i class="fas fa-check"></i>
            @elseif($result == 'Failed')
                <i class="fas fa-times"></i>
            @else
                <i class="fas fa-info"></i>
            @endif
        </div>

        <!-- Result Title -->
        <h1 class="result-title">
            @if($result == 'Success')
                Approval Submitted Successfully!
            @elseif($result == 'Failed')
                Action Failed
            @else
                Form Already Processed
            @endif
        </h1>

        <!-- Result Message -->
        <p class="result-message">
            @if($result == 'Success')
                Your decision to <strong>{{ ucfirst($status) }}</strong> Pre-MPDR <strong>{{ $form_no }}</strong> has been recorded in the system.
            @elseif($result == 'Failed')
                Unable to process this approval action. Please verify your connection or contact system administration.
            @else
                You or another approver have already submitted a decision for this form.
            @endif
        </p>

        <!-- DECISION & APPROVER DETAILS -->
        <div class="section-box">
            <div class="section-header">
                <i class="fas fa-clipboard-check text-primary"></i> Decision Details
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="label">Action Recorded</div>
                    <div class="value">
                        @if($result == 'Success')
                            <span class="badge bg-success">{{ ucfirst($status) }}</span>
                        @elseif($result == 'Failed')
                            <span class="badge bg-danger">{{ ucfirst($status) }} Failed</span>
                        @else
                            <span class="badge bg-warning text-dark">Already {{ ucfirst($status) }}</span>
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <div class="label">Timestamp</div>
                    <div class="value">{{ now()->format('d M Y, H:i:s') }}</div>
                </div>
                @if(isset($approver) && $approver)
                <div class="detail-item">
                    <div class="label">Approver</div>
                    <div class="value">{{ $approver->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Role / Position</div>
                    <div class="value">{{ $approverDetail->approver_position ?? optional($approver->position)->name ?? 'Level ' . ($approverDetail->level ?? 'Approver') }}</div>
                </div>
                @elseif(isset($approverDetail) && $approverDetail)
                <div class="detail-item">
                    <div class="label">Approver</div>
                    <div class="value">{{ $approverDetail->name ?? $approverDetail->approver }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Role / Position</div>
                    <div class="value">{{ $approverDetail->approver_position ?: 'Level ' . ($approverDetail->level ?? '-') }}</div>
                </div>
                @endif
            </div>

            @php
                $dispComment = $comment ?? ($approverDetail->comment ?? null);
            @endphp
            @if(!empty($dispComment))
            <div class="comment-box">
                <div style="font-weight: 700; font-size: 11px; text-transform: uppercase; margin-bottom: 2px;">Approver Note / Review Comment:</div>
                "{!! nl2br(e($dispComment)) !!}"
            </div>
            @endif
        </div>

        <!-- FORM SUMMARY DETAILS (IF LOADED) -->
        <div class="section-box">
            <div class="section-header">
                <i class="fas fa-file-alt text-primary"></i> Form Information Summary
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="label">Form Number</div>
                    <div class="value">{{ $form_no }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Revision</div>
                    <div class="value">Rev {{ optional($form?->revision)->no ?? optional($form?->revision)->revision ?? '0' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Project Name</div>
                    <div class="value">{{ $form?->project_name ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Brand Name</div>
                    <div class="value">{{ $form?->brand_name ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Requester</div>
                    <div class="value">{{ $form?->user?->name ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Priority</div>
                    <div class="value">
                        @php
                            $prio = strtolower($form?->level_priority ?? 'normal');
                            $bClass = match($prio) {
                                'high' => 'bg-danger',
                                'medium' => 'bg-warning text-dark',
                                'low' => 'bg-success',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $bClass }}">{{ ucfirst($prio) }}</span>
                    </div>
                </div>
                @if($form?->detail?->target_launch)
                <div class="detail-item">
                    <div class="label">Target Launch</div>
                    <div class="value">{{ $form->detail->target_launch }}</div>
                </div>
                @endif
                @if($form?->category?->category)
                <div class="detail-item">
                    <div class="label">Category</div>
                    <div class="value">{{ $form->category->category }}{{ !empty($form->category->other) ? ' (' . $form->category->other . ')' : '' }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Status Information -->
        <div class="status-info">
            <i class="fas fa-info-circle fa-lg mt-1"></i>
            <div>
                <strong>Next Steps:</strong><br/>
                @if($result == 'Success')
                    The decision has been recorded into the audit trail. Related parties and the requester have been notified by email.
                @elseif($result == 'Failed')
                    Please check your network and try again. If issues persist, contact your IT support team.
                @else
                    This request has already been finalized or processed. No duplicate action is required.
                @endif
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="action-buttons">
            <a href="{{ route('prempdr.form', ['no_reg' => $form_no]) }}" target="_blank" class="btn-primary-custom">
                <i class="fas fa-external-link-alt"></i> Open Form in Portal
            </a>
            <a href="{{ route('prempdr.print', ['no_reg' => $form_no]) }}" target="_blank" class="btn-secondary-custom">
                <i class="fas fa-file-pdf"></i> Print / Download PDF
            </a>
            <button type="button" class="btn-cancel" onclick="window.close()" style="padding: 10px 18px; font-size: 13px;">
                <i class="fas fa-times-circle"></i> Close Window
            </button>
        </div>

        <!-- Countdown Section -->
        <div class="countdown-section" id="countdown-section">
            <div class="countdown-text">
                <i class="fas fa-stopwatch me-1"></i>
                Auto-closing in <span class="countdown-number" id="countdown">10</span> seconds
            </div>
            <button class="btn-cancel" onclick="cancelAutoClose()">
                <i class="fas fa-pause me-1"></i> Keep Window Open
            </button>
        </div>

    </div>

    <script>
        let countdown = 10;
        let countdownInterval;
        let autoCloseActive = true;

        function startCountdown() {
            const countdownElement = document.getElementById('countdown');
            const countdownSection = document.getElementById('countdown-section');

            countdownInterval = setInterval(() => {
                countdown--;

                if (countdown > 0) {
                    countdownElement.textContent = countdown;
                } else {
                    clearInterval(countdownInterval);
                    countdownSection.innerHTML = `
                        <div class="countdown-text" style="color: #64748b;">
                            <i class="fas fa-spinner fa-spin me-1"></i> Closing tab now...
                        </div>
                    `;

                    setTimeout(() => {
                        window.close();
                    }, 800);
                }
            }, 1000);
        }

        function cancelAutoClose() {
            if (autoCloseActive) {
                clearInterval(countdownInterval);
                autoCloseActive = false;

                const countdownSection = document.getElementById('countdown-section');
                countdownSection.style.background = '#f0fdf4';
                countdownSection.style.borderColor = '#bbf7d0';
                countdownSection.innerHTML = `
                    <div class="countdown-text" style="color: #166534;">
                        <i class="fas fa-check-circle me-1"></i> Auto-close paused. You may review the details above.
                    </div>
                `;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();

            // Cancel countdown if user clicks anywhere inside the card or presses a key
            document.addEventListener('click', function(e) {
                if (e.target.closest('.action-buttons')) return;
                if (autoCloseActive) {
                    cancelAutoClose();
                }
            });

            document.addEventListener('keydown', function() {
                if (autoCloseActive) {
                    cancelAutoClose();
                }
            });
        });
    </script>
</body>
</html>
