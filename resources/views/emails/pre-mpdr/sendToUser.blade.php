<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pre-MPDR Status Update</title>
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
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f1f5f9; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }
        span.MsoHyperlink { mso-style-priority: 99; color: inherit !important; }
        span.MsoHyperlinkFollowed { mso-style-priority: 99; color: inherit !important; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f1f5f9" style="background-color: #f1f5f9; table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 25px 12px;">
                <!--[if (gte mso 9)|(IE)]>
                <table align="center" border="0" cellspacing="0" cellpadding="0" width="680">
                <tr>
                <td align="center" valign="top" width="680">
                <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 680px; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.06); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                    
                    <!-- HEADER -->
                    <tr>
                        <td bgcolor="#0f172a" align="center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 28px 30px; text-align: center; border-bottom: 3px solid {{ $form->status == 'Approved' ? '#16a34a' : ($form->status == 'Rejected' ? '#dc2626' : '#2563eb') }};">
                            <div style="display: inline-block; padding: 4px 12px; background-color: rgba(37,99,235,0.25); border: 1px solid rgba(147,197,253,0.3); border-radius: 20px; color: #93c5fd; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                                Pre-MPDR Notification
                            </div>
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 700; margin: 0 0 6px 0; letter-spacing: -0.3px;">Pre-MPDR Status Update</h1>
                            <p style="color: #94a3b8; font-size: 13px; margin: 0;">Pre-Marketing Product Development Request &bull; Form: <strong style="color: #f8fafc;">{{ $form->no }}</strong> (Rev {{ optional($form->revision)->no ?? optional($form->revision)->revision ?? '0' }})</p>
                        </td>
                    </tr>

                    @php
                        $statusTheme = match($form->status) {
                            'Approved' => ['border' => '#16a34a', 'bg' => '#f0fdf4', 'badge_bg' => '#16a34a', 'badge_text' => '#ffffff', 'title' => 'Form Approved', 'text' => 'Congratulations! Your Pre-MPDR request has successfully passed all approvals.'],
                            'Rejected' => ['border' => '#dc2626', 'bg' => '#fef2f2', 'badge_bg' => '#dc2626', 'badge_text' => '#ffffff', 'title' => 'Form Rejected', 'text' => 'Your Pre-MPDR request has been rejected. Please review the feedback notes below.'],
                            'In Approval' => ['border' => '#2563eb', 'bg' => '#eff6ff', 'badge_bg' => '#2563eb', 'badge_text' => '#ffffff', 'title' => 'Progress Update', 'text' => 'An approval step was completed for your Pre-MPDR form. It is progressing through the workflow.'],
                            default => ['border' => '#64748b', 'bg' => '#f8fafc', 'badge_bg' => '#64748b', 'badge_text' => '#ffffff', 'title' => 'Status Notification', 'text' => 'Your Pre-MPDR request status has been updated.']
                        };

                        $latestApproval = null;
                        if (isset($form->approvedDetail)) {
                            $latestApproval = $form->approvedDetail->whereNotNull('status')->sortByDesc('approved_date')->first();
                        }
                    @endphp

                    <!-- STATUS HIGHLIGHT BANNER -->
                    <tr>
                        <td style="padding: 24px 30px 14px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="{{ $statusTheme['bg'] }}" style="background-color: {{ $statusTheme['bg'] }}; border-left: 5px solid {{ $statusTheme['border'] }}; border-radius: 6px; border: 1px solid #e2e8f0; border-left-width: 5px; border-left-color: {{ $statusTheme['border'] }};">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                            <span style="font-size: 15px; font-weight: 700; color: #0f172a;">Dear {{ $user->name ?? 'User' }},</span>
                                            <span style="display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; background-color: {{ $statusTheme['badge_bg'] }}; color: {{ $statusTheme['badge_text'] }}; text-transform: uppercase;">
                                                {{ $form->status }}
                                            </span>
                                        </div>
                                        <div style="font-size: 14px; color: #334155; line-height: 1.5; margin-bottom: 10px;">
                                            {{ $statusTheme['text'] }}
                                        </div>
                                        @if($latestApproval)
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; margin-top: 8px;">
                                            <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 2px;">
                                                📌 Latest Decision by: <strong style="color: #0f172a;">{{ $latestApproval->name ?? $latestApproval->approver }}</strong> ({{ $latestApproval->approver_position ?: 'Level ' . ($latestApproval->level ?? '-') }})
                                            </div>
                                            <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">
                                                Decision: <strong style="color: {{ $statusTheme['border'] }};">{{ ucfirst($latestApproval->status) }}</strong> on {{ $latestApproval->approved_date ? \Carbon\Carbon::parse($latestApproval->approved_date)->format('d M Y, H:i') : '-' }}
                                            </div>
                                            @if($latestApproval->comment)
                                            <div style="font-size: 12px; color: #334155; background-color: #f8fafc; border-left: 3px solid {{ $statusTheme['border'] }}; padding: 6px 10px; border-radius: 0 4px 4px 0; font-style: italic;">
                                                "{{ $latestApproval->comment }}"
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- QUICK ACTIONS (DOWNLOAD PDF / VIEW PORTAL) -->
                    <tr>
                        <td style="padding: 0 30px 16px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    @if(isset($downloadLink) && !empty($downloadLink))
                                    <td width="50%" align="center" valign="middle" style="padding-right: 6px;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $downloadLink }}" style="height:44px;v-text-anchor:middle;width:290px;" arcsize="12%" stroke="f" strokecolor="#0f172a" fillcolor="#0f172a">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:13px;font-weight:bold;">
                                            <font color="#ffffff"><span style="color:#ffffff !important;text-decoration:none !important;">Download / Print PDF</span></font>
                                        </center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-- -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate;">
                                            <tr>
                                                <td align="center" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px;">
                                                    <a href="{{ $downloadLink }}" target="_blank" style="display: block; text-align: center; color: #ffffff !important; padding: 12px 16px; font-size: 13px; font-weight: 700; text-decoration: none !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 120%;">
                                                        <span style="color: #ffffff !important; text-decoration: none !important;">Download / Print PDF</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                        <!--<![endif]-->
                                    </td>
                                    @endif
                                    <td width="{{ isset($downloadLink) && !empty($downloadLink) ? '50%' : '100%' }}" align="center" valign="middle" style="padding-left: {{ isset($downloadLink) && !empty($downloadLink) ? '6px' : '0' }};">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ route('prempdr.index') }}" style="height:44px;v-text-anchor:middle;width:{{ isset($downloadLink) && !empty($downloadLink) ? '290px' : '600px' }};" arcsize="12%" stroke="f" strokecolor="#2563eb" fillcolor="#2563eb">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:13px;font-weight:bold;">
                                            <font color="#ffffff"><span style="color:#ffffff !important;text-decoration:none !important;">Open Pre-MPDR Portal &rarr;</span></font>
                                        </center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-- -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate;">
                                            <tr>
                                                <td align="center" bgcolor="#2563eb" style="background-color: #2563eb; border-radius: 6px;">
                                                    <a href="{{ route('prempdr.index') }}" target="_blank" style="display: block; text-align: center; color: #ffffff !important; padding: 12px 16px; font-size: 13px; font-weight: 700; text-decoration: none !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 120%;">
                                                        <span style="color: #ffffff !important; text-decoration: none !important;">Open Pre-MPDR Portal &rarr;</span>
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

                    <!-- SECTION 1: REQUEST INFORMATION -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        📄 1. Request Information
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Registration Number</div>
                                            <div style="font-size: 14px; font-weight: 700; color: #0f172a;">{{ $form->no }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Revision Number</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">Rev {{ optional($form->revision)->no ?? optional($form->revision)->revision ?? '0' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Project Name</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->project_name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Proposed BRAND Name</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->brand_name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Level Priority</div>
                                            <div>
                                                @php
                                                    $pColor = match(strtolower($form->level_priority ?? '')) {
                                                        'high' => '#dc2626',
                                                        'medium' => '#d97706',
                                                        'low' => '#16a34a',
                                                        default => '#475569'
                                                    };
                                                @endphp
                                                <span style="display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; color: #ffffff; background-color: {{ $pColor }};">
                                                    {{ ucfirst($form->level_priority ?? 'Normal') }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Requester / Submitter</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->user->name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Submission Date</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->created_at ? $form->created_at->format('d M Y, H:i') : 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Target Launch Date</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->detail->target_launch ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION 2: BUSINESS RATIONALE & CHANNEL -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        🎯 2. Development Rational & Channel
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Rational For Development</div>
                                            <div style="font-size: 13px; color: #0f172a; line-height: 1.5;">{!! nl2br(e($form->detail->rational_for_development ?? 'N/A')) !!}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Category</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->category->category ?? 'N/A' }}{{ !empty($form->category->other) ? ' (' . $form->category->other . ')' : '' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Sales Channel & Country</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->channel->category ?? 'N/A' }}{{ !empty($form->channel->country) ? ' (' . $form->channel->country . ')' : '' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Certification Requirements</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->certification->category ?? 'N/A' }}{{ !empty($form->certification->other) ? ' - ' . $form->certification->other : '' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION 3: PRODUCT DESCRIPTION & FUNCTION -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        📦 3. Product Description & Requirements
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Product Description (Form / Color / Flavor)</div>
                                            <div style="font-size: 13px; color: #0f172a; line-height: 1.5;">{!! nl2br(e($form->description->product_description ?? 'N/A')) !!}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Usage Description (Application in Customer)</div>
                                            <div style="font-size: 13px; color: #0f172a; line-height: 1.5;">{!! nl2br(e($form->description->usage_description ?? 'N/A')) !!}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Storage Temperature</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->description->storage_temperature ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Delivery Temperature</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->description->delivery_temperature ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION 4: PACKAGING & MARKET INFORMATION -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        📊 4. Packaging Details & Market Estimates
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Packaging Category & Details</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">
                                                {{ $form->packaging->category ?? 'N/A' }}
                                                @if(!empty($form->packaging?->detail))
                                                    <span style="font-weight: normal; color: #475569;">({{ $form->packaging->detail }})</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Weight</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->packaging->weight ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Variation List</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->packaging->product_variation ?: 'None specified' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="33.33%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Potential Volume</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $form->market->potential_volume ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="33.33%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Expected Margin</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #16a34a;">{{ $form->market->expected_margin ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="33.33%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; height: 100%;">
                                            <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Price Estimate</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #2563eb;">{{ $form->market->price_estimate ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION 5: COMPETITOR PRODUCTS -->
                    @if(isset($form->competitor) && count($form->competitor) > 0)
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        🏆 5. Competitor's Product to Match / Beat
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 6px 6px; border-collapse: collapse;">
                                <tr bgcolor="#f1f5f9" style="background-color: #f1f5f9;">
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: center; border: 1px solid #e2e8f0; color: #475569;">No</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Product Name</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Size</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Packaging</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Price Indication</th>
                                </tr>
                                @foreach($form->competitor as $index => $competitor)
                                <tr>
                                    <td align="center" style="padding: 8px 10px; font-size: 12px; border: 1px solid #e2e8f0; color: #64748b;">{{ $index + 1 }}</td>
                                    <td style="padding: 8px 10px; font-size: 12px; font-weight: 600; border: 1px solid #e2e8f0; color: #0f172a;">{{ $competitor->name ?? 'N/A' }}</td>
                                    <td style="padding: 8px 10px; font-size: 12px; border: 1px solid #e2e8f0; color: #334155;">{{ $competitor->size ?? 'N/A' }}</td>
                                    <td style="padding: 8px 10px; font-size: 12px; border: 1px solid #e2e8f0; color: #334155;">{{ $competitor->packaging ?? 'N/A' }}</td>
                                    <td style="padding: 8px 10px; font-size: 12px; font-weight: 700; border: 1px solid #e2e8f0; color: #16a34a;">{{ $competitor->price ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    @endif

                    <!-- SECTION 6: COMPLETE APPROVAL WORKFLOW PROGRESS -->
                    @if(isset($form->approvedDetail) && $form->approvedDetail->count() > 0)
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">
                                        👥 6. Approval Workflow Progress
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 6px 6px; border-collapse: collapse;">
                                <tr bgcolor="#f1f5f9" style="background-color: #f1f5f9;">
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Level / Role</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Approver Name</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: center; border: 1px solid #e2e8f0; color: #475569;">Status</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Decision Date</th>
                                    <th style="padding: 8px 10px; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">Comments / Notes</th>
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
                                        {{ $detail->name ?? $detail->approver ?? 'N/A' }}
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

                    <!-- FOOTER ACTIONS -->
                    <tr>
                        <td style="padding: 15px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <tr>
                                    <td align="center" style="padding: 16px 20px; text-align: center;">
                                        <div style="font-size: 12px; color: #64748b; margin-bottom: 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                                            Need to track your request in the web portal or generate formal reports?
                                        </div>
                                        <div>
                                            <a href="{{ route('prempdr.form', ['no_reg' => $form->no]) }}" target="_blank" style="font-size: 13px; font-weight: 700; color: #2563eb; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; display: inline-block; padding: 4px 10px;">
                                                Open Pre-MPDR in Portal &rarr;
                                            </a>
                                            @if(isset($downloadLink) && !empty($downloadLink))
                                            <span style="color: #cbd5e1; margin: 0 4px;">|</span>
                                            <a href="{{ $downloadLink }}" target="_blank" style="font-size: 13px; font-weight: 700; color: #0f172a; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; display: inline-block; padding: 4px 10px;">
                                                Print Form PDF &rarr;
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td bgcolor="#0f172a" align="center" style="background-color: #0f172a; padding: 25px 30px; text-align: center; color: #94a3b8; font-size: 12px; line-height: 1.6; border-top: 1px solid #1e293b;">
                            <strong style="color: #ffffff; font-size: 13px;">PT Sinar Meadow International Indonesia</strong><br/>
                            Marketing Product Development Department<br/>
                            <div style="height: 1px; background-color: #334155; margin: 15px auto; width: 60%;"></div>
                            This is an automated status update. Please do not reply directly to this email.<br/>
                            &copy; {{ date('Y') }} PT Sinar Meadow International Indonesia. All rights reserved.
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