# Equipment Registration & Reporting System - Implementation Guide

## 🎯 Overview

The PC Maintenance System has been completely restructured to follow a hierarchical equipment management workflow:

**Admin → Registers infrastructure → Users → Report issues on registered equipment**

### New Workflow
1. **Admin** registers:
   - Departments
   - Computer Labs (within departments)
   - Equipment (PCs, Accessories, Network Devices) assigned to labs

2. **Users** submit requests by:
   - Selecting a department
   - Selecting a computer lab (optional)
   - Choosing equipment type
   - Selecting specific equipment from registered items
   - Describing the issue

3. **Admin** assigns requests to technicians
4. **Technicians** fix the reported equipment

---

## 📊 Database Schema

### New Tables

#### Departments Table
```
- id (primary key)
- name (string) - e.g., "Computer Science Department"
- code (string, unique) - e.g., "CS", "IT"
- description (text, nullable)
- location (string, nullable) - e.g., "Building A, Floor 2"
- timestamps
```

#### Computer Labs Table
```
- id (primary key)
- name (string) - e.g., "Computer Lab A"
- code (string, unique) - e.g., "LAB001"
- department_id (foreign key → departments.id)
- location (string, nullable) - e.g., "Room 201"
- capacity (integer, nullable) - Number of seats
- description (text, nullable)
- timestamps
```

### Updated Tables

#### Equipment Tables (PCs, Accessories, Network Devices)
- Added: `computer_lab_id` (foreign key → computer_labs.id)
- Equipment can now be assigned to specific labs

#### Requests Table
- Added: `department_id` (foreign key → departments.id)
- Added: `computer_lab_id` (foreign key → computer_labs.id)
- Added: `pc_id` (foreign key → pcs.id)
- Added: `accessory_id` (foreign key → accessories.id)
- Added: `network_device_id` (foreign key → network_devices.id)
- Made nullable: `first_name`, `last_name`, `email`, `telephone`, `unit`

---

## 🚀 New Features

### 1. Department Management (Admin)

**Routes:**
- `GET /admin/departments` - List all departments
- `GET /admin/departments/create` - Create new department
- `GET /admin/departments/{id}/edit` - Edit department

**Features:**
- ✅ Create departments with code, name, location, description
- ✅ View department list with computer lab count
- ✅ Edit existing departments
- ✅ Delete departments (cascades to labs)
- ✅ Unique department codes

**Components:**
- `App\Livewire\Admin\Departments\Index`
- `App\Livewire\Admin\Departments\Form`

### 2. Computer Lab Management (Admin)

**Routes:**
- `GET /admin/computer-labs` - List all computer labs
- `GET /admin/computer-labs/create` - Create new lab
- `GET /admin/computer-labs/{id}/edit` - Edit lab

**Features:**
- ✅ Create labs assigned to departments
- ✅ Set lab capacity (number of seats)
- ✅ Define lab location
- ✅ View lab list with PC count
- ✅ Edit existing labs
- ✅ Delete labs (removes lab assignments from equipment)
- ✅ Unique lab codes

**Components:**
- `App\Livewire\Admin\ComputerLabs\Index`
- `App\Livewire\Admin\ComputerLabs\Form`

### 3. Enhanced Equipment Registration (Admin/Technician)

Equipment (PCs, Accessories, Network Devices) can now be:
- Assigned to specific computer labs
- Tracked by department through lab relationship
- Reported by users through structured forms

### 4. Equipment-Based Request Submission (User)

**New Request Flow:**
1. **Select Department** - Choose from registered departments
2. **Select Computer Lab** (Optional) - Filtered by selected department
3. **Choose Equipment Type:**
   - PC/Computer
   - Accessory
   - Network Device
   - General/Lab Issue
4. **Select Specific Equipment** - Dropdown shows equipment in selected lab
5. **Describe Issue** - Request type and detailed description
6. **Contact Info** - Telephone number

**Features:**
- ✅ Dynamic form that adapts based on selections
- ✅ Cascading dropdowns (Department → Labs → Equipment)
- ✅ Equipment filtered by computer lab
- ✅ Clear equipment identification in requests
- ✅ Support for general lab issues (no specific equipment)

