<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pre-MPDR Approval Request</title>
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
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }
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
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: bold; margin: 0; font-family: Arial, sans-serif;">Pre-MPDR Approval Request</h1>
                            <p style="color: #94a3b8; font-size: 13px; margin: 6px 0 0 0; font-family: Arial, sans-serif;">Pre-Marketing Product Development Request</p>
                        </td>
                    </tr>

                    <!-- GREETING -->
                    <tr>
                        <td style="padding: 25px 30px 15px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 15px 20px; color: #1e293b; font-size: 14px; line-height: 1.5;">
                                        <strong>Dear {{ $user->name ?? 'Approver' }},</strong><br/>
                                        A new Pre-MPDR request has been submitted for your review and approval. Please inspect the details below and choose your decision.
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
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Project Name</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->project_name ?? 'N/A' }}</div>
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
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Proposed BRAND Name</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->brand_name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Category</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->category->category ?? '' }} {{ $form->category->other ?? '' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Target Launch Date</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->detail->target_launch ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION: DEVELOPMENT RATIONAL & CHANNEL -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 13px; font-weight: bold;">
                                        🎯 DEVELOPMENT RATIONAL & CHANNEL
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Rational For Development</div>
                                            <div style="font-size: 13px; color: #0f172a;">{{ $form->detail->rational_for_development ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Channel Category</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->channel->category ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Country</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->channel->country ?? 'No country specified' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SECTION: GENERAL PRODUCT DESCRIPTION -->
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 13px; font-weight: bold;">
                                        📦 PRODUCT DESCRIPTION & FUNCTION
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 10px; border-radius: 0 0 6px 6px;">
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Product Description</div>
                                            <div style="font-size: 13px; color: #0f172a;">{!! $form->description->product_description ?? 'N/A' !!}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Usage Description</div>
                                            <div style="font-size: 13px; color: #0f172a;">{!! $form->description->usage_description ?? 'N/A' !!}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Storage Temperature</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->description->storage_temperature ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 5px;">
                                        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                                            <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 3px;">Delivery Temperature</div>
                                            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $form->description->delivery_temperature ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- COMPETITOR TABLE -->
                    @if(isset($form->competitor) && count($form->competitor) > 0)
                    <tr>
                        <td style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0f172a" style="background-color: #0f172a; border-radius: 6px 6px 0 0;">
                                <tr>
                                    <td style="padding: 10px 16px; color: #ffffff; font-size: 13px; font-weight: bold;">
                                        🏆 COMPETITOR'S PRODUCT TO MATCH OR BEAT
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 6px 6px;">
                                <tr bgcolor="#f1f5f9" style="background-color: #f1f5f9;">
                                    <th style="padding: 8px 12px; font-size: 12px; text-align: center; border-bottom: 1px solid #e2e8f0;">No</th>
                                    <th style="padding: 8px 12px; font-size: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Product Name</th>
                                    <th style="padding: 8px 12px; font-size: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Size</th>
                                    <th style="padding: 8px 12px; font-size: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Packaging</th>
                                    <th style="padding: 8px 12px; font-size: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Price</th>
                                </tr>
                                @foreach($form->competitor as $index => $competitor)
                                <tr>
                                    <td style="padding: 8px 12px; font-size: 12px; text-align: center; border-bottom: 1px solid #f1f5f9;">{{ $index + 1 }}</td>
                                    <td style="padding: 8px 12px; font-size: 12px; border-bottom: 1px solid #f1f5f9;">{{ $competitor->name ?? 'N/A' }}</td>
                                    <td style="padding: 8px 12px; font-size: 12px; border-bottom: 1px solid #f1f5f9;">{{ $competitor->size ?? 'N/A' }}</td>
                                    <td style="padding: 8px 12px; font-size: 12px; border-bottom: 1px solid #f1f5f9;">{{ $competitor->packaging ?? 'N/A' }}</td>
                                    <td style="padding: 8px 12px; font-size: 12px; border-bottom: 1px solid #f1f5f9;">{{ $competitor->price ?? 'N/A' }}</td>
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
                                        <table align="center" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                        <td align="center" valign="top">
                                        <![endif]-->
                                        <table border="0" cellpadding="0" cellspacing="0" align="center">
                                            <tr>
                                                <td align="center" bgcolor="#16a34a" style="background-color: #16a34a; border-radius: 6px; padding: 10px 18px;">
                                                    <a href="{{ $approvalNotReviewLink }}" target="_blank" style="color: #ffffff; font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; text-decoration: none; display: inline-block;">Approve</a>
                                                </td>
                                                <td width="10">&nbsp;</td>
                                                <td align="center" bgcolor="#d97706" style="background-color: #d97706; border-radius: 6px; padding: 10px 18px;">
                                                    <a href="{{ $approvalWithReviewLink }}" target="_blank" style="color: #ffffff; font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; text-decoration: none; display: inline-block;">Approve with Review</a>
                                                </td>
                                                <td width="10">&nbsp;</td>
                                                <td align="center" bgcolor="#dc2626" style="background-color: #dc2626; border-radius: 6px; padding: 10px 18px;">
                                                    <a href="{{ $notApproveLink }}" target="_blank" style="color: #ffffff; font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; text-decoration: none; display: inline-block;">Not Approve</a>
                                                </td>
                                            </tr>
                                        </table>
                                        <!--[if mso]>
                                        </td>
                                        </tr>
                                        </table>
                                        <![endif]-->
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