# Jagriti Pro - Student Support Management System

## Overview
Jagriti Pro is a lightweight PHP-based student support management system designed for NGOs, small organizations, and educational institutions. It helps manage students, donations, volunteers, assignments, feedback, and books with role-based access control.

## Current State
- **Status**: Fully functional with role-based access control
- **Database**: SQLite for Replit compatibility
- **Server**: Running PHP 8.2 development server on port 5000
- **Access**: Available at the Replit webview URL
- **Theme**: Vibrant orange color scheme (#f97316, #fb923c)

## Recent Changes (November 10, 2025)
### Database & Infrastructure
- Converted database from MySQL to SQLite for Replit environment
- Updated `config/db.php` to use SQLite with PDO
- Modified `setup.php` to create SQLite-compatible schema
- Created startup script `start.sh` for PHP development server
- Initialized database with default admin, volunteer, and student users
- Added `.gitignore` for SQLite database files
- Configured workflow to run PHP server on port 5000
- **NEW**: Added `user_id` column to students table to link student records with user accounts
- **NEW**: Added `address` column to students table for complete student profiles

### Role-Based Access Control (RBAC)
- Implemented comprehensive RBAC system with three roles: **admin**, **volunteer**, and **student**
- Created centralized authentication system in `includes/auth.php`
- Added permission helper functions (`require_admin()`, `require_staff()`, `can_manage_students()`, etc.)
- Updated all pages and API endpoints with proper permission checks
- Created admin-only user management interface (`public/users.php`)
- Restricted volunteer management to admin-only access
- Updated navigation menu to show/hide links based on user role
- **NEW**: Added `is_student()` helper function and `get_student_record()` for student data access
- **NEW**: Implemented role-based redirects (students → portal, staff → dashboard)

### Student Portal (NEW)
- Created dedicated student portal (`public/student_portal.php`) with personalized dashboard
- Students can view their profile, assignments, available books, and feedback
- Beautiful hero section with Jagriti logo and welcoming message
- Color-coded stats cards showing student ID, class, and assignment count
- Assignment status tracking (active vs past due)
- Library books catalog with titles, authors, and availability
- Personal feedback history from teachers and administrators
- Responsive card-based design with orange gradient theme

### UI/UX Enhancements
- Applied vibrant orange color theme across all pages
- Enhanced content on all pages with better copy and user-focused messaging
- Improved login page with clear role descriptions and test credentials for all three roles
- Updated all forms and tables with modern, accessible design
- **NEW**: Integrated official Jagriti logo (sun with bird) throughout the application
- **NEW**: Logo appears in navigation header, login page, and student portal
- **NEW**: Enhanced visual hierarchy with icons and emoji indicators
- **NEW**: Improved homepage with logo integration and professional branding

## User Roles & Permissions

### Administrator Role
**Full system access** - Can manage everything
- ✅ Manage users (create/delete volunteer, admin, and student accounts)
- ✅ Manage volunteers (add/remove volunteers)
- ✅ Manage students, donations, assignments, books, feedback
- ✅ View all reports and statistics

### Volunteer Role
**Limited operational access** - Cannot manage volunteers or users
- ❌ Cannot manage users or volunteers
- ✅ Manage students (add/remove/view student records)
- ✅ Manage donations (track cash and item donations)
- ✅ Manage assignments (create/track student assignments)
- ✅ Manage books (library inventory management)
- ✅ Manage feedback (student feedback and reviews)

### Student Role (NEW)
**Read-only personal portal** - Can only view their own information
- ✅ View personal profile (name, roll number, class, age, contact)
- ✅ View all assignments with due dates and status
- ✅ Browse available library books
- ✅ View feedback from teachers and administrators
- ❌ Cannot access management features or other students' data
- ❌ Cannot modify any data (read-only access)

## Project Architecture

### Directory Structure
```
jagriti_pro/
├── api/              # REST API endpoints with permission checks
│   ├── student_api.php
│   ├── donation_api.php
│   ├── volunteer_api.php
│   ├── feedback_api.php
│   ├── assignment_api.php
│   ├── book_api.php
│   └── auth_api.php
├── config/           # Configuration files
│   └── db.php       # Database connection (SQLite)
├── data/            # SQLite database storage
│   └── jagriti.sqlite
├── includes/        # Shared PHP components
│   ├── auth.php     # Authentication & permission helpers
│   ├── header.php
│   ├── navbar.php   # Role-aware navigation
│   └── footer.php
├── public/          # Web-accessible pages
│   ├── assets/
│   │   ├── css/style.css           # Orange theme styles
│   │   └── images/jagriti-logo.jpeg # Official Jagriti logo
│   ├── index.php            # Dashboard (staff only)
│   ├── login.php            # Login with test credentials (all roles)
│   ├── student_portal.php   # Student dashboard (NEW)
│   ├── users.php            # Admin-only user management
│   ├── students.php         # Student management (staff only)
│   ├── donations.php        # Donation tracking (staff only)
│   ├── volunteers.php       # Admin-only volunteer management
│   ├── feedback.php         # Feedback management (staff only)
│   ├── assignments.php      # Assignment tracking (staff only)
│   └── books.php           # Book library (staff only)
├── setup.php        # Database initialization script
└── start.sh         # Server startup script
```

### Database Schema (SQLite)
- **users**: User accounts with roles (admin/volunteer/student), hashed passwords
- **students**: Student records with contact information and optional link to user account (user_id)
- **donations**: Cash and item donation tracking
- **volunteers**: Volunteer management with skills
- **feedback**: Feedback linked to students
- **assignments**: Assignment tracking with due dates
- **books**: Book library management

### Technology Stack
- **Backend**: PHP 8.2 with PDO
- **Database**: SQLite 3
- **Frontend**: HTML, CSS (custom orange theme), vanilla JavaScript
- **Server**: PHP built-in development server
- **Authentication**: Session-based with password hashing

## Default Test Credentials

### Administrator Account
- **Email**: admin@jagriti.local
- **Password**: admin123
- **Access**: Full system access

### Volunteer Account
- **Email**: volunteer@jagriti.local
- **Password**: volunteer123
- **Access**: Limited to students, donations, assignments, books, feedback

### Student Account (NEW)
- **Email**: student@jagriti.local
- **Password**: student123
- **Name**: Rahul Kumar
- **Roll Number**: STU001
- **Access**: Read-only student portal with personal information

⚠️ **Important**: Change these passwords after first login for security

## Development Notes
- The SQLite database file is stored in `data/jagriti.sqlite`
- PHP server runs on 0.0.0.0:5000 for Replit compatibility
- Document root is set to `public/` directory
- Foreign keys are enabled in SQLite for referential integrity
- All database operations use prepared statements to prevent SQL injection

## Security Features
- **Password Security**: Passwords hashed using PHP's password_hash() with bcrypt
- **SQL Injection Protection**: PDO prepared statements for all database queries
- **Session Management**: Secure session-based authentication
- **Role-Based Access Control**: Permission checks on all protected pages and API endpoints
- **Input Validation**: Server-side validation on all forms
- **No Direct Access**: API files check authentication before processing requests

## How to Use

### First Time Setup
1. Access the Replit webview URL
2. Run `php setup.php` if database doesn't exist
3. Login with admin credentials
4. Change default passwords in user management

### Creating New Users (Admin Only)
1. Login as admin
2. Navigate to "User Management"
3. Fill in user details and select role
4. Click "Create User"

### Managing Volunteers (Admin Only)
1. Login as admin
2. Navigate to "Volunteers"
3. Add/remove volunteer records

### Managing Students (Both Roles)
1. Login as admin or volunteer
2. Navigate to "Students"
3. Add/edit/delete student records

## Future Enhancements
- Password change functionality for users
- User profile editing
- More granular permission levels
- Activity logging and audit trails
- Export functionality for reports
- Email notifications
