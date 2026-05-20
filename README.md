# MediaWeb — Photography Booking, Invoicing & Portfolio CMS

MediaWeb is a full‑featured **Laravel 12** content management system built for a boutique cinematic photography and videography studio. It manages the complete client lifecycle — from public booking inquiries through confirmed bookings, crew assignment, equipment checkout, invoicing with PDF generation, and portfolio/media management — all secured behind role‑based access control.

The system is designed for a team of **Administrators**, **Managers**, and **Photographers/Videographers** (crew), each with granular permissions tailored to their role. It powers the studio's public website (portfolio gallery, service listings, pricing tiers, testimonials, contact forms) and provides a comprehensive internal operations dashboard for scheduling, billing, and asset management.

---

## Features

### Portfolio & Public Site
- **Project Gallery**: Categorised portfolio projects with featured hero media, filtering, and public API endpoints
- **Service Listings**: Configurable service offerings with pricing, icons, and feature lists displayed on the homepage
- **Investment Tiers**: Pricing packages with featured/badge support, JSON feature sets, and ordering
- **Process Steps**: Editable "how it works" workflow displayed on the landing page
- **Testimonials**: Client reviews with ratings, initials auto-generation, and featured flags
- **Dynamic Homepage**: Hero section, about content, and CTAs all configurable via the admin panel
- **XML Sitemap**: Auto‑generated `sitemap.xml` for SEO

### Booking System
- **Public Inquiry Form**: Rate‑limited (5/min) contact form capturing name, email, phone, event date, event type, location, and notes
- **Booking Request Management**: Pending requests with crew assignment, status tracking (pending → confirmed)
- **Confirmed Bookings**: Full CRUD with event details, pricing, investment tier, and status workflow (pending → confirmed → completed/cancelled)
- **Crew Assignment**: Photographers and videographers can be assigned to both requests and bookings via pivot tables
- **Double-Booking Prevention**: Validates crew availability before assigning to a booking
- **Soft Delete & Restore**: Bookings support soft deletion, restoration, and even restoration from cancellation

### Invoicing
- **Invoice Generation**: Create invoices from confirmed bookings with rate, hours, and notes; auto‑generates unique invoice numbers (INV‑XXXXXX)
- **PDF Export**: Downloads invoices as PDF via DomPDF with a dedicated Blade template
- **Status Workflow**: Draft → Sent → Paid with timestamps and audit trail
- **Role‑Filtered Access**: Managers see all invoices; photographers see only their own
- **Rate Limiting**: Invoice creation throttled at 10/min to prevent abuse
- **Soft Delete**: Invoices are soft‑deleted for audit trail preservation

### Media Library
- **Multi‑Format Support**: Upload images, videos, and GIFs with MIME validation
- **Image Sanitisation**: EXIF/metadata stripping and re‑encoding via Intervention Image (GD driver)
- **WebP Optimisation**: Automatic conversion to WebP at 85% quality for hero images, about portraits, and equipment photos
- **Tagging & Collections**: Organise media with tags and curated collections
- **Filtering**: Search by name, filter by media type, tag, collection, or project
- **Bulk Operations**: Add multiple media items to a collection at once

### Equipment Management
- **Inventory System**: Full CRUD for equipment items organised by categories (Cameras, Lenses, Lighting, Audio, Support)
- **Checkout Workflow**: Managers assign equipment to crew for a booking; crew members check in equipment when returned
- **Availability Checking**: Date‑based availability scoping (`scopeAvailableFor`) with DB row locking to prevent race conditions
- **Specialty Validation**: Photography gear can only be assigned to photographers, videography gear to videographers
- **Overdue Tracking**: Admin report showing overdue equipment with expected return dates
- **Maintenance Tracking**: Condition tracking, maintenance notes, and status workflow (available / checked out / maintenance / retired)

### Email System
- **Configurable Templates**: Admins edit email subject/body via a Quill rich‑text editor with live preview
- **Dynamic Placeholders**: Templates support `{name}`, `{email}`, `{event_type}`, `{site_name}`, `{year}`, and more — resolved via the `HasEmailPlaceholders` trait
- **Async Queue Processing**: Booking notifications sent via queued jobs (database queue in dev, Redis in production)
- **XSS Prevention**: All placeholder values are HTML‑escaped before rendering
- **Encrypted Configuration**: SMTP credentials and email settings stored encrypted in the database
- **Five Mail Types**: Booking request (admin), visitor confirmation, crew booking confirmation, invoice to client, photographer request notification

