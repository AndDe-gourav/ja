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
- Initialized database with default admin and volunteer users
- Added `.gitignore` for SQLite database files
- Configured workflow to run PHP server on port 5000

### Role-Based Access Control (RBAC)
- Implemented comprehensive RBAC system with two roles: **admin** and **volunteer**
- Created centralized authentication system in `includes/auth.php`
- Added permission helper functions (`require_admin()`, `can_manage_students()`, etc.)
- Updated all pages and API endpoints with proper permission checks
- Created admin-only user management interface (`public/users.php`)
- Restricted volunteer management to admin-only access
- Updated navigation menu to show/hide links based on user role

### UI/UX Enhancements
- Applied vibrant orange color theme across all pages
- Enhanced content on all pages with better copy and user-focused messaging
- Improved login page with clear role descriptions and test credentials
- Updated all forms and tables with modern, accessible design

## User Roles & Permissions

### Administrator Role
**Full system access** - Can manage everything
- ✅ Manage users (create/delete volunteer and admin accounts)
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
│   ├── assets/css/style.css  # Orange theme styles
│   ├── index.php            # Dashboard
│   ├── login.php            # Login with test credentials
│   ├── users.php            # Admin-only user management
│   ├── students.php         # Student management
│   ├── donations.php        # Donation tracking
│   ├── volunteers.php       # Admin-only volunteer management
│   ├── feedback.php         # Feedback management
│   ├── assignments.php      # Assignment tracking
│   └── books.php           # Book library
├── setup.php        # Database initialization script
└── start.sh         # Server startup script
```

### Database Schema (SQLite)
- **users**: User accounts with roles (admin/volunteer), hashed passwords
- **students**: Student records with contact information
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
