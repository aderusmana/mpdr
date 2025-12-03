<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Permit Form HSE</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        .email-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Header */
        .email-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
            position: relative;
        }
        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="20" fill="url(%23dots)"/></svg>');
            opacity: 0.3;
        }
        .header-content {
            position: relative;
            z-index: 1;
        }
        .email-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .email-subtitle {
            font-size: 16px;
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        /* Content */
        .email-content {
            padding: 40px;
        }
        
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            border-left: 4px solid #2c3e50;
        }
        
        .approval-notice {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px 20px;
            margin: 20px 0;
            color: #0c5460;
        }
        
        .approval-notice p {
            margin: 0;
            font-size: 16px;
        }
        
        .btn-link {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }
        
        .btn-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.4);
            color: white;
        }
        
        /* Info Section */
        .info-section {
            margin-bottom: 30px;
        }
        .section-title {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 12px 20px;
            margin: 0 0 15px 0;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            font-size: 16px;
        }
        .info-grid {
            width: 100%;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e9ecef;
            border-top: none;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table tr {
            border-bottom: 1px solid #e9ecef;
        }
        .info-table tr:last-child {
            border-bottom: none;
        }
        .info-table td {
            padding: 12px 15px;
            font-size: 15px;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #495057;
            width: 35%;
        }
        .info-table td:nth-child(2) {
            width: 5%;
            color: #6c757d;
            text-align: center;
        }
        .info-table td:last-child {
            color: #2c3e50;
            background: white;
            border-radius: 4px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 14px;
        }
        .status-in-review {
            background: #fff3cd;
            color: #856404;
        }
        .status-in-approval {
            background: #cce7ff;
            color: #0066cc;
        }
        
        .closing-message {
            margin: 25px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            border-left: 4px solid #28a745;
            color: #2c3e50;
        }
        
        .closing-message p {
            margin: 5px 0;
        }
        
        /* Footer */
        .email-footer {
            background: #2c3e50;
            color: white;
            padding: 30px 40px;
            text-align: center;
        }
        .footer-content {
            max-width: 600px;
            margin: 0 auto;
        }
        .company-info {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .company-tagline {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 20px;
        }
        .footer-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 20px 0;
        }
        .copyright {
            font-size: 12px;
            opacity: 0.7;
            margin-top: 15px;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px !important;
                border-radius: 8px !important;
            }
            .email-content, .email-header, .email-footer {
                padding: 20px !important;
            }
            .info-table td:first-child {
                width: 40% !important;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="header-content">
                <h1 class="email-title">Work Permit Form HSE</h1>
                <p class="email-subtitle">PT Sinar Meadow International Indonesia</p>
            </div>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <!-- Greeting -->
            <div class="greeting">
                <strong>Dear {{$role}},</strong>
            </div>
            
            <!-- Approval Notice -->
            @if($form->status === "In Approval")
            <div class="approval-notice">
                <p>There is a Work Permit that requires your approval.</p>
                <a href="{{ route('approval.table') }}" class="btn-link">
                    Click here to approve
                </a>
            </div>
            @endif
            
            <!-- Work Permit Details -->
            <div class="info-section">
                <h3 class="section-title">📋 Work Permit Details</h3>
                <div class="info-grid">
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <td>Company</td>
                                <td>:</td>
                                <td>{{ $detail->company_department }}</td>
                            </tr>
                            <tr>
                                <td>Supervisor</td>
                                <td>:</td>
                                <td>{{ $detail->supervisor }}</td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td>:</td>
                                <td>{{ $detail->location }}</td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>:</td>
                                <td>{{ $detail->start_date }} - {{$detail->end_date}}</td>
                            </tr>
                            <tr>
                                <td>Time</td>
                                <td>:</td>
                                <td>{{ $detail->start_time }} - {{$detail->end_time}}</td>
                            </tr>
                            <tr>
                                <td>Employee Amount</td>
                                <td>:</td>
                                <td>{{ $detail->workers_count }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if($form->status === "In Review")
                                        <span class="status-badge status-in-review">{{ $form->status }}</span>
                                    @elseif($form->status === "In Approval" || $form->status === "In Approval (Extend)")
                                        <span class="status-badge status-in-approval">{{ $form->status }}</span>
                                    @else
                                        <span class="status-badge">{{ $form->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Closing Message -->
            <div class="closing-message">
                @if($form->status === "In Review")
                    <p><strong>Kindly review it at your earliest convenience so we can proceed.</strong></p>
                @elseif($form->status === "In Approval" || $form->status === "In Approval (Extend)")
                    <p><strong>Kindly approve it at your earliest convenience so we can proceed.</strong></p>
                @endif
                <p>Thank you for your attention.</p>
            </div>
            
            <div style="margin-top: 30px; color: #2c3e50;">
                <p style="margin: 5px 0;"><strong>Best regards,</strong></p>
                <p style="margin: 5px 0;">PT Sinar Meadow International Indonesia</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-content">
                <div class="company-info">PT Sinar Meadow International Indonesia</div>
                <div class="company-tagline">Health, Safety & Environment Department</div>
                <div class="footer-divider"></div>
                <div class="copyright">
                    © {{ date('Y') }} PT Sinar Meadow International Indonesia. All rights reserved.<br>
                    This is an automated message, please do not reply directly to this email.
                </div>
            </div>
        </div>
    </div>
</body>
</html>