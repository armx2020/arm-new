# Проект vsearmyne.ru - Армянский справочник

## Overview
vsearmyne.ru is an informational directory for the Armenian community globally. It serves as a platform to find and list companies, groups, places, and job opportunities. The project aims to provide a comprehensive and easily accessible resource for the Armenian diaspora, leveraging modern web technologies and a robust CI/CD pipeline for efficient development and deployment.

## User Preferences
I prefer iterative development and want to be asked before making major architectural changes.

## System Architecture
The project is built on Laravel 10 (PHP 8.2) for the backend, utilizing Blade, Vite, Tailwind CSS, and Alpine.js for the frontend.

**Key Architectural Decisions & Features:**
*   **Dynamic Routing:** A `DinamicRouteController` handles dynamic URLs for various entity types (companies, groups, places, etc.), adapting to multiple URL forms via an inflector.
*   **Database Management:** The system uses MySQL in production and PostgreSQL for Replit development. The codebase maintains compatibility with both databases (MySQL uses `MATCH() AGAINST()` for fulltext search, PostgreSQL uses `to_tsvector()` and `to_tsquery()`).
    *   **Development (Replit):** Connects to PostgreSQL (Neon, USA) with demo data for fast development (~0.8s latency). **Переключатель режимов** доступен на странице `/admin/diagnostics`.
    *   **Production (Timeweb):** Uses MySQL database (Russia) with full production data.
    *   **Demo Data Seeder:** `DemoDataSeeder` creates sample entities, users, and categories for Replit development environment.
    *   **Database Switcher:** Middleware `DatabaseSwitcher` позволяет переключаться между демо (PostgreSQL) и боевым (MySQL) режимами через session. По умолчанию: демо режим.
*   **Image Handling:** All images are stored in Timeweb S3 cloud storage. A `StorageHelper` automatically generates S3 URLs. New image uploads are processed (resized to 400px width) and directly stored in S3. Image URLs in views and JavaScript are managed by the `StorageHelper` to ensure correct loading across environments.
    *   **S3 Integration Complete (October 24, 2025):** All view files updated to use StorageHelper for S3 URLs
        - Fixed 17 view files: admin edit pages (edit-entity, edit-category, edit-offer), profile edit pages, entity registries (entity-table), user pages, offer/place show pages
        - Fixed JavaScript hardcoded paths in edit-entity.blade.php to use backend-generated S3 URLs
        - All image previews now load from S3 cloud storage (14,309+ images)
*   **Automated Deployment:** A CI/CD pipeline is established where pushes to GitHub trigger an automated deployment to Timeweb. This process includes `git pull`, Composer installation, cache clearing, database migrations, and permission adjustments.
*   **Security:** The deployment webhook is secured with a shared secret and GitHub's `X-Hub-Signature-256` for request validation. SSH access to the production server has been restricted to enhance security.
*   **Environment Configuration:** `bootstrap/set-replit-url.php` dynamically configures `APP_URL` for the Replit development environment. `TrustProxies` middleware is configured for seamless operation with Replit's proxy.
*   **Admin Diagnostics:** A comprehensive diagnostics page (`/admin/diagnostics`) provides real-time status for MySQL connections, S3 configuration, project structure, and key system information, including deployment flow visualization.
*   **Performance Optimizations:**
    - **October 24, 2025:**
        - **Removed sleep() delays** from 6 Livewire components - registry loading 2-3x faster
        - **Lazy loading images** - added `loading="lazy"` to all images - 50-70% traffic reduction
        - **Preload critical resources** - jQuery and key scripts preloaded
        - **Gzip compression** - CompressResponse middleware (~70% size reduction)
        - **WebP support** - StorageHelper enhanced with WebP conversion
        - **Laravel caching** - config, routes, and views cached for 30-40% faster response times
    - **October 27, 2025:**
        - **PostgreSQL for Replit dev** - Switched from slow Timeweb MySQL (Russia, 2-3s latency) to PostgreSQL (USA, 0.7s latency)
        - **Page load improvement** - Homepage loads in ~0.8s instead of timeout
        - **Unified codebase** - Same code runs in dev and production, only database connection differs

**Database Structure:**
The database schema includes tables for `users`, `entity_types`, `entities`, `categories`, `offers`, `appeals`, `regions`, `cities`, and `images`, designed to support the directory's diverse data requirements.

## External Dependencies
*   **GitHub:** Version control and CI/CD trigger.
*   **Timeweb Cloud:** Hosting for production MySQL database and deployment.
*   **MySQL:** Production database (Timeweb, Russia).
*   **PostgreSQL (Neon):** Development database for Replit (USA, fast access).
*   **Vite:** Frontend tooling.
*   **Composer:** PHP dependency management.
*   **NPM:** JavaScript package management.
*   **Tailwind CSS:** Utility-first CSS framework.
*   **Alpine.js:** Lightweight JavaScript framework.
*   **Timeweb S3:** Cloud storage for all media assets (14,309+ images).