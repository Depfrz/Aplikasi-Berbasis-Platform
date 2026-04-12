# Student CRUD Management System - Week 06 Assignment

This is a complete Student Data CRUD module built with the Laravel framework.

## Features
- **Add Student**: Form with validation for NIM, Name, Email, Phone, and Study Program.
- **Edit Student**: Pre-populated form with update functionality and validation.
- **Soft Delete**: Move records to trash instead of permanent deletion.
- **Restore & Permanent Delete**: Manage trashed records.
- **Student List**: Responsive table with:
    - Search by NIM, Name, Email, or Study Program.
    - Sorting by NIM and Name.
    - Pagination (10 items per page).
- **Validation**:
    - NIM: Required and unique.
    - Name: Min 3, max 100, alphabetic characters and spaces only.
    - Email: Valid email format.
    - Phone: Numeric only.
- **Responsive Design**: Styled using Bootstrap 5.
- **Testing**: Comprehensive feature tests for all CRUD operations and validation rules.

## Technical Stack
- **Framework**: Laravel
- **Database**: SQLite
- **ORM**: Eloquent
- **Frontend**: Blade & Bootstrap 5

## How to Run
1. Ensure PHP and Composer are installed.
2. Run `composer install` to install dependencies.
3. Run `php artisan migrate` to set up the database.
4. Run `php artisan serve` to start the development server.
5. Visit `http://localhost:8000` in your browser.

## Running Tests
Run the following command to execute all tests:
```bash
php artisan test
```