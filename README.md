# HimRishtey

A Laravel matrimony application serving HimRishtey, Dogri Rishtey, Gallpakki, and Dev Bhoomi Rishtey from one codebase. Hostname-based configuration selects the database connection, branding, support details, and session cookie.

## Requirements and local development

- PHP 8.2 or later and Composer 2. The included DDEV configuration uses PHP 8.2 and MariaDB 11.8.
- Node.js and npm for the locked Vite dependencies.
- An existing application database: the migrations in this repository do **not** create the complete legacy member/membership schema.

For a new local checkout:

```sh
cp .env.example .env
ddev start
ddev composer install
ddev php artisan key:generate
ddev npm ci
```

Configure `.env` for your local databases and services. DDEV database connections normally use hostname `db`; configure each `site1`–`site4` connection as well as the default connection. Restore the approved existing database schema/data before applying outstanding migrations. Do not run `migrate:fresh` against an existing application database.

```sh
ddev php artisan migrate
ddev npm run dev
```

Vite's existing development configuration uses `http://himrishtey.ddev.site` and port 5172 through DDEV. Additional site aliases are listed in `.ddev/config.yaml` and `config/site.php`. For static production assets, run `npm run build`.

## Project structure

```text
app/
  Auth/                 Custom member authentication provider
  Http/Controllers/     Request handling for public and member features
  Http/Middleware/      Host resolution, authentication, and web middleware
  Models/               Eloquent models for the existing database
  Providers/            Framework, authentication, view, and route registration
  Services/             SMS, email, push, and profile-unlock pricing
  Support/              Host-specific site context
bootstrap/              Application bootstrapping and generated caches
config/                 Laravel and multi-site configuration
routes/
  web.php               Loads the three web route groups
  public.php            Landing, content, contact, and policy routes
  auth.php              Registration, login, OTP, recovery, and callbacks
  member.php            Routes protected by auth:member
  api.php               API middleware routes
resources/
  views/pages/          Public pages, including home and child-safety
  views/auth/           Authentication views
  views/blog/           Blog views
  views/dashboard/      Member feature views
  views/layouts/        Shared layouts
  views/partials/       Shared public header and footer
  css/home/             Public CSS modules
  js/home.js            Public JavaScript Vite entry point
  js/home/              Public navigation, icons, and animations
  js/public/            Scripts imported by the public entry point
public/
  index.php             HTTP entry point
  assets/               Directly served legacy CSS, JS, images, and fonts
  build/                Generated Vite output
storage/                Logs, caches, sessions, and application files
  app/project-archive/  Recoverable cleanup archive, excluded from source control
database/               Incremental migrations, factories, seeders, and index SQL
tests/                  PHPUnit unit and feature tests
docs/                   File audit, inventory, and movement manifests
```

`RouteServiceProvider` applies the `web` middleware to `routes/web.php`; included route files inherit it. Keep member authentication in the `auth:member` group in `routes/member.php`.

The application uses Laravel 12 with the existing kernel/provider bootstrap structure. Those classes are actively registered in `bootstrap/app.php`; replacing them with a different skeleton requires a separate migration of their behavior.

## Views and assets

`WelcomeController` resolves public pages with names such as `pages.home` and `pages.child-safety`. Public URLs and route names remain unchanged.

There are two active asset paths:

- `layouts.public` and the standalone `pages.home` load `resources/css/home/home.css` and `resources/js/home.js` through Vite.
- `layouts.app` and `layouts.dashboard` still load files directly from `public/assets`. These layouts have active callers and different styling contracts; do not remove or merge their assets just because similar files exist in `resources`.

Edit the resource files for Vite-rendered pages and the directly served files for legacy pages. The login, signup, and shared script files differ between those paths. New compiled assets belong in `resources` and must be registered in Vite before templates can load them.

Uploaded photos and database-managed images remain in their existing locations. Their names can be stored in the database, so a missing source-code reference is not evidence that an image is unused.

## Validation

```sh
npm run build
ddev exec php artisan test
php artisan route:list
php artisan view:cache
php artisan view:clear
```

Feature tests need a separate `himrishtey_test` database with the legacy schema. `MemberJourneyTest` uses transactions and MySQL-specific session settings; it is not an SQLite-only suite. Match the test host and site database configuration to your test environment.

At the 2026-09-05 structure audit, the DDEV suite passed 15 tests and failed five member-journey cases. The same five cases failed against the pre-change source snapshot. Details are in [the audit](docs/project-audit.md).

## Deployment

Point the web server document root at `public/`, as the DDEV configuration already does. Keep environment files, source files, logs, and the cleanup archive outside that document root. The root `.htaccess` and `server.php` are retained for compatibility with the previous hosting setup; review that deployment before retiring them.

Install Composer dependencies, build Vite assets, configure the site databases and integrations, and apply reviewed outstanding migrations. The repository contains example environment files; use deployment-managed credentials for actual environments.

See [the file audit](docs/project-audit.md), [the file inventory](docs/file-inventory.tsv), [archived files](docs/archived-files.json), and [view moves](docs/moved-files.json) for the cleanup record.

## Member password migration

Member login and password changes verify hashes with Laravel's `Hash::check`.
Registration and all password update handlers store `Hash::make` output.
Plaintext credentials must be migrated before enabling password login with this code.

Preview and migrate each member database using its configured connection name:

```sh
php artisan members:hash-passwords --connection=site1 --dry-run
php artisan members:hash-passwords --connection=site1
```

Repeat for the other configured site connections (`site2`, `site3`, `site4`) as applicable.
In DDEV, prefix these commands with `ddev exec`. Without `--connection`, the command
uses the default database connection; it does not automatically process all sites.
Use a database backup before migration and ensure the legacy password column can
store the configured hash (60 characters for bcrypt; 255 accommodates Argon hashes).
The command preserves existing bcrypt/Argon hashes and null/empty values, can be
rerun, and skips updates when the stored password changed after reading a batch.
It does not recover plaintext or rehash existing hashes to a different algorithm.
