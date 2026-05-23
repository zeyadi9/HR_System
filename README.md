# HR System - نظام إدارة الموارد البشرية

A comprehensive Human Resources Management System built with [Laravel 12](https://laravel.com/) and [Tailwind CSS 4](https://tailwindcss.com/). The system is designed to streamline HR operations, manage employee data, track attendance, and generate detailed reports.

نظام متكامل لإدارة الموارد البشرية مبني باستخدام إطار عمل Laravel 12 و Tailwind CSS 4. يهدف النظام إلى تسهيل العمليات الخاصة بالموارد البشرية، إدارة بيانات الموظفين، تتبع الحضور والانصراف، وإصدار التقارير المفصلة.

---

## 🌟 الميزات الرئيسية (Key Features)

- **إدارة ملفات الموظفين (Employee Profiles):** إضافة وتعديل وعرض تفاصيل وبيانات الموظفين.
- **تتبع الحضور والانصراف (Attendance Tracking):** تسجيل وإدارة حركات الدخول والخروج (Check In/Out) للموظفين.
- **الإجازات والأذونات (Leaves & Permissions):** إدارة طلبات الإجازات والمغادرات وتتبع الأرصدة.
- **إدارة العمل الإضافي (Overtime Management):** حساب وتسجيل ساعات العمل الإضافية.
- **الخصومات والجزاءات (Penalties):** تسجيل وإدارة الخصومات المالية والإدارية على الموظفين.
- **المكافآت والحوافز (Incentives):** إدارة وتوثيق المكافآت المالية.
- **التسويات المالية (Settlements):** متابعة التسويات المالية وحساب الرواتب والمستحقات.
- **نظام التقارير (Reports):** تصدير تقارير شاملة باستخدام Excel (مدعوم بواسطة `maatwebsite/excel`).
- **نظام الصلاحيات (Roles & Permissions):** واجهات مخصصة للمدير العام (Super Admin) والمديرين مع نظام تدقيق (Audit Log).
- **النسخ الاحتياطي (Backups):** نسخ احتياطي آلي لقواعد البيانات (مدعوم بواسطة `spatie/laravel-backup`).
- **واجهة برمجة التطبيقات (Mobile API):** واجهات API متكاملة لربط النظام بتطبيق الموبايل باستخدام `Laravel Sanctum`.

---

## 🛠 التقنيات المستخدمة (Tech Stack)

- **الواجهة الخلفية (Backend):** PHP 8.2+, Laravel 12
- **الواجهة الأمامية (Frontend):** Blade Templates, Tailwind CSS 4, Vite
- **قاعدة البيانات (Database):** MySQL
- **حزم إضافية (Packages):** 
  - `maatwebsite/excel` (لإصدار التقارير)
  - `spatie/laravel-backup` (للنسخ الاحتياطي)
  - `laravel/sanctum` (للمصادقة الخاصة بالـ API)

---

## 🚀 كيفية التشغيل (Installation & Setup)

1. **استنساخ المشروع (Clone the repository):**
   ```bash
   git clone https://github.com/zeyadi9/HR_System.git
   cd HR_System
   ```

2. **تثبيت الحزم (Install Dependencies):**
   ```bash
   composer install
   npm install
   ```

3. **إعداد ملف البيئة (Environment Setup):**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *قم بتعديل بيانات الاتصال بقاعدة البيانات في ملف `.env`.*

4. **تجهيز قاعدة البيانات (Database Migration):**
   ```bash
   php artisan migrate
   ```

5. **تشغيل المشروع (Run the application):**
   ```bash
   # تشغيل خادم Laravel
   php artisan serve

   # تشغيل خادم الواجهة الأمامية (Vite)
   npm run dev
   ```

---

## 📱 تطبيق الموبايل (Mobile App Integration)
يحتوي النظام على مجلد `Api` داخل الـ Controllers لتوفير مسارات خاصة بتطبيق الموبايل مثل تسجيل الدخول، الإشعارات، وطلبات الإجازات، مما يتيح للموظفين التفاعل مع النظام عبر هواتفهم الذكية بسهولة.

---

## 🔒 الأمان والمراقبة (Security & Auditing)
يشتمل النظام على لوحة تحكم لتتبع التغييرات (Audit Logs)، وملاحظات الإدارة (Admin Notes)، إلى جانب صلاحيات دخول دقيقة لضمان حماية بيانات الموظفين.
