<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-MPDR Approval Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .result-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            padding: 40px;
            text-align: center;
            max-width: 550px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .result-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #28a745, #20c997);
        }

        .result-card.failed::before {
            background: linear-gradient(90deg, #dc3545, #c82333);
        }

        .result-card.already::before {
            background: linear-gradient(90deg, #ffc107, #ff9800);
        }

        .result-icon {
            width: 90px;
            height: 90px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            position: relative;
        }

        .result-icon.failed {
            background: #dc3545;
        }

        .result-icon.already {
            background: #ffc107;
        }

        .result-icon::after {
            content: '';
            position: absolute;
            width: 110px;
            height: 110px;
            border: 3px solid #28a745;
            border-radius: 50%;
            opacity: 0.3;
            animation: pulse 2s infinite;
        }

        .result-icon.failed::after {
            border-color: #dc3545;
        }

        .result-icon.already::after {
            border-color: #ffc107;
        }

        .result-icon i {
            color: white;
            font-size: 40px;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.3;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.1;
            }
            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

        .result-title {
            color: #2c3e50;
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .result-message {
            color: #7f8c8d;
            font-size: 17px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .result-details {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 25px 0;
            text-align: left;
            border-radius: 0 10px 10px 0;
        }

        .result-details.failed {
            border-left-color: #dc3545;
        }

        .result-details.already {
            border-left-color: #ffc107;
        }

        .result-details .detail-item {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .result-details .detail-item:last-child {
            margin-bottom: 0;
        }

        .result-details .detail-item i {
            width: 25px;
            margin-right: 10px;
            color: #2c3e50;
        }

        .result-details strong {
            color: #2c3e50;
            margin-right: 5px;
        }

        .status-info {
            background: #e8f4f8;
            border: 1px solid #bee5eb;
            border-radius: 10px;
            padding: 18px;
            margin: 25px 0;
        }

        .status-info i {
            color: #17a2b8;
        }

        .countdown-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0 0 0;
        }

        .countdown-text {
            color: #856404;
            font-weight: 500;
            font-size: 15px;
        }

        .countdown-number {
            font-size: 20px;
            font-weight: bold;
            color: #dc3545;
            margin: 0 5px;
        }

        .btn-cancel {
            background: #6c757d;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.3s ease;
            margin-top: 10px;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
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
                <i class="fas fa-info-circle"></i>
            @endif
        </div>

        <!-- Result Title -->
        <h1 class="result-title">
            @if($result == 'Success')
                Action Successful!
            @elseif($result == 'Failed')
                Action Failed!
            @else
                Already Processed
            @endif
        </h1>

        <!-- Result Message -->
        <p class="result-message">
            @if($result == 'Success')
                The Pre-MPDR form has been {{ $status }} successfully.
            @elseif($result == 'Failed')
                Failed to {{ $status }} the Pre-MPDR form. Please try again or contact support.
            @else
                You have already {{ $status }} this form previously.
            @endif
        </p>

        <!-- Result Details -->
        <div class="result-details {{ $result == 'Failed' ? 'failed' : ($result == 'Success' ? '' : 'already') }}">
            <div class="detail-item">
                <i class="fas fa-file-alt"></i>
                <div>
                    <strong>Form Number:</strong> {{ $form_no }}
                </div>
            </div>
            <div class="detail-item">
                <i class="fas fa-calendar-check"></i>
                <div>
                    <strong>Date & Time:</strong> {{ now()->format('d F Y, H:i:s') }}
                </div>
            </div>
            <div class="detail-item">
                <i class="fas fa-flag"></i>
                <div>
                    <strong>Action:</strong>
                    @if($result == 'Success')
                        <span class="badge status-badge bg-success">{{ ucfirst($status) }}</span>
                    @elseif($result == 'Failed')
                        <span class="badge status-badge bg-danger">{{ ucfirst($status) }} Failed</span>
                    @else
                        <span class="badge status-badge bg-warning text-dark">Already {{ ucfirst($status) }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="status-info">
            <i class="fas fa-info-circle"></i>
            <strong>What happens next?</strong><br>
            <span class="text-muted">
                @if($result == 'Success')
                    This action has been recorded. The requester and related parties will be notified of this {{ $status }}.
                @elseif($result == 'Failed')
                    Please check your connection and try again. If the problem persists, contact your system administrator.
                @else
                    This form has been processed previously. No further action is required from you.
                @endif
            </span>
        </div>

        <!-- Countdown Section -->
        <div class="countdown-section" id="countdown-section">
            <div class="countdown-text">
                <i class="fas fa-stopwatch"></i>
                Auto-close in
                <span class="countdown-number" id="countdown">5</span>
                seconds
            </div>
            <button class="btn-cancel" onclick="cancelAutoClose()">
                <i class="fas fa-hand-paper"></i> Cancel Auto-Close
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let countdown = 5;
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
                        <div class="countdown-text">
                            <i class="fas fa-spinner fa-spin"></i> Closing window now...
                        </div>
                    `;

                    setTimeout(() => {
                        if (window.opener) {
                            window.close();
                        } else {
                            window.close();

                            setTimeout(() => {
                                if (window.history.length > 1) {
                                    window.history.back();
                                } else {
                                    document.body.innerHTML = `
                                        <div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:Arial,sans-serif;text-align:center;background:#f8f9fa;">
                                            <div>
                                                <h3 style="color:#2c3e50;">Please close this tab manually</h3>
                                                <p style="color:#6c757d;">This window should have closed automatically.<br>Please close this tab manually.</p>
                                            </div>
                                        </div>
                                    `;
                                }
                            }, 500);
                        }
                    }, 1000);
                }
            }, 1000);
        }

        function cancelAutoClose() {
            if (autoCloseActive) {
                clearInterval(countdownInterval);
                autoCloseActive = false;

                const countdownSection = document.getElementById('countdown-section');
                countdownSection.style.background = '#d1ecf1';
                countdownSection.style.borderColor = '#bee5eb';
                countdownSection.innerHTML = `
                    <div class="countdown-text" style="color: #0c5460;">
                        <i class="fas fa-check-circle"></i> Auto-close cancelled
                    </div>
                `;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();

            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-cancel')) return;
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
