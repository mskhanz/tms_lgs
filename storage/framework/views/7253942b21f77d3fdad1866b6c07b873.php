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
            <h1><?php echo e(config('app.name')); ?></h1>
        </div>
        <div class="content">
            <h2>New Assignment</h2>

            <p>Dear <?php echo e($trainee->name); ?>,</p>

            <p>A new assignment has been published for your training:</p>

            <table style="width: 100%; margin: 20px 0; border-collapse: collapse;">
                <tr style="background-color: #e9e9e9;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Title:</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo e($assignment->title); ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Assigned to:</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo e($assignment->assignmentLabel()); ?></td>
                </tr>
                <tr style="background-color: #e9e9e9;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>Due date:</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <?php echo e($assignment->due_at ? $assignment->due_at->format('F d, Y h:i A') : 'No due date'); ?>

                    </td>
                </tr>
            </table>

            <?php if($assignment->instructions): ?>
            <p><strong>Instructions:</strong></p>
            <p><?php echo e(\Illuminate\Support\Str::limit(strip_tags($assignment->instructions), 300)); ?></p>
            <?php endif; ?>

            <p style="text-align: center; margin: 30px 0;">
                <a href="<?php echo e(route('trainee.assignments.show', $assignment)); ?>" class="button">Open Assignment</a>
            </p>

            <p>Please complete and submit your work before the due date.</p>

            <p>Best regards,<br>
            Training Department<br>
            Local Governance School, KP</p>
        </div>
        <div class="footer">
            <p>&copy; <?php echo e(date('Y')); ?> Local Governance School, Government of Khyber Pakhtunkhwa</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/emails/assignment-published.blade.php ENDPATH**/ ?>