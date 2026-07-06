# AGENTS.md — Minch / SIC-MINCH

## Dual context

This repository contains **both** the source code of SIC-MINCH (a Laravel Livewire financial management app for Bolivian accounting) **and** the academic documentation produced across 10 project activities.

- **Codebase**: Laravel 13 + Livewire 4.1 app for retentions, bank books, cash, account statements.
- **Academic docs**: 8 LaTeX files in `docs/` covering the full software engineering lifecycle (profile → analysis → use cases → Scrum → feasibility → implementation → testing → quality → manuals).

---

### Codebase context

A Laravel Livewire financial management app (Bolivian accounting context: retentions, bank books, account statements).

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
| `composer ci:check` | same as `@test` but without `config:clear` |
| `./vendor/bin/pest tests/Feature/ExampleTest.php` | Run a single test |

## Stack

**Laravel 13**, **Livewire 4.1**, **TallStackUI 3.1**, **Fortify** (auth), **Spatie Permission** (RBAC), **Tailwind CSS v4**, **mPDF** (PDF), **Maatwebsite/Excel** (exports).

- Auth middleware aliases registered in `bootstrap/app.php`: `role`, `permission`, `role_or_permission`.
- Routes: `routes/web.php` (main UI with `permission` middleware guards), `routes/settings.php` (required from web.php), `routes/api.php` (Sanctum — supplier search), `routes/console.php`.
- 28+ Livewire components in `app/Livewire/` organized by feature subdirectory; views in `resources/views/livewire/`.
- Key services: `AccountStatementService`, `CashBalanceService`, `MovementBalanceService`, `PersonSupplierService`.

## Config & env quirks

- `.env.example` defaults to **SQLite**; actual `.env` uses **MySQL** (`DB_CONNECTION=mysql`). Always check which DB is active.
- `.env.example` has `SESSION_DRIVER=database`, but actual `.env` may use `file`. Queue and cache use `database` driver.
- `.npmrc` has `ignore-scripts=true` — lifecycle hooks won't run on `npm install`.
- App locale defaults to **`es`** (`config/app.php:81`). Validation strings in `lang/es/`.
- `Carbon::setLocale('es')` is called in several models/controllers for Spanish date formatting.

## Testing

- Pest PHP: SQLite `:memory:`, array cache, sync queue (see `phpunit.xml`).
- `RefreshDatabase` is **commented out** in `tests/Pest.php` — tests may need manual DB state.
- Test order: `config:clear → lint:check → test`. No CI workflows found in `.github/`.
- Helper: `$this->skipUnlessFortifyHas('feature')` in `tests/TestCase.php` for Fortify-gated tests.

## Database

Migrations in `database/migrations/`. Seed order (`DatabaseSeeder`):
1. `TaxeSeeder`
2. `PermissionSeeder`
3. Creates Admin role with all permissions, assigns to user **"Hamura"** + 3 extra users + 8 bank accounts
4. `DemoDataSeeder` (cooperatives, suppliers, customers, retentions, movements, etc.)

Run: `php artisan db:seed`

## Key domain notes

| Concept | Details |
|---|---|
| **Movement** types | `D` = Débito (debit), `C` = Crédito (credit), `B` = Balance (balance carry) |
| **Transaction** `payment_type` | `T` = Transferencia, `CH` = Cheque |
| **Transaction** numbering | Per type per **fiscal year (Oct–Sep)**, `lockForUpdate()` in `creating` boot event |
| **Box** numbering | Per type per **calendar month**, `lockForUpdate()` in `creating` boot event |
| **NumberHelper** | `require_once app_path('Helpers/NumberHelper.php')` in `AppServiceProvider::boot()`. Converts numbers to Spanish literal (for receipts). |
| **Person** | Shared parent for `Supplier` and `Customer` (one-to-one). `Movement` has `person_id`. |
| **Retention** `Taxe` type | `S` = Servicios, `G` = Bienes, `A` = Todos |
| **Account** | `HasManyThrough` to `Movement` via `Transaction` |
| **Account statement** route validation | Uses `whereIn('type', AccountStatementService::TYPES)` — accepts `'supplier'` or `'customer'` |

## Miscellaneous

- `Date::use(CarbonImmutable::class)` and password defaults (12+ chars in production) configured in `AppServiceProvider::boot()`.
- TallStackUi heavily customized in `AppServiceProvider::boot()` — table, modal, button, inputs, selects, date picker, sidebar.
- No `.github/` CI workflows present.

---

### Academic documentation (docs/)

All files use `\documentclass[stu]{apa7}` (APA 7th ed. LaTeX). The `.env.example` is **not** the academic reference — the actual project context lives in these files:

| File | Activity | What it covers |
|---|---|---|
| `docs/perfil-proyecto.tex` | 1 — Perfil | Introduction, problem, objectives, justification, methods. Stack corrected to Laravel 13 + Livewire 4 + MySQL. |
| `docs/analisis-documental.tex` | 2 — Analysis | 10-document matrix, 3 questionnaires, 3 interview guides, 2 observation guides, 9 pain points. |
| `docs/casos-de-uso.tex` | 3 — Use Cases | 6 user profiles, 7 modules, 12 detailed use cases with flows, pre/post-conditions, contrast tables. |
| `docs/actividad-4-scrum.tex` | 4 — Scrum | Vision, 3 personas (Carlos/Carmen/Pedro), 6 epics, 27 user stories (108 SP), Sprint 1 (40 SP). |
| `docs/actividad-6-factibilidad.tex` | 6 — Feasibility | COCOMO semiacoplado (19 KDSI, 84.9 pm), Bs. 424,545 total, 8 risks, VAN/B/C analysis. |
| `docs/actividad-7-implementacion.tex` | 7 — Implementation | Sprint Final (7 HU, 43 SP), 22 tasks, 17 interfaces, release prep, CI/CD pipeline. |
| `docs/actividad-8-pruebas.tex` | 8 — Testing | 96 tests, 98.9% success. Pest (unit), Cypress (E2E UI), Postman (API), JMeter (load), black-box. |
| `docs/actividad-9-calidad.tex` | 9 — Quality | ISO 25010 evaluation: 93.6/100 overall. Adapted for monolithic Livewire (no JWT, no biometrics). |
| `docs/actividad-10-manuales.tex` | 10 — Manuals | 4 manuals: user, admin, installation/deployment, database. Includes glossary of 14 Bolivian accounting terms. |
| `docs/RESUMEN-ACTIVIDADES.md` | All | Complete 192-line summary of all 10 activities with tables, stack, and business rules. |

### Key business rules (verified against source code)

| Rule | Detail |
|---|---|
| Retention calculation | Service: RC-IVA 13% + IT 3%. Goods: IUE 5% + IT 3%. Formula: `total = amount / (1 - sum(rates)/100)`. |
| Transaction numbering | Sequential per type (D/C) per **fiscal year Oct–Sep**, `lockForUpdate()` in `Transaction::creating()`. |
| Box numbering | Sequential per type (D/C) per **calendar month**, `lockForUpdate()` in `Box::creating()`. |
| Movement types | `D` = Débito, `C` = Crédito, `B` = Balance carry. |
| Payment types | `T` = Transferencia, `CH` = Cheque (check number required). |
| Account statement types | `supplier` or `customer` (validated via `AccountStatementService::TYPES`). |

### Agents

| File | Purpose |
|---|---|
| `.opencode/agents/sic-minch-docs.json` | Agent specialized in the 10 academic activities. Load with `skill` tool or reference when answering doc questions. |

Run `composer test` to verify codebase, or check individual docs with your LaTeX compiler.
