<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9f9f9; padding: 20px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #10b981; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="content">
            <h2>Training Enrollment Confirmation</h2>
            
            <p>Dear {{ $enrollment->trainee->name }},</p>
            
            <p>You have been successfully enrolled in the following training program:</p>
            
            <table style="width: 100%; margin: 20px 0; border-collapse: collapse;">
                <tr style="background-color: #e9e9e9;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Training Program:</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $enrollment->trainingBatch->trainingProgram->title }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Batch Code:</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $enrollment->trainingBatch->batch_code }}</td>
                </tr>
                <tr style="background-color: #e9e9e9;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Start Date:</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $enrollment->trainingBatch->start_date->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>End Date:</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $enrollment->trainingBatch->end_date->format('F d, Y') }}</td>
                </tr>
                <tr style="background-color: #e9e9e9;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Venue:</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $enrollment->trainingBatch->venue ?? 'To be announced' }}</td>
                </tr>
            </table>
            
            <p>Please ensure your attendance and active participation throughout the training program.</p>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/trainee/dashboard') }}" class="button">View Dashboard</a>
            </p>
            
            <p>If you have any questions, please contact the training department.</p>
            
            <p>Best regards,<br>
            Training Department<br>
            Local Government Department, KP</p>
        </div>
        <div class="footer">
            <p>© 2025 Local Government Department, Government of Khyber Pakhtunkhwa</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
