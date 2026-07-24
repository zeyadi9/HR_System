<div align="center">
  <h1>🏢 نظام إدارة الموارد البشرية المتكامل (HRMS)</h1>
  <p><b>حل برلمجي شامل وإنسيابي لإدارة الموظفين، الحضور والانصراف، الأذونات والإجازات، التصفية المالية وتطبيق الهواتف الذكية.</b></p>

  <!-- Badges -->
  <p>
    <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
    <img src="https://img.shields.io/badge/Flutter-Cross--Platform-02569B?style=for-the-badge&logo=flutter&logoColor=white" alt="Flutter" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/MySQL-Database-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
    <img src="https://img.shields.io/badge/REST_API-Sanctum-000000?style=for-the-badge&logo=json&logoColor=white" alt="Sanctum API" />
  </p>
</div>

---

## 📋 فهرس المحتويات (Table of Contents)
- [📖 عن المشروع (About)](#-عن-المشروع-about)
- [✨ المميزات الرئيسية (Key Features)](#-المميزات-الرئيسية-key-features)
- [🏗️ الهيكلية التقنية (Architecture & Tech Stack)](#️-الهيكلية-التقنية-architecture--tech-stack)
- [📱 تطبيق الهواتف الذكية (Mobile Application)](#-تطبيق-الهواتف-الذكية-mobile-application)
- [🚀 دليل التثبيت والتشغيل (Getting Started)](#-دليل-التثبيت-والتشغيل-getting-started)
- [📡 واجهات البرمجة (API Documentation)](#-واجهات-البرمجة-api-documentation)
- [🛡️ الأمان والنسخ الاحتياطي (Security & Backups)](#️-الأمان-والنسخ-الاحتياطي-security--backups)
- [👥 التطوير والدعم (Contribution & Support)](#-التطوير-والدعم-contribution--support)

---

## 📖 عن المشروع (About)

نظام **HR Management System** هو منصة متكاملة مخصصة لإدارة العمليات اليومية للموارد البشرية داخل المؤسسات والشركات بشكل مؤتمت بالكامل. يجمع النظام بين لوحة تحكم سحابية سلسة لإدارة الموظفين والحسابات (تطوير **Laravel 12**) وتطبيق جوال متعدد المنصات (تطوير **Flutter**).

تم تصميم النظام ليعالج كافة المعاملات المالية والإدارية بدقة متناهية، متضمناً حساب الأذونات، الإجازات، الخصومات والجزاءات، الإضافي، التصفية المالية الشهرية، والنسخ الاحتياطي التلقائي لقواعد البيانات.

---

## ✨ المميزات الرئيسية (Key Features)

### 👥 1. إدارة الموظفين والحسابات (Personnel & Access Control)
- **سجلات الموظفين:** إدارة بيانات الموظفين شاملة المسمى الوظيفي، الراتب الأساسي، وساعات العمل.
- **الأدوار والصلاحيات:** تحكم دقيق بالصلاحيات لمدراء النظام (`Super Admin`, `Admin`) والموظفين (`User`).
- **الملاحظات الإدارية (Admin Notes):** تدوين وتوثيق الملاحظات السرية والإدارية حول الموظفين.

### ⏰ 2. الحضور والانصراف والأذونات (Time, Attendance & Permissions)
- **تسجيل الحضور اليومي:** تتبع مواعيد الدخول والخروج والغياب والساعات التأخيرية.
- **إدارة الأذونات (Permission Management):** تقديم طلبات الأذونات اليومية مع تطبيق ضابط تلقائي أقصاه **3 ساعات شهرياً (180 دقيقة)** لكل موظف.
- **حساب الإضافي والتأخير:** احتساب تلقائي لساعات العمل الإضافية والتأخيرات بناءً على الجدول المعتمد.

### 🏖️ 3. الإجازات والاستئذانات (Leave Management)
- **أنواع الإجازات:** دعم إجازات اعتادية، مرضية، وبدون أجر.
- **نظام الموافقات:** دورة اعتماد مرنة لطلبات الإجازات والأذونات من قبل الإدارة مع إمكانية القبول أو الرفض وتوضيح الأسباب.

### 💰 4. المالية والرواتب والتصفية (Payroll & Financials)
- **المكافآت والجزاءات:** إصدار المكافآت المالية والخصومات والجزاءات الإدارية مع توثيق الأسباب.
- **التصفية المالية (Monthly Settlement):** احتساب صافي الراتب المستحق بنهاية الشهر بعد خصم الجزاءات والتأخيرات وإضافة المكافآت والإضافي.

### 📊 5. التقارير والتصدير (Reporting & Data Export)
- **تصدير كشوفات Excel:** تصدير تقارير الرواتب، الحضور والانصراف، والجزاءات بصيغة Excel عالية الجودة باستخدام مكتبة `Maatwebsite/Excel`.
- **سجل المراجعة (Audit Log):** تتبع وتوثيق كل العمليات الحساسة التي قام بها المدراء لحماية البيانات وشعور بالأمان العالي.

---

## 🏗️ الهيكلية التقنية (Architecture & Tech Stack)

### 🖥️ لوحة التحكم والويب (Backend & Web Dashboard)
- **الإطار البرمجي:** Laravel 12 / PHP 8.2+
- **قاعدة البيانات:** MySQL
- **التصميم الواجهي:** Tailwind CSS 4 + Blade Templates + Vite
- **المصادقة والتأمين:** Laravel Sanctum (APIs) & Web Sessions

### 📱 تطبيق الجوال (Mobile App)
- **الإطار البرمجي:** Flutter (Dart)
- **مجلد التطبيق:** `tk_mobile/`
- **الخدمات:** استهلاك واجهات RESTful API، تقديم الطلبات، استعلامات الحضور والراتب.

---

## 📱 تطبيق الهواتف الذكية (Mobile Application)

يحتوي المجلد `tk_mobile` على مشروع **Flutter** جاهز للربط مع السيرفر:
- **تسجيل الدخول والتأمين:** عبر توكنات Laravel Sanctum.
- **الخدمة الذاتية للموظف (Employee Self-Service):**
  - تقديم طلب إذن أو إجازة مباشرة من الموبايل.
  - متابعة حالة الطلبات (مقبول / مجهول / مرفوض).
  - الاستعلام عن كشف الحضور والانصراف اليومي.
  - عرض تفاصيل المستحقات والرواتب.

---

## 🚀 دليل التثبيت والتشغيل (Getting Started)

### 1️⃣ المتطلبات الأساسية (Prerequisites)
تأكد من تثبيت البرامج التالية على جهازك:
- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.0 & **npm**
- **MySQL Database**
- **Flutter SDK** (في حال تشغيل تطبيق الموبايل)

---

### 2️⃣ تثبيت مشروع الويب (Web App Setup)

```bash
# 1. استنساخ المشروع
git clone https://github.com/viora4software/HR-System.git
cd HR-System

# 2. تثبيت اعتماديات الباك إند (PHP Dependencies)
composer install

# 3. تثبيت اعتماديات الفروانت إند (Node Dependencies)
npm install

# 4. إعداد ملف البيئة (.env)
cp .env.example .env

# قم بتحديث بيانات الاتصال بقاعدة البيانات في ملف .env
# DB_DATABASE=tk_hr
# DB_USERNAME=root
# DB_PASSWORD=

# 5. توليد مفتاح التطبيق
php artisan key:generate

# 6. تشغيل الهجرات والبذور (Migrations & Seeders)
php artisan migrate --seed

# 7. بناء الملفات وتكثيف الخادم
npm run build
php artisan serve
```

سيكون لوحة التحكم متاحة على: `http://127.0.0.1:8000`

---

### 3️⃣ تشغيل تطبيق الموبايل (Flutter App Setup)

```bash
# الانتقال لمجلد تطبيق الموبايل
cd tk_mobile

# جلب الاعتماديات
flutter pub get

# تشغيل التطبيق (على المحاكي أو الجهاز المتصل)
flutter run
```

---

## 📡 واجهات البرمجة (API Documentation)

تتوفر واجهات RESTful API للموبايل محصنة عبر **Laravel Sanctum Middleware**:

| Endpoint | Method | الوصف |
|---|---|---|
| `/api/login` | `POST` | تسجيل دخول الموظف واستلام API Token |
| `/api/user` | `GET` | الحصول على بيانات الموظف الحالي |
| `/api/permission/store` | `POST` | تقديم طلب إذن (مع فحص حد الـ 3 ساعات شهرياً) |
| `/api/leave/store` | `POST` | تقديم طلب إجازة جديدة |
| `/api/my-requests` | `GET` | عرض قائمة الطلبات الخاصة بالموظف وحالتها |
| `/api/attendance` | `GET` | كشف الحضور والانصراف للموظف |

---

## 🛡️ الأمان والنسخ الاحتياطي (Security & Backups)

- **النسخ الاحتياطي الآلي:** يعتمد النظام على حزمة `spatie/laravel-backup` للنسخ الاحتياطي التلقائي.
- **جدولة المهام (Windows Task Scheduler):** يوجد سكريبت جاهز `run_db_backup.bat` و `setup_db_backup_task.bat` لإجراء النسخ الاحتياطي الدوري التلقائي لحفظ قاعدة البيانات في مجلد `DB BK/`.

---

<div align="center">
  <p><b>تم إنشاؤه وتطويره بواسطة Viora Software 🚀</b></p>
</div>