### Role‑Based Access Control
- **Admin**: Full system control — user management, all content CRUD (services, projects, testimonials, process steps, investment tiers), site settings, email configuration, system health monitoring, equipment management
- **Manager**: Booking operations — view pending requests, create/confirm/cancel bookings, assign crew, assign equipment, manage all invoices, dashboard with KPIs
- **Photographer/Videographer (Crew)**: Calendar view of assigned bookings, assigned booking requests, notification preferences, equipment checkout ("My Gear"), own invoice visibility

### Security
- **Content‑Security‑Policy**: Custom middleware applies CSP headers on every response
- **Cross‑Origin Isolation**: Applied to admin email routes for SharedArrayBuffer support (e.g., ffmpeg.wasm)
- **Forced Password Change**: Users flagged with `must_change_password` are redirected to update their password on first login
- **Image Sanitisation**: EXIF data stripped and images re‑encoded on upload
- **HTML Purification**: All rich‑text input (Quill editor, site settings) sanitised via `mews/purifier`
- **Rate Limiting**: Applied to contact form (5/min), login (5/min), invoice creation (10/min), booking creation (10/min), media upload (10/min), email verification (6/min), email config (5/min), site config (5/min)
- **Soft Deletes**: Users, bookings, invoices, equipment items, and process steps preserve audit trails
- **Email Verification**: New accounts require verified email before access (registration is admin‑only)

### Documentation System
- **Role‑Filtered Markdown Docs**: Built‑in documentation viewer serves role‑specific guides (admin, manager, photographer, public, architecture) from `docs/` directory
- **CommonMark Rendering**: GFM support with table and task‑list extensions
- **Caching**: Rendered Markdown is cached for performance

### System Health
- **Dashboard**: Visual health check covering database connectivity, cache read/write, email configuration, storage permissions, role system, disk space, queue status, and application environment
- **JSON API**: Nine health‑check endpoints for external monitoring

---

## Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Backend | Laravel | 12.x |
| PHP | PHP | 8.2+ |
| Database | SQLite (dev), PostgreSQL / MySQL (production) |
| Cache / Queue | Database (dev), Redis (production) |
| PDF Generation | DomPDF | — |
| Role Management | Spatie Laravel Permission | — |
| Authentication | Laravel Breeze (with email verification) | 2.x |
| ORM | Eloquent (soft deletes, encrypted attributes) | — |
| Frontend | Tailwind CSS | ^4.0 |
| Frontend | Alpine.js | ^3.15 |
| Frontend | Vite | ^7.0 |
| Animation | GSAP | ^3.15 |
| Rich Text | Quill | ^2.0 |
| Diagrams | Mermaid | ^11.12 |
| Image Processing | Intervention Image | — |
| HTML Sanitisation | mews/purifier | — |

---

## Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (included with PHP) or PostgreSQL 12+ / MySQL 8.0+
- Redis 6+ (production only)

---

## Quick Start

### 1. Install Dependencies

```bash
composer install
npm install && npm run build
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Create Database & Run Migrations

```bash
# Using SQLite (default for development)
touch database/database.sqlite

# Or PostgreSQL
createdb mediaweb

# Run migrations
php artisan migrate
```

### 4. Seed Roles & Default Data

```bash
php artisan db:seed
```

### 5. Create Storage Symlink

```bash
php artisan storage:link
```

### 6. Start Development Server

```bash
composer run dev
```

This runs the Laravel dev server, queue worker, log watcher, and Vite dev server in parallel.

Visit: <http://localhost:8000>

---

## Production Deployment

### Environment Configuration

Update `.env` for production:

```dotenv
APP_ENV=production
APP_DEBUG=false
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=cookie
```

### Database

Use PostgreSQL or MySQL:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=your-postgres-host
DB_DATABASE=mediaweb
DB_USERNAME=db_user
DB_PASSWORD=secure_password
```

### Deployment Steps

```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Generate APP_KEY
php artisan key:generate

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start queue worker (use supervisor or systemd)
php artisan queue:work redis --daemon

# Setup cron job for Laravel scheduler
* * * * * cd /path/to/mediaweb && php artisan schedule:run >> /dev/null 2>&1
```

---

## API Documentation

### Public Endpoints

#### `GET /`

Homepage with services, site settings, process steps, investment tiers, and testimonials.

