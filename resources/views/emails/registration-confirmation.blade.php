<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmation - {{ config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f7f4;font-family:'Segoe UI',Tahoma,sans-serif;color:#1b2e24;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f7f4;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border:1px solid #d8e6dc;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#059669,#10b981);padding:22px 24px;color:#ffffff;">
                            <div style="font-size:18px;font-weight:700;">{{ config('app.name') }}</div>
                            <div style="font-size:13px;opacity:0.9;margin-top:4px;">Registration Confirmation</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 12px;font-size:15px;">Hello {{ $user->name }},</p>
                            <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#31463b;">
                                Your trainee account has been created successfully. Please save your login details below and verify your email to activate your account.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:20px 0;border-collapse:collapse;border:1px solid #d8e6dc;border-radius:10px;overflow:hidden;">
                                <tr>
                                    <td style="padding:12px 16px;background:#f0fdf4;font-size:13px;font-weight:700;color:#065f46;border-bottom:1px solid #d8e6dc;" colspan="2">
                                        Your Login Credentials
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;font-size:13px;color:#5f7368;border-bottom:1px solid #eef4f0;width:35%;"><strong>Selected Training</strong></td>
                                    <td style="padding:12px 16px;font-size:14px;color:#1b2e24;border-bottom:1px solid #eef4f0;">{{ $training->title }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;font-size:13px;color:#5f7368;border-bottom:1px solid #eef4f0;width:35%;"><strong>Login Email</strong></td>
                                    <td style="padding:12px 16px;font-size:14px;color:#1b2e24;border-bottom:1px solid #eef4f0;">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;font-size:13px;color:#5f7368;width:35%;"><strong>Password</strong></td>
                                    <td style="padding:12px 16px;font-size:14px;color:#1b2e24;font-family:monospace;">{{ $plainPassword }}</td>
                                </tr>
                            </table>

                            <p style="margin:0 0 16px;font-size:13px;color:#b45309;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px 12px;">
                                Please keep this email secure. Do not share your password with anyone.
                            </p>

                            <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#31463b;">
                                Click the button below to verify your email address and complete your registration:
                            </p>

                            <p style="text-align:center;margin:24px 0;">
                                <a href="{{ $verificationUrl }}"
                                   style="display:inline-block;background:#10b981;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:10px;font-weight:700;">
                                    Verify Email &amp; Activate Account
                                </a>
                            </p>

                            <p style="margin:0 0 12px;font-size:12px;color:#5f7368;line-height:1.6;">
                                If the button does not work, copy and paste this link into your browser:<br>
                                <a href="{{ $verificationUrl }}" style="color:#059669;word-break:break-all;">{{ $verificationUrl }}</a>
                            </p>

                            <p style="margin:16px 0 0;font-size:14px;line-height:1.6;color:#31463b;">
                                After verification, you will be redirected to the home page and can sign in at:<br>
                                <a href="{{ route('login') }}" style="color:#059669;">{{ route('login') }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 24px;background:#fafcfa;border-top:1px solid #eef4f0;font-size:11px;color:#5f7368;">
                            This is an automated message from {{ config('app.name') }}. Local Government School, KP.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
