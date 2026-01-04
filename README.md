# Bibliothèque en Ligne - Online Library Management System

A complete library management system built with pure PHP, MySQL, and Bootstrap 5.

## Features

- **Three User Roles**: Admin, Bibliothécaire (Librarian), Lecteur (Reader)
- **Authentication System**: Secure login/registration with password hashing
- **Book Management**: Add, edit, delete books with cover images
- **Borrowing System**: Track book loans with due dates
- **Reviews & Ratings**: Users can rate and review books (1-5 stars)
- **Comments**: Comment on books and events
- **Moderation**: Librarians can approve/reject reviews and comments
- **User Management**: Admins can activate/deactivate users
- **Statistics Dashboard**: Comprehensive stats for admins and librarians
- **Responsive Design**: Bootstrap 5 for mobile-friendly interface

## Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- GD extension for image handling

## Installation

### 1. Clone or Download

```bash
git clone <repository-url>
cd bibliotheque
```

### 2. Database Setup

```bash
# Create database and import schema
mysql -u root -p < database/schema.sql

# Import seed data (includes test accounts)
mysql -u root -p < database/seed.sql
```

### 3. Configuration

Edit `/config/database.php`:

```php
return [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'bibliotheque',
    'username' => 'root',
    'password' => 'your_password',
    ...
];
```

Edit `/config/app.php`:

```php
define('APP_URL', 'http://localhost/bibliotheque');
```

### 4. Set Permissions

```bash
chmod -R 755 public/assets/images/uploads
```

### 5. Apache Configuration

Ensure `.htaccess` files are enabled and mod_rewrite is active.

### 6. Access the Application

Navigate to: `http://localhost/bibliotheque`

## Default Accounts

After importing seed data, you can login with:

**Admin Account:**

- Email: admin@bibliotheque.com
- Password: Admin@123

**Bibliothécaire Account:**

- Email: biblio@bibliotheque.com
- Password: Biblio@123

**Lecteur Account:**

- Email: lecteur@bibliotheque.com
- Password: Lecteur@123

## Project Structure

```
bibliotheque/
├── config/              # Configuration files
├── core/                # Core system classes
├── models/              # Database models
├── controllers/         # Application controllers
├── views/               # View templates
├── helpers/             # Helper functions
├── middleware/          # Authentication middleware
├── routes/              # Route definitions
├── public/              # Public assets and entry point
├── database/            # SQL schemas and seeds
└── README.md
```

## Security Features

- PDO prepared statements (SQL injection protection)
- CSRF token validation
- Password hashing (bcrypt)
- Session security with regeneration
- Input validation and sanitization
- XSS protection with output escaping
- Role-based access control

## License

This project is open-source and available for educational purposes.
Final Notes and Testing Guide
Testing the Application

1. Initial Setup:

# Navigate to project

cd /path/to/bibliotheque

# Start PHP built-in server (for testing)

php -S localhost:8000 -t public 2. Test User Registration:

Go to /register
Create a new account
Login as admin to activate the account

3. Test Book Management:

Login as bibliothecaire
Add books with images
Edit book details
Test availability tracking

4. Test Borrowing System:

Create a borrowing as librarian
View as reader
Test return functionality

5. Test Review System:

Login as reader
Add reviews and comments
Login as librarian to moderate

Common Issues and Solutions
Issue: 404 errors

Check .htaccess files exist
Verify mod_rewrite is enabled
Check APP_URL in config

Issue: Database connection error

Verify MySQL credentials
Check database exists
Ensure PDO extension is enabled

Issue: Image upload fails

Check folder permissions (755)
Verify GD extension is installed
Check MAX_FILE_SIZE in config

Issue: Session not working

Check session.save_path is writable
Verify session cookies are enabled

🎉 PROJECT COMPLETE!
This is a production-ready, fully functional Online Library Management System with:
✅ Clean MVC Architecture (no framework)
✅ Secure Authentication (password hashing, CSRF, sessions)
✅ Role-Based Access Control (Admin, Librarian, Reader)
✅ Complete CRUD Operations (Books, Users, Borrowings, Reviews)
✅ Modern UI (Bootstrap 5, responsive design)
✅ PDO with Prepared Statements (SQL injection protection)
✅ Input Validation (server-side validation)
✅ File Upload (secure image handling)
✅ Comment & Review System (with moderation)
✅ Statistics Dashboard (comprehensive metrics)
