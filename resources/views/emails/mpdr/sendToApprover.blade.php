<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MPDR Approval Request</title>
    <!--[if gte mso 9]>
    <xml>
      <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f4f6f9; }
        span.MsoHyperlink { mso-style-priority: 99; color: inherit !important; }
        span.MsoHyperlinkFollowed { mso-style-priority: 99; color: inherit !important; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Arial, Helvetica, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f4f6f9" style="background-color: #f4f6f9; table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 20px 10px;">
                <!--[if (gte mso 9)|(IE)]>
                <table align="center" border="0" cellspacing="0" cellpadding="0" width="650">
                <tr>
                <td align="center" valign="top" width="650">
                <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 650px; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, Helvetica, sans-serif;">
                    
                    <!-- HEADER -->
                    <tr>
                        <td bgcolor="#1e293b" align="center" style="background-color: #1e293b; padding: 25px 30px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: bold; margin: 0; font-family: Arial, sans-serif;">MPDR Approval Request</h1>
                            <p style="color: #94a3b8; font-size: 13px; margin: 6px 0 0 0; font-family: Arial, sans-serif;">Marketing Product Development Request</p>
                        </td>
                    </tr>

                    <!-- GREETING -->
                    <tr>
                        <td style="padding: 25px 30px 15px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 15px 20px; color: #1e293b; font-size: 14px; line-height: 1.5;">
                                        <strong>Dear {{ $user->name ?? 'Approver' }},</strong><br/>
                                        A new MPDR request has been submitted for your review and approval. Please inspect the details below and choose your decision.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION: REQUEST INFORMATION -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 13px; font-weight: bold;">
                                        📄 REQUEST INFORMATION
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Registration Number</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->no }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Name</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->product_name ?: 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Level Priority</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->level_priority ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Request Type</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->type_of_request ?: 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                @if($form->preMpdr)
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Pre-MPDR Project Name</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->preMpdr->project_name ?: 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Pre-MPDR Brand Name</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->preMpdr->brand_name ?: 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Initiator</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->initiatorDetail->initiator_name ?? $form->initiator ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Requester</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->requester->name ?? $form->user->name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION: BUSINESS REQUEST -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 13px; font-weight: bold;">BUSINESS REQUEST</td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Rationale for Development</div>
                                            <div style="font-size: 13px; color: #0f172a;">{!! nl2br(e($form->detail->rational_for_development ?? 'N/A')) !!}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Category</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->category->category ?? 'N/A' }}{{ !empty($form->category->other) ? ': ' . $form->category->other : '' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Target Launch</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->detail->target_launch ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Channel</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->channel->category ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Country</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->channel->country ?: 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION: PRODUCT DESCRIPTION -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 13px; font-weight: bold;">PRODUCT DESCRIPTION & REQUIREMENTS</td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Description</div>
                                            <div style="font-size: 13px; color: #0f172a;">{!! nl2br(e($form->description->product_description ?? 'N/A')) !!}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Usage Description</div>
                                            <div style="font-size: 13px; color: #0f172a;">{!! nl2br(e($form->description->usage_description ?? 'N/A')) !!}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Storage Temperature</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->description->storage_temperature ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Delivery Temperature</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->description->delivery_temperature ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Certification Requirement</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->certification->category ?? 'N/A' }}{{ !empty($form->certification->other) ? ': ' . $form->certification->other : '' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Variation</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->packaging->product_variation ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION: PACKAGING AND MARKET -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 13px; font-weight: bold;">PACKAGING & MARKET INFORMATION</td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Packaging</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->packaging->category ?? 'N/A' }}{{ !empty($form->packaging->detail) ? ': ' . $form->packaging->detail : '' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Weight</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->packaging->weight ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Potential Volume (Mt/Annually)</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->market->potential_volume ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Expected Margin (%)</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->market->expected_margin ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Price Estimate</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->market->price_estimate ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @if($form->competitor->isNotEmpty())
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 13px; font-weight: bold;">COMPETITOR PRODUCTS</td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border: 1px solid #e2e8f0; font-size: 12px;">
                                <tr bgcolor="#f8fafc">
                                    <th align="left" style="padding: 9px; border: 1px solid #e2e8f0;">Product</th>
                                    <th align="left" style="padding: 9px; border: 1px solid #e2e8f0;">Size</th>
                                    <th align="left" style="padding: 9px; border: 1px solid #e2e8f0;">Packaging</th>
                                    <th align="left" style="padding: 9px; border: 1px solid #e2e8f0;">Price</th>
                                </tr>
                                @foreach($form->competitor as $competitor)
                                <tr>
                                    <td style="padding: 9px; border: 1px solid #e2e8f0;">{{ $competitor->name ?: 'N/A' }}</td>
                                    <td style="padding: 9px; border: 1px solid #e2e8f0;">{{ $competitor->size ?: 'N/A' }}</td>
                                    <td style="padding: 9px; border: 1px solid #e2e8f0;">{{ $competitor->packaging ?: 'N/A' }}</td>
                                    <td style="padding: 9px; border: 1px solid #e2e8f0;">{{ $competitor->price ?: 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    @endif

                    <!-- SECTION: APPROVAL WORKFLOW PROGRESS -->
                    @if(isset($form->approvedDetail) && $form->approvedDetail->count() > 0)
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 12px; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase;">
                                        👥 APPROVAL WORKFLOW PROGRESS
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 6px 6px; border-collapse: collapse;">
                                <tr bgcolor="#f1f5f9" style="background-color: #f1f5f9;">
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Level / Role</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Approver Name</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: center; border: 1px solid #e2e8f0; color: #475569;">Status</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Decision Date</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Comments</th>
                                </tr>
                                @foreach($form->approvedDetail->sortBy([['level', 'asc'], ['id', 'asc']]) as $detail)
                                @php
                                    $st = strtolower(trim($detail->status ?? 'pending'));
                                    $stBadge = match($st) {
                                        'approve', 'approved' => ['bg' => '#16a34a', 'text' => '#ffffff', 'label' => 'Approved'],
                                        'approve with review' => ['bg' => '#d97706', 'text' => '#ffffff', 'label' => 'Approved w/ Review'],
                                        'not approve', 'not approved', 'rejected' => ['bg' => '#dc2626', 'text' => '#ffffff', 'label' => 'Not Approved'],
                                        'vacant' => ['bg' => '#94a3b8', 'text' => '#ffffff', 'label' => 'Vacant'],
                                        default => ['bg' => '#0284c7', 'text' => '#ffffff', 'label' => 'Pending']
                                    };
                                @endphp
                                <tr>
                                    <td style="padding: 8px 10px; font-size: 11px; border: 1px solid #e2e8f0; color: #334155; font-weight: 600;">
                                        {{ $detail->approver_position ?: 'Level ' . ($detail->level ?? '-') }}
                                    </td>
                                    <td style="padding: 8px 10px; font-size: 12px; border: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">
                                        {{ $detail->approver_name ?? $detail->name ?? $detail->approver_nik ?? $detail->approver ?? 'N/A' }}
                                    </td>
                                    <td align="center" style="padding: 8px 10px; border: 1px solid #e2e8f0;">
                                        <span style="display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700; background-color: {{ $stBadge['bg'] }}; color: {{ $stBadge['text'] }};">
                                            {{ $stBadge['label'] }}
                                        </span>
                                    </td>
                                    <td style="padding: 8px 10px; font-size: 11px; border: 1px solid #e2e8f0; color: #64748b;">
                                        {{ $detail->approved_date ? \Carbon\Carbon::parse($detail->approved_date)->format('d M Y, H:i') : '—' }}
                                    </td>
                                    <td style="padding: 8px 10px; font-size: 11px; border: 1px solid #e2e8f0; color: #334155; font-style: italic;">
                                        {{ $detail->comment ?: '—' }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    @endif

                    <!-- ACTION BUTTONS -->
                    <tr>
                        <td style="padding: 15px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 2px solid #334155; border-radius: 8px; padding: 20px; text-align: center;">
                                <tr>
                                    <td align="center" style="font-size: 16px; font-weight: bold; color: #0f172a; padding-bottom: 6px;">⚡ TAKE ACTION</td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-size: 13px; color: #64748b; padding-bottom: 18px;">Please review the request above and select your decision:</td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <!--[if mso]>
                                        <table align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                                        <tr>
                                        <td align="center" valign="middle">
                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $approvalNotReviewLink }}" style="height:44px;v-text-anchor:middle;width:145px;" arcsize="14%" stroke="f" strokecolor="#16a34a" fillcolor="#16a34a">
                                            <w:anchorlock/>
                                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:13px;font-weight:bold;">
                                                <font color="#ffffff"><span style="color:#ffffff !important;text-decoration:none !important;">Approve</span></font>
                                            </center>
                                            </v:roundrect>
                                        </td>
                                        <td width="10">&nbsp;</td>
                                        <td align="center" valign="middle">
                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $approvalWithReviewLink }}" style="height:44px;v-text-anchor:middle;width:195px;" arcsize="14%" stroke="f" strokecolor="#d97706" fillcolor="#d97706">
                                            <w:anchorlock/>
                                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:13px;font-weight:bold;">
                                                <font color="#ffffff"><span style="color:#ffffff !important;text-decoration:none !important;">Approve with Review</span></font>
                                            </center>
                                            </v:roundrect>
                                        </td>
                                        <td width="10">&nbsp;</td>
                                        <td align="center" valign="middle">
                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $notApproveLink }}" style="height:44px;v-text-anchor:middle;width:145px;" arcsize="14%" stroke="f" strokecolor="#dc2626" fillcolor="#dc2626">
                                            <w:anchorlock/>
                                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:13px;font-weight:bold;">
                                                <font color="#ffffff"><span style="color:#ffffff !important;text-decoration:none !important;">Not Approve</span></font>
                                            </center>
                                            </v:roundrect>
                                        </td>
                                        </tr>
                                        </table>
                                        <![endif]-->
                                        <!--[if !mso]><!-- -->
                                        <table border="0" cellpadding="0" cellspacing="0" align="center" style="margin: 0 auto; border-collapse: separate;">
                                            <tr>
                                                <td align="center" bgcolor="#16a34a" style="background-color: #16a34a; border-radius: 6px;">
                                                    <a href="{{ $approvalNotReviewLink }}" target="_blank" style="color: #ffffff !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 700; text-decoration: none !important; display: block; padding: 12px 22px; border-radius: 6px; background-color: #16a34a; line-height: 120%;">
                                                        <span style="color: #ffffff !important; text-decoration: none !important;">Approve</span>
                                                    </a>
                                                </td>
                                                <td width="10">&nbsp;</td>
                                                <td align="center" bgcolor="#d97706" style="background-color: #d97706; border-radius: 6px;">
                                                    <a href="{{ $approvalWithReviewLink }}" target="_blank" style="color: #ffffff !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 700; text-decoration: none !important; display: block; padding: 12px 22px; border-radius: 6px; background-color: #d97706; line-height: 120%;">
                                                        <span style="color: #ffffff !important; text-decoration: none !important;">Approve with Review</span>
                                                    </a>
                                                </td>
                                                <td width="10">&nbsp;</td>
                                                <td align="center" bgcolor="#dc2626" style="background-color: #dc2626; border-radius: 6px;">
                                                    <a href="{{ $notApproveLink }}" target="_blank" style="color: #ffffff !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 700; text-decoration: none !important; display: block; padding: 12px 22px; border-radius: 6px; background-color: #dc2626; line-height: 120%;">
                                                        <span style="color: #ffffff !important; text-decoration: none !important;">Not Approve</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                        <!--<![endif]-->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td bgcolor="#0f172a" align="center" style="background-color: #0f172a; padding: 25px 30px; text-align: center; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                            <strong style="color: #ffffff; font-size: 14px;">PT Sinar Meadow International Indonesia</strong><br/>
                            Marketing Product Development Department<br/>
                            <div style="height: 1px; background-color: #334155; margin: 15px 0;"></div>
                            This is an automated notification. Please process this request at your earliest convenience.<br/>
                            © {{ date('Y') }} PT Sinar Meadow International Indonesia. All rights reserved.
                        </td>
                    </tr>

                </table>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
