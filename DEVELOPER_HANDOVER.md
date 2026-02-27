# HEKA Clinic Management System — Developer Handover Document

> **Project:** HEKA CMS · **Repository:** [github.com/Oz6ix/HEKA-CMS](https://github.com/Oz6ix/HEKA-CMS)
> **Live URL:** [heka-cms-1024385989093.us-central1.run.app](https://heka-cms-1024385989093.us-central1.run.app)
> **Date:** 27 February 2026

---

## Table of Contents
1. [Project Overview](#1-project-overview)
2. [Getting Started (Local Setup)](#2-getting-started-local-setup)
3. [Project Structure](#3-project-structure)
4. [Architecture Deep Dive](#4-architecture-deep-dive)
5. [Database Schema](#5-database-schema)
6. [Frontend Architecture](#6-frontend-architecture)
7. [Authentication & Authorization](#7-authentication--authorization)
8. [File Upload System](#8-file-upload-system)
9. [Git Workflow & Deployment](#9-git-workflow--deployment)
10. [Configuration Reference](#10-configuration-reference)
11. [Known Issues & Technical Debt](#11-known-issues--technical-debt)
12. [Key Conventions](#12-key-conventions)

---

## 1. Project Overview

HEKA is a **clinic management system** built with Laravel 11. It manages patients, appointments, EMR (Electronic Medical Records), pharmacy/inventory, diagnostics, billing, and staff administration.

### Credentials

| Environment | URL | Email | Password |
|---|---|---|---|
| **Local** | `http://127.0.0.1:8000/admin/login` | `admin@admin.com` | `password` |
| **Cloud Run** | See live URL above | `admin@admin.com` | `password` |

### Tech Stack Summary

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11, PHP 8.4 (production) / 8.5 (local) |
| Database | SQLite (file-based) |
| Frontend | Blade, Tailwind CSS (CDN), Alpine.js 3.14 |
| JS Libraries | jQuery 3.7, Dropzone 5.9, Toastr, FullCalendar 6.1 |
| Build | Vite 7.x |
| Hosting | Google Cloud Run (free tier) |
| Container | Docker (Nginx + PHP-FPM 8.4 + SQLite) |

---

## 2. Getting Started (Local Setup)

### Prerequisites
- PHP 8.2+ with extensions: `pdo_sqlite`, `gd`, `bcmath`, `mbstring`
- Composer 2.x
- Node.js 18+ / npm
- Git

### First-Time Setup

```bash
# 1. Clone the repo
git clone https://github.com/Oz6ix/HEKA-CMS.git heka-modern
cd heka-modern

# 2. Install PHP dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Create SQLite database and run migrations
touch database/database.sqlite
php artisan migrate

# 5. Seed demo data (admin user + 10 records per module)
php artisan db:seed

# 6. Install frontend dependencies and build
npm install
npm run build

# 7. Start the dev server
php artisan serve
```

### Quick Start (Composer Script)
```bash
composer setup   # Runs steps 2-6 automatically
composer dev     # Starts server + queue + logs + Vite simultaneously
```

### Daily Development
```bash
# Option A: Full stack with hot reload
composer dev

# Option B: Manual
php artisan serve          # Terminal 1 — Backend on :8000
npm run dev                # Terminal 2 — Vite HMR on :5173
```

> [!IMPORTANT]
> The app requires Vite to run for Tailwind CSS compilation. If Vite is NOT running, the Tailwind CSS **CDN fallback** loads automatically (defined in `modern.blade.php`), so the app still works but with slightly different behavior.

---

## 3. Project Structure

```
heka-modern/
├── app/
│   ├── Helpers/                    # 4 auto-loaded helper files
│   │   ├── AppHelper.php           #   generate_log(), url_prefix helpers
│   │   ├── AuthHelper.php          #   Authentication helpers
│   │   ├── Communication.php       #   Email/SMS helpers
│   │   └── FrontendHelper.php      #   Frontend utility functions
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminModule/        # ⭐ 45 controllers (all admin features)
│   │   │   └── Frontend/           # Customer-facing controllers
│   │   ├── Middleware/
│   │   │   └── EnsurePermissions.php  # Role-based access control
│   │   └── Requests/               # Form validation classes
│   ├── Models/                     # 61 Eloquent models
│   ├── Repositories/
│   │   ├── Contracts/              # Repository interfaces
│   │   └── Eloquent/               # Repository implementations
│   ├── Services/                   # 5 service classes (business logic)
│   └── Providers/
│       └── AppServiceProvider.php  # Binds repositories to interfaces
│
├── config/
│   ├── app.php                     # app_route_prefix = 'admin' (default)
│   ├── global.php                  # Gender, marital status, unit enums
│   └── filesystems.php             # 'uploads' disk → public/uploads/
│
├── database/
│   ├── database.sqlite             # SQLite database file
│   ├── migrations/                 # 26 migration files
│   └── seeders/
│       ├── DatabaseSeeder.php      # Admin user (firstOrCreate) + DemoDataSeeder
│       └── DemoDataSeeder.php      # 10+ records per module (idempotent)
│
├── docker/
│   ├── nginx.conf                  # Nginx reverse proxy config
│   ├── php.ini                     # PHP production overrides
│   └── start.sh                    # Container startup script
│
├── resources/views/backend/
│   ├── layouts/
│   │   ├── modern.blade.php        # ⭐ PRIMARY layout (Tailwind + Alpine)
│   │   ├── admin.blade.php         # Legacy layout (Bootstrap + jQuery)
│   │   └── includes/
│   │       ├── sidebar_menu.blade.php      # Navigation sidebar
│   │       ├── header_modern.blade.php     # Top header bar
│   │       └── admin_modal_popup_alert.blade.php  # Alpine.js alert modal
│   ├── admin_module/               # ⭐ All feature view directories
│   │   ├── appointment/            # List, create, edit, view, calendar
│   │   ├── emr/                    # EMR workbench (show.blade.php)
│   │   ├── patient/                # CRUD + photo upload
│   │   ├── pharmacy/               # Medicine management
│   │   ├── inventory/              # Stock management
│   │   ├── staff/                  # Staff CRUD
│   │   └── ...                     # 20+ more feature directories
│   └── auth/                       # Login, password reset views
│
├── routes/
│   └── web.php                     # 589 lines — ALL routes (single file)
│
├── public/uploads/                 # User-uploaded files (patient photos, etc.)
├── Dockerfile                      # 3-stage Docker build
└── vite.config.js                  # Vite build configuration
```

---

## 4. Architecture Deep Dive

### Pattern: MVC + Service + Repository

```
Request → Route → Middleware → Controller → Service → Repository → Model → DB
                                    ↓
                              Blade View (response)
```

### Controllers (45 total, organized by domain)

| Domain | Controllers | Purpose |
|--------|-----------|---------|
| **Clinical** | `AppointmentController`, `EMRController`, `PatientController`, `PatientDiagnosisController`, `MedicalCertificateController`, `ReferralController` | Core patient care workflows |
| **Administration** | `StaffController`, `StaffDepartmentController`, `StaffDesignationController`, `StaffRoleController`, `StaffSpecialistController`, `AdminUserController`, `UserGroupController`, `ProfileController` | Staff & user management |
| **Pharmacy** | `SettingPharmacyController`, `SettingPharmacyCategoryController`, `PharmacyGenericController`, `PharmacyDosageController`, `PharmacySalesController` | Medicine & dispensing |
| **Inventory** | `InventoryCategoryController`, `InventoryItemMasterController`, `InventoryStockController` | Stock tracking |
| **Diagnostics** | `SettingPathologyController`, `SettingPathologyCategoryController`, `SettingRadiologyController`, `SettingRadiologyCategoryController` | Lab & radiology tests |
| **Billing** | `PatientBillController`, `RevenueCycleManagementController` | Invoicing & RCM |
| **Settings** | `SettingGeneralController`, `SettingHospitalChargeController`, `SettingHospitalChargeCategoryController`, `SettingSupplierController`, `SettingUnitController`, `SettingQuantityController`, `SettingConfigurationController`, `SettingNotificationController` | System configuration |
| **Other** | `DashboardController`, `AuthenticateController`, `ReportController`, `TpaController`, `CasualtyController`, `CenterController`, `FrequencyController`, `SymptomTypeController`, `ImagesController` | Dashboard, auth, reports |

### Services (5 classes)

| Service | Size | Key Responsibilities |
|---------|------|---------------------|
| `PatientDiagnosisService` | 33KB | SOAP notes, prescriptions, vitals, lab orders, documents — the largest service |
| `InventoryService` | 7KB | Stock in/out, adjustments, batch tracking |
| `AppointmentService` | 6KB | Booking, calendar events, status management |
| `StaffService` | 5KB | Staff CRUD, department assignment |
| `PatientService` | 2KB | Patient CRUD, photo management |

### Repository Layer

Each service uses a repository interface (in `app/Repositories/Contracts/`) bound to an Eloquent implementation (in `app/Repositories/Eloquent/`). Bindings are in `AppServiceProvider.php`.

> [!WARNING]
> Some controllers bypass the service/repository pattern and access Eloquent models directly. This is legacy code. New features should always go through the service layer.

### Helper Files (Auto-loaded via `composer.json`)

| File | Key Functions |
|------|-------------|
| `AppHelper.php` | `generate_log()`, `url_prefix()` |
| `AuthHelper.php` | Auth-related utilities |
| `Communication.php` | Email/notification helpers |
| `FrontendHelper.php` | Customer portal utilities |

---

## 5. Database Schema

### SQLite Database
- **Location:** `database/database.sqlite`
- **Connection:** Configured in `.env` as `DB_CONNECTION=sqlite`

### Migrations (26 files, chronological)

| Migration Group | Tables Created |
|----------------|---------------|
| **Laravel Defaults** | `users`, `cache`, `jobs` |
| **Staff** | `staff`, `staff_departments`, `staff_designations`, `staff_roles`, `staff_specialists`, `staff_documents` |
| **Patients** | `patients`, `blood_groups` |
| **Settings** | `settings_site_generals`, `settings_configurations`, `settings_notifications`, `settings_suppliers`, `setting_site_logos`, `hospital_charges`, `hospital_charge_categories`, `tpas`, `casualties`, `centers`, `frequencies`, `symptom_types` |
| **Clinical** | `appointments`, `appointment_basics_details`, `appointment_reminders`, `patient_diagnoses`, `patient_prescriptions`, `patient_brief_notes`, `patient_medical_tests`, `medical_certificates`, `referrals` |
| **Inventory** | `inventory_categories`, `inventory_item_masters`, `inventory_stocks`, `stock_adjustments` |
| **Medical Tests** | `pathologies`, `pathology_categories`, `pathology_parameters`, `radiologies`, `radiology_categories`, `radiology_parameters` |
| **Pharmacy** | `pharmacies`, `pharmacy_categories`, `pharmacy_generics`, `pharmacy_dosages`, `pharmacy_sales`, `dispensed_items`, `external_prescriptions` |
| **RCM** | `rcm_invoices`, `rcm_bill_items`, `patient_bills`, `patient_bill_consumables`, `patient_bill_pathologies`, `patient_bill_radiologies` |
| **Documents** | `patient_documents`, `patient_diagnosis_reports` |

### Seeders

| Seeder | Purpose |
|--------|---------|
| `DatabaseSeeder` | Creates admin user (`admin@admin.com` / `password`) via `firstOrCreate`, then calls `DemoDataSeeder` |
| `DemoDataSeeder` | Creates 10+ demo records per module (patients, doctors, appointments, pharmacy, lab tests, etc.) — fully idempotent |

> [!TIP]
> Run `php artisan db:seed` multiple times safely — both seeders use `firstOrCreate` to prevent duplicates.

---

## 6. Frontend Architecture

### Dual Layout System

The app has two Blade layouts. **New features should use `modern.blade.php`.**

| Layout | File | Used By | Stack |
|--------|------|---------|-------|
| **Modern** ✅ | `layouts/modern.blade.php` | Patient, Appointment, EMR, Dashboard, Staff, Pharmacy, Inventory, Settings | Tailwind CSS (CDN), Alpine.js, jQuery, Dropzone, Toastr |
| **Legacy** ⚠️ | `layouts/admin.blade.php` | Some older pages | Bootstrap 4, jQuery, KT Theme, Select2, SweetAlert2 |

### CDN Dependencies (loaded in `modern.blade.php`)

```html
<!-- HEAD -->
<link rel="stylesheet" href="cdnjs/font-awesome@6.4.0">
<link rel="stylesheet" href="cdnjs/dropzone@5.9.3">
<link rel="stylesheet" href="cdnjs/toastr">
<script src="cdn.tailwindcss.com">          <!-- Tailwind CDN fallback -->
<script defer src="cdnjs/alpinejs@3.14.9">  <!-- Alpine.js -->

<!-- BODY (before </body>) -->
<script src="cdnjs/jquery@3.7.1">
<script src="cdnjs/dropzone@5.9.3">
<script src="cdnjs/toastr">
@yield('scripts')                           <!-- Page-specific JS -->
```

### Alpine.js Component Pattern

> [!CAUTION]
> **Critical:** Alpine.js components MUST be registered using `Alpine.data()` inside an `alpine:init` event listener. Plain `function` declarations cause a race condition because Alpine (loaded with `defer`) initializes before the function is available.

**✅ Correct Pattern (use this):**
```javascript
@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('myComponent', () => ({
        items: [],
        init() { /* runs on mount */ }
    }));
});
</script>
@endsection
```

**❌ Wrong Pattern (causes blank page):**
```javascript
<!-- This will NOT work — function undefined when Alpine loads -->
<script>
function myComponent() { return { items: [] } }
</script>
```

### Tailwind CSS Notes
- The app uses `cdn.tailwindcss.com` as a **fallback** when Vite isn't running (production or no dev server)
- Tailwind config is inlined in `modern.blade.php` with custom `primary` color palette
- **Some Tailwind classes don't compile properly in production** (e.g., gradients, arbitrary values like `text-[10px]`). Use **inline styles** for critical colors on dark backgrounds

---

## 7. Authentication & Authorization

### Auth Flow
1. User visits `/admin/login` → `AuthenticateController@login`
2. On submit → `AuthenticateController@store` validates credentials
3. On success → redirects to `/admin/dashboard`
4. Session-based auth with Laravel's built-in `auth` guard

### Middleware
- **`auth`** — Standard Laravel auth check
- **`EnsurePermissions`** — Custom middleware (`app/Http/Middleware/EnsurePermissions.php`) that checks user group permissions against the requested route

### User Model
- Users have a `group_id` linking to `UserGroup`
- `UserGroup` defines permissions (JSON field storing allowed route names)
- Admin user (seeded) has full access

---

## 8. File Upload System

### Patient Photos
- **Upload:** Via Dropzone.js in `patient/edit.blade.php`
- **Storage:** `public/uploads/patient/{patient_folder_name}/{filename}`
- **Disk:** `uploads` (configured in `config/filesystems.php`, root = `public_path('uploads')`)
- **Controller:** `PatientController@storeImage` (saves file + updates `patient_photo` column)
- **Display:** `{{ asset('uploads/patient/'.$item->patient_folder_name.'/'.$item->patient_photo) }}`

### Staff Photos
- Similar pattern via `StaffController@storeImage`
- Storage: `public/uploads/staff/{folder}/{filename}`

---

## 9. Git Workflow & Deployment

### Branches

| Branch | Purpose |
|--------|---------|
| `main` | Development — all work happens here |
| `pre-prod` | Staging/deploy — merged from `main` before deploying |

### Deployment Steps

```bash
# 1. Commit and push to main
git add -A && git commit -m "description" && git push origin main

# 2. Merge to pre-prod
git checkout pre-prod
git merge main
git push origin pre-prod

# 3. Deploy to Cloud Run
gcloud run deploy heka-cms \
  --source=. \
  --project=heka-cms \
  --region=asia-southeast1 \
  --allow-unauthenticated

# 4. Switch back to main
git checkout main
```

### Docker Build (3-stage)

| Stage | Image | Action |
|-------|-------|--------|
| 1. Frontend | `node:20-alpine` | `npm ci` + `npm run build` (Vite/Tailwind) |
| 2. Composer | `composer:2` | `composer install --no-dev` |
| 3. Production | `php:8.4-fpm-alpine` | Nginx + PHP-FPM + SQLite, port 8080 |

### Container Startup (`docker/start.sh`)
1. Creates `.env` from environment variables
2. `php artisan key:generate`
3. `php artisan migrate --force`
4. `php artisan db:seed --force` (idempotent)
5. Caches config/routes/views
6. Starts PHP-FPM (background) → Nginx (foreground on port 8080)

> [!NOTE]
> The SQLite database is **ephemeral** — it's created fresh on each container startup. Data persists only within the container's lifecycle. For production data persistence, consider Cloud SQL or a mounted volume.

---

## 10. Configuration Reference

### Key Config Files

| File | Key Settings |
|------|-------------|
| `config/app.php` | `app_route_prefix` → defaults to `'admin'` (env: `APP_ROUTE_PREFIX`) |
| `config/global.php` | Enums: gender (1=Male,2=Female,3=Other), marital status, height/weight units, temperature |
| `config/filesystems.php` | `uploads` disk (root: `public/uploads/`), `public_uploads` disk |

### Route Prefix
All admin routes use `$url_prefix = Config::get('app.app_route_prefix', 'admin')`. This means:
- Routes are at `/admin/dashboard`, `/admin/patient`, etc.
- Blade views use `$url_prefix` variable for URL generation
- **Never hardcode `/admin/` or `/am/` in JavaScript** — always use `{{ $url_prefix }}`

### Environment Variables

```env
# Required
APP_KEY=                    # Auto-generated
DB_CONNECTION=sqlite        # or mysql
DB_DATABASE=database/database.sqlite

# Optional (Cloud Run sets these)
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-cloud-run-url
LOG_CHANNEL=stderr          # For Cloud Run logging
```

---

## 11. Known Issues & Technical Debt

### ⚠️ Must-Know Gotchas

| Issue | Details | Workaround |
|-------|---------|-----------|
| **Tailwind classes not compiling** | Some Tailwind utility classes (gradients, arbitrary values) don't work in production because the CDN fallback doesn't scan all templates | Use **inline styles** for critical colors, especially on dark backgrounds |
| **Alpine.js race condition** | Alpine loads with `defer`, so component functions must use `Alpine.data()` registration | See [Frontend Architecture](#6-frontend-architecture) for correct pattern |
| **Ephemeral database** | SQLite in Docker = data lost on redeploy | Seeders are idempotent; consider Cloud SQL for production |
| **Mixed layout usage** | Some pages still use `admin.blade.php` (Bootstrap) | Migrate to `modern.blade.php` (Tailwind) over time |
| **Legacy URL patterns** | Some JS files may have hardcoded `/am/` instead of `$url_prefix` | Always grep for hardcoded paths when debugging 404s |
| **Protected property access** | Some controllers try to access `$service->repository` (protected) | Use service methods or direct Eloquent queries instead |

### Technical Debt

1. **Single route file** — `routes/web.php` is 589 lines. Should be split into domain-specific route files.
2. **No automated tests** — Only default Laravel example tests exist. No unit or feature tests for business logic.
3. **No API** — Everything is server-rendered Blade. No REST/GraphQL API for mobile apps.
4. **Hard-coupled views** — Some views contain business logic that should be in controllers/services.
5. **No queue usage** — Email sending and heavy operations are synchronous.

---

## 12. Key Conventions

### Naming
- **Controllers:** `PascalCase` + `Controller` suffix (e.g., `PatientController`)
- **Models:** Singular `PascalCase` (e.g., `Patient`, `PharmacySale`)
- **Views:** `snake_case` in directories matching controller domains
- **Routes:** Named routes use `dot.notation` (e.g., `patient.index`, `patient.edit`)

### URL Pattern
All admin URLs follow: `/{prefix}/{resource}/{action}/{id?}`
Example: `/admin/patient/edit/3`, `/admin/appointment/create`

### Data Patterns
- **Soft deletes:** Use `delete_status` column (0 = active, 1 = deleted) instead of Laravel's `SoftDeletes` trait
- **Status fields:** Generally `1 = Active`, `0 = Inactive` (or custom per module)
- **Timestamps:** Standard Laravel `created_at`, `updated_at`
- **Patient codes:** Auto-generated as `HEKA00001`, `HEKA00002`, etc.

### View Variables
Every view receives `$url_prefix` (the route prefix, default `'admin'`). Use it in all URL generation:

```blade
<!-- ✅ Correct -->
<a href="{{ url($url_prefix . '/patient/edit/' . $item->id) }}">

<!-- ❌ Wrong (breaks if prefix changes) -->
<a href="/admin/patient/edit/{{ $item->id }}">
```

---

## Quick Reference: Common Tasks

| Task | Command / Location |
|------|--------------------|
| Start dev server | `composer dev` or `php artisan serve` |
| Run migrations | `php artisan migrate` |
| Seed demo data | `php artisan db:seed` |
| Reset database | `rm database/database.sqlite && touch database/database.sqlite && php artisan migrate && php artisan db:seed` |
| Build for production | `npm run build` |
| Deploy to Cloud Run | `gcloud run deploy heka-cms --source . --project=heka-cms --region=asia-southeast1` |
| Clear all caches | `php artisan optimize:clear` |
| View routes | `php artisan route:list` |
| Find a controller | `app/Http/Controllers/AdminModule/` |
| Find a view | `resources/views/backend/admin_module/{feature}/` |
| Check logs | `storage/logs/laravel.log` (local) or Cloud Run Logs (production) |
