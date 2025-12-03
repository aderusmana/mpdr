<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-MPDR Approval Request</title>
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
        
        /* Action Buttons */
        .action-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
            border: 2px solid #2c3e50;
        }
        .action-title {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .action-subtitle {
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 16px;
        }
        .button-group {
            text-align: center;
        }
        .button-group table {
            margin: 0 auto;
            border-collapse: collapse;
        }
        .button-group td {
            padding: 7px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            min-width: 140px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .btn-approve {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .btn-reject {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            color: white;
        }
        .btn-review {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
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
            .button-group td {
                display: block !important;
                width: 100% !important;
            }
            .btn {
                width: 100% !important;
                margin-bottom: 10px !important;
            }
            .competitor-table {
                font-size: 12px !important;
            }
            .competitor-table th, .competitor-table td {
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
                <h1 class="email-title">Pre-MPDR Approval Request</h1>
                <p class="email-subtitle">Pre-Marketing Product Development Request</p>
            </div>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <!-- Greeting -->
            <div class="greeting">
                <strong>Dear {{$user->name}},</strong><br>
                A new Pre-MPDR request has been submitted for your review and approval. Please review the details below and provide your decision.
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
                                    <div class="info-label">Channel Category</div>
                                    <div class="info-value">{{$form->channel->category ?? 'N/A'}}</div>
                                </div>
                            </td>
                            <td>
                                <div class="info-item">
                                    <div class="info-label">Country</div>
                                    <div class="info-value">{{$form->channel->country ?? 'No country specified'}}</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Product Description -->
            <div class="info-section">
                <h3 class="section-title">📦 General Product Description and Function</h3>
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
                    <div class="info-item-full">
                        <div class="info-label">Certification Requirement</div>
                        <div class="info-value">
                            {{ $form->certification->category ?? 'N/A' }}
                            @if($form->certification->other ?? '')
                                <br>{{ $form->certification->other }}
                            @endif
                        </div>
                    </div>
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
                        <div class="info-value">
                            {{ $form->packaging->category ?? 'N/A' }}
                            @if($form->packaging->detail ?? '')
                                <br>{{ $form->packaging->detail }}
                            @endif
                        </div>
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
            
            <!-- Action Buttons -->
            <div class="action-section">
                <h3 class="action-title">⚡ Take Action</h3>
                <p class="action-subtitle">Please review the request above and choose your action below</p>
                <div class="button-group">
                    <table>
                        <tr>
                            <td>
                                <a href="{{$approvalNotReviewLink}}" target="_blank" class="btn btn-approve">
                                    ✅ Approve
                                </a>
                            </td>
                            <td>
                                <a href="{{$approvalWithReviewLink}}" target="_blank" class="btn btn-review">
                                    📝 Approve with Review
                                </a>
                            </td>
                            <td>
                                <a href="{{$notApproveLink}}" target="_blank" class="btn btn-reject">
                                    ❌ Not Approve
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Important Note -->
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <h4 style="color: #856404; margin: 0 0 10px 0;">⚠️ Important Notice</h4>
                <ul style="color: #856404; margin: 0; padding-left: 20px;">
                    <li>This approval request is time-sensitive</li>
                    <li>Quick actions (Approve/Not Approve) will be processed immediately</li>
                    <li>Use "Approve with Review" if you need to add comments</li>
                    <li>Contact the requester directly if you need additional information</li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-content">
                <div class="company-info">PT Sinar Meadow International Indonesia</div>
                <div class="company-tagline">Marketing Product Development</div>
                <div class="footer-divider"></div>
                <div class="contact-info">
                    <strong>Need Help?</strong><br>
                    Kindly approve it at your earliest convenience so we can proceed.<br>
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