**Component:**
- `App\Livewire\User\Requests\Form` (completely redesigned)

---

## 🔄 Updated Workflow

### Step 1: Admin Setup (One-time)

#### Create Departments
```
1. Login as admin@pcm.local
2. Navigate to "Departments" in menu
3. Click "Add Department"
4. Fill in:
   - Code: CS
   - Name: Computer Science Department
   - Location: Building A, Floor 2
   - Description: Department of Computer Science
5. Click "Save"
```

#### Create Computer Labs
```
1. Navigate to "Labs" in menu
2. Click "Add Computer Lab"
3. Fill in:
   - Code: CS-LAB-A
   - Name: Computer Lab A
   - Department: Computer Science Department
   - Location: Room 201
   - Capacity: 40
   - Description: Main computer lab for CS courses
4. Click "Save"
```

#### Register Equipment (Technician or Admin)
```
1. As technician, navigate to "Equipment"
2. Click "Add PC" (or Accessory/Network Device)
3. Fill in equipment details
4. Select Computer Lab: CS-LAB-A
5. Click "Save"
```

### Step 2: User Reports Issue

```
1. Login as user
2. Click "Submit New Request"
3. Select Department: Computer Science Department
4. Select Computer Lab: Computer Lab A (optional)
5. Select Equipment Type: PC/Computer
6. Select Specific PC: Dell Optiplex - Intel i5 (Windows 10)
7. Request Type: PC Not Booting
8. Description: The computer won't start when I press the power button. No lights or sounds.
9. Telephone: +250 123 456 789
10. Click "Submit Request"
```

### Step 3: Admin Assigns to Technician

```
1. Admin sees request with:
   - User info
   - Department: Computer Science Department
   - Lab: Computer Lab A
   - Equipment: Dell Optiplex (PC #5)
   - Issue: PC Not Booting
2. Clicks "Assign"
3. Selects available technician
4. Clicks "Assign" to confirm
```

### Step 4: Technician Fixes Issue

```
1. Technician logs in
2. Views "My Requests"
3. Sees assigned request with equipment details
4. Goes to Computer Lab A, finds the specific PC
5. Fixes the issue
6. Marks request as "Fixed" or "Not Fixed"
```

---

## 📂 Files Created

### Models
- `app/Models/Department.php`
- `app/Models/ComputerLab.php`

### Migrations
- `2025_11_27_000002_create_departments_table.php`
- `2025_11_27_000003_create_computer_labs_table.php`
- `2025_11_27_000004_add_computer_lab_to_equipment.php`
- `2025_11_27_000005_update_requests_for_equipment_reporting.php`

### Livewire Components
- `app/Livewire/Admin/Departments/Index.php`
- `app/Livewire/Admin/Departments/Form.php`
- `app/Livewire/Admin/ComputerLabs/Index.php`
- `app/Livewire/Admin/ComputerLabs/Form.php`

### Views
- `resources/views/livewire/admin/departments/index.blade.php`
- `resources/views/livewire/admin/departments/form.blade.php`
- `resources/views/livewire/admin/computer-labs/index.blade.php`
- `resources/views/livewire/admin/computer-labs/form.blade.php`

### Modified Files
- `app/Models/PC.php` - Added computer_lab_id and relationships
- `app/Models/Accessory.php` - Added computer_lab_id and relationships
- `app/Models/NetworkDevice.php` - Added computer_lab_id and relationships
- `app/Models/Request.php` - Added department, lab, equipment relationships
- `app/Livewire/User/Requests/Form.php` - Complete redesign for equipment selection
- `resources/views/livewire/user/requests/form.blade.php` - New cascading form
- `routes/web.php` - Added department and lab routes
- `resources/views/livewire/layout/navigation.blade.php` - Added nav links

---

## 🎨 Navigation Updates

### Admin Navigation
- Dashboard
- **Departments** (NEW)
- **Labs** (NEW)
- Technicians
- Users
- Requests

### Technician Navigation
- Dashboard
- Equipment (can assign to labs when creating)
- My Requests