#### `GET /portfolio`

View all portfolio projects grouped by category.

**Response:**
```json
{
  "featured": [
    {
      "id": 1,
      "title": "Wedding Celebration",
      "slug": "wedding-celebration",
      "description": "Beautiful wedding photography",
      "category": "weddings",
      "hero_media": {
        "id": 5,
        "file_path": "media/project-1-hero.jpg"
      },
      "media_count": 42
    }
  ]
}
```

#### `GET /portfolio/{project:slug}`

View single project with all media items.

**Response:**
```json
{
  "id": 1,
  "title": "Wedding Celebration",
  "description": "Beautiful wedding photography",
  "media": [
    {
      "id": 5,
      "title": "First Dance",
      "description": "Couple's first dance",
      "file_path": "media/wedding-1-dance.jpg",
      "media_type": "image"
    }
  ]
}
```

#### `POST /contact`

Submit a booking request (rate limited: 5 per minute).

**Request:**
```json
{
  "name": "John",
  "surname": "Doe",
  "email": "john@example.com",
  "phone": "555-1234",
  "event_date": "2024-06-15T14:00",
  "event_type": "Wedding",
  "event_location": "Paris, France",
  "notes": "Please confirm availability"
}
```

**Response:** `200 OK` with success message

#### `GET /sitemap.xml`

Auto‑generated XML sitemap.

### Authenticated Endpoints

#### `GET /photographer/calendar`

View photographer's calendar of assigned bookings.

#### `GET /photographer/calendar/events`

Get calendar events as JSON (for frontend calendar widget).

**Response:**
```json
[
  {
    "id": 1,
    "title": "Smith Wedding - Paris Venue",
    "start": "2024-06-15T14:00:00Z",
    "end": "2024-06-15T22:00:00Z",
    "color": "#4f46e5",
    "extendedProps": {
      "location": "Paris, France",
      "status": "confirmed",
      "notes": "Bring backup batteries"
    }
  }
]
```

#### `GET /invoices`

List invoices created by logged-in user (managers see all; photographers see own).

#### `GET /invoices/{invoice}`

View invoice details.

#### `GET /invoices/{invoice}/pdf`

Download invoice as PDF.

#### `POST /invoices/{booking}` (rate limited: 10 per minute)

Create a new invoice for a booking.

**Request:**
```json
{
  "rate": "250.50",
  "hours": "8.5",
  "notes": "Travel fees included"
}
```

**Response:**
```json
{
  "id": 1,
  "invoice_number": "INV-000001",
  "total_amount": "2129.25",
  "status": "pending",
  "issued_at": "2024-02-27T15:30:00Z"
}
```

---

## Project Structure

