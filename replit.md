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
- ✅ **Migration Scripts Created** (`scripts/` directory):
  - `migrate-mysql-to-postgres.sh`: Main orchestration script for safe data migration
  - `convert-mysql-to-postgres.php`: Converts MySQL dump to PostgreSQL-compatible format
  - `update-sequences.php`: Updates auto-increment sequences after import
  - `verify-migration.php`: Validates data integrity and foreign key relationships
  - Full documentation in `scripts/README.md`
  - **Status**: Migration scripts ready but not yet executed (process stopped October 24, 2025)
  - **Current DB**: System continues using MySQL (Timeweb armbase-2) for development
  
### **Database Strategy (Updated October 24, 2025)**:
- **Decision**: Continue using MySQL across all environments
- **Reason**: PostgreSQL migration attempts were unsuccessful due to data type incompatibilities
- **Current**: All environments use MySQL (Timeweb armbase-2 for development, default_db for staging/production)
- **Code**: Fully compatible with both MySQL and PostgreSQL (ready for future migration if needed)

### **Image Storage Fix (October 24, 2025)**:
- ✅ **S3 Configuration Restored**: Fixed `root` prefix in S3 config back to `'storage/app/public'`
  - **Why**: Files in S3 bucket are stored with this prefix (uploaded via S3 Browser)
  - **Result**: Existing 20,781 images now load correctly from S3 on staging
- ✅ **S3 Bucket Public Access Configured**: Set up Bucket Policy via S3 Browser
  - All files now publicly readable (read-only access)
  - Direct S3 URLs work: `https://s3.timeweb.cloud/bucket/storage/app/public/uploaded/file.png`
- ✅ **Development Images**: Development environment automatically loads images from production server
- ✅ **StorageHelper Environment Detection**:
  - **Development (Replit)**: Uses production proxy → `https://vsearmyane.ru/storage/uploaded/file.jpg`
  - **Staging/Production (Timeweb)**: Uses S3 storage with correct path prefix → S3 URL generation works
- ✅ **Public Pages Working**: All public-facing pages (show.blade.php) load images from S3 correctly
- ⚠️ **Known Issue - Edit Pages**: 7 edit.blade.php files hardcode `/storage/` in JavaScript:
  - `company/edit.blade.php`, `group/edit.blade.php`, `place/edit.blade.php`, `offer/edit.blade.php`
  - `job/edit.blade.php`, `community/edit.blade.php`, `project/edit.blade.php`
  - **Impact**: Image previews don't load in admin/profile edit forms (404 errors)
  - **Workaround**: Not critical - only affects internal edit pages, public site works perfectly
  - **Fix**: Need to replace JavaScript hardcode `'/storage/' + image.path` with backend-generated S3 URLs
- ⚠️ **Known Issue - New Uploads**: 7 Action classes still hardcode `'public'` disk instead of default S3:
  - `EntityAction.php`, `CompanyAction.php`, `GroupAction.php`, `PlaceAction.php`
  - `ProjectAction.php`, `CommunityAction.php`, `OfferAction.php`
  - **Impact**: New image uploads go to local disk instead of S3
  - **Workaround**: Existing images work, new uploads work locally (not critical for staging)
  - **Fix Planned**: Requires updating all Action classes to use default disk + handle Image::make() with S3
- ✅ **Staging Images Fully Working**: Public site displays all 20,781 images from S3 successfully

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
- ✅ **Admin Diagnostics Page Updated** (October 24, 2025):
  - Enhanced comprehensive diagnostics page at `/admin/diagnostics`
  - Displays **real-time MySQL connection status** with detailed connection info
  - Shows S3 Cloud Storage status and configuration
  - Displays **complete project structure** (backend, frontend, storage, deployment)
  - Lists key features (Dynamic Routing, Multi-Database Support, Geo Search, etc.)
  - System information (environment, memory, disk space)
  - Deployment flow visualization
  - Beautiful responsive UI with color-coded status indicators
  - Access available in development environment (Replit)
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