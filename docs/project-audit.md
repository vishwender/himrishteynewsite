# Project file and structure audit

Audit date: 2026-09-05.

Update (2026-09-14): the 33 retained candidates below and `public/assets/js/signup.js` were deleted with user approval. See [cleanup record](unused-files-review.md) for the exact list and validation. This document and `file-inventory.tsv` otherwise preserve the historical audit snapshot.

## Scope and confidence

Inventoried the project files and inspected first-party source references, route registration, Blade inheritance/includes, Vite entry points and imports, configuration, model/class references, duplicate file contents, and tests. Dependency internals (`vendor` and `node_modules`), generated caches, logs, and binary/user uploads are classified separately; this is not a line-by-line dependency or security audit. Environment secret values are not included in this report.

Static absence of references cannot establish whether a database record, external consumer, or previous deployment uses a file. The candidate files below remain in place. Confirmed backups, unimported identical resource copies, Finder metadata, and empty stylesheets were archived rather than permanently deleted.

## Structural changes

- Split `routes/web.php` into `routes/public.php`, `routes/auth.php`, and `routes/member.php`, loaded through the original web middleware registration. All 97 route definitions retain their URLs, methods, names, actions, and middleware. Closure source paths changed as expected.
- Moved 11 public page templates into `resources/views/pages` and updated `WelcomeController`. Renamed `child_safety.blade.php` to `pages/child-safety.blade.php`. See `moved-files.json` for every mapping.
- Archived 41 unused/obsolete files under `storage/app/project-archive/2026-09-05`, preserving relative paths and SHA-256 digests in `archived-files.json`.
- Moved the root `error_log` to `storage/logs/legacy-root-error.log`.
- Updated `.gitignore` for Finder files, PHPUnit caches, the root error log, the archive, and asset ZIP archives.
- Replaced the Laravel boilerplate README with application-specific structure, setup, asset, deployment, and validation documentation.

## Files no longer required by the current application

These files have been moved to the recovery archive. The table shows their original paths.

| Original file | Reason |
| --- | --- |
| `resources/js/public/search-home-member.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/delete-profile.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/profile-detail.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/edit-profile.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/search-by-id.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/login-old-delete.js` | Unimported obsolete script; its identical public copy is also archived. |
| `resources/js/public/profile-photo.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/quick-search.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/interests.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/verify-account.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/edit-profile-old-delete.js` | Unimported obsolete script; its identical public copy is also archived. |
| `resources/js/public/wallet.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/change-password.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/memberships.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/search-results.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/success-stories.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/js/public/advanced-search.js` | Unimported resource copy; identical served copy remains in public/assets/js. |
| `resources/views/master-backup/layout.blade.php` | Unreferenced backup/obsolete file; retained here for recovery. |
| `resources/views/master-backup/dashboard-layout.blade.php` | Unreferenced backup/obsolete file; retained here for recovery. |
| `resources/views/layouts/profile.blade.php` | Unreferenced backup/obsolete file; retained here for recovery. |
| `resources/views/dashboard/view-my-profile.blade.php` | Unreferenced backup/obsolete file; retained here for recovery. |
| `resources/views/dashboard/home/partials/profile-card.blade.php` | Unreferenced backup/obsolete file; retained here for recovery. |
| `public/assets/js/login-old-delete.js` | Unreferenced backup/obsolete file; retained here for recovery. |
| `public/assets/js/edit-profile-old-delete.js` | Unreferenced backup/obsolete file; retained here for recovery. |
| `public/assets/assets.zip` | Unreferenced backup/obsolete file; retained here for recovery. |
| `.DS_Store` | macOS Finder metadata; not application source. |
| `resources/.DS_Store` | macOS Finder metadata; not application source. |
| `resources/images/.DS_Store` | macOS Finder metadata; not application source. |
| `resources/views/.DS_Store` | macOS Finder metadata; not application source. |
| `resources/views/dashboard/.DS_Store` | macOS Finder metadata; not application source. |
| `resources/views/dashboard/home/.DS_Store` | macOS Finder metadata; not application source. |
| `public/.DS_Store` | macOS Finder metadata; not application source. |
| `public/assets/.DS_Store` | macOS Finder metadata; not application source. |
| `public/assets/images/.DS_Store` | macOS Finder metadata; not application source. |
| `resources/views/welcome.blade.php` | Unused former landing page; the welcome route renders pages.home. |
| `resources/css/app.css` | Empty stylesheet, absent from the Vite entry points and CSS imports. |
| `resources/css/home/sections.css` | Empty stylesheet, absent from the Vite entry points and CSS imports. |
| `resources/css/themes/gallpakki.css` | Empty stylesheet, absent from the Vite entry points and CSS imports. |
| `resources/css/themes/dogririshtey.css` | Empty stylesheet, absent from the Vite entry points and CSS imports. |
| `resources/css/themes/devbhoomi.css` | Empty stylesheet, absent from the Vite entry points and CSS imports. |
| `resources/css/themes/himrishtey.css` | Empty stylesheet, absent from the Vite entry points and CSS imports. |

