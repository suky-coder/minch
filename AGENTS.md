# AGENTS.md — Minch

A Laravel Livewire financial management app (Bolivian accounting context: retentions, bank books, account statements).

## Quick start

```bash
composer setup     # full first-time: install, .env, key:generate, migrate, npm i, npm build
composer dev       # serve + queue:listen --tries=1 + vite concurrently
```

## Commands

| Command | What it runs |
|---|---|
| `composer lint` | `pint --parallel` |
| `composer lint:check` | `pint --parallel --test` |
| `composer test` | `config:clear → lint:check → php artisan test` |
| `composer ci:check` | `@test` (no config:clear) |
| `./vendor/bin/pest` | Direct Pest runner (CI uses this) |
| `npm run build` / `npm run dev` | `vite build` / `vite` |

Run a single test: `./vendor/bin/pest tests/Feature/ExampleTest.php`

## Architecture

- **Stack**: Laravel 13, Livewire 4.1, TallStackUI 3.1, Fortify (auth), Spatie Permission (RBAC), Tailwind CSS v4, SQLite.
- **Routes**: `routes/web.php` (main UI with `permission` middleware guards), `routes/settings.php` (profile, appearance, security — required from web.php), `routes/api.php` (Sanctum — supplier search), `routes/console.php`.
- **Frontend**: Livewire components in `app/Livewire/` (23+ components, organized by feature in subdirectories), Blade views in `resources/views/`.
- **Services**: `app/Services/AccountStatementService.php`, `CashBalanceService.php`, `MovementBalanceService.php`, `PersonSupplierService.php`.
- **PDF**: `mpdf/mpdf` (dev-master) via `app/Http/Controllers/PdfController.php`.
- **Exports**: `maatwebsite/excel` via `app/Http/Controllers/ExcelController.php`.

## Key conventions

- Auth middleware aliases: `role`, `permission`, `role_or_permission` registered in `bootstrap/app.php`.
- Default DB in `.env.example` is SQLite, but **production/development uses MySQL**. Session, cache, and queue use `database` driver.
- `.npmrc` has `ignore-scripts=true` — lifecycle hooks won't run on `npm install`.
- `NumberHelper.php` is loaded via `require_once` in `AppServiceProvider::boot()`. It is PSR-4 namespaced (`App\Helpers`) but eagerly loaded for convenience.
- `RefreshDatabase` is **commented out** in `tests/Pest.php` — tests may need manual DB state handling.
- App locale defaults to `es` (`config/app.php:81`). `Carbon::setLocale('es')` is called in PdfController, Transaction model, ViewComponent, and Retention model.
- `AppServiceProvider::boot()` heavily customizes TallStackUi defaults (table, modal, button, input, select, date picker, sidebar).

## Testing

- Pest PHP (config in `phpunit.xml`): SQLite `:memory:`, array cache, sync queue.
- CI runs on PHP 8.3 / 8.4 / 8.5 matrix.
- Test command order: `config:clear → lint:check → test`.

## Database

Migrations in `database/migrations/`. Seed in order: `TaxeSeeder`, `PermissionSeeder`, then Admin role with all permissions assigned to user "Hamura". Run with `php artisan db:seed`.

## Model notes

- `Transaction` and `Box` auto-number via `lockForUpdate()` in a `creating` boot event — concurrent-safe sequential numbering. `HasConsecutiveNumber` trait in `app/Helpers/` provides shared logic. Transaction numbering is per type per fiscal year (Oct–Sep); Box numbering is per type per calendar month.
- `Person` is a shared parent for `Supplier` and `Customer` (one-to-one). `Movement` links to both `Transaction` and `Box` (hasOne each).
- `Retention` has `Taxe` (via `taxes()`) and `Discount` (via `discounts()`) children. `Taxe` has a `type` field with labels: `S` (Servicios), `G` (Bienes), `A` (Todos).
- `Account` has a `HasManyThrough` relationship to `Movement` via `Transaction`.
- Route parameter validation on account statement routes uses `AccountStatementService::TYPES`.
