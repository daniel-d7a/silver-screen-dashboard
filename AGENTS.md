# Silver Screen Admin Dashboard

## Project Overview

Admin dashboard for a cinema ticket booking app. Built with Laravel + Filament, connected to an existing Supabase PostgreSQL database.

## Tech Stack

- **Framework:** Laravel 13.x
- **Admin Panel:** Filament 5.x
- **Database:** Supabase (PostgreSQL) — schema managed externally, no Laravel migrations
- **Auth:** Separate `admin_users` table (not Clerk — Clerk handles app auth)
- **Deployment:** Docker (nginx + php-fpm)

## Key Conventions

- **No Laravel migrations.** The database schema lives in Supabase. Eloquent models point to existing tables via `$table` and `$primaryKey`.
- **UUID primary keys.** All tables use `uuid` PKs (except `profiles` which uses `text` for Clerk IDs). Models must set `public $keyType = 'string'` and `public $incrementing = false`.
- **No timestamps on some models.** Some tables have `created_at` but no `updated_at`. Check each table before adding `$timestamps`.
- **Read-only resources** use `Filament\Resources\Pages\ListRecords` without create/edit actions.
- **Seat auto-generation** happens in `Showtime::booted()` via `observe()` or inline `created` event — 48 seats (A1–F8) per showtime.

## Commands

```bash
# Run locally
php artisan serve

# Filament panel at
http://localhost:8000/admin

# Create admin user
php artisan make:filament-user

# Docker build & run
docker build -t silver-screen-admin .
docker run -p 8080:80 silver-screen-admin
```

## Database Tables

| Table | Managed by |
|-------|-----------|
| `films` | Dashboard (full CRUD) |
| `showtimes` | Dashboard (CRUD + bulk create) |
| `seats` | Auto-generated on showtime creation |
| `bookings` | Dashboard (read-only) |
| `bookmarks` | Dashboard (read-only) |
| `profiles` | Dashboard (read-only, Clerk-managed) |
| `admin_users` | Dashboard (auth) |
| `default_showtimes` | Dashboard (CRUD — showtime templates) |

## Seat Hold Logic

- `held_until` on `seats` table = timestamp when hold expires
- Hold duration: 15 minutes
- Checkout must reject if `held_until < now`
- Available seats: `held_until IS NULL OR held_until < now`