### User Navigation
- Dashboard
- My Requests (new form with equipment selection)

---

## 🧪 Testing Guide

### 1. Setup Infrastructure (Admin)

**Create a Department:**
```
Code: IT
Name: Information Technology
Location: Building B
```

**Create a Computer Lab:**
```
Code: IT-LAB-1
Name: IT Lab 1
Department: Information Technology
Location: Room 305
Capacity: 30
```

### 2. Register Equipment (Technician)

**Add a PC:**
```
Login as tech@pcm.local
Navigate to Equipment → Add PC
Fill in:
  - Specifications: Intel Core i5, 8GB RAM
  - HDD: 500GB
  - RAM: 8GB
  - OS: Windows 10
  - Brand: Dell
  - Computer Lab: IT Lab 1
  - Registration Year: 2024
```

### 3. Submit Request (User)

**Create test user first (Admin):**
```
Name: Test User
Email: testuser@pcm.local
Password: password
```

**Submit request (User):**
```
Login as testuser@pcm.local
Click "Submit New Request"
Department: Information Technology
Computer Lab: IT Lab 1
Equipment Type: PC/Computer
Select PC: Dell - Intel Core i5 (Windows 10)
Request Type: Slow Performance
Description: The computer is very slow, takes 5 minutes to boot
Telephone: +250 123 456 789
Submit
```

### 4. Assign and Complete (Admin & Technician)

**Admin assigns:**
```
View request in admin panel
See all equipment details
Assign to available technician
```

**Technician completes:**
```
View assigned request
See exact PC location (IT Lab 1)
Fix the issue
Mark as Fixed
```

---

## 🔑 Key Benefits

### For Admins
- ✅ Centralized equipment database
- ✅ Hierarchical organization (Department → Lab → Equipment)
- ✅ Clear visibility of all infrastructure
- ✅ Easy tracking of equipment locations
- ✅ Better request management with equipment context

### For Technicians
- ✅ Know exactly which equipment to fix
- ✅ Find equipment location easily (department + lab)
- ✅ Track equipment history through requests
- ✅ Organized equipment management

### For Users
- ✅ Easy equipment identification through guided form
- ✅ No need to manually describe equipment
- ✅ Faster request submission
- ✅ Clear request tracking with equipment info

---

## 📝 Database Relationships

```
Department (1) → (Many) ComputerLabs
ComputerLab (1) → (Many) PCs
ComputerLab (1) → (Many) Accessories
ComputerLab (1) → (Many) NetworkDevices
ComputerLab (1) → (Many) Requests

Request → (1) Department
Request → (1) ComputerLab (nullable)
Request → (1) PC (nullable)
Request → (1) Accessory (nullable)
Request → (1) NetworkDevice (nullable)
Request → (1) Technician (nullable)
Request → (1) User (nullable)
```

---

## 🚀 Next Steps

1. **Run migrations** (Already done):
   ```bash
   php artisan migrate
   ```

2. **Start the server**:
   ```bash
   php artisan serve
   ```

3. **Setup workflow**:
   - Login as admin
   - Create departments
   - Create computer labs
   - Have technicians register equipment in labs
   - Create user accounts
   - Users can now submit equipment-specific requests

4. **Test complete flow**:
   - Admin creates infrastructure
   - Technician registers equipment
   - User submits request for specific equipment
   - Admin assigns to technician
   - Technician fixes and marks complete

---

## ⚠️ Important Notes

- Equipment can only be assigned to one computer lab
- Requests now track specific equipment instead of just descriptions
- Users must select equipment type (or "General" for lab-wide issues)
- Deleting a department will delete all its labs and unassign equipment
- Deleting a lab will unassign equipment but won't delete it
- Old request fields (first_name, unit, etc.) are now nullable for backward compatibility

---

## 🎯 Summary

The system now follows a structured approach:
- **Admin manages infrastructure** (departments, labs)
- **Technicians register equipment** in labs
- **Users report issues** on specific registered equipment
- **Clear traceability** from request → equipment → lab → department

This provides better organization, easier equipment tracking, and more efficient maintenance workflows!