```directory
app/
├── Models/                     # 20 Eloquent models
│   ├── User.php                #   Authenticatable, soft deletes, Spatie roles
│   ├── BookingRequest.php      #   Public booking inquiry
│   ├── Booking.php             #   Confirmed booking with crew assignment
│   ├── Invoice.php             #   Soft-deletable invoices with PDF
│   ├── Media.php               #   Portfolio images/videos/GIFs
│   ├── Project.php             #   Portfolio project groups
│   ├── Service.php             #   Homepage service offerings
│   ├── Testimonial.php         #   Client reviews with ratings
│   ├── InvestmentTier.php      #   Pricing packages
│   ├── ProcessStep.php         #   How-it-works workflow steps
│   ├── Tag.php                 #   Media tagging
│   ├── Collection.php          #   Curated media collections
│   ├── SiteSetting.php         #   Key-value site configuration
│   ├── EmailConfiguration.php  #   Encrypted email settings
│   ├── EmailTemplate.php       #   Editable email templates
│   ├── NotificationPreference.php  # Per-user notification settings
│   ├── EquipmentCategory.php   #   Equipment category hierarchy
│   ├── EquipmentItem.php       #   Individual equipment with availability
│   ├── EquipmentCheckout.php   #   Checkout/check-in records
│   └── EquipmentImage.php      #   Equipment photos
├── Http/Controllers/           # 28 controllers
│   ├── Auth/                   #   Standard Laravel auth suite
│   ├── AdminController.php     #   Email configuration
│   ├── BookingRequestController.php  # Booking inquiry lifecycle
│   ├── BookingController.php   #   Booking CRUD & calendar
│   ├── InvoiceController.php   #   Invoice CRUD & PDF
│   ├── MediaController.php     #   Media library & public portfolio
│   ├── ProjectController.php   #   Portfolio project CRUD
│   ├── ServiceController.php   #   Service CRUD
│   ├── TestimonialController.php   # Testimonial CRUD
│   ├── InvestmentTierController.php  # Pricing tier CRUD
│   ├── ProcessStepController.php    # Process step CRUD
│   ├── TagController.php       #   Tag REST API
│   ├── CollectionController.php #   Collection CRUD
│   ├── SiteSettingController.php    # Site config (hero, about, CTA)
│   ├── UserManagementController.php # User CRUD
│   ├── ManagerController.php   #   Manager dashboard
│   ├── EquipmentCategoryController.php  # Equipment category CRUD
│   ├── EquipmentItemController.php  # Equipment item CRUD
│   ├── EquipmentCheckoutController.php  # Checkout/check-in workflow
│   ├── DocumentationController.php  # Role-filtered Markdown docs
│   ├── SitemapController.php   #   XML sitemap
│   ├── HealthCheckController.php   # System health dashboard & API
│   ├── NotificationPreferenceController.php  # Crew notification settings
│   └── ProfileController.php   #   User profile management
├── Services/                   # Business logic
│   ├── EmailTemplateService.php    # Renders email templates with placeholders
│   ├── ImageSanitizer.php         # EXIF stripping & image re-encoding
│   └── HtmlPurifier.php           # Rich-text sanitisation
├── Jobs/
│   └── SendBookingRequestEmail.php  # Queued email notification
├── Mail/                       # 5 mailables
├── Policies/                   # 6 authorization policies
├── Traits/
│   └── HasEmailPlaceholders.php    # {placeholder} resolution
└── Http/Middleware/
    ├── ContentSecurityPolicy.php   # CSP headers on every response
    ├── CrossOriginIsolation.php    # COOP/COEP for admin pages
    └── ForcePasswordChange.php     # First-login password enforcement

database/
├── migrations/                 # Schema with FKs, indexes, unique constraints
├── factories/                  # 12 model factories for testing
└── seeders/                    # Roles, permissions & default data

routes/
├── web.php                    # All application routes with rate limiting
├── auth.php                   # Authentication routes
└── console.php                # Artisan commands

resources/views/
├── admin/                     # Admin panel (collections, equipment, media,
│                              #   projects, services, tiers, steps, settings,
│                              #   testimonials, users, docs, email-config,
│                              #   health-check)
├── auth/                      # Login, password reset, email verification
├── bookings/                  # Shared booking views
├── booking-requests/          # Booking request list & detail
├── components/                # Reusable Blade components (hero, cards,
│                              #   modals, toasts, buttons, inputs, badges,
│                              #   skeletons, process-section)
├── docs/                      # Documentation viewer
├── emails/                    # 5 email templates (booking, invoice, etc.)
├── errors/                    # 8 HTTP error pages
├── invoices/                  # Invoice list, create, show, PDF
├── layouts/                   # App, guest, and email layouts
├── manager/                   # Dashboard, create-booking, equipment
├── photographer/              # Calendar, requests, preferences, my-gear
├── portfolio/                 # Public project view
├── profile/                   # Profile edit & delete
├── contact-form.blade.php     # Landing page booking form
├── portfolio.blade.php        # Portfolio gallery
├── sitemap.blade.php          # XML sitemap template
└── welcome.blade.php          # Homepage

tests/
├── Unit/                      # 16 unit tests (models, services)
└── Feature/                   # 35 feature tests (auth, booking workflow,
                               #   invoices, media, equipment, settings,
                               #   security, validation, photographers)
```

---

## Database Schema

### Key Tables

