# MediaWeb - Photography Booking & Invoicing CMS

A Laravel 12 content management system with photographer booking, invoicing, and receipt generation capabilities.

## Features

- **Portfolio Management**: Upload and manage images, videos, and GIFs organized by projects. Administrators and photographers can add assets; other roles have read‑only access.
- **Booking System**: Public contact form, manager scheduling, photographer calendar views
- **Invoicing**: Generate invoices with PDF download, payment tracking, and audit trail
- **Role-Based Access**: Admin, Manager, and Photographer roles with granular permissions (site narrative edits are admin‑only, media uploads allowed for photographers and admins)
- **Async Email**: Background queue processing for booking request notifications
- **Configurable Emails**: Admin can customize email templates with templating support
- **Email Verification**: Secure user registration with email verification requirement
- **Rate Limiting**: Built-in protection against spam and abuse on sensitive endpoints

## Tech Stack

- **Backend**: Laravel 12.x
- **Database**: SQLite (dev), PostgreSQL/MySQL (production)
- **Cache/Queue**: Database (dev), Redis (production)
- **PDF Generation**: DomPDF
- **Role Management**: Spatie Laravel Permission
- **Authentication**: Laravel Breeze with email verification
- **ORM**: Eloquent with soft deletes and encrypted attributes

## Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (included with PHP) or PostgreSQL 12+ / MySQL 8.0+
- Redis 6+ (production only)

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

### 6. Start Queue Worker (for async emails)

```bash
php artisan queue:work
```

In a separate terminal:

```bash
php artisan serve
```

Visit: <http://localhost:8000>

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

## API Documentation

### Public Endpoints

#### GET /portfolio

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

#### GET /portfolio/{project:slug}

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

#### POST /contact

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

### Authenticated Endpoints

#### GET /photographer/calendar

View photographer's calendar of assigned bookings.

#### GET /photographer/calendar/events

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

#### GET /invoices

List invoices created by logged-in user (managers see all; photographers see own).

#### GET /invoices/{invoice}

View invoice details.

#### GET /invoices/{invoice}/pdf

Download invoice as PDF.

#### POST /invoices/{booking} (rate limited: 10 per minute)

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

## Project Structure

```directory
app/
├── Models/              # Eloquent models
│   ├── User.php         # User with soft deletes
│   ├── Booking.php      # Booking with cancel() semantics
│   ├── Invoice.php      # Soft-deletable invoices
│   ├── Media.php        # Portfolio media
│   └── ...
├── Http/Controllers/    # Route handlers
├── Jobs/                # Queue jobs (SendBookingRequestEmail)
├── Policies/            # Authorization (including photographer invoice access)
└── Mail/                # Mailable classes with XSS prevention

database/
├── migrations/          # All schema changes with FK constraints & indexes
├── factories/           # Model factories for testing
└── seeders/             # Database seeders

routes/
└── web.php             # All routes with rate limiting on sensitive endpoints

resources/views/        # Blade templates by role
```

## Database Schema

### Key Tables

| Table | Purpose |
| ------- | --------- |
| `users` | Users with soft deletes, email verification |
| `booking_requests` | Public booking inquiries (status: pending/confirmed) |
| `bookings` | Confirmed bookings assigned to photographers (rate, hours, total) |
| `invoices` | Generated invoices with soft deletes for audit trail |
| `media` | Portfolio images/videos/GIFs with projects |
| `projects` | Portfolio project groups with hero media |
| `email_configurations` | Encrypted email templates and settings |

### Key Features

- **Foreign Key Constraints**: Cascade delete for data integrity (bookings → media, invoices soft-delete on booking delete)
- **Indexes**: Performance optimization on `photographer_id`, `status`, `project_id`, `created_by`
- **Unique Constraints**: Invoice numbers cannot be duplicated (race condition prevention)
- **Soft Deletes**: Users & Invoices preserves audit trail; Bookings are soft-deleted

## Testing

Run the test suite:

```bash
php artisan test

# With coverage reporting
php artisan test --coverage
```

### Key Test Areas

- User registration and email verification
- Booking state transitions (pending → confirmed → completed/cancelled)
- Invoice number uniqueness under concurrent requests
- Photographer's ability to view only their invoices
- Authorization policies for roles
- Rate limiting on sensitive endpoints
- Email XSS prevention in templates

## Security Features

✅ **Email Verification**: New accounts require verified email before access
✅ **XSS Prevention**: Email configuration params are escaped; booking data sanitized  
✅ **Encrypted Sensitive Data**: Email configs stored encrypted in database
✅ **Rate Limiting**: Throttle on invoice creation (10/min), config updates (5/min), public contact (5/min)
✅ **Authorization Policies**: Role-based access; photographers access own invoices only
✅ **Soft Deletes**: Audit trail preserved for users and invoices
✅ **Foreign Key Constraints**: Data integrity at database level
✅ **Input Validation**: Monetary values capped at reasonable limits (max $999,999.99)

## Configuration

### Email Templates

Admin panel at `/admin/email-config` allows customizing:

- Recipient email address
- Subject line with placeholders: `{name}`, `{surname}`, `{email}`, `{phone}`, `{event_type}`, etc.
- Email body template (escape-safe)

### Queue Processing

For async booking emails:

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
sudo apt install supervisor
# Create /etc/supervisor/conf.d/mediaweb-queue.conf with queue:work command
sudo supervisorctl reread && sudo supervisorctl update
```

## License

Apache 2.0 License - Open source

## Login Credentials for Testing

- **Admin**: <admin@mediaweb.local> / password
- **Manager**: <manager@mediaweb.local> / password
- **Photographer**: <photographer@mediaweb.local> / password

## Storage Link

```bash
php artisan storage:link
```