## Additional removal candidates — retained

No active references were found for the following paths, or their only references are within inactive scaffolding. They are listed individually for review; they were not removed.

### Unused controller paths (no registered routes)

- `app/Http/Controllers/SmsController.php`
- `app/Http/Controllers/Auth/ConfirmPasswordController.php`
- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Http/Controllers/Auth/VerificationController.php`
- `app/Http/Controllers/MemberAuth/ForgotPasswordController.php`
- `app/Http/Controllers/MemberAuth/ResetPasswordController.php`

### Unreferenced model classes

- `app/Models/Agent.php`
- `app/Models/AgentProfile.php`
- `app/Models/Citie.php`
- `app/Models/EmployeeActivityLog.php`
- `app/Models/MemberLog.php`
- `app/Models/MemberProfileRange.php`
- `app/Models/ProfileRange.php`
- `app/Models/StaffviewedContact.php`
- `app/Models/UserActivity.php`
- `app/Models/UserActivityLog.php`
- `app/Models/UserRating.php`

### Inactive mail and password-reset scaffolding

- `app/Mail/InterestMail.php`
- `resources/views/auth/passwords/email.blade.php`
- `resources/views/auth/passwords/reset.blade.php`

### Unreferenced directly served CSS

- `public/assets/css/about-us.css`
- `public/assets/css/about-theme.css`
- `public/assets/css/refund-policy.css`
- `public/assets/css/view-my-profile.css`
- `public/assets/css/privacy-policy.css`
- `public/assets/css/legal-pages.css`
- `public/assets/css/terms-and-conditions.css`

### Unreferenced directly served JavaScript (resource versions remain active)

- `public/assets/js/landing.js`
- `public/assets/js/privacy-policies.js`

### Unused alternative frontend entry points

- `resources/js/app.js`
- `resources/js/bootstrap.js`
- `resources/sass/app.scss`
- `resources/sass/_variables.scss`

Notes on candidates:

- `Citie` duplicates the convention represented by the active `City` model, but neither database tables nor columns were renamed.
- `auth/passwords/reset.blade.php` extends the missing `master.layout`; its member password-reset controller has no registered route. The active password recovery uses `Auth/ForgotPasswordController` and `auth/forgot-password.blade.php`.
- `InterestMail` references the absent `emails.interestmail` view. No caller of that mail class was found. Do not activate it without adding its template.
- The alternative JS/Sass entry points are not registered in `vite.config.js`; their mutual imports do not make them reachable from the application.
- Unreferenced model classes may represent features owned by another application using the same legacy schema. Their tables and records are not removal candidates.

## Files and directories that must remain

- `vendor/` and `node_modules/` are installed dependencies, not unused source. Keep the lockfiles; dependency folders can be regenerated through their package managers.
- `bootstrap/app.php`, the HTTP/console kernels, exception handler, providers, and middleware are active bootstrap code. Their older layout is not evidence they are obsolete.
- `layouts.app`, `layouts.public`, and `layouts.dashboard` all have active callers. Their CSS/JS loading differs.
- `public/assets` contains live directly served assets. Vite only builds the public entry points, not the entire dashboard.
- `.env`, the example environment files, `.ddev`, and framework configuration are environment/setup inputs. Secrets were not edited.
- Images, fonts, uploads, `public/sw.js`, migration files, and `database/performance/indexes.sql` were retained. Runtime/database references may not appear as literal source paths.
- `.htaccess` at the project root and `server.php` are legacy hosting compatibility files. DDEV already uses `public/` as its document root; confirm production hosting before removing them.
- `storage` and `bootstrap/cache` contain writable runtime files. Generated contents can be cleared through Laravel commands; the directories and their ignore files are required.

## Structural limits still present

- The migration history does not create the full members, wallet, membership, and lookup schema. A clean database cannot be reconstructed from these migrations alone; obtain the approved schema before attempting a fresh deployment.
- Large controllers still contain substantial business logic and some commented-out legacy implementations. Extracting those workflows into services/Form Requests needs behavior-focused tests, separate from this file-organization change.
- Asset delivery still has two active paths. Converting all dashboard and older public layouts to Vite requires checking globals, load order, and page styles. Similar names are not interchangeable: resource and served login/signup/script files differ.

## Verification

- Route comparison: 97 definitions unchanged, ignoring closure source file/line metadata.
- Blade template compilation: passed.
- Vite production build: passed before and after cleanup with identical output filenames (`home-44b85a72.css`, `home-c795f398.js`). Existing warnings concern runtime image URLs and the large JS chunk. The two referenced placeholder image files exist in `public/images/profile_photos`.
- PHP syntax checks: passed for all modified/new PHP files.
- Content integrity: all 11 moved views are byte-identical to their originals; all 41 archived files match their recorded SHA-256 digests. Compiled views were cleared after validation.
- DDEV PHPUnit suite: 15 passed, five failed. A source snapshot from before the cleanup fails the same five test cases. The baseline gallery failure manifests differently in the nested snapshot, so this establishes the failing case rather than an identical filesystem symptom.
- Host PHP test execution cannot resolve the DDEV-only `db` hostname; use DDEV for database-backed tests.

The five failing member journey cases are:

1. `test_landing_dashboard_cta_uses_member_authentication_state`: expected old `btn-cta-secondary` markup is absent.
2. `test_member_can_log_in_and_access_authenticated_search`: login returns 401.
3. `test_member_can_request_a_persistent_login`: login returns 401.
4. `test_member_can_add_and_remove_gallery_photos`: uploaded gallery file assertion fails in the working tree; the baseline fails to retrieve the expected photo record.
5. `test_member_can_unlock_a_visible_contact_with_wallet_balance`: unlock request returns 404.

## Recovery

`archived-files.json` maps every original path to its archive location and original SHA-256 digest. Copy an individual archived file back to its original path if it is needed. Do not copy the whole archive into `public/`. The temporary baseline test snapshot is also in the ignored archive directory; it is not part of application routing or the 41-file cleanup manifest.

The pre-change routes, resources, WelcomeController, README, and ignore file were additionally backed up at `/private/tmp/himrishtey-structure-backup-20260905`. That temporary backup is local to this environment, not a replacement for version control.

## Inventory totals

The [file inventory](file-inventory.tsv) lists 401 individual project files and 6 grouped dependency/runtime paths (excluding the inventory itself). The archive list separately records the 41 removed-from-source paths.

| Classification | File count |
| --- | ---: |
| application or project source | 275 |
| audit documentation | 3 |
| development environment | 51 |
| image or upload | 35 |
| local environment | 1 |
| retained removal candidate | 33 |
| static media | 3 |

| Grouped directory | File count |
| --- | ---: |
| `.phpunit.cache/` | 1 |
| `bootstrap/cache/` | 3 |
| `node_modules/` | 5452 |
| `public/build/` | 3 |
| `storage/` | 321 |
| `vendor/` | 9798 |
