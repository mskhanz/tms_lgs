<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - {{ config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f7f4;font-family:'Segoe UI',Tahoma,sans-serif;color:#1b2e24;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f7f4;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border:1px solid #d8e6dc;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#059669,#10b981);padding:22px 24px;color:#ffffff;">
                            <div style="font-size:18px;font-weight:700;">{{ config('app.name') }}</div>
                            <div style="font-size:13px;opacity:0.9;margin-top:4px;">Email Verification Required</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 12px;font-size:15px;">Hello {{ $user->name }},</p>
                            <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#31463b;">
                                Thank you for registering with {{ config('app.name') }}.
                                Please verify your email address by clicking the button below to complete your registration.
                            </p>
                            <p style="text-align:center;margin:24px 0;">
                                <a href="{{ $url }}"
                                   style="display:inline-block;background:#10b981;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:10px;font-weight:700;">
                                    Verify Email Address
                                </a>
                            </p>
                            <p style="margin:0 0 12px;font-size:12px;color:#5f7368;line-height:1.6;">
                                If the button does not work, copy and paste this link into your browser:<br>
                                <a href="{{ $url }}" style="color:#059669;word-break:break-all;">{{ $url }}</a>
                            </p>
                            <p style="margin:16px 0 0;font-size:12px;color:#5f7368;">
                                If you did not create an account, no further action is required.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 24px;background:#fafcfa;border-top:1px solid #eef4f0;font-size:11px;color:#5f7368;">
                            This is an automated message from {{ config('app.name') }}. Do not share this link with anyone.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
