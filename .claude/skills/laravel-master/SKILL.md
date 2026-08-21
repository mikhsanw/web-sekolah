---
name: laravel-master
description: "Reference for the Master 2024 reusable Laravel 13 admin template — its architecture, config (config/master.php), routing/permission model, and the make:crud generator. Use whenever working in this repo or any project cloned from it: adding modules/CRUD, controllers, views, file handling, roles/permissions, or following its conventions."
---

# Master 2024 — Laravel 13 Admin Template

A reusable Laravel 13 starter (PHP ^8.3) with an admin panel, auth, Spatie roles/permissions,
activity log, a file/media system (base64-in-DB + Google Drive backup), a dynamic menu, a
Sanctum API, and an **artisan CRUD generator**. This skill captures its conventions so generated
and hand-written code stays consistent with the template.

> When in doubt, read `config/master.php`, `app/Http/Controllers/Controller.php`, and the relevant
> stub under `resources/stubs/`. Those three define almost every convention below.

## When this applies

- Working in the `master2024` repo or any project cloned from it (look for `config/master.php`
  with an `app.profile.name` of "Main Master", and `composer.json` describing a "Reusable Laravel
  master template").
- Adding a module / CRUD, a backend controller, a view, file handling, or roles/permissions.
- Touching auth (web or API), the menu system, or the generator.

## Architecture map

| Area | Location |
|------|----------|
| Central config | `config/master.php` (paths, urls, view roots, permission levels, validation regex, content enums) |
| Base controller | `app/Http/Controllers/Controller.php` — sets `$view`, `$code`, `$model`, `$url`, `$help` from the current menu |
| Backend controllers | `app/Http/Controllers/Backend/*` |
| API controllers | `app/Http/Controllers/Api/*` (Sanctum) |
| Routes | `routes/web.php` (public), `routes/backend.php` (admin), `routes/api.php` |
| Middleware | `Backend` (shares menu/user to views), `CheckRoutePermission` (auto route→permission mapping) |
| Models | `app/Models/*` — `User`, `Menu`, `File`, `Berita` (sample) |
| Helpers | `app/Helpers/Helper.php` + `app/Helpers/HelpTheme.php` (autoloaded via composer `files`) |
| Generator | `app/Console/Commands/MakeCrud.php` + `app/Services/Make*Service.php` |
| Stubs | `resources/stubs/*` (Controller, Model, Request, migration, views/*, api/*) |
| Backup jobs | `app/Jobs/Backup*ToGoogle.php`, commands `backup:database`, `backup:google` |

## Config system — `config/master.php`

Everything is driven from here. Key keys:

- `master.app.url.backend` → `admin` (admin URL prefix), `master.app.url.frontend` → `web`
- `master.app.view.backend` → `backend` (Blade view root: `resources/views/backend/...`)
- `master.app.web.template` → `eduadmin` (frontend asset/template folder under `public/`)
- `master.app.level` → `['list','create','edit','delete']` — the permission verbs per resource
- `master.app.validation.regex` → named validation rule fragments (json, number, file, image, uuid…)
- `master.content.*` → status/color enums for users, announcements, etc.

**Never hardcode** `admin`, `backend`, or template names — read them from `config('master.app...')`.

## Routing & middleware

`bootstrap/app.php` wires three groups:

```php
// Admin: every backend route is protected by this stack
Route::middleware(['web','auth','backend','check.permission'])
    ->namespace('App\Http\Controllers\Backend')
    ->prefix('admin')
    ->group(base_path('routes/backend.php'));
```

- `auth` — must be logged in.
- `backend` (`App\Http\Middleware\Backend`) — shares `$user`, `$menus`, `$page`, theme config to all
  views, updates `last_login_at`, and redirects to maintenance/coming-soon when the menu flags it.
- `check.permission` (`CheckRoutePermission`) — auto-maps the route to a Spatie permission.

### Permission model

- Roles/permissions via **spatie/laravel-permission**. Permission names follow `"{resource} {verb}"`,
  e.g. `users list`, `berita delete`. Verbs come from `master.app.level`.
- **Super Admin** bypasses all checks via `Gate::before` in `AppServiceProvider` and an explicit
  check in `CheckRoutePermission`.
- `CheckRoutePermission` maps the route **name** (`users.index` → `users list`, `*.store` → `* create`,
  `*.update` → `* edit`, `*.destroy` → `* delete`, `*.show`/`*.index` → `* list`). Unnamed routes fall
  back to `"{segment[1]} list"`. So **name your resource routes** to get correct verb mapping;
  unnamed routes only ever require `list`.

### Security conventions (follow these — they are load-bearing)

- **Never** accept role/permission assignment from public input (e.g. registration). Assign roles only
  from authenticated admin endpoints.
- **State-changing actions must not use GET.** Use `POST`/`DELETE` (CSRF-protected). The backend
  `delete/{id}` GET routes only render a confirmation modal; the real delete goes through the
  resource `destroy` (DELETE). File deletion uses `Route::delete('file/delete/...')`.
- API auth routes are rate-limited (`throttle:6,1`).
- File serving adds `X-Content-Type-Options: nosniff`; physical file reads are constrained inside the
  template base dir (no raw filesystem paths from DB).
- Uploads validate **both** real MIME and extension (`App\Rules\FileAllowed`), plus a content scan
  (`App\Rules\SafeFile`).

## Base controller contract

`Controller::__construct()` resolves the current menu (via `Helper::menu()`) and sets:

- `$this->code` — menu code, also the permission resource prefix (e.g. `users`)
- `$this->model` — fully-qualified model class for the menu
- `$this->url` — `admin/{menu-url}` used by datatable action buttons
- `$this->view` — `backend.{code}` view namespace
- `$this->help` — the `Helper` singleton

Backend controllers extend this and rely on `$this->model`, `$this->view`, `$this->url`, `$this->code`.

### Controller pattern (see `resources/stubs/Controller.stub`)

- `index()` returns a **Yajra DataTables** JSON when `$request->ajax()`, else `view($this->view.'.index')`.
- Action buttons are built inline with `data-action="show|edit|delete"`, `data-url`, `data-id`, gated
  by `hasPermissionTo("{code} edit|delete")` (Super Admin sees all).
- `store`/`update` return **JSON** `{status, message}` (AJAX form submit), not redirects.
- `show`/`edit`/`create` return Blade views; `create`/`edit` share select options via a `data` array.
- Views are **modal-driven**: `data-action` loads a form/show/delete partial into a modal; the modal's
  form submits via AJAX. Delete partials use `html()->form('DELETE', route($page->code.'.destroy', $id))`.

## CRUD generator

Artisan commands (all run from the project root):

| Command | Purpose |
|---------|---------|
| `php artisan make:crud {Name} {columns}` | Full backend CRUD: controller, request, model, migration, views, route, permissions seeder, menu seeder |
| `php artisan make:views {Dir} {columns}` | Just the Blade views |
| `php artisan make:rest-api {Name} {columns}` | API controller + request + resource |
| `php artisan rm:crud {Name} [--force]` | Remove a generated backend CRUD |
| `php artisan rm:rest-api {Name} [--force]` | Remove a generated API CRUD |
| `php artisan menu:generate-seeder` | Regenerate the menu seeder from current menus |

### Column format

Comma-separated `name:type[:size]`. Examples:

```bash
php artisan make:crud Product "name:string,price:integer,description:text,stock:integer:11"
php artisan make:crud Order "code:string,customer_id:relasi,status:select"
```

Type rules (`MakeMigrationService`):
- `name:relasi` → `foreignUuid('name')->nullable()->constrained()` (FK to another table)
- `name:select` → `string('name')->nullable()` (rendered as a select input in the form)
- `name:type` / `name:type:size` → `$table->{type}('name'[, size])->nullable()` (any Blueprint method:
  `string`, `text`, `integer`, `decimal`, `boolean`, `date`, `datetime`, …; third segment is the size/precision)

Naming is derived automatically (`MakeGlobalService::getNamingConvention`): plural/singular class names,
snake-case table name (`Product` → table `products`, controller `ProductsController`).

### How generation injects itself

The generator appends to files at **marker comments** — keep these markers in place:
- `routes/backend.php` → `//gencrud`
- `database/seeders/DatabaseSeeder.php` → `// gencrud-permissions`
- `database/seeders/MenuSeeder.php` → `// gencrud-menus`

### After generating — always do this

1. Review every generated file (the generator prints: *"sifatnya hanya membantu, cek kembali seluruh filenya"*).
2. Run `php artisan migrate`.
3. Re-seed permissions + menu so the new module is reachable and authorized
   (`php artisan db:seed` or the specific seeder), then assign the new permissions to roles.
4. Confirm the generated route is **named** (resource routes are) so `CheckRoutePermission` maps verbs correctly.

## Models & data conventions

- **UUID primary keys** + **SoftDeletes** are the norm (`User`, `File` use `HasUuids`).
- `User`: `HasRoles`, `HasApiTokens`, `LogsActivity`; `detail` is a JSON-cast array; `$fillable`
  excludes role fields (roles set via `syncRoles`, never mass-assignment).
- `File`: content stored on a storage disk (`take`/`exists` accessors), with append accessors
  `link_stream`, `link_download`, `link_delete`, `link_public_stream`. Mass deletion cleans the disk
  via the `forceDeleting` model event. Backups go to the `google` disk.
- Activity logging via **spatie/laravel-activitylog** (`getActivitylogOptions`).

## Files, media & Google Drive

- Public streaming: `/file-stream/{code}?id=` and `/file-thumbnail/{code}?id=` (no auth — intended for
  public assets only; do not route private files here).
- Admin streaming/download/delete under `/admin/file/...` (delete is `DELETE`).
- Google Drive disk is registered in `AppServiceProvider::boot()` (`Storage::extend('google', …)`),
  configured by `GOOGLE_DRIVE_*` env vars. Backups run via `backup:database` / `backup:google` and the
  settings page.

## Helpers

- `Helper::menu($code=null)`, `Helper::displayDateTime()`, `Helper::formatDate()`,
  `Helper::shortDescription()`, `Helper::generateSeoURL()`, `Helper::generateTahunAkademikOptions()`,
  `Helper::buildUnitOptions()`. Autoloaded; call statically or via `$this->help` / `app(Helper::class)`.
- `HelpTheme` / `theme()` — Metronic-style theme/asset helpers (CSS/JS vendors, icons, RTL, dark mode).
  Frontend template folder = `config('master.app.web.template')`.

## Quick recipes

- **Add a module:** `php artisan make:crud Foo "..."` → review → `migrate` → seed permissions+menu →
  assign permissions to roles.
- **Find why a 403 happens:** check the route name → `CheckRoutePermission` mapping → whether the user's
  role has `"{resource} {verb}"`. Super Admin bypasses everything.
- **Change admin prefix or view root:** edit `config/master.php` (`app.url.backend`, `app.view.backend`);
  do not grep-replace literals.
- **Add a public image endpoint:** use `File` + `link_public_stream`; never expose private/owned files
  through the unauthenticated `file-stream` route.
