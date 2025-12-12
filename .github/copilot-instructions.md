<!-- Copied-style, concise repository-specific instructions for AI coding agents -->
# Copilot instructions for smart-edu

This project is a Laravel 12 application (PHP ^8.2) implementing a Smart Education system. The notes below are focused, actionable guidance to make an AI coding agent immediately productive in this repository.

Core facts
- **Framework / language**: Laravel 12, PHP ^8.2.
- **DB**: MySQL (migrations in `database/migrations`; a generator script exists at `setup_migrations.sh`).
- **Front-end**: Vite + `resources/js` + `resources/css` (see `vite.config.js`, `package.json`).
- **Key composer scripts**: `composer setup` (install, env, migrate, npm build), `composer dev` (starts server, queue worker, logs, vite), `composer test` (runs `artisan test`). Use these instead of guessing long multi-step commands.

Important repository layout & conventions
- `app/Http/Controllers/` is organized by role; e.g. teacher controllers live under `app/Http/Controllers/Teacher`. Follow that convention when adding controllers.
- Route files are split by role in `routes/` (`admin.php`, `teacher.php`, `student.php`, `counselor.php`, etc.). Add new endpoints to the appropriate role file rather than `web.php` by default.
- Eloquent models live in `app/Models` and commonly include a `uuid` column. Migrations and model code use `uuid` alongside integer `id` — preserve that pattern when adding new models or relations.
- The project uses `foreignId(...)->constrained()->cascadeOnDelete()` and similar fluent constraints in migrations; follow that pattern for relational integrity.
- Global helpers are in `app/helpers.php` and are autoloaded by Composer. Use or update them for small, cross-cutting utilities.

Patterns & examples to mimic
- Controllers: keep logic thin; business logic is typically in `app/Services` or models. Example path: `app/Http/Controllers/Teacher/TeacherExamController.php`.
- Routes: mount controllers from role-specific route files. Example: add teacher routes to `routes/teacher.php`.
- Migrations: many are generated via `setup_migrations.sh`. New migrations should use `Schema::create` with `$table->uuid('uuid')->unique();` when appropriate.
- Permissions: the project uses `spatie/laravel-permission` (see `composer.json`). Use roles/permissions APIs rather than inventing custom role checks.

Dev & run commands (copyable)
- Install & initial setup: `composer run setup`
- Run full dev environment (server, queue, logs, vite): `composer run dev`
- Run tests: `composer run test` or `php artisan test`
- Run migrations only: `php artisan migrate`

Testing & quality
- Unit/feature tests live under `tests/`. The repo uses `phpunit`/`artisan test` (PHPUnit 11). Run `composer run test`.
- Formatting / linting: `laravel/pint` is present; use `./vendor/bin/pint` or `composer exec -- pint` if needed.

Integration points & notable libraries
- `spatie/laravel-permission` — role & permission management. Check for migrations/seeders that seed roles.
- `morilog/jalali` and `hekmatinasser/verta` — Persian date handling; you'll see `*_fa` and `title_fa` fields in models and migrations.
- `kavenegar/laravel` — SMS provider integration (check `config/` for API keys).

When changing code
- Prefer small, focused changes. Keep controller methods concise; put domain logic into services or models.
- When adding routes, add them to the role-appropriate `routes/*.php` file and register middleware there if needed.
- When introducing DB changes, add migrations and, if necessary, update `setup_migrations.sh` (it is used to regenerate the `database/migrations` set).

What not to change without asking
- Global app structure (role-based controllers and route split) — this separation is intentional for authorization and UX flows.
- The `setup_migrations.sh` script is the canonical generator for migration files; if you must restructure migrations, coordinate first.

References (important files)
- `composer.json` — dependency list and scripts
- `setup_migrations.sh` — mass-migration generator used in this repo
- `routes/` — role-separated routing
- `app/Http/Controllers/` — role-specific controllers (e.g. `Teacher/`)
- `app/Models` — Eloquent models; many include `uuid` columns and Persian-named fields
- `app/helpers.php` — global helpers autoloaded by Composer
- `phpunit.xml` — test runner config

If something is ambiguous, ask: give the target file(s), brief desired behavior, and whether database changes are allowed in the same PR.

-- End of instructions --
