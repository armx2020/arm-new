# Проект vsearmyne.ru - Армянский справочник

## Overview
vsearmyne.ru is an informational directory for the Armenian community globally, serving as a platform to find and list companies, groups, places, and job opportunities. The project is migrating development to Replit with a robust CI/CD pipeline involving GitHub and Timeweb for automated deployment.

## Recent Changes (October 22-24, 2025)

### **MySQL ↔ PostgreSQL Compatibility (October 24, 2025)**
- ✅ **Database Compatibility Layer Implemented**: Code now supports both MySQL and PostgreSQL
- ✅ **Search Trait Updated** (`app/Models/Traits/Search.php`):
  - MySQL: Uses `MATCH() AGAINST()` fulltext search
  - PostgreSQL: Uses `to_tsvector()` and `to_tsquery()` for fulltext search
  - Automatically switches based on `DB_CONNECTION` environment variable
- ✅ **SQL Compatibility Fixed**:
  - Replaced MySQL-only `FIELD()` function with universal `CASE WHEN` in `DinamicRouteController.php`
  - Works identically in both MySQL and PostgreSQL
- ✅ **GeoHelper Created** (`app/Helpers/GeoHelper.php`):
  - Centralizes geospatial queries (`ST_Distance_Sphere`)
  - Geosearch currently works only with MySQL (requires PostGIS extension for PostgreSQL)
  - Gracefully handles PostgreSQL by returning null when geo features unavailable
  - Updated: `CompanyAction.php`, `Entity.php` (scopeNearby)
- ⏳ **Remaining Action Files** (require same GeoHelper update as CompanyAction.php):
  - `GroupAction.php`, `PlaceAction.php`, `JobAction.php`, `ProjectAction.php`
  - `EntityAction.php`, `CommunityAction.php`, `OfferAction.php`
  - Pattern: Replace `findCityByCoordinates()` and `findRegionByCoordinates()` with `GeoHelper` calls
  
### **Migration Strategy**:
1. **Development**: Replit uses PostgreSQL (Neon) for local development
2. **Staging**: Timeweb staging currently uses MySQL, plan to migrate to PostgreSQL
3. **Production**: Will transition Timeweb staging → production by moving vsearmyane.ru domain
4. **Goal**: Unified PostgreSQL across all environments (dev, staging, production)

## Recent Changes (October 22-24, 2025 continued)
- ✅ **GitHub Repository Created**: Successfully created new repository `armx2020/arm-new` at https://github.com/armx2020/arm-new
- ✅ **Initial Code Push**: Pushed full Laravel codebase (1176 files) to GitHub
- ✅ **GitHub Integration Configured**: Set up official Replit GitHub integration for OAuth-based authentication
  - Git UI now fully functional for commits and pushes
  - Alternative: GitHub Personal Access Token stored in Replit Secrets (`GITHUB_PERSONAL_ACCESS_TOKEN`)
- ✅ **Timeweb Laravel App Staging**: Successfully deployed to https://armx2020-arm-new-d635.twc1.net/
  - Auto-deploy configured: push to GitHub automatically rebuilds application
  - Connected to MySQL staging database `default_db` on 46.229.214.78
  - Images loaded from production server (https://vsearmyane.ru/storage)
  - Environment variables configured: APP_URL, ASSET_URL, FORCE_HTTPS, USE_PRODUCTION_IMAGES
  - **MySQL Firewall Fix**: Added Timeweb server IP (82.97.252.127) to MySQL firewall to allow staging app database access
- ✅ **StorageHelper Fixed**: Modified to load production images in any environment when USE_PRODUCTION_IMAGES=true
- ✅ **MySQL Dev Connection in Replit**: Successfully configured connection to Timeweb MySQL database (armbase-2) from Replit
  - Database user: `gen_user2` with proper credentials
  - Replit IPs (35.185.248.192, 34.82.139.18) whitelisted in Timeweb firewall (note: Replit uses dynamic IPs)
  - Connection working via both .env file and Replit Secrets
  - Application now loads real data from staging database in development environment
- ✅ **Admin UX Improvement**: Added clickable link to entity card from appeal edit form
  - Entity name and ID in appeals now link directly to entity edit page for easier navigation
- ✅ **Production Server Crisis Resolved** (October 24, 2025):
  - Fixed 504 Gateway Timeout on vsearmyane.ru
  - Increased PHP-FPM workers from 5 to 20 (`pm.max_children`)
  - Closed SSH port 22 to public (0.0.0.0/0) - stopped brute-force attacks
  - Recommended Fail2Ban installation for ongoing security
- ✅ **Staging Photo Gallery Fixed** (October 24, 2025):
  - Installed Node.js 20 and npm on Timeweb staging server
  - Compiled Vite assets (Swiper.js for photo galleries)
  - Added `URL::forceScheme('https')` in AppServiceProvider to force HTTPS
  - Fixed Mixed Content errors blocking JavaScript
  - Photo galleries now display correctly with horizontal thumbnails and navigation
- ✅ **Admin Diagnostics Page Added** (October 24, 2025):
  - Created comprehensive diagnostics page at `/admin/diagnostics`
  - Access restricted to super-admin user +79782205008
  - Displays project structure, database status, S3 status, system info, and statistics
  - Shows deployment flow diagram and real-time system metrics
  - Added to admin navigation menu for easy access
- ✅ **S3 Cloud Storage Migration Completed** (October 23-24, 2025):
  - Migrated 4.64 GB (20,781 files, 27,114 total objects) from production server to Timeweb S3
  - S3 Bucket: `46885a37-67c8e067-4002-4498-a06b-cb98be807ea3`
  - S3 Endpoint: `https://s3.timeweb.cloud`
  - Laravel configured with S3 as default filesystem disk (`FILESYSTEM_DISK=s3`)
  - S3 disk configuration uses `'root' => 'storage/app/public'` to match file structure in bucket
  - Base URL set to bucket root: `https://s3.timeweb.cloud/46885a37-67c8e067-4002-4498-a06b-cb98be807ea3`
  - StorageHelper uses `Storage::disk('s3')->url($path)` without adding prefix (handled by root config)
  - Migration performed using S3 Browser (GUI tool) after downloading via FileZilla
  - Credentials stored in Replit Secrets and Timeweb environment: `S3_ACCESS_KEY`, `S3_SECRET_KEY`, `FILESYSTEM_DISK=s3`
  - Created ImageUploadHelper for proper image processing and S3 upload
  - Fixed image URLs in profile and admin views to use storage_url() helper
  - Test page available at `/test-s3-config` for S3 diagnostics
  - Connected Replit dev to MySQL database (IP: 34.83.81.116 added to whitelist)

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
- **Image Handling:** All images are now stored in Timeweb S3 cloud storage (4.64 GB, 20,781 files). The `StorageHelper` automatically generates S3 URLs for all images. Files are stored with `storage/app/public/` prefix in S3, while database paths remain without prefix (e.g., `uploaded/file.jpg`). Future plans include CDN integration for faster delivery.
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
-   **Timeweb S3:** Cloud storage for media assets (4.64 GB, 20,781 image files).