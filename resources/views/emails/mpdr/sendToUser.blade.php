<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MPDR Status Update</title>
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
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: bold; margin: 0; font-family: Arial, sans-serif;">MPDR Status Update</h1>
                            <p style="color: #94a3b8; font-size: 13px; margin: 6px 0 0 0; font-family: Arial, sans-serif;">Marketing Product Development Request</p>
                        </td>
                    </tr>

                    <!-- GREETING & STATUS BADGE -->
                    <tr>
                        <td style="padding: 25px 30px 15px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 15px 20px; color: #1e293b; font-size: 14px; line-height: 1.5;">
                                        <strong>Dear {{ $user->name ?? 'User' }},</strong><br/>
                                        Your MPDR request <strong>{{ $form->no }}</strong> status has been updated to: 
                                        <span style="font-weight: bold; color: {{ $form->status == 'Approved' ? '#16a34a' : ($form->status == 'Rejected' ? '#dc2626' : '#2563eb') }}; font-size: 15px;">
                                            {{ $form->status }}
                                        </span>
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
                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td bgcolor="#0f172a" align="center" style="background-color: #0f172a; padding: 25px 30px; text-align: center; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                            <strong style="color: #ffffff; font-size: 14px;">PT Sinar Meadow International Indonesia</strong><br/>
                            Marketing Product Development Department<br/>
                            <div style="height: 1px; background-color: #334155; margin: 15px 0;"></div>
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
