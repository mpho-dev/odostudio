# AGENTS.md

MediaWeb is a Laravel 12 CMS for photography booking, invoicing, and portfolio management with role-based access (Admin, Manager, Photographer).

## Essential Commands

### Development
```bash
composer run dev          # Start server, queue worker, logs, and Vite in parallel
npm run dev              # Start Vite dev server only
npm run build            # Build frontend assets for production
```

### Testing
```bash
php artisan test --compact                                    # Run all tests
php artisan test --compact tests/Feature/BookingWorkflowTest.php  # Run single file
php artisan test --compact --filter=test_visitor_can_submit      # Run by test name
```

### Code Quality
```bash
vendor/bin/pint --dirty --format agent   # Format changed PHP files (run after every edit)
php artisan test                          # Verify tests pass after changes
```

### Database
```bash
php artisan migrate              # Run pending migrations
php artisan db:seed              # Seed roles and default data
php artisan storage:link         # Create storage symlink for media
```

## Architecture

### Role-Based Access
- **Admin**: Site settings, user management, CRUD for services/projects/testimonials, email config
- **Manager**: Booking requests, booking creation, all invoices
- **Photographer**: Assigned bookings on calendar, booking requests, notification preferences
- Routes use `role:admin`, `role:manager`, `role:photographer_videographer` middleware (defined in `bootstrap/app.php`)

### Core Models & Relationships
- **Booking flow**: `BookingRequest` (public inquiry) -> `Booking` (confirmed) -> `Invoice`
- **Portfolio**: `Project` has many `Media` with featured/hero selection
- **Users**: Soft-deletable, email verification, encrypted sensitive attributes
- **EmailConfiguration**: Admin templates with placeholders ({name}, {event_type}, etc.)
- All models use explicit return type hints and PHPDoc blocks

### File Locations
- Models: `app/Models/` | Controllers: `app/Http/Controllers/`
- Form Requests: `app/Http/Requests/` | Policies: `app/Policies/`
- Mail: `app/Mail/` | Jobs: `app/Jobs/` | Services: `app/Services/`
- Views: `resources/views/` (organized by role)
- Tests: `tests/Feature/` and `tests/Unit/`
- Routes: `routes/web.php`, `routes/auth.php`, `routes/console.php`
- Migrations: `database/migrations/` | Factories: `database/factories/`

## Code Style

### PHP
- Always use explicit return type declarations: `protected function foo(): bool`
- Use PHP 8 constructor property promotion: `public function __construct(public Model $model) {}`
- Always use curly braces for control structures, even single-line bodies
- Prefer PHPDoc blocks over inline comments; never comment inside logic unless exceptionally complex
- Enum keys should be TitleCase (e.g., `Monthly`, `Active`)
- Use `casts()` method on models (Laravel 12 convention), not `$casts` property

### Eloquent & Database
- Prefer Eloquent relationships over raw queries; use eager loading to prevent N+1
- Avoid `DB::`; prefer `Model::query()` and the ORM
- When modifying columns in migrations, include all previously-defined attributes or they will be dropped
- Use relationship methods with proper return type hints

### Controllers & Validation
- Always create Form Request classes for validation (not inline validation in controllers)
- Check sibling Form Requests for the array vs string validation rule convention
- Use `$this->authorize()` or policies for authorization

### Configuration & Security
- Use `config('key')` for environment values, never `env()` outside config files
- Use `route()` with named routes for URL generation
- Email template params are HTML-escaped to prevent XSS

## Testing

- Use PHPUnit (not Pest). Create tests with `php artisan make:test --phpunit {name}`
- Most tests should be feature tests; add `--unit` for unit tests
- Tests auto-seed `RolesAndPermissionsSeeder` in `setUp()`
- Use `RefreshDatabase` trait and model factories for test data
- Check factory custom states before manually setting attributes
- Cover happy paths, failure paths, and edge cases
- Never remove test files without approval

## Frontend

- **Blade** templates in `resources/views/` organized by role
- **Tailwind CSS v4**: CSS-first config (`@import "tailwindcss"`), no `tailwind.config.js`
- **Alpine.js v3**: Lightweight interactivity in templates
- **Vite**: `resources/css/app.css` and `resources/js/app.js` are entry points
- Activate `tailwindcss-development` skill when styling; check existing components first
- If frontend changes aren't visible, run `npm run build` or `npm run dev`

## Laravel 12 Specifics

- Middleware configured in `bootstrap/app.php` (not `app/Http/Kernel.php`)
- Service providers in `bootstrap/providers.php`
- Console commands in `app/Console/Commands/` are auto-discovered
- Use `search-docs` tool for version-specific documentation before making changes

## Rate Limits

- Contact form: 5/min | Invoice creation: 10/min | Admin config: 5/min
- Applied at route level: `middleware('throttle:limit,period')`

## Available Skills

- `tailwindcss-development` — Activate when working with any CSS/styling/UI changes

## Debugging Tools (Laravel Boost)

- `search-docs` — Version-specific Laravel/Tailwind docs (use before coding)
- `tinker` / `database-query` / `database-schema` — Debug database and models
- `list-artisan-commands` — Verify Artisan command options
- `browser-logs` — Read recent browser errors
- `get-absolute-url` — Generate correct project URLs
