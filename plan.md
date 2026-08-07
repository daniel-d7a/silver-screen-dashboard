# Silver Screen Admin Dashboard — Implementation Plan

## Prerequisites (Manual — Supabase Dashboard)

Two new tables need to be created directly in Supabase (no Laravel migration):

**`admin_users`** — Filament auth table:
```sql
create table public.admin_users (
  id uuid not null default gen_random_uuid(),
  name text not null,
  email text not null,
  password text not null,
  created_at timestamp with time zone null default now(),
  updated_at timestamp with time zone null default now(),
  constraint admin_users_pkey primary key (id),
  constraint admin_users_email_key unique (email)
);
```

**`default_showtimes`** — reusable time templates per screen:
```sql
create table public.default_showtimes (
  id uuid not null default gen_random_uuid(),
  screen text not null,
  starts_at time not null,
  tier text not null default 'Standard'::text,
  price numeric(10, 2) not null default 0,
  created_at timestamp with time zone null default now(),
  constraint default_showtimes_pkey primary key (id),
  constraint default_showtimes_tier_check check (
    tier = any (array['Gold'::text, 'Standard'::text])
  )
);
```

## Phase 1: Project Setup

1. `composer create-project laravel/laravel silver-screen-admin`
2. Install Filament: `composer require filament/filament` → `php artisan filament:install --panels`
3. Configure `.env` for Supabase PostgreSQL (`DB_CONNECTION=pgsql`, host `db.<ref>.supabase.co`)
4. Configure Filament auth guard to use `admin_users` table via `AdminUser` model implementing `Authenticatable`
5. Seed an initial admin user via `php artisan make:filament-user`

## Phase 2: Eloquent Models (no migrations — point to existing tables)

| Model | Table | Key fields |
|-------|-------|------------|
| `Film` | `films` | `tmdb_id` (PK), title, description, poster_url, etc. |
| `Showtime` | `showtimes` | `id` (uuid PK), `tmdb_movie_id` (FK→films), screen, starts_at, tier, price |
| `Seat` | `seats` | `id` (uuid PK), `showtime_id` (FK→showtimes), seat_label, status, held_by, held_until |
| `Booking` | `bookings` | `id` (uuid PK), `user_id`, `showtime_id`, `seat_ids`, status, idempotency_key |
| `Bookmark` | `bookmarks` | composite PK (user_id, tmdb_id) |
| `Profile` | `profiles` | `id` (text PK — Clerk user ID), name, email, role |
| `DefaultShowtime` | `default_showtimes` | `id` (uuid PK), screen, starts_at (time), tier, price |
| `AdminUser` | `admin_users` | `id` (uuid PK), name, email, password |

Relationships:
- `Film` hasMany `Showtime`
- `Showtime` belongsTo `Film` (via `tmdb_movie_id`)
- `Showtime` hasMany `Seat`
- `Seat` belongsTo `Showtime`
- `Booking` belongsTo `Showtime`

## Phase 3: Filament Resources

**FilmResource** — full CRUD
- Table: title, tmdb_id, rating, runtime, release_date, genres (json badge)
- Form: all fields including poster_url, trailer, starring, genres
- Relation manager: Showtimes (nested on Film edit/view page)

**ShowtimeResource** — list/view, created via relation or bulk action
- Table: screen, starts_at, tier, price, seats_available (computed)
- Form: screen, starts_at, tier, price (tmdb_movie_id set by context)
- On create: auto-generate 48 seats (A1-A8 through F1-F8)

**DefaultShowtimeResource** — full CRUD
- Table: screen, starts_at (time), tier, price
- Form: screen (text/dropdown), starts_at (time picker), tier (select), price

**BookingResource** — view only (read-only)
- Table: user_id, showtime (relation), seat_ids, status, created_at, confirmed_at
- No create/edit actions

**SeatResource** — view only (read-only)
- Table: seat_label, status, held_by, held_until, showtime (relation)
- Filters: status (available/held/booked)

**ProfileResource** — view only (read-only)
- Table: id, name, email, role, last_login

**BookmarkResource** — view only (read-only)
- Table: user_id, tmdb_id, film (relation), created_at

## Phase 4: Bulk Showtime Creation (Custom Page/Action)

Custom Filament page or action on FilmResource:

1. Select a movie
2. Pick a date
3. Show a multi-select of available `default_showtimes` records
4. On submit: for each selected default showtime, create a `showtime` record with:
   - `tmdb_movie_id` = selected film
   - `screen` = from default_showtime
   - `starts_at` = date + default_showtime.starts_at
   - `tier` / `price` = from default_showtime
   - Auto-generate 48 seats

This will be a custom Filament Action (modal form) on the FilmResource view/edit page.

## Phase 5: Seat Auto-Generation

On `Showtime::created` event (via Eloquent `booted()` method):
- Generate 48 seats: rows A-F, columns 1-8
- Labels: A1, A2, ... A8, B1, B2, ... F8
- Status: `available`

## Phase 6: Docker

**Dockerfile** (multi-stage, PHP 8.3 + nginx):
- Stage 1: Build assets with Node
- Stage 2: PHP-FPM with dependencies
- Nginx serving public/ + PHP-FPM
- Exposes port 80

**docker-compose.yml**:
- Single `admin` service
- Environment variables for Supabase DB connection
- Volume for `.env`

## File Structure

```
silver-screen-admin/
├── app/
│   ├── Models/
│   │   ├── Film.php
│   │   ├── Showtime.php
│   │   ├── Seat.php
│   │   ├── Booking.php
│   │   ├── Bookmark.php
│   │   ├── Profile.php
│   │   ├── DefaultShowtime.php
│   │   └── AdminUser.php
│   ├── Filament/
│   │   └── Panels/
│   │       └── Admin/
│   │           ├── Resources/
│   │           │   ├── FilmResource.php
│   │           │   ├── ShowtimeResource.php
│   │           │   ├── DefaultShowtimeResource.php
│   │           │   ├── BookingResource.php
│   │           │   ├── SeatResource.php
│   │           │   ├── ProfileResource.php
│   │           │   └── BookmarkResource.php
│   │           └── Pages/
│   │               └── BulkShowtimeCreation.php
│   └── Providers/
│       └── Filament/AdminPanelProvider.php
├── Dockerfile
├── docker-compose.yml
├── AGENTS.md
└── plan.md
```

## Ticket Hold Logic (for reference — app-side, not dashboard)

- `seats.held_until` = now + 15 minutes when a user initiates booking
- Checkout fails if `held_until < now` (hold expired)
- App only shows seats where `held_until IS NULL OR held_until < now` (available)
