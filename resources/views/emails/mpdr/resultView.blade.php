<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MPDR Approval Result - {{ $form_no ?? '' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @php
        $approverName = $approver->name ?? $approverDetail->approver_name ?? $approverDetail->name ?? $approverDetail->approver_nik ?? $approverDetail->approver ?? null;
        $dispComment = $comment ?? ($approverDetail->comment ?? null);
        $isSuccess = ($result === 'Success');
        $isFailed = ($result === 'Failed');
        $isAlready = !$isSuccess && !$isFailed;

        $statusLower = strtolower($status ?? '');

        // Determine Theme & Content per Condition
        if ($isFailed) {
            $theme = 'failed';
            $themeTitle = 'Action Failed';
            $themeIcon = 'fas fa-triangle-exclamation';
            $themeMsg = 'Unable to process this approval action. Please verify your connection or contact IT support.';
            $statusLabel = ucfirst($status ?? 'Failed');
        } elseif ($isAlready) {
            $theme = 'already';
            $themeTitle = 'Already Processed';
            $themeIcon = 'fas fa-clock-rotate-left';
            $themeMsg = (!empty($result) && $result !== 'Success' && $result !== 'Failed')
                ? $result 
                : 'You or another approver have already submitted a decision for this form.';
            $statusLabel = match($statusLower) {
                'approve' => 'Approved',
                'approve with review' => 'Approved with Review',
                'not approve', 'reject', 'rejected' => 'Not Approved',
                default => ucfirst($status ?? 'Processed')
            };
        } elseif (in_array($statusLower, ['not approve', 'reject', 'rejected', 'not approved'])) {
            $theme = 'rejected';
            $themeTitle = 'MPDR Rejected';
            $themeIcon = 'fas fa-circle-xmark';
            $themeMsg = 'Your decision to <strong>Not Approve</strong> MPDR <strong>' . e($form_no ?? '') . '</strong> has been recorded.';
            $statusLabel = 'Not Approved';
        } elseif ($statusLower === 'approve with review') {
            $theme = 'review';
            $themeTitle = 'Approved with Review';
            $themeIcon = 'fas fa-clipboard-check';
            $themeMsg = 'Your decision to <strong>Approve with Review</strong> MPDR <strong>' . e($form_no ?? '') . '</strong> has been recorded.';
            $statusLabel = 'Approved with Review';
        } else {
            $theme = 'success';
            $themeTitle = 'Approval Submitted!';
            $themeIcon = 'fas fa-circle-check';
            $themeMsg = 'Your decision to <strong>Approve</strong> MPDR <strong>' . e($form_no ?? '') . '</strong> has been recorded.';
            $statusLabel = 'Approved';
        }
    @endphp

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --theme-primary: #10b981;
            --theme-secondary: #059669;
            --theme-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --theme-glow: rgba(16, 185, 129, 0.4);
            --theme-ambient-bg: radial-gradient(circle at 50% 25%, rgba(16, 185, 129, 0.22) 0%, rgba(5, 150, 105, 0.08) 50%, #0b1120 100%);
            --theme-badge-bg: #ecfdf5;
            --theme-badge-text: #065f46;
            --theme-badge-border: #a7f3d0;
            --theme-icon-bg: #d1fae5;
            --theme-icon-color: #059669;
        }

        /* THEME 1: SUCCESS (Emerald Green) */
        body.theme-success {
            --theme-primary: #10b981;
            --theme-secondary: #059669;
            --theme-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --theme-glow: rgba(16, 185, 129, 0.35);
            --theme-ambient-bg: radial-gradient(ellipse at top, #064e3b 0%, #022c22 45%, #0b1120 100%);
            --theme-badge-bg: #ecfdf5;
            --theme-badge-text: #047857;
            --theme-badge-border: #a7f3d0;
            --theme-icon-bg: #d1fae5;
            --theme-icon-color: #059669;
        }

        /* THEME 2: REVIEW (Vibrant Royal Blue) */
        body.theme-review {
            --theme-primary: #3b82f6;
            --theme-secondary: #1d4ed8;
            --theme-gradient: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            --theme-glow: rgba(59, 130, 246, 0.35);
            --theme-ambient-bg: radial-gradient(ellipse at top, #1e3a8a 0%, #172554 45%, #0b1120 100%);
            --theme-badge-bg: #eff6ff;
            --theme-badge-text: #1d4ed8;
            --theme-badge-border: #bfdbfe;
            --theme-icon-bg: #dbeafe;
            --theme-icon-color: #2563eb;
        }

        /* THEME 3: ALREADY PROCESSED (Warm Amber / Gold) */
        body.theme-already {
            --theme-primary: #f59e0b;
            --theme-secondary: #d97706;
            --theme-gradient: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            --theme-glow: rgba(245, 158, 11, 0.35);
            --theme-ambient-bg: radial-gradient(ellipse at top, #78350f 0%, #451a03 45%, #0b1120 100%);
            --theme-badge-bg: #fffbeb;
            --theme-badge-text: #b45309;
            --theme-badge-border: #fde68a;
            --theme-icon-bg: #fef3c7;
            --theme-icon-color: #d97706;
        }

        /* THEME 4: REJECTED / NOT APPROVE (Vibrant Crimson) */
        body.theme-rejected {
            --theme-primary: #f43f5e;
            --theme-secondary: #e11d48;
            --theme-gradient: linear-gradient(135deg, #fb7185 0%, #e11d48 100%);
            --theme-glow: rgba(244, 63, 94, 0.35);
            --theme-ambient-bg: radial-gradient(ellipse at top, #881337 0%, #4c0519 45%, #0b1120 100%);
            --theme-badge-bg: #fff1f2;
            --theme-badge-text: #be123c;
            --theme-badge-border: #fecdd3;
            --theme-icon-bg: #ffe4e6;
            --theme-icon-color: #e11d48;
        }

        /* THEME 5: FAILED / ERROR (Coral Red) */
        body.theme-failed {
            --theme-primary: #ef4444;
            --theme-secondary: #dc2626;
            --theme-gradient: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
            --theme-glow: rgba(239, 68, 68, 0.35);
            --theme-ambient-bg: radial-gradient(ellipse at top, #7f1d1d 0%, #450a0a 45%, #0b1120 100%);
            --theme-badge-bg: #fef2f2;
            --theme-badge-text: #b91c1c;
            --theme-badge-border: #fecaca;
            --theme-icon-bg: #fee2e2;
            --theme-icon-color: #dc2626;
        }

        body {
            background: var(--theme-ambient-bg);
            background-color: #0b1120;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 30px 16px;
            color: #1e293b;
            position: relative;
            overflow-x: hidden;
        }

        /* Dynamic Floating Ambient Glow Blobs */
        .ambient-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.5;
            pointer-events: none;
            z-index: 0;
            animation: blobPulse 8s ease-in-out infinite alternate;
        }

        .blob-1 {
            width: 380px;
            height: 380px;
            top: 5%;
            left: 20%;
            background: var(--theme-primary);
        }

        .blob-2 {
            width: 320px;
            height: 320px;
            bottom: 10%;
            right: 20%;
            background: var(--theme-secondary);
            animation-delay: -4s;
        }

        @keyframes blobPulse {
            0% { transform: scale(0.9) translateY(0); opacity: 0.35; }
            100% { transform: scale(1.15) translateY(-30px); opacity: 0.6; }
        }

        /* Result Card with Glass & Entrance Animation */
        .result-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 60px -15px var(--theme-glow), 0 0 0 1px rgba(255, 255, 255, 0.2), 0 15px 35px -5px rgba(0, 0, 0, 0.3);
            padding: 40px 36px 32px;
            text-align: center;
            max-width: 480px;
            width: 100%;
            position: relative;
            z-index: 10;
            overflow: hidden;
            animation: cardEnter 0.55s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardEnter {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.94);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Top Vibrant Accent Bar */
        .card-top-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--theme-gradient);
        }

        /* Pulsing Animated Icon */
        .icon-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .result-icon {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: var(--theme-icon-bg);
            color: var(--theme-icon-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            position: relative;
            z-index: 2;
            box-shadow: 0 10px 25px -5px var(--theme-glow);
            animation: iconPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both;
        }

        .icon-halo {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--theme-primary);
            opacity: 0.35;
            z-index: 1;
            animation: haloPulse 2.2s cubic-bezier(0.24, 0, 0.38, 1) infinite;
        }

        @keyframes iconPop {
            0% { transform: scale(0.3) rotate(-20deg); opacity: 0; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        @keyframes haloPulse {
            0% { transform: scale(1); opacity: 0.5; }
            70% { transform: scale(1.6); opacity: 0; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        .result-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            letter-spacing: -0.4px;
        }

        .result-message {
            color: #64748b;
            font-size: 14.5px;
            line-height: 1.55;
            margin-bottom: 24px;
        }

        .result-message strong {
            color: #0f172a;
            font-weight: 700;
        }

        /* Summary Info Card */
        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 18px;
            margin-bottom: 20px;
            text-align: left;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #edf2f7;
            font-size: 13.5px;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #64748b;
            font-weight: 500;
        }

        .summary-value {
            color: #0f172a;
            font-weight: 700;
            text-align: right;
        }

        .font-monospace {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            letter-spacing: 0.3px;
        }

        /* Themed Pill Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            background: var(--theme-badge-bg);
            color: var(--theme-badge-text);
            border: 1px solid var(--theme-badge-border);
            letter-spacing: 0.2px;
        }

        /* Comment / Note Box */
        .comment-card {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-left: 3.5px solid var(--theme-primary);
            border-radius: 10px;
            padding: 11px 15px;
            text-align: left;
            margin-bottom: 22px;
            font-size: 13px;
        }

        .comment-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #92400e;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }

        .comment-body {
            color: #78350f;
            line-height: 1.45;
            word-break: break-word;
        }

        /* Guaranteed 5-Second Animated Countdown Bar */
        .countdown-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 22px;
        }

        .countdown-bar-wrap {
            height: 6px;
            background: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
            margin-bottom: 8px;
            position: relative;
        }

        .countdown-bar {
            height: 100%;
            width: 100%;
            background: var(--theme-gradient);
            border-radius: 9999px;
            transition: width 0.1s linear;
        }

        .countdown-label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .countdown-num {
            font-weight: 800;
            color: var(--theme-secondary);
            font-size: 14px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .btn-close-window {
            background: var(--theme-gradient);
            color: #ffffff;
            padding: 12px 28px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 20px var(--theme-glow);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            min-width: 200px;
        }

        .btn-close-window:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--theme-glow);
            filter: brightness(1.08);
        }

        .btn-close-window:active {
            transform: translateY(0);
        }

        @media (max-width: 480px) {
            .result-card {
                padding: 28px 20px;
            }
            .action-buttons {
                width: 100%;
            }
            .btn-close-window {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body class="theme-{{ $theme }}">

    <!-- Ambient Floating Orbs -->
    <div class="ambient-blob blob-1"></div>
    <div class="ambient-blob blob-2"></div>

    <div class="result-card">
        <!-- Themed Top Gradient Bar -->
        <div class="card-top-bar"></div>

        <!-- Animated Pulsing Icon -->
        <div class="icon-wrapper">
            <div class="icon-halo"></div>
            <div class="result-icon">
                <i class="{{ $themeIcon }}"></i>
            </div>
        </div>

        <!-- Result Title -->
        <h1 class="result-title">{{ $themeTitle }}</h1>

        <!-- Result Message -->
        <p class="result-message">{!! $themeMsg !!}</p>

        <!-- Summary Details -->
        <div class="summary-card">
            <div class="summary-row">
                <span class="summary-label">Form Number</span>
                <span class="summary-value font-monospace">{{ $form_no ?? '-' }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Decision</span>
                <span class="summary-value">
                    <span class="status-badge">{{ $statusLabel }}</span>
                </span>
            </div>
            @if(!empty($approverName))
            <div class="summary-row">
                <span class="summary-label">Approver</span>
                <span class="summary-value">{{ $approverName }}</span>
            </div>
            @endif
            <div class="summary-row">
                <span class="summary-label">Timestamp</span>
                <span class="summary-value">{{ now()->format('d M Y, H:i') }}</span>
            </div>
        </div>

        <!-- Review Comment / Note (If Any) -->
        @if(!empty($dispComment))
        <div class="comment-card">
            <div class="comment-label"><i class="fas fa-comment-dots" style="margin-right: 5px;"></i> Review Note</div>
            <div class="comment-body">"{!! nl2br(e($dispComment)) !!}"</div>
        </div>
        @endif

        <!-- Guaranteed 5-Second Countdown Progress Bar -->
        <div class="countdown-container">
            <div class="countdown-bar-wrap">
                <div class="countdown-bar" id="countdown-bar"></div>
            </div>
            <div class="countdown-label" id="countdown-label">
                <i class="fas fa-stopwatch" style="color: var(--theme-primary);"></i>
                Closing window in <span id="countdown" class="countdown-num">5</span>s...
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn-close-window" onclick="handleUserClose()">
                <i class="fas fa-times"></i> Close Window Now
            </button>
        </div>
    </div>

    <script>
        const TOTAL_SECONDS = 5;
        let remainingMs = TOTAL_SECONDS * 1000;
        const intervalMs = 100;
        const barEl = document.getElementById('countdown-bar');
        const numEl = document.getElementById('countdown');
        const labelEl = document.getElementById('countdown-label');
        let autoCloseFired = false;

        function closeWindowTab() {
            // Method 1: standard close
            try {
                window.close();
            } catch (e) {}

            // Method 2: self.close
            try {
                self.close();
            } catch (e) {}

            // Method 3: window.open trick
            try {
                window.open('', '_self', '');
                window.close();
            } catch (e) {}
        }

        function handleAutoClose() {
            if (autoCloseFired) return;
            autoCloseFired = true;

            if (barEl) barEl.style.width = '0%';
            if (numEl) numEl.textContent = '0';

            closeWindowTab();

            // If browser security policy blocks auto-closing without direct user gesture:
            setTimeout(() => {
                if (labelEl) {
                    labelEl.innerHTML = '<span style="color: var(--theme-primary); font-weight: 700;"><i class="fas fa-hand-pointer" style="margin-right: 4px;"></i> Completed! Click "Close Window Now" below.</span>';
                }
            }, 300);
        }

        function handleUserClose() {
            closeWindowTab();

            // If browser strictly prevents closing top-level tab by script, guide user without navigating to about:blank
            setTimeout(() => {
                if (labelEl) {
                    labelEl.innerHTML = '<span style="color: #64748b; font-weight: 600;"><i class="fas fa-info-circle" style="margin-right: 4px;"></i> Please close this tab (Ctrl + W).</span>';
                }
            }, 300);
        }

        // Guaranteed countdown timer that always executes handleAutoClose() after 5 seconds
        const timer = setInterval(() => {
            if (autoCloseFired) {
                clearInterval(timer);
                return;
            }

            remainingMs -= intervalMs;

            if (remainingMs <= 0) {
                clearInterval(timer);
                handleAutoClose();
            } else {
                const pct = (remainingMs / (TOTAL_SECONDS * 1000)) * 100;
                if (barEl) barEl.style.width = pct + '%';
                const secsLeft = Math.ceil(remainingMs / 1000);
                if (numEl && numEl.textContent != secsLeft) {
                    numEl.textContent = secsLeft;
                }
            }
        }, intervalMs);
    </script>
</body>
</html>
