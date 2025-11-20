# PC Maintenance System - Authentication Guide

## Overview
This PC Maintenance System uses Laravel Breeze (Livewire stack) for authentication with role-based access control. The system supports two user roles: **Admin** and **Technician**.

## Test Credentials

### Admin User
- **Email**: admin@pcm.local
- **Password**: password
- **Access**: Full system administration including technician management and request assignment

### Technician User
- **Email**: tech@pcm.local
- **Password**: password
- **Access**: Equipment management and assigned request handling

## Features

### Authentication
- ✅ **Login/Registration**: Secure authentication using Laravel Breeze
- ✅ **Email Verification**: Optional email verification flow
- ✅ **Password Reset**: Forgot password functionality
- ✅ **Profile Management**: Users can update their profile information
- ✅ **Session Management**: Secure session handling with logout

### Role-Based Access Control
- ✅ **Middleware Protection**: Routes protected by `auth`, `verified`, and custom `role` middleware
- ✅ **Automatic Redirects**: Users redirected to appropriate dashboard based on role
- ✅ **403 Errors**: Unauthorized access attempts blocked with 403 responses

## User Roles

### Admin Role (`admin`)
**Dashboard**: `/admin/dashboard`
- View equipment counts by category (PCs, Accessories, Network Devices)
- Monitor equipment status (Working, Not working, Damaged, Old)
- Category-specific status breakdown

**Navigation**:
- Dashboard
- Technicians (Manage technician users)
- Requests (View and assign requests to technicians)

**Permissions**:
- Create, edit, and delete technician accounts
- View all maintenance requests
- Assign requests to available technicians
- Monitor system-wide equipment status

### Technician Role (`technician`)
**Dashboard**: `/technician/dashboard`
- View personal equipment counts
- See recent assigned requests
- Monitor personal report summary by status

**Navigation**:
- Dashboard
- Equipment (Manage PCs, Accessories, Network Devices)
- My Requests (View and update assigned requests)

**Permissions**:
- Add, edit, and report on equipment (PCs, Accessories, Network Devices)
- Create reports with status updates (Working, Not working, Damaged, Old)
- View assigned maintenance requests
- Mark requests as Fixed or Not Fixed

## Routes Structure

### Public Routes
```php
GET  /                      # Landing page
GET  /login                 # Login page
POST /login                 # Login action
GET  /register              # Registration page
POST /register              # Registration action
GET  /forgot-password       # Password reset request
POST /forgot-password       # Send reset link
GET  /reset-password/{token} # Password reset form
POST /reset-password        # Reset password action
```

### Admin Routes (requires `auth`, `verified`, `role:admin`)
```php
GET  /admin/dashboard                      # Admin dashboard
GET  /admin/technicians                    # List technicians
GET  /admin/technicians/create             # Add technician form
POST /admin/technicians                    # Save new technician
GET  /admin/technicians/{id}/edit          # Edit technician form
PUT  /admin/technicians/{id}               # Update technician
DELETE /admin/technicians/{id}             # Delete technician
GET  /admin/requests                       # List all requests
POST /admin/requests/{id}/assign           # Assign request to technician
```

### Technician Routes (requires `auth`, `verified`, `role:technician`)
```php
GET  /technician/dashboard                 # Technician dashboard
GET  /technician/equipment                 # List all equipment
GET  /technician/equipment/pc/create       # Add PC form
POST /technician/equipment/pc              # Save PC
GET  /technician/equipment/pc/{id}/edit    # Edit PC form
PUT  /technician/equipment/pc/{id}         # Update PC
GET  /technician/equipment/accessory/create # Add accessory form
POST /technician/equipment/accessory       # Save accessory
GET  /technician/equipment/accessory/{id}/edit # Edit accessory form
PUT  /technician/equipment/accessory/{id}  # Update accessory
GET  /technician/equipment/network-device/create # Add network device form
POST /technician/equipment/network-device  # Save network device
GET  /technician/equipment/network-device/{id}/edit # Edit network device form
PUT  /technician/equipment/network-device/{id} # Update network device
POST /technician/equipment/report          # Create equipment report
GET  /technician/requests                  # List assigned requests
POST /technician/requests/{id}/mark-fixed  # Mark request as fixed
POST /technician/requests/{id}/mark-not-fixed # Mark request as not fixed
```

