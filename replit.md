# Jagriti Pro - Student Support Management System

## Overview
Jagriti Pro is a lightweight PHP-based student support management system designed for small organizations and college projects. It helps manage students, donations, volunteers, assignments, feedback, and books.

## Current State
- **Status**: Successfully migrated to Replit environment
- **Database**: Converted from MySQL to SQLite for Replit compatibility
- **Server**: Running PHP 8.2 development server on port 5000
- **Access**: Available at the Replit webview URL

## Recent Changes (November 10, 2025)
- Converted database from MySQL to SQLite for Replit environment
- Updated `config/db.php` to use SQLite with PDO
- Modified `setup.php` to create SQLite-compatible schema
- Created startup script `start.sh` for PHP development server
- Initialized database with default admin user
- Added `.gitignore` for SQLite database files
- Configured workflow to run PHP server on port 5000

## Project Architecture

### Directory Structure
```
jagriti_pro/
├── api/              # REST API endpoints for CRUD operations
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
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
├── public/          # Web-accessible pages
│   ├── assets/css/
│   ├── index.php
│   ├── login.php
│   ├── students.php
│   ├── donations.php
│   ├── volunteers.php
│   ├── feedback.php
│   ├── assignments.php
│   └── books.php
├── setup.php        # Database initialization script
└── start.sh         # Server startup script
```

### Database Schema (SQLite)
- **users**: Admin and staff accounts with authentication
- **students**: Student records with contact information
- **donations**: Cash and item donation tracking
- **volunteers**: Volunteer management with skills
- **feedback**: Feedback linked to students
- **assignments**: Assignment tracking with due dates
- **books**: Book library management

### Technology Stack
- **Backend**: PHP 8.2 with PDO
- **Database**: SQLite 3
- **Frontend**: HTML, CSS, vanilla JavaScript
- **Server**: PHP built-in development server

## Default Credentials
- **Email**: admin@jagriti.local
- **Password**: admin123

## Development Notes
- The SQLite database file is stored in `data/jagriti.sqlite`
- PHP server runs on 0.0.0.0:5000 for Replit compatibility
- Document root is set to `public/` directory
- Foreign keys are enabled in SQLite for referential integrity

## Security Considerations
- Passwords are hashed using PHP's password_hash()
- PDO prepared statements prevent SQL injection
- Session management for authentication
- Change default admin password after first login
