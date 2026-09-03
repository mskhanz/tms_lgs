# LGS Training Management System (LGS-TMS)

A comprehensive training management system built for the Local Government Department, Government of Khyber Pakhtunkhwa, Pakistan.

## 🎯 Overview

LGS-TMS is a government-grade training management system designed to streamline training operations, manage trainee profiles, handle enrollments, track attendance, evaluate assessments, and issue digital certificates.

## ✨ Key Features

### Core Modules

1. **Trainee Registration & Profile Management**
   - Official training email verification required
   - Comprehensive profile with personal, posting, and qualification details
   - Trainer-completed profile with trainee editing capability
   - Document upload support (CNIC, photos, certificates)

2. **Role-Based Access Control (RBAC)**
   - System Admin
   - Director
   - Deputy Director
   - Training Officer
   - Department Admin
   - Institute Admin
   - Trainer
   - Trainee
   - Auditor (read-only)

3. **Training Programs & Batches**
   - Course and training program management
   - Categories: Mandatory, Refresher, Optional
   - Batch scheduling with capacity management
   - Trainer assignment
   - Approval workflows

4. **Enrollment Management**
   - Admin/Training Officer enrollment
   - Department-based nominations
   - Automatic seat management
   - Email notifications to trainees

5. **Attendance Tracking**
   - Session-based attendance
   - Multiple marking methods: Manual, QR Code, Biometric
   - Attendance percentage calculation

6. **Assessment & Evaluation**
   - Pre-test, Post-test, Quizzes, Assignments
   - Trainer evaluation
   - Admin approval workflow
   - Grading system

7. **Certification**
   - Digital certificate generation
   - QR code for verification
   - Approval workflow
   - PDF export

8. **Comprehensive Dashboards**
   - Trainee: Enrollments, certificates, notifications
   - Admin: Statistics, enrollments, upcoming batches
   - Responsive design for mobile, tablet, desktop

9. **Notification System**
   - In-app notifications
   - Email notifications (via mis.lcb.kp@gmail.com)
   - Queue-based email sending

10. **Activity Logging & Audit Trail**
    - Full action logging
    - User activity tracking
    - IP address and user agent capture

## 🛠️ Technical Stack

- **Framework:** Laravel 10 (PHP 8.1+)
- **Database:** MySQL (lgs_tms)
- **Frontend:** TailwindCSS 3+, Alpine.js
- **Authentication:** Laravel Breeze (Email Verification Required)
- **Icons:** Font Awesome 6
- **Email:** SMTP (configured for Gmail)

## 📋 System Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- XAMPP or similar local development environment

## 🚀 Installation & Setup

### 1. Navigate to Project
```bash
cd c:\xampp\htdocs\tms_lgs
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
The `.env` file is already configured with:
- Database: `lgs_tms`
- Mail: Gmail SMTP (update password)

Update the mail password in `.env`:
```env
MAIL_PASSWORD=your_gmail_app_password_here
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```

### 6. Start Development Server
```bash
php artisan serve
```

Visit: http://localhost:8000

## 👥 Default User Credentials

### System Admin
- **Email:** admin@lgstms.kp.gov.pk
- **Password:** password

### Director
- **Email:** director@lgstms.kp.gov.pk
- **Password:** password

### Deputy Director
- **Email:** deputydirector@lgstms.kp.gov.pk
- **Password:** password

### Training Officer
- **Email:** training.officer@lgstms.kp.gov.pk
- **Password:** password

### Trainer
- **Email:** trainer@lgstms.kp.gov.pk
- **Password:** password

### Trainee (Profile Incomplete)
- **Email:** trainee@lgstms.kp.gov.pk
- **Password:** password

### Trainee (Profile Complete)
- **Email:** trainee2@lgstms.kp.gov.pk
- **Password:** password

## 📁 Project Structure

```
tms_lgs/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/           # Authentication controllers
│   │   ├── Admin/          # Admin controllers
│   │   └── Trainee/        # Trainee controllers
│   ├── Models/             # Eloquent models
│   ├── Mail/               # Mailable classes
│   └── Helpers/            # Helper functions
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── resources/views/
│   ├── auth/               # Authentication views
│   ├── admin/              # Admin dashboards
│   ├── trainee/            # Trainee dashboards
│   ├── emails/             # Email templates
│   └── layouts/            # Layout templates
└── routes/
    └── web.php             # Web routes
```

## 🔒 Security Features

- Email verification mandatory
- Password hashing (bcrypt)
- CSRF protection
- Role-based authorization
- Activity logging with IP tracking
- Soft deletes for data recovery
- Secure file uploads

## 📊 Database Schema

### Key Tables
- `users` - User accounts
- `roles` & `permissions` - RBAC
- `trainee_profiles` - Comprehensive trainee information
- `organizations` - Government hierarchy
- `training_programs` - Course/training definitions
- `training_batches` - Scheduled training instances
- `training_enrollments` - Trainee enrollments
- `attendance_records` - Session attendance
- `assessments` & `assessment_results` - Evaluations
- `certificates` - Digital certificates
- `notifications` - User notifications
- `activity_logs` - Audit trail

## 🎨 UI/UX Features

- **Responsive Design:** Works on desktop, tablet, and mobile
- **Modern Interface:** TailwindCSS utility-first styling
- **Interactive Components:** Alpine.js for dynamic interactions
- **Dashboard Statistics:** Real-time data visualization
- **Alert Messages:** Success/error feedback
- **Accessible:** WCAG 2.1 compliant

## 📧 Email Configuration

Update `.env` with your Gmail app password:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=mis.lcb.kp@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="mis.lcb.kp@gmail.com"
```

**Note:** Generate an app password from Google Account settings.

## 📝 Usage Workflow

### For Trainees:
1. Register using official training email
2. Verify email address
3. Login and view dashboard
4. Wait for trainer to complete profile
5. View enrollments and training schedules
6. Track attendance and assessment results
7. Download certificates

### For Trainers:
1. Login with trainer credentials
2. Complete trainee profiles
3. Mark attendance in sessions
4. Evaluate assessments
5. View assigned training batches

### For Admin/Training Officers:
1. Login with admin credentials
2. View comprehensive dashboard
3. Enroll trainees into training batches
4. Manage training programs
5. Approve nominations
6. Generate reports
7. Issue certificates

## 🐛 Troubleshooting

### Database Connection Issues
- Ensure MySQL is running
- Check `.env` database credentials
- Verify database exists

### Email Not Sending
- Check Gmail app password
- Ensure SMTP settings are correct
- Check firewall/antivirus settings

### Migration Errors
```bash
php artisan migrate:fresh --seed
```

### Cache Issues
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📄 License

Government of Khyber Pakhtunkhwa - Official Use Only

## 👨‍💻 Development

Built following:
- Laravel best practices
- SOLID principles
- Clean architecture
- Government compliance standards
- Audit-ready implementation

## 📞 Support

For issues or questions:
- Email: mis.lcb.kp@gmail.com
- Department: Local Government Department, KP

## 🎓 Training Fields Included

### Personal Information
- CNIC, Name, Father Name, Gender
- Personal Number, Trainee Type
- DOB, Domicile, Cadre
- Contact information, Addresses
- Profile picture

### Current Posting
- District, Tehsil
- Organization, Section
- Designation, BPS
- Posting date

### Qualifications
- Degree, Institute
- Country, Subject
- Passing Year, Marks

---

**© 2025 Local Government Department, Government of Khyber Pakhtunkhwa**
