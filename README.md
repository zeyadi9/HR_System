<div align="center">
  <h1>🏢 Modern HR Management System</h1>
  <p>A comprehensive, scalable, and secure Human Resources Management solution built for the modern workforce.</p>

  <!-- Badges -->
  <p>
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  </p>
</div>

---

## 📋 Table of Contents
- [About The Project](#-about-the-project)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Getting Started](#-getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Mobile API Integration](#-mobile-api-integration)
- [Security & Auditing](#-security--auditing)

---

## 📖 About The Project

This **Human Resources Management System (HRMS)** is designed to streamline day-to-day HR operations, manage employee data, track attendance, and generate detailed analytical reports. Built with **Laravel 12** and styled using **Tailwind CSS 4**, it provides a fast, responsive, and intuitive interface for both administrators and employees. 

Whether you need to manage complex payroll settlements, track daily check-ins, or integrate with a custom mobile application, this system offers a robust backend architecture to handle it all efficiently.

---

## ✨ Key Features

### 👥 Personnel Management
- **Employee Profiles:** Maintain comprehensive employee records, including personal details, job titles, and historical data.
- **Roles & Permissions:** Granular access control for Super Admins, HR Managers, and standard users.

### ⏰ Time & Attendance
- **Check-In/Out Tracking:** Seamlessly record and monitor daily attendance.
- **Leave Management:** Process vacation requests, sick leaves, and custom permissions. Track remaining balances automatically.
- **Overtime Calculation:** Automatically calculate and log extra working hours.

### 💰 Payroll & Financials
- **Incentives & Bonuses:** Issue and document financial rewards.
- **Penalties & Deductions:** Manage administrative and financial penalties.
- **Financial Settlements:** Calculate accurate end-of-month dues and final settlements.

### 📊 Reporting & Maintenance
- **Excel Exports:** Generate complex, data-rich reports using `maatwebsite/excel`.
- **Automated DB Backups:** Secure your data with scheduled backups powered by `spatie/laravel-backup`.
- **System Auditing:** Keep track of sensitive administrative actions with a built-in Audit Log.

---

## 🛠 Tech Stack

**Core Technologies:**
- **Backend:** PHP 8.2+, Laravel 12
- **Frontend:** Blade Templates, Tailwind CSS 4, Vite
- **Database:** MySQL
- **Authentication:** Laravel Sanctum (API), Web Session Auth

**Key Packages:**
- `maatwebsite/excel`: Fast and robust Excel exports.
- `spatie/laravel-backup`: Reliable database and file backup solution.

---

## 🚀 Getting Started

Follow these steps to get a local copy up and running.

### Prerequisites
Make sure you have the following installed on your local machine:
- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/)
- [Node.js & npm](https://nodejs.org/)
- [MySQL](https://www.mysql.com/)

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/zeyadi9/HR_System.git
   cd HR_System
   ```

2. **Install PHP Dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM Dependencies:**
   ```bash
   npm install
   ```

4. **Environment Configuration:**
   Copy the `.env.example` file to create your local `.env` configuration.
   ```bash
   cp .env.example .env
   ```
   *Update the database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) in your `.env` file.*

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations:**
   ```bash
   php artisan migrate
   ```

7. **Start the Development Servers:**
   You will need two terminal windows to run both the backend and frontend asset bundler.
   
   *Terminal 1 (Laravel Server):*
   ```bash
   php artisan serve
   ```
   
   *Terminal 2 (Vite):*
   ```bash
   npm run dev
   ```

---

## 📱 Mobile API Integration

The system comes equipped with a dedicated `Api` directory inside the Controllers. This provides a secure set of RESTful endpoints powered by **Laravel Sanctum**.

- **Authentication:** Secure mobile login and token generation.
- **Real-time Sync:** Push notifications and live data sync for mobile clients.
- **Employee Self-Service:** APIs for employees to request leaves, view attendance, and check salary details directly from their smartphones.

---

## 🔒 Security & Auditing

Data integrity and security are top priorities.
- **Audit Logs:** Every major action taken by an administrator is logged. You can review "who did what and when".
- **Admin Notes:** Secure internal communication and note-taking for HR personnel.
- **Route Protection:** All endpoints are strictly protected by middleware ensuring only authorized personnel can access sensitive financial and personal data.

---
<div align="center">
  <i>Developed with ❤️ for streamlined HR management.</i>
</div>
