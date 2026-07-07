# AGENTS.md — Minch / SIC-MINCH

## Dual context

This repository contains **both** the source code of SIC-MINCH (a Laravel Livewire financial management app for Bolivian accounting) **and** the academic documentation produced across 10 project activities.

- **Codebase**: Laravel 13 + Livewire 4.1 app for retentions, bank books, cash, account statements.
- **Academic docs**: 8 LaTeX files in `docs/` covering the full software engineering lifecycle.

---

## Quick start

```bash
composer setup     # full first-time: install, .env, key:generate, migrate, npm i, npm build
composer dev       # serve + queue:listen --tries=1 + vite concurrently
composer test      # config:clear → lint:check → php artisan test
```

## Commands

| Command | What it runs |
|---|---|
| `composer setup` | install → .env → key:generate → migrate → npm i → build |
| `composer dev` | `php artisan serve` + `queue:listen --tries=1` + `vite` (concurrently) |
| `composer lint` | `pint --parallel` |
| `composer lint:check` | `pint --parallel --test` |
| `composer test` | `config:clear` → `@lint:check` → `php artisan test` |
| `php artisan test --coverage-html=public/coverage` | Code coverage report (requires pcov or xdebug) |
| `./vendor/bin/pest tests/Feature/ExampleTest.php` | Run a single test |

## Stack

**Laravel 13**, **Livewire 4.1**, **TallStackUI 3.1**, **Fortify** (auth), **Spatie Permission** (RBAC), **Tailwind CSS v4**, **mPDF** (PDF), **Maatwebsite/Excel** (exports).

- Auth middleware aliases in `bootstrap/app.php`: `role`, `permission`, `role_or_permission`.
- Routes: `routes/web.php` (main UI with `permission` middleware guards), `routes/settings.php` (required from web.php), `routes/api.php` (Sanctum — supplier search), `routes/console.php`.
- 28+ Livewire components in `app/Livewire/` organized by feature subdirectory; views in `resources/views/livewire/`.
- Key services: `AccountStatementService`, `CashBalanceService`, `MovementBalanceService`, `PersonSupplierService`.

## Config & env quirks

- `.env.example` defaults to **SQLite**; actual `.env` uses **MySQL** (`DB_CONNECTION=mysql`). Check which DB is active.
- `.env.example` has `SESSION_DRIVER=database`, actual `.env` may use `file`. Queue and cache use `database` driver.
- `.npmrc` has `ignore-scripts=true` — lifecycle hooks won't run on `npm install`.
- App locale defaults to **`es`** (`config/app.php:81`). Validation strings in `lang/es/`.
- `Carbon::setLocale('es')` is called in several models/controllers for Spanish date formatting.
- **PHP needs `pdo_sqlite` extension enabled** for tests to run (SQLite :memory: in phpunit.xml).

## Testing

- **Pest PHP**: SQLite `:memory:`, array cache, sync queue (see `phpunit.xml`).
- `RefreshDatabase` is enabled in `tests/Pest.php` for Feature tests.
- Tests that use Eloquent models **must** be in `tests/Feature/` — Unit tests don't bootstrap Laravel.
- Auth tests (Registration, Profile, Security) have pre-existing failures unrelated to business logic.
- Factory files for all business models are in `database/factories/`. They use `HasFactory` trait added to all models.
- **Coverage**: Run `php artisan test --coverage-html=public/coverage` (requires pcov or xdebug).
- Livewire tests that need permissions: use `$this->seed(\Database\Seeders\PermissionSeeder::class)` + `$user->givePermissionTo(Permission::all())`.

### Test structure

| Directory | Contents |
|---|---|
| `tests/Feature/Models/` | Unit tests for all 12 Eloquent models (accounts, boxes, movements, transactions, retentions, taxes, etc.) |
| `tests/Feature/Services/` | Tests for 4 services (AccountStatement, CashBalance, MovementBalance, PersonSupplier) |
| `tests/Feature/Http/` | Livewire feature tests (dashboard, taxes, accounts, suppliers, customers, retentions, account statements) |

## Database

Migrations in `database/migrations/`. Seed order (`DatabaseSeeder`):
1. `TaxeSeeder` — RC-IVA (13%, S), IUE (5%, G), IT (3%, A)
2. `PermissionSeeder` — 50+ permissions, syncs to Admin role (does NOT create the role)
3. Creates Admin role + 4 users + 8 bank accounts
4. `DemoDataSeeder` + `MoreDataSeeder` — demo data

```bash
php artisan db:seed
```

## Key domain notes

| Concept | Details |
|---|---|
| **Movement** types | `D` = Débito, `C` = Crédito, `B` = Balance carry |
| **Transaction** `payment_type` | `T` = Transferencia, `CH` = Cheque |
| **Transaction** numbering | Per type per **fiscal year (Oct–Sep)**, `lockForUpdate()` in `creating` boot event |
| **Box** numbering | Per type per **calendar month**, `lockForUpdate()` in `creating` boot event |
| **NumberHelper** | `require_once app_path('Helpers/NumberHelper.php')` in `AppServiceProvider::boot()`. Converts numbers to Spanish literal. |
| **Person** | Shared parent for `Supplier` and `Customer` (one-to-one). `Movement` has `person_id`. |
| **Retention** `Taxe` type | `S` = Servicios, `G` = Bienes, `A` = Todos |
| **Account** | `HasManyThrough` to `Movement` via `Transaction` |
| **Account statement** route validation | Uses `whereIn('type', AccountStatementService::TYPES)` — accepts `'supplier'` or `'customer'` |
| **Retention calculation** | Service: RC-IVA 13% + IT 3%. Goods: IUE 5% + IT 3%. Formula: `total = amount / (1 - sum(rates)/100)`. |

## Schema quirks (mismatches between models and migrations)

These relationships exist in models but are **not backed by database columns**:
- `Transaction::supplier()` — `suppliers` table has no FK in `transactions`
- `Supplier::transactions()` — querying `transactions.supplier_id` which doesn't exist
- `Supplier::cooperative()` — `suppliers` table has no `cooperative_id`
- `Taxe::retention()` — `taxes` table has no `retention_id`
- `Transaction` model accessors (`calculate_label`, `date_label`) reference `amount`/`date` columns not present in the `transactions` table

## Miscellaneous

- `Date::use(CarbonImmutable::class)` and password defaults (12+ chars in production) configured in `AppServiceProvider::boot()`.
- TallStackUi heavily customized in `AppServiceProvider::boot()` — table, modal, button, inputs, selects, date picker, sidebar.
- No `.github/` CI workflows present.
- Spatie Permission `guard_name` defaults to `web`. Admin role is created in `DatabaseSeeder`, not `PermissionSeeder`.

---

### Academic documentation (docs/)

All files use `\documentclass[stu]{apa7}` (APA 7th ed. LaTeX). See `docs/RESUMEN-ACTIVIDADES.md` for a 192-line summary.

### Agents

| File | Purpose |
|---|---|
| `.opencode/agents/sic-minch-docs.json` | Agent specialized in the 10 academic activities. |