### Authenticated Routes (all authenticated users)
```php
GET  /profile              # User profile page
PUT  /profile              # Update profile
DELETE /profile            # Delete account
POST /logout               # Logout
```

## Database Schema

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - Unique email address
- `password` - Hashed password
- `role` - User role: 'admin' or 'technician'
- `availability_status` - Technician availability: 'Available', 'Not available', 'Busy'
- `email_verified_at` - Email verification timestamp
- `remember_token` - Remember me token
- `timestamps` - Created/updated timestamps

## Middleware

### EnsureUserHasRole
Custom middleware that validates user role for protected routes.

**Location**: `app/Http/Middleware/EnsureUserHasRole.php`

**Registration**: Registered as `'role'` alias in `bootstrap/app.php`

**Usage**:
```php
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Admin-only routes
});
```

## Getting Started

### 1. Start the Development Server
```bash
php artisan serve
```

### 2. Access the Application
Open your browser to: http://127.0.0.1:8000

### 3. Login as Admin
- Click "Log in" on the landing page
- Email: admin@pcm.local
- Password: password
- You'll be redirected to `/admin/dashboard`

### 4. Login as Technician
- Click "Log in" on the landing page
- Email: tech@pcm.local
- Password: password
- You'll be redirected to `/technician/dashboard`

## Creating New Users

### Via Admin Dashboard
1. Login as admin
2. Navigate to "Technicians" in the navigation menu
3. Click "Add Technician"
4. Fill in the form (name, email, password, availability status)
5. Click "Save"

### Via Tinker (Command Line)
```bash
php artisan tinker
```

**Create Admin**:
```php
App\Models\User::create([
    'name' => 'New Admin',
    'email' => 'newadmin@pcm.local',
    'password' => bcrypt('password'),
    'role' => 'admin',
]);
```

**Create Technician**:
```php
App\Models\User::create([
    'name' => 'New Technician',
    'email' => 'newtechnician@pcm.local',
    'password' => bcrypt('password'),
    'role' => 'technician',
    'availability_status' => 'Available',
]);
```

### Via Database Seeder
Add users to `database/seeders/DatabaseSeeder.php`:
```php
User::firstOrCreate(
    ['email' => 'admin@pcm.local'],
    [
        'name' => 'System Admin',
        'password' => bcrypt('password'),
        'role' => User::ROLE_ADMIN,
    ]
);
```

Then run:
```bash
php artisan db:seed
```

## Security Features

### Password Hashing
All passwords are hashed using bcrypt before storage.

### CSRF Protection
All forms include CSRF tokens for protection against cross-site request forgery.

### Session Security
- Secure session configuration in `config/session.php`
- HTTP-only cookies prevent JavaScript access
- Session regeneration on login

### Email Verification
Optional email verification can be enforced on routes using the `verified` middleware.

### Rate Limiting
Login attempts are rate-limited to prevent brute force attacks.

## Troubleshooting

### "403 Forbidden" Error
- **Cause**: User trying to access route without proper role
- **Solution**: Ensure user has correct role assigned in database

### Redirected to Home Instead of Dashboard
- **Cause**: Dashboard redirect logic not executing
- **Solution**: Check `routes/web.php` for `/dashboard` route redirect logic

### Login Not Working
- **Cause**: Incorrect credentials or user doesn't exist
- **Solution**: Verify user exists with `php artisan tinker` and check email/password

### Changes Not Reflected
- **Cause**: Cached views or routes
- **Solution**: 
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## Technology Stack

- **Framework**: Laravel 11
- **Authentication**: Laravel Breeze (Livewire stack)
- **Frontend**: Livewire 3 + Tailwind CSS
- **Database**: SQLite
- **Session Driver**: File-based sessions

## Additional Resources

- [Laravel Breeze Documentation](https://laravel.com/docs/11.x/starter-kits#laravel-breeze)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Laravel Authentication](https://laravel.com/docs/11.x/authentication)
- [Laravel Authorization](https://laravel.com/docs/11.x/authorization)
