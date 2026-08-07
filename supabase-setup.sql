-- Run this in the Supabase SQL Editor to add the two tables the dashboard needs.
-- (All other tables already exist in the schema.)

-- 1) Filament auth table
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

-- 2) Reusable showtime templates (screen + time, no movie/date)
create table if not exists public.default_showtimes (
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