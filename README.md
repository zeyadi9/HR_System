# HR System

A comprehensive Human Resources Management System built with [Laravel 12](https://laravel.com/) and [Tailwind CSS 4](https://tailwindcss.com/). The system is designed to streamline HR operations, manage employee data, track attendance, and generate detailed reports.

---

## 🌟 Key Features

- **Employee Profiles:** Add, edit, and view comprehensive employee details and data.
- **Attendance Tracking:** Manage and record employee Check In/Out events.
- **Leaves & Permissions:** Track and manage leave requests, permissions, and balances.
- **Overtime Management:** Calculate and record overtime hours.
- **Penalties & Deductions:** Register and manage financial or administrative penalties.
- **Incentives:** Manage and document employee financial bonuses.
- **Settlements:** Follow up on financial settlements and calculate salaries/dues.
- **Reporting System:** Export comprehensive data reports to Excel (powered by `maatwebsite/excel`).
- **Roles & Permissions:** Customized interfaces for Super Admin and Administrators, alongside an Audit Log system.
- **Automated Backups:** Automated database backups (powered by `spatie/laravel-backup`).
- **Mobile API:** Fully integrated API routing to connect the system with a mobile application using `Laravel Sanctum`.

---

## 🛠 Tech Stack

- **Backend:** PHP 8.2+, Laravel 12
- **Frontend:** Blade Templates, Tailwind CSS 4, Vite
- **Database:** MySQL
- **Key Packages:** 
  - `maatwebsite/excel` (for reporting)
  - `spatie/laravel-backup` (for database backups)
  - `laravel/sanctum` (for API authentication)

---

## 🚀 Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/zeyadi9/HR_System.git
   cd HR_System
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Make sure to configure your database connection details in the `.env` file.*

4. **Database Migration:**
   ```bash
   php artisan migrate
   ```

5. **Run the application:**
   ```bash
   # Run the Laravel development server
   php artisan serve

   # Run the Vite frontend server
   npm run dev
   ```

---

## 📱 Mobile App Integration
The system includes an `Api` directory within the Controllers to provide dedicated routes for the mobile application, such as login, notifications, and leave requests. This allows employees to easily interact with the system via their smartphones.

---

## 🔒 Security & Auditing
The system features an Audit Logs dashboard to track data changes and Admin Notes, along with strict role-based access control to ensure the protection of employee data.