| Table | Purpose |
|-------|---------|
| `users` | Users with soft deletes, email verification, Spatie roles |
| `booking_requests` | Public booking inquiries (status: pending/confirmed) |
| `bookings` | Confirmed bookings assigned to crew (rate, hours, total, status) |
| `booking_crew` | Pivot: many-to-many crew assignment on bookings |
| `booking_request_crew` | Pivot: crew assignment on booking requests |
| `invoices` | Generated invoices with soft deletes for audit trail |
| `media` | Portfolio images/videos/GIFs with projects |
| `projects` | Portfolio project groups with hero media |
| `services` | Homepage service offerings with JSON features |
| `testimonials` | Client reviews with ratings |
| `investment_tiers` | Pricing packages with JSON features |
| `process_steps` | "How it works" workflow steps (soft deletes) |
| `tags` | Media tagging taxonomy |
| `collections` | Curated media collections |
| `collection_media` | Pivot: media-to-collection with ordering |
| `media_tags` | Pivot: media-to-tag |
| `site_settings` | Key-value site configuration (hero, about, CTAs) |
| `email_configurations` | Encrypted email SMTP/from settings |
| `email_templates` | Editable email templates with placeholders |
| `notification_preferences` | Per-user notification toggles |
| `equipment_categories` | Equipment category hierarchy |
| `equipment_items` | Equipment inventory with condition, status, specs |
| `equipment_images` | Equipment photos |
| `equipment_checkouts` | Checkout/check-in records with booking linkage |

### Key Design Decisions

- **Foreign Key Constraints**: Cascade delete for data integrity
- **Indexes**: Performance optimisation on `photographer_id`, `status`, `project_id`, `created_by`, `email`
- **Unique Constraints**: Invoice numbers, tag slugs, collection slugs, step numbers (including soft-delete awareness)
- **Soft Deletes**: Users, bookings, invoices, equipment items, process steps
- **Encrypted Columns**: Email configuration values stored encrypted at rest
- **JSON Columns**: Features on services/investment tiers, specifications on equipment items

---

## Testing

Run the full test suite:

```bash
php artisan test --compact
```

Run a specific test file:

```bash
php artisan test --compact tests/Feature/BookingWorkflowTest.php
```

Run a specific test by name:

```bash
php artisan test --compact --filter=test_visitor_can_submit
```

### Key Test Areas

- User registration and email verification
- Booking state transitions (pending → confirmed → completed/cancelled)
- Crew assignment and double-booking prevention
- Invoice number uniqueness under concurrent requests
- Photographer access to own invoices only
- Authorization policies for all roles
- Rate limiting on sensitive endpoints
- Email XSS prevention in templates
- Image upload and WebP conversion
- Site settings image upload
- Equipment checkout/check-in workflow
- System health check endpoints
- Documentation access control

### Testing Conventions

- PHPUnit (not Pest)
- `RefreshDatabase` trait with in-memory SQLite
- Model factories for test data (12 factories)
- Auto-seeds `RolesAndPermissionsSeeder` in `setUp()`
- Covers happy paths, failure paths, and edge cases

---

## Security Features

| Feature | Details |
|---------|---------|
| Content-Security-Policy | Custom middleware on every response |
| Cross-Origin Isolation | SharedArrayBuffer support for admin email routes |
| Email Verification | Required before system access (admin creates users) |
| Forced Password Change | Redirect on first login until password updated |
| Image Sanitisation | EXIF stripping and re-encoding via Intervention |
| HTML Purification | Quill editor output sanitised via mews/purifier |
| XSS Prevention | All email template placeholders escaped |
| Encrypted Data | Email configs stored encrypted at rest |
| Rate Limiting | On all mutation endpoints (5-20 req/min) |
| Soft Deletes | Users, bookings, invoices, equipment items |
| Foreign Key Constraints | Referential integrity at database level |
| Input Validation | Monetary values capped at $999,999.99 |

---

## Configuration

### Email Templates

Admin panel at `/admin/email-config` allows customising:

- SMTP settings (host, port, encryption, username, password)
- From name and address
- Subject line and body templates with placeholders

Available placeholders: `{name}`, `{surname}`, `{email}`, `{phone}`, `{event_type}`, `{event_date}`, `{event_location}`, `{notes}`, `{site_name}`, `{year}`

### Site Settings

Admin panel at `/admin/site-config` for editing:

- Hero heading, subheading, and background image
- About section heading, content, and portrait image
- CTA section heading, content, button text/URL/link, and enable/disable toggle

### Queue Processing

For async email processing:

**Development** (database queue):
```bash
php artisan queue:work
```

**Production** (Redis queue):
```bash
php artisan queue:work redis --daemon
```

Monitor with supervisor:
```bash
sudo supervisorctl reread && sudo supervisorctl update
```

---

## License & Copyright

Copyright (c) 2026 Mpho. All rights reserved.

This software and its source code are proprietary. Unauthorized copying,
distribution, modification, or use of this file, via any medium,
is strictly prohibited.

This repository is published publicly solely for portfolio demonstration
and employment review purposes.
