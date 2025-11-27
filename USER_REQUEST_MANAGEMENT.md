# User Request Management - Implementation Summary

## Overview
The PC Maintenance System has been enhanced with a complete user request management workflow. The system now supports three user roles:

1. **Admin** - Manages technicians, users, and assigns requests
2. **Technician** - Handles equipment and assigned maintenance requests
3. **User** - Submits and tracks maintenance requests

## New Features Implemented

### 1. User Role Management

#### Models Updated
- **User Model** (`app/Models/User.php`)
  - Added `ROLE_USER` constant
  - Added `submittedRequests()` relationship to track user's requests

- **Request Model** (`app/Models/Request.php`)
  - Added `user_id` field to track which user submitted the request
  - Added `description` field for detailed problem description
  - Added `user()` relationship

#### Database Changes
- **Migration**: `2025_11_27_000001_add_user_id_and_description_to_requests_table.php`
  - Adds `user_id` foreign key to `requests` table
  - Adds `description` text field to `requests` table

### 2. Admin User Management

Admin can now register and manage regular users who can submit requests.

#### New Routes
- `GET /admin/users` - List all users
- `GET /admin/users/create` - Create new user form
- `GET /admin/users/{id}/edit` - Edit user form

#### Components Created
- **Admin\Users\Index.php** - User listing with delete functionality
- **Admin\Users\Form.php** - User creation/editing form

#### Features
- ✅ View all registered users
- ✅ Create new users with name, email, and password
- ✅ Edit existing users (update info or reset password)
- ✅ Delete users (with confirmation modal)
- ✅ See request count per user

### 3. User Dashboard & Request Submission

Users can now log in, view their dashboard, and submit maintenance requests.

#### New Routes
- `GET /user/dashboard` - User dashboard with stats
- `GET /user/requests` - View all submitted requests
- `GET /user/requests/create` - Submit new request form

#### Components Created
- **User\Dashboard.php** - Dashboard with statistics
- **User\Requests\Index.php** - List all user's requests
- **User\Requests\Form.php** - Request submission form

#### Features
- ✅ Dashboard showing:
  - Total requests
  - Pending requests
  - Assigned requests
  - Fixed requests
  - Not fixed requests
  - Recent requests list
- ✅ Submit new requests with:
  - Request type (e.g., PC Repair, Network Issue)
  - Detailed description
  - Unit/Department
  - Contact telephone
- ✅ View all submitted requests with status tracking
- ✅ See assigned technician (if assigned)

### 4. Enhanced Admin Request Management

The admin requests view has been updated to show more information about requests.

#### Updated Features
- ✅ Shows requester information (name, email, phone)
- ✅ Distinguishes between user-submitted and guest requests
- ✅ Displays request description
- ✅ Color-coded status badges
- ✅ Assign requests to available technicians
- ✅ All existing assignment functionality preserved

### 5. Navigation Updates

Navigation has been updated to include links for all roles:

#### Admin Navigation
- Dashboard
- Technicians
- **Users** (NEW)
- Requests

#### User Navigation
- Dashboard (NEW)
- My Requests (NEW)

## Workflow

### Complete Request Lifecycle

1. **User Registration**
   - Admin creates user account via `/admin/users/create`
   - User receives credentials (or registers via standard registration)

2. **Request Submission**
   - User logs in and navigates to dashboard
   - Clicks "Submit New Request"
   - Fills in request details:
     - Request Type
     - Description
     - Unit/Department
     - Contact Phone
   - Submits request (status: **Pending**)

3. **Admin Review**
   - Admin views all requests at `/admin/requests`
   - Sees pending requests with user details
   - Clicks "Assign" on a pending request
   - Selects available technician from dropdown
   - Assigns request (status: **Technician Assigned**)

4. **Technician Action**
   - Technician logs in and views assigned requests
   - Sees request details and user contact info
   - Performs maintenance work
   - Marks request as:
     - **Fixed** - Problem resolved
     - **Not Fixed** - Could not resolve

5. **User Tracking**
   - User can view request status at any time
   - Dashboard shows counts by status
   - Request list shows current status and assigned technician
   - User is notified of progress

## Testing the New Features

### 1. Create a Test User (as Admin)
```
1. Login as admin@pcm.local / password
2. Navigate to "Users" in the menu
3. Click "Add User"
4. Fill in:
   - Name: Test User
   - Email: testuser@pcm.local
   - Password: password
   - Password Confirmation: password
5. Click "Save"
```

### 2. Submit a Request (as User)
```
1. Logout from admin
2. Login as testuser@pcm.local / password
3. Click "Submit New Request" button
4. Fill in:
   - Request Type: Printer Not Working
   - Description: Office printer on 3rd floor is not printing. Shows paper jam error.
   - Unit: IT Department
   - Telephone: +250 123 456 789
5. Click "Submit Request"
```

### 3. Assign Request (as Admin)
```
1. Logout and login as admin@pcm.local
2. Navigate to "Requests"
3. Find the pending request from Test User
4. Click "Assign" button
5. Select a technician from dropdown
6. Click "Assign" to confirm
```

### 4. Complete Request (as Technician)
```
1. Logout and login as tech@pcm.local / password
2. Navigate to "My Requests"
3. Find the assigned request
4. Click "Mark Fixed" or "Mark Not Fixed"
```

### 5. View Status (as User)
```
1. Logout and login as testuser@pcm.local
2. View dashboard to see updated status counts
3. Navigate to "My Requests" to see detailed status
```

## Files Created/Modified

### New Files
- `database/migrations/2025_11_27_000001_add_user_id_and_description_to_requests_table.php`
- `app/Livewire/Admin/Users/Index.php`
- `app/Livewire/Admin/Users/Form.php`
- `app/Livewire/User/Dashboard.php`
- `app/Livewire/User/Requests/Index.php`
- `app/Livewire/User/Requests/Form.php`
- `resources/views/livewire/admin/users/index.blade.php`
- `resources/views/livewire/admin/users/form.blade.php`
- `resources/views/livewire/user/dashboard.blade.php`
- `resources/views/livewire/user/requests/index.blade.php`
- `resources/views/livewire/user/requests/form.blade.php`

### Modified Files
- `app/Models/User.php` - Added ROLE_USER constant and submittedRequests relationship
- `app/Models/Request.php` - Added user_id and description to fillable, added user relationship
- `routes/web.php` - Added admin user management and user routes
- `resources/views/livewire/layout/navigation.blade.php` - Added user navigation links
- `resources/views/livewire/admin/requests/index.blade.php` - Enhanced to show user info and description

## Database Schema

### Users Table (updated)
- Supports three roles: 'admin', 'technician', 'user'

### Requests Table (updated)
```
- id
- first_name
- last_name
- email
- telephone
- date
- unit
- status (Pending, Technician Assigned, Fixed, Not fixed)
- request_type
- description (NEW)
- technician_id (nullable, references users.id)
- user_id (NEW, nullable, references users.id)
- timestamps
```

## Next Steps

1. **Run the application**:
   ```bash
   php artisan serve
   ```

2. **Access the system**:
   - Admin: http://127.0.0.1:8000/login
     - Email: admin@pcm.local
     - Password: password

3. **Create your first user** via Admin → Users → Add User

4. **Test the complete workflow** as described above

## Notes

- Users can only see and manage their own requests
- Admins can see all requests and assign them to technicians
- Technicians can only see requests assigned to them
- All roles are protected by authentication and role-based middleware
- The description field helps technicians understand the problem better
- Request status is automatically updated during the workflow
