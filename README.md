# 🚀 Elevate Workforce Solutions — Job Portal

> **Unit 22: Application Development | Level 5 | ISMT Nepal**
> Built with Laravel 11, MySQL, Bootstrap 5 & Blade Templating

![Laravel](https://img.shields.io/badge/Laravel-v11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

---

## 📋 Table of Contents

- [About the Project](#about-the-project)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [MVC Architecture](#mvc-architecture)
- [OOP Principles](#oop-principles)
- [Database ERD](#database-erd)
- [Installation](#installation)
- [Test Accounts](#test-accounts)
- [Screenshots](#screenshots)
- [Assignment Criteria](#assignment-criteria)

---

## 📖 About the Project

**Elevate Workforce Solutions** is a full-stack job portal web application built for a Level 5 Application Development assignment at ISMT Nepal. The client is a well-established employment agency in Nepal looking to digitize their hiring process and connect employers with job seekers.

The application strictly follows **Object-Oriented Programming (OOP)** principles and the **MVC (Model-View-Controller)** design pattern throughout, as required by the assignment criteria.

---

## ✨ Features

### For Job Seekers
- ✅ Register and log in securely
- ✅ Browse all active job listings with pagination (10 per page)
- ✅ Search jobs by keyword, type, category, and location
- ✅ View full job details
- ✅ Apply for jobs with a cover letter and resume upload
- ✅ Track all applications and their status from a personal dashboard

### For Companies
- ✅ Register with a company profile
- ✅ Post new job listings
- ✅ Edit and update existing job listings
- ✅ Delete job listings
- ✅ View all applications received per job
- ✅ Update applicant status (Pending → Reviewed → Shortlisted → Rejected)
- ✅ Company dashboard with stats

### Security
- ✅ Password hashing using `bcrypt`
- ✅ CSRF protection on all forms
- ✅ Role-based middleware (company vs jobseeker)
- ✅ Session management and secure logout
- ✅ Ownership authorization (companies can only edit their own jobs)

---

## 🛠️ Tech Stack

| Layer      | Technology                          | Why Chosen                                              |
|------------|-------------------------------------|---------------------------------------------------------|
| Backend    | PHP 8.5 + Laravel 11                | Strict MVC architecture, built-in OOP, Eloquent ORM     |
| Database   | MySQL 8.0                           | Relational data, perfect for ERD, integrates with Laravel|
| Frontend   | HTML5, CSS3, Bootstrap 5            | Responsive UI, professional look with minimal effort    |
| Templating | Blade (Laravel's built-in engine)   | Reusable layouts, demonstrates good software design     |
| Build Tool | Vite                                | Fast asset compilation for CSS and JS                   |

---

## 📁 Project Structure

```
portal-fresh/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/                  ← CONTROLLERS (C in MVC)
│   │   │   ├── AuthController.php        (register, login, logout)
│   │   │   ├── JobController.php         (CRUD: create/read/update/delete)
│   │   │   ├── ApplicationController.php (apply for jobs, update status)
│   │   │   └── DashboardController.php   (role-based dashboard routing)
│   │   │
│   │   └── Middleware/
│   │       └── RoleMiddleware.php        (guards routes by user role)
│   │
│   └── Models/                           ← MODELS (M in MVC)
│       ├── User.php                      (jobseeker / company / admin)
│       ├── Company.php                   (company profile, linked to User)
│       ├── Job.php                       (job listing with query scopes)
│       └── Application.php              (links User ↔ Job with status)
│
├── resources/
│   └── views/                            ← VIEWS (V in MVC — Blade Templates)
│       ├── layouts/
│       │   └── app.blade.php             (master layout: navbar + footer)
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── jobs/
│       │   ├── index.blade.php           (paginated job listings + filters)
│       │   ├── show.blade.php            (job detail + application form)
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── dashboard/
│       │   ├── company.blade.php         (manage jobs + view applicants)
│       │   └── jobseeker.blade.php       (track my applications)
│       └── home.blade.php
│
├── database/
│   ├── migrations/                       (database schema definitions)
│   └── seeders/
│       └── DatabaseSeeder.php            (demo data)
│
├── routes/
│   └── web.php                           (URL → Controller mappings)
│
├── bootstrap/
│   └── app.php                           (middleware registration)
│
└── public/
    └── css/
        └── app.css                       (custom styles)
```

---

## 🏗️ MVC Architecture

This application strictly follows the **Model-View-Controller** pattern:

```
Browser Request
      │
      ▼
  routes/web.php          ← Maps URL to the correct Controller method
      │
      ▼
  Controller              ← Receives request, contains business logic
  (e.g. JobController)
      │
      ├──► Model           ← Interacts with the database (Eloquent ORM)
      │    (e.g. Job)       returns data back to Controller
      │
      └──► View            ← Controller passes data to Blade template
           (e.g. jobs/index.blade.php)
                │
                ▼
          HTML Response sent back to the Browser
```

### Controllers

| Controller | Responsibility |
|---|---|
| `AuthController` | Register, login, logout |
| `JobController` | Full CRUD for job listings |
| `ApplicationController` | Submit applications, update status |
| `DashboardController` | Routes users to their role-specific dashboard |

---

## 🧱 OOP Principles

### 1. Encapsulation
All data and related behaviour is bundled inside classes. For example, `User.php` keeps `$password` hidden via `$hidden` array and exposes only safe methods like `isCompany()`.

### 2. Inheritance
- `User` extends `Authenticatable` (which extends `Model`)
- All Controllers extend the base `Controller` class
- All Models extend Laravel's `Model` class

### 3. Abstraction
The `RoleMiddleware` class abstracts away all authorization logic, keeping Controllers clean. Controllers don't need to know *how* role checking works — they just use `middleware('role:company')`.

### 4. Polymorphism
The `DashboardController::index()` method returns different Views based on the user's role — same method call, different behaviour and output.

### Query Scopes (OOP in Eloquent)
```php
// Job.php — reusable query logic encapsulated as methods
Job::active()->search('developer')->ofType('full-time')->paginate(10);
```

---

## 🗄️ Database ERD

```
┌─────────────────┐         ┌──────────────────────┐
│     users        │         │      companies        │
├─────────────────┤         ├──────────────────────┤
│ id (PK)         │──1───►  │ id (PK)              │
│ name            │         │ user_id (FK)          │
│ email (unique)  │         │ name                  │
│ password        │         │ description           │
│ role            │         │ location              │
│ created_at      │         │ industry              │
│ updated_at      │         │ website               │
└─────────────────┘         │ created_at            │
         │                  │ updated_at            │
         │                  └──────────────────────┘
         │                            │
         │                            │ 1
         │                            ▼ N
         │                  ┌──────────────────────┐
         │                  │        jobs           │
         │                  ├──────────────────────┤
         │                  │ id (PK)              │
         │                  │ company_id (FK)       │
         │                  │ title                 │
         │                  │ description           │
         │                  │ requirements          │
         │                  │ location              │
         │                  │ type                  │
         │                  │ salary_range          │
         │                  │ category              │
         │                  │ status                │
         │                  │ deadline              │
         │                  │ created_at            │
         │                  │ updated_at            │
         │                  └──────────────────────┘
         │                            │
         │ 1                          │ 1
         ▼ N                          ▼ N
┌─────────────────────────────────────────────────┐
│                  applications                    │
├─────────────────────────────────────────────────┤
│ id (PK)                                         │
│ user_id (FK) ──────────────────────────────────►│
│ job_id (FK)  ──────────────────────────────────►│
│ cover_letter                                    │
│ resume_path                                     │
│ status (pending/reviewed/shortlisted/rejected)  │
│ created_at                                      │
│ updated_at                                      │
└─────────────────────────────────────────────────┘

Relationships:
  User       ──[1:1]──► Company
  Company    ──[1:N]──► Job
  User       ──[1:N]──► Application
  Job        ──[1:N]──► Application
```

---

## 💻 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js (LTS)
- MySQL 8.0
- Git

### Step-by-Step Setup

**1. Clone the repository**
```bash
git clone https://github.com/YOURUSERNAME/elevate-job-portal.git
cd elevate-job-portal
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Install Node dependencies**
```bash
npm install
```

**4. Set up environment**
```bash
cp .env.example .env        # Mac/Linux
copy .env.example .env      # Windows CMD
Copy-Item .env.example .env # Windows PowerShell
```

Edit `.env` and set:
```env
DB_DATABASE=elevate_job_portal
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

**5. Generate application key**
```bash
php artisan key:generate
```

**6. Create the database in MySQL**
```sql
CREATE DATABASE elevate_job_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**7. Run migrations and seed demo data**
```bash
php artisan migrate --seed
```

**8. Start the development servers**

Terminal 1:
```bash
npm run dev
```

Terminal 2:
```bash
php artisan serve
```

**9. Open in browser**

Visit: **http://localhost:8000**

---

## 🔑 Test Accounts

| Role | Email | Password | Access |
|---|---|---|---|
| Job Seeker | ram@example.com | password | Browse & apply for jobs |
| Company 1 | hr@technepal.com | password | Post & manage jobs, view applicants |
| Company 2 | recruitment@hbl.com | password | Post & manage jobs, view applicants |
| Admin | admin@elevate.com | password | Full system access |

---

## ✅ Assignment Criteria Met

| Criteria | Requirement | Implementation |
|---|---|---|
| P1 | Problem definition & requirements | Documented in assignment report |
| P2 | Risk review | Documented in assignment report |
| P3 | Research software development tools | Laravel (MVC), MySQL (RDBMS), Bootstrap 5 |
| P4 | Peer review | Documented in assignment report |
| P5 | Functional business application | This working job portal |
| P6 | Performance review | Documented in assignment report |
| M1 | Software design document with ERD | ERD above, class diagrams in report |
| M2 | Justify tools and methodology | Laravel = strict MVC + OOP; Agile methodology |
| M4 | App based on software design doc | Matches ERD and UML class diagrams |
| D1 | Evaluate solution and methodology | Documented in assignment report |

---

## 👨‍💻 Developer

**Student:** *(Your Name)*
**Assessor:** Bhuwan Subedi
**Institution:** International School of Management & Technology (ISMT), Nepal
**Unit:** Unit 22 — Application Development (Level 5)
**Submission Date:** July 15, 2026

---

## 📄 License

This project was built for academic purposes at ISMT Nepal.
