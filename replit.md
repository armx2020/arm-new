# Проект vsearmyne.ru - Армянский справочник

## Overview
vsearmyne.ru is an informational directory for the Armenian community globally. It serves as a platform to find and list companies, groups, places, and job opportunities. The project aims to provide a comprehensive and easily accessible resource for the Armenian diaspora, leveraging modern web technologies and a robust CI/CD pipeline for efficient development and deployment.

## User Preferences
I prefer iterative development and want to be asked before making major architectural changes.

## System Architecture
The project is built on Laravel 10 (PHP 8.2) for the backend, utilizing Blade, Vite, Tailwind CSS, and Alpine.js for the frontend.

**Key Architectural Decisions & Features:**
*   **Dynamic Routing:** A `DinamicRouteController` handles dynamic URLs for various entity types (companies, groups, places, etc.), adapting to multiple URL forms via an inflector.
*   **Database Management:** The system primarily uses MySQL across all environments due to past migration challenges. The codebase, however, maintains compatibility with both MySQL (using `MATCH() AGAINST()` for fulltext search) and PostgreSQL (using `to_tsvector()` and `to_tsquery()`). Geo-search functionalities currently rely on MySQL's `ST_Distance_Sphere`.
    *   **Development:** Connects to a Timeweb MySQL database (armbase-2) for real-time data.
    *   **Staging/Production:** Uses MySQL database instances.
*   **Image Handling:** All images are stored in Timeweb S3 cloud storage. A `StorageHelper` automatically generates S3 URLs. New image uploads are processed (resized to 400px width) and directly stored in S3. Image URLs in views and JavaScript are managed by the `StorageHelper` to ensure correct loading across environments.
    *   **S3 Integration Complete (October 24, 2025):** All view files updated to use StorageHelper for S3 URLs
        - Fixed 17 view files: admin edit pages (edit-entity, edit-category, edit-offer), profile edit pages, entity registries (entity-table), user pages, offer/place show pages
        - Fixed JavaScript hardcoded paths in edit-entity.blade.php to use backend-generated S3 URLs
        - All image previews now load from S3 cloud storage (14,309+ images)
*   **Automated Deployment:** A CI/CD pipeline is established where pushes to GitHub trigger an automated deployment to Timeweb. This process includes `git pull`, Composer installation, cache clearing, database migrations, and permission adjustments.
*   **Security:** The deployment webhook is secured with a shared secret and GitHub's `X-Hub-Signature-256` for request validation. SSH access to the production server has been restricted to enhance security.
*   **Environment Configuration:** `bootstrap/set-replit-url.php` dynamically configures `APP_URL` for the Replit development environment. `TrustProxies` middleware is configured for seamless operation with Replit's proxy.
*   **Admin Diagnostics:** A comprehensive diagnostics page (`/admin/diagnostics`) provides real-time status for MySQL connections, S3 configuration, project structure, and key system information, including deployment flow visualization.

**Database Structure:**
The database schema includes tables for `users`, `entity_types`, `entities`, `categories`, `offers`, `appeals`, `regions`, `cities`, and `images`, designed to support the directory's diverse data requirements.

## External Dependencies
*   **GitHub:** Version control and CI/CD trigger.
*   **Timeweb Cloud:** Hosting for MySQL databases and production deployment.
*   **MySQL:** Primary database solution.
*   **PostgreSQL:** Codebase maintains compatibility for potential future migration.
*   **Vite:** Frontend tooling.
*   **Composer:** PHP dependency management.
*   **NPM:** JavaScript package management.
*   **Tailwind CSS:** Utility-first CSS framework.
*   **Alpine.js:** Lightweight JavaScript framework.
*   **Timeweb S3:** Cloud storage for all media assets.