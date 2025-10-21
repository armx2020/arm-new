# Проект vsearmyne.ru - Армянский справочник

## Overview
vsearmyne.ru is an informational directory for the Armenian community globally, serving as a platform to find and list companies, groups, places, and job opportunities. The project is migrating development to Replit with a robust CI/CD pipeline involving GitHub and Timeweb for automated deployment.

## Recent Changes (October 21, 2025)
- ✅ **GitHub Repository Created**: Successfully created new repository `armx2020/arm-new` at https://github.com/armx2020/arm-new
- ✅ **Initial Code Push**: Pushed full Laravel codebase (1176 files) to GitHub
- ✅ **Authentication Setup**: Configured GitHub Personal Access Token in Replit Secrets (`GITHUB_PERSONAL_ACCESS_TOKEN`)
- ✅ **Git Push Helper**: Created `git-push.sh` script for easy push to GitHub using stored token
  - Usage: `./git-push.sh` (pushes to main branch)
  - Usage: `./git-push.sh branch-name` (pushes to specific branch)

## User Preferences
I prefer iterative development and want to be asked before making major architectural changes.

## System Architecture
The project is built on Laravel 10 (PHP 8.2) for the backend, utilizing Blade, Vite, Tailwind CSS, and Alpine.js for the frontend.

**Key Architectural Decisions & Features:**
- **Dynamic Routing:** A `DinamicRouteController` handles dynamic URLs for various entity types (companies, groups, places, etc.), adapting to multiple URL forms via an inflector.
- **Database Management:** The system supports three database environments:
    1.  **PostgreSQL (Development):** Local to Replit for development, with `FULLTEXT` indexes adapted for PostgreSQL.
    2.  **MySQL Dev (armbase-2):** A full-access copy of the production database hosted on Timeweb Cloud (46.229.214.78) for primary development, including SSL.
    3.  **MySQL Production:** A read-only connection to the live production database for viewing current data.
- **Image Handling:** In development, all images are sourced from the production server via a `storage_url()` helper. Future plans include migrating images to Timeweb S3 with CDN integration for unified storage and faster delivery.
- **Automated Deployment:** A CI/CD pipeline is set up: changes pushed from Replit to GitHub trigger a webhook to Timeweb, which executes a `deploy.sh` script to update the production environment. This includes `git pull`, `composer install`, cache clearing, `php artisan migrate`, configuration caching, and permission setting.
- **Security:** The deployment webhook is secured with a shared secret and GitHub's `X-Hub-Signature-256` for request validation.
- **Environment Configuration:** `bootstrap/set-replit-url.php` automatically configures `APP_URL` for Replit. `TrustProxies` middleware is configured for correct operation with Replit's proxy.

**Database Structure:**
-   `users`: User accounts with roles and permissions.
-   `entity_types`: Defines types of entities (companies, groups, places).
-   `entities`: Main table for all entities.
-   `categories`: Nested categories for classification.
-   `offers`: Promotions and special offers.
-   `appeals`: User messages/feedback.
-   `regions`, `cities`: Geographical data.
-   `images`: Stores image metadata for entities.

## External Dependencies
-   **GitHub:** Version control and trigger for automated deployments.
-   **Timeweb Cloud:** Hosting environment for MySQL Dev database and production deployment.
-   **MySQL:** Production and development database instances.
-   **PostgreSQL:** Development database instance.
-   **Vite:** Frontend tooling.
-   **Composer:** PHP dependency management.
-   **NPM:** JavaScript package management.
-   **Tailwind CSS:** Utility-first CSS framework.
-   **Alpine.js:** Lightweight JavaScript framework for reactive interfaces.
-   **Timeweb S3 (Planned):** Cloud storage for media assets.