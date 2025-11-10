Jagriti Pro — Complete Project (PHP + MySQL)

Installation (local LAMPP/XAMPP):

1. Copy the folder 'jagriti_pro' into your web root, e.g. /opt/lampp/htdocs/jagriti_pro
2. Edit config/db.php to set DB_USER/DB_PASS if needed.
3. In browser open: http://localhost/jagriti_pro/setup.php  -> this creates the database, tables and default admin user.
   Default admin: admin@jagriti.local / admin123
4. After setup, open: http://localhost/jagriti_pro/public/login.php and log in.
5. Remove or protect setup.php after first run.

If you prefer CLI to create DB:
   mysql -u root -p -e "CREATE DATABASE jagriti CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u root -p jagriti < /path/to/jagriti_pro/database/db_schema.sql

Files:
- config/db.php : database connection
- setup.php : creates database and admin
- public/ : web root pages (index.php, login.php, students, donations, volunteers, ...)
- includes/: header, navbar, footer
- api/: endpoints for CRUD actions
