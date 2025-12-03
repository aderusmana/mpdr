<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-MPDR Status Update</title>
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
            max-width: 900px;
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
        
        /* Status Badge */
        .status-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            margin: 25px 0;
            border: 2px solid #2c3e50;
        }
        .status-badge {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 10px 0;
        }
        .status-approved {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        .status-rejected {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        .status-in-approval {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }
        
        /* Info Cards */
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
        .info-grid table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            width: 50%;
            vertical-align: top;
            padding: 7px;
        }
        .info-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }
        .info-item-full {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .info-value {
            color: #2c3e50;
            font-size: 15px;
            word-break: break-word;
        }
        
        /* Competitor Table */
        .competitor-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        .competitor-table th {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }
        .competitor-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
            background: white;
        }
        .competitor-table tr:nth-child(even) td {
            background-color: #f8f9fa;
        }
        .competitor-table tr:hover td {
            background-color: #e9ecef;
        }
        
        /* Approval History Table */
        .approval-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        .approval-table th {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }
        .approval-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
            background: white;
        }
        .approval-table tr:nth-child(even) td {
            background-color: #f8f9fa;
        }
        .approval-table tr:hover td {
            background-color: #e9ecef;
        }
        .approval-status {
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 15px;
            display: inline-block;
            font-size: 13px;
        }
        .approval-approve {
            color: #155724;
            background: #d4edda;
        }
        .approval-review {
            color: #856404;
            background: #fff3cd;
        }
        .approval-reject {
            color: #721c24;
            background: #f8d7da;
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
        .contact-info {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.8;
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
            .info-grid td {
                width: 100% !important;
                display: block !important;
            }
            .competitor-table, .approval-table {
                font-size: 12px !important;
            }
            .competitor-table th, .competitor-table td,
            .approval-table th, .approval-table td {
                padding: 8px !important;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="header-content">
                <h1 class="email-title">Pre-MPDR Status Update</h1>
                <p class="email-subtitle">Pre-Marketing Product Development Request</p>
            </div>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <!-- Greeting -->
            <div class="greeting">
                @if($form->status == 'Approved')
                    <strong>Dear All,</strong><br>
                    We are pleased to inform you about the following Pre-MPDR status update.
                @elseif($form->status == 'Rejected')
                    <strong>Dear Initiator & Super Admin,</strong><br>
                    This is to inform you about an important status update regarding the Pre-MPDR request.
                @elseif($form->status == 'In Approval')
                    <strong>Dear Super Admin,</strong><br>
                    We would like to update you on the approval progress of the Pre-MPDR request.
                @endif
            </div>
            
            <!-- Status Section -->
            <div class="status-section">
                <h3 style="margin: 0 0 15px 0; color: #2c3e50;">📋 Form Status</h3>
                @if($form->status == 'Approved')
                    <div class="status-badge status-approved">✅ APPROVED</div>
                    <p style="margin: 15px 0 0 0; color: #2c3e50; font-size: 16px;">
                        Form Pre-MPDR <strong>{{$form->no}}</strong> has been successfully approved.
                    </p>
                @elseif($form->status == 'Rejected')
                    <div class="status-badge status-rejected">❌ REJECTED</div>
                    <p style="margin: 15px 0 0 0; color: #2c3e50; font-size: 16px;">
                        Form Pre-MPDR <strong>{{$form->no}}</strong> has been rejected by <strong>{{$form->approvedDetail->where('status', 'not approve')->first()->name}}</strong>.
                    </p>
                @elseif($form->status == 'In Approval')
                    <div class="status-badge status-in-approval">🔄 IN APPROVAL</div>
                    <p style="margin: 15px 0 0 0; color: #2c3e50; font-size: 16px;">
                        Form Pre-MPDR <strong>{{$form->no}}</strong> has been approved by <strong>{{$form->approvedDetail->whereIn('status', ['approve', 'approve with review'])->last()->name}}</strong>.
                    </p>
                @endif
            </div>
            
            <!-- Basic Information -->
            <div class="info-section">
                <h3 class="section-title">📄 Request Information</h3>
                <div class="info-grid">
                    <table>
                        <tr>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Registration Number</div>
                                    <div class="info-value">{{$form->no}}</div>
                                </div>
                            </td>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Project Name</div>
                                    <div class="info-value">{{$form->project_name ?? 'N/A'}}</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Level Priority</div>
                                    <div class="info-value">{{$form->level_priority ?? 'N/A'}}</div>
                                </div>
                            </td>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Proposed BRAND Name</div>
                                    <div class="info-value">{{$form->brand_name ?? 'N/A'}}</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Product Category</div>
                                    <div class="info-value">{{$form->category->category ?? ''}} {{$form->category->other ?? ''}}</div>
                                </div>
                            </td>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Target Launch Date</div>
                                    <div class="info-value">{{ $form->detail->target_launch ?? 'N/A' }}</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Rational & Channel -->
            <div class="info-section">
                <h3 class="section-title">🎯 Development Rational & Channel</h3>
                <div class="info-grid">
                    <div class="info-item-full">
                        <div class="info-label">Rational For Development</div>
                        <div class="info-value">{{$form->detail->rational_for_development ?? 'N/A'}}</div>
                    </div>
                    <table>
                        <tr>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Channel</div>
                                    <div class="info-value">{{$form->channel->category ?? 'N/A'}} {{$form->channel->country ?? ''}}</div>
                                </div>
                            </td>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Certification Requirement</div>
                                    <div class="info-value">{{ $form->certification->category ?? 'N/A' }} {{ $form->certification->other ?? '' }}</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Product Description -->
            <div class="info-section">
                <h3 class="section-title">📦 General Product Description</h3>
                <div class="info-grid">
                    <div class="info-item-full">
                        <div class="info-label">Product Description (Form/Color/Flavor brief)</div>
                        <div class="info-value">{!! $form->description->product_description ?? 'N/A' !!}</div>
                    </div>
                    <div class="info-item-full">
                        <div class="info-label">Usage Description (Application in customer)</div>
                        <div class="info-value">{!! $form->description->usage_description ?? 'N/A' !!}</div>
                    </div>
                    <table>
                        <tr>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Storage Temperature</div>
                                    <div class="info-value">{{ $form->description->storage_temperature ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Delivery Temperature</div>
                                    <div class="info-value">{{ $form->description->delivery_temperature ?? 'N/A' }}</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Competitor Products -->
            @if(isset($form->competitor) && count($form->competitor) > 0)
            <div class="info-section">
                <h3 class="section-title">🏆 Competitor's Product to Match or Beat</h3>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 0 0 8px 8px; border: 1px solid #e9ecef; border-top: none;">
                    <table class="competitor-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Size</th>
                                <th>Packaging</th>
                                <th>Price Indication</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($form->competitor as $index => $competitor)
                            <tr>
                                <td style="text-align: center; font-weight: 600;">{{$index + 1}}</td>
                                <td>{{$competitor->name ?? 'N/A'}}</td>
                                <td>{{$competitor->size ?? 'N/A'}}</td>
                                <td>{{$competitor->packaging ?? 'N/A'}}</td>
                                <td>{{$competitor->price ?? 'N/A'}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            
            <!-- Packaging Details -->
            <div class="info-section">
                <h3 class="section-title">📦 Detailed Packaging Required</h3>
                <div class="info-grid">
                    <table>
                        <tr>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Weight of Product (kg/carton)</div>
                                    <div class="info-value">{{ $form->packaging->weight ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Product Variation List</div>
                                    <div class="info-value">{{ $form->packaging->product_variation ?? 'N/A' }}</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="info-item-full">
                        <div class="info-label">Packaging</div>
                        <div class="info-value">{{ $form->packaging->category ?? 'N/A' }} {{ $form->packaging->detail ?? '' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Market Update -->
            <div class="info-section">
                <h3 class="section-title">📊 Market Update & Pricing Strategy</h3>
                <div class="info-grid">
                    <table>
                        <tr>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Potential Volume (Mt/Annually)</div>
                                    <div class="info-value">{{ $form->market->potential_volume ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Expected Margin (%)</div>
                                    <div class="info-value">{{ $form->market->expected_margin ?? 'N/A' }}</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="info-item">
                                    <div class="info-label">Price Estimate</div>
                                    <div class="info-value">{{ $form->market->price_estimate ?? 'N/A' }}</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Approval History -->
            <div class="info-section">
                <h3 class="section-title">✅ Approval History</h3>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 0 0 8px 8px; border: 1px solid #e9ecef; border-top: none;">
                    <table class="approval-table">
                        <thead>
                            <tr>
                                <th>Approver</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                            @foreach($form->approvedDetail as $index => $approvedDetail)
                            <tr>
                                <td style="font-weight: 600;">{{ $approvedDetail->name }}</td>
                                <td>
                                    @if($approvedDetail->status == 'approve')
                                        <span class="approval-status approval-approve">✅ Approved</span>
                                    @elseif($approvedDetail->status == 'approve with review')
                                        <span class="approval-status approval-review">📝 Approved with Review</span>
                                    @else
                                        <span class="approval-status approval-reject">❌ Not Approved</span>
                                    @endif
                                </td>
                                <td style="color: #6c757d;">{{$approvedDetail->approved_date !== null ? $approvedDetail->approved_date : '-'}}</td>
                                <td>{{$approvedDetail->comment ?? '-'}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Closing Message -->
            @if($form->status == 'Approved')
            <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center;">
                <h4 style="color: #155724; margin: 0 0 10px 0;">✅ Congratulations!</h4>
                <p style="color: #155724; margin: 0; line-height: 1.6;">
                    Thank you for your cooperation, and we wish you a safe and successful completion of your tasks.
                </p>
            </div>
            @elseif($form->status == 'Rejected')
            <div style="background: #f8d7da; border: 1px solid #f1b0b7; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center;">
                <h4 style="color: #721c24; margin: 0 0 10px 0;">❌ Request Rejected</h4>
                <p style="color: #721c24; margin: 0; line-height: 1.6;">
                    We appreciate your understanding and look forward to your revised submission.
                </p>
            </div>
            @elseif($form->status == 'In Approval')
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center;">
                <h4 style="color: #856404; margin: 0 0 10px 0;">🔄 In Progress</h4>
                <p style="color: #856404; margin: 0; line-height: 1.6;">
                    Your request is currently in the approval process. You will be notified once the final decision is made.
                </p>
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-content">
                <div class="company-info">PT Sinar Meadow International Indonesia</div>
                <div class="company-tagline">Marketing Product Development</div>
                <div class="footer-divider"></div>
                <div class="contact-info">
                    <strong>Need Help?</strong><br>
                    For any questions or concerns, please contact your supervisor or the marketing department.<br>
                    Thank you for your attention.
                </div>
                <div class="copyright">
                    © {{ date('Y') }} PT Sinar Meadow International Indonesia. All rights reserved.<br>
                    This is an automated message, please do not reply directly to this email.
                </div>
            </div>
        </div>
    </div>
</body>
</html>