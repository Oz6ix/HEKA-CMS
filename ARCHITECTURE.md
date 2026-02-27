# HEKA Clinic Management System — Architecture & Infrastructure

> **Last Updated:** 27 Feb 2026 · **Repository:** [github.com/Oz6ix/HEKA-CMS](https://github.com/Oz6ix/HEKA-CMS) · **Live:** [heka-cms-1024385989093.us-central1.run.app](https://heka-cms-1024385989093.us-central1.run.app)

---

## Infrastructure Overview

```mermaid
graph TB
    subgraph DEV["🧑‍💻 Local Development"]
        LOCAL["macOS · PHP 8.5\nphp artisan serve :8000"]
        VITE["Vite Dev Server :5173\nHot Module Reload"]
    end

    subgraph GIT["📦 GitHub — Oz6ix/HEKA-CMS"]
        MAIN["main branch\n(development)"]
        PREPROD["pre-prod branch\n(staging/deploy)"]
    end

    subgraph GCP["☁️ Google Cloud Platform"]
        CLOUDBUILD["Cloud Build\n(Docker multi-stage)"]
        ARTIFACT["Artifact Registry\n(Docker images)"]
        CLOUDRUN["Cloud Run\nheka-cms\nus-central1"]
    end

    subgraph CONTAINER["🐳 Docker Container"]
        NGINX["Nginx :8080\n(reverse proxy)"]
        PHPFPM["PHP-FPM 8.4\n(Alpine)"]
        SQLITE["SQLite\n(file-based DB)"]
        UPLOADS["📁 /public/uploads\n(patient photos)"]
    end

    subgraph CDN["🌐 CDN Libraries"]
        TW["Tailwind CSS CDN"]
        JQUERY["jQuery 3.7"]
        DROPZONE["Dropzone 5.9"]
        TOASTR["Toastr.js"]
        ALPINE["Alpine.js 3.14"]
        FA["Font Awesome 6.4"]
        FCAL["FullCalendar 6.1"]
    end

    LOCAL -->|"git push"| MAIN
    MAIN -->|"git merge"| PREPROD
    PREPROD -->|"gcloud run deploy\n--source ."| CLOUDBUILD
    CLOUDBUILD -->|"build image"| ARTIFACT
    ARTIFACT -->|"deploy"| CLOUDRUN
    CLOUDRUN --> CONTAINER
    NGINX --> PHPFPM
    PHPFPM --> SQLITE
    PHPFPM --> UPLOADS
    CDN -.->|"loaded at runtime"| NGINX

    classDef gcp fill:#e8f0fe,stroke:#4285f4,stroke-width:2px
    classDef dev fill:#fef3c7,stroke:#f59e0b,stroke-width:2px
    classDef cdn fill:#d1fae5,stroke:#10b981,stroke-width:2px
    classDef git fill:#fce7f3,stroke:#ec4899,stroke-width:2px
    class CLOUDBUILD,ARTIFACT,CLOUDRUN gcp
    class LOCAL,VITE dev
    class MAIN,PREPROD git
    class TW,JQUERY,DROPZONE,TOASTR,ALPINE,FA,FCAL cdn
```

---

## Git Branching Strategy

```mermaid
gitgraph
    commit id: "initial setup"
    branch pre-prod
    checkout main
    commit id: "sidebar fixes"
    commit id: "dashboard cards"
    commit id: "Alpine.js CDN fix"
    commit id: "demo data seeder"
    commit id: "profile redesign"
    checkout pre-prod
    merge main id: "merge to pre-prod"
    checkout main
    commit id: "EMR header fix"
    commit id: "photo upload fix"
    commit id: "appointment list fix"
    checkout pre-prod
    merge main id: "deploy v2"
```

| Branch | Purpose | Deploys To |
|--------|---------|-----------|
| **`main`** | Active development — all feature work and bug fixes land here first | Local dev |
| **`pre-prod`** | Staging/deploy branch — merged from `main` when ready to deploy | Cloud Run |

### Workflow
1. Develop and commit on **`main`**
2. `git checkout pre-prod && git merge main && git push origin pre-prod`
3. `gcloud run deploy heka-cms --source . --project=heka-cms --region=asia-southeast1 --allow-unauthenticated`

---

## Cloud Run Deployment Pipeline

```mermaid
flowchart LR
    A["gcloud run deploy\n--source ."] --> B["Cloud Build"]
    B --> C["Stage 1: Node 20\nnpm ci + npm run build"]
    C --> D["Stage 2: Composer 2\ncomposer install"]
    D --> E["Stage 3: PHP 8.4-fpm\n+ Nginx + SQLite"]
    E --> F["Artifact Registry"]
    F --> G["Cloud Run\nheka-cms"]
    G --> H["start.sh\nmigrate + seed + cache"]
```

### Docker Build (3-stage)
| Stage | Base Image | Action |
|-------|-----------|--------|
| **1. Frontend** | `node:20-alpine` | `npm ci` → `npm run build` (Vite/Tailwind) |
| **2. Composer** | `composer:2` | `composer install --no-dev --optimize` |
| **3. Production** | `php:8.4-fpm-alpine` | Nginx + PHP-FPM + SQLite, port 8080 |

### Container Startup (`start.sh`)
1. Generate `.env` from environment variables
2. `php artisan key:generate`
3. `php artisan migrate --force`
4. `php artisan db:seed --force` (idempotent — uses `firstOrCreate`)
5. `php artisan config:cache && route:cache && view:cache`
6. Start PHP-FPM (background) → Nginx (foreground, port 8080)

### Cloud Run Configuration
| Setting | Value |
|---------|-------|
| **Service Name** | `heka-cms` |
| **Project** | `heka-cms` |
| **Region** | `us-central1` |
| **Port** | `8080` |
| **Auth** | Unauthenticated (public) |
| **Tier** | Free tier |

---

## Application Architecture

```mermaid
graph TB
    subgraph FRONTEND["📦 Frontend Layer"]
        TAILWIND["Tailwind CSS\n(CDN + Vite build)"]
        ALPINEJS["Alpine.js 3.14\n(UI interactivity)"]
        DZ["Dropzone.js\n(file uploads)"]
        JQ["jQuery 3.7\n(AJAX + DOM)"]
    end

    subgraph LAYOUTS["🎨 Blade Layouts"]
        MODERN["modern.blade.php\n✅ Active - Tailwind"]
        ADMIN["admin.blade.php\n⚠️ Legacy - Bootstrap"]
        LOGIN["admin_login.blade.php"]
    end

    subgraph CONTROLLERS["🎛️ Controllers"]
        CLINICAL["🏥 Clinical\nAppointment · EMR\nPatient · Diagnosis"]
        ADMIN_C["👤 Admin\nStaff · Profile\nRoles · Users"]
        PHARMACY_C["💊 Pharmacy\nMedicine · Categories\nSales · Inventory"]
        DIAGNOSTICS["🔬 Diagnostics\nPathology · Radiology"]
        RCM_C["💰 RCM\nBilling · Invoices"]
        SETTINGS_C["⚙️ Settings\nCharges · Suppliers"]
    end

    subgraph SERVICES["Service Layer"]
        SVC["PatientService\nAppointmentService\nDiagnosisService\nStaffService\nInventoryService"]
    end

    subgraph REPOS["Repository Layer"]
        REPO["PatientRepo\nAppointmentRepo\nDiagnosisRepo\nStaffRepo\nInventoryRepo"]
    end

    subgraph DB["💾 Storage"]
        SQLITE["SQLite DB\n/database/database.sqlite"]
        UPLOADS_DB["File Uploads\n/public/uploads/patient/"]
    end

    FRONTEND --> LAYOUTS --> CONTROLLERS
    CONTROLLERS --> SERVICES --> REPOS --> SQLITE
    CONTROLLERS --> UPLOADS_DB

    classDef modern fill:#d1fae5,stroke:#10b981,stroke-width:2px
    classDef legacy fill:#fef3c7,stroke:#f59e0b,stroke-width:1px
    class MODERN,TAILWIND,ALPINEJS modern
    class ADMIN legacy
```

---

## Module Breakdown

| Module | Controllers | Models | Key Features |
|--------|-----------|--------|-------------|
| **Clinical** | 5 | 12 | Appointments, EMR Workbench, Prescriptions, Documents, Referrals, Certificates |
| **Administration** | 6 | 7 | Staff, Departments, Roles, User Groups, Profile/Password |
| **Hospital Setup** | 7 | 6 | Charges, TPA, Casualty, Symptoms, Centers, Frequencies |
| **Pharmacy** | 8 | 12 | Medicines, Categories, Generics, Dosages, Sales, Inventory |
| **Diagnostics** | 4 | 8 | Pathology, Radiology Tests & Categories |
| **Billing & RCM** | 2 | 5 | Patient Bills, Revenue Cycle, Invoice Items |
| **Settings** | 4 | 5 | General, Suppliers, Units, Quantities |

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 11, PHP 8.4 (Cloud Run) / 8.5 (Local) |
| **Database** | SQLite (file-based, in-container) |
| **Frontend** | Blade Templates, Tailwind CSS (CDN + Vite), Alpine.js 3.14 |
| **Libraries** | jQuery 3.7, Dropzone 5.9, Toastr, FullCalendar 6.1, Font Awesome 6.4 |
| **Build** | Vite 7.x, Docker 3-stage build |
| **Hosting** | Google Cloud Run (free tier, us-central1) |
| **CI/CD** | Manual: `gcloud run deploy --source .` via Cloud Build |
| **Repository** | [github.com/Oz6ix/HEKA-CMS](https://github.com/Oz6ix/HEKA-CMS) |
| **Live URL** | [heka-cms-1024385989093.us-central1.run.app](https://heka-cms-1024385989093.us-central1.run.app) |
| **Architecture** | MVC + Service + Repository Pattern |

---

## Key Files Reference

| File | Purpose |
|------|---------|
| `Dockerfile` | 3-stage Docker build (Node → Composer → PHP-FPM) |
| `docker/start.sh` | Container startup: migrate, seed, cache, start services |
| `docker/nginx.conf` | Nginx reverse proxy config, port 8080 |
| `docker/php.ini` | PHP production config overrides |
| `resources/views/backend/layouts/modern.blade.php` | Primary layout (Tailwind + Alpine + jQuery + Dropzone + Toastr) |
| `database/seeders/DemoDataSeeder.php` | Idempotent demo data (10+ records per module) |
| `database/seeders/DatabaseSeeder.php` | Admin user + demo data seeder (auto-runs on deploy) |
| `config/filesystems.php` | `uploads` disk → `public/uploads/` |

---

## Recent Changes Log

| Date | Change | Files Modified |
|------|--------|---------------|
| 27 Feb | Fix: blank appointment list (Alpine.js race condition → `Alpine.data()`) | `appointment/index.blade.php`, `modern.blade.php` |
| 26 Feb | Fix: patient photo upload 500 error + display in patient list & EMR | `PatientController.php`, `patient/index.blade.php`, `emr/show.blade.php` |
| 26 Feb | Fix: patient update button (hardcoded `/am/` → `$url_prefix`, JSON response) | `patient/edit.blade.php`, `PatientController.php` |
| 26 Feb | Fix: stray "Modal Title / Close" text + add jQuery/Dropzone/Toastr CDNs | `admin_modal_popup_alert.blade.php`, `modern.blade.php` |
| 26 Feb | Fix: EMR workbench dimmed patient name (inline styles for dark header) | `emr/show.blade.php` |
| 26 Feb | Feat: admin profile & password change page redesign | `profile/edit.blade.php` |
| 25 Feb | Fix: DemoDataSeeder column mismatches, DatabaseSeeder idempotency | `DemoDataSeeder.php`, `DatabaseSeeder.php` |
| 24 Feb | Feat: appointment calendar, patient EMR link, dashboard improvements | Multiple files |
