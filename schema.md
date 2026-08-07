create table public.bookings (
  id uuid not null default gen_random_uuid (),
  user_id text not null,
  showtime_id uuid not null,
  seat_ids uuid[] not null,
  status text not null default 'pending'::text,
  idempotency_key text not null,
  stripe_payment_intent_id text null,
  created_at timestamp with time zone null default now(),
  confirmed_at timestamp with time zone null,
  cancelled_at timestamp with time zone null,
  constraint bookings_pkey primary key (id),
  constraint bookings_idempotency_key_key unique (idempotency_key),
  constraint bookings_showtime_id_fkey foreign KEY (showtime_id) references showtimes (id),
  constraint bookings_status_check check (
    (
      status = any (
        array[
          'pending'::text,
          'confirmed'::text,
          'cancelled'::text
        ]
      )
    )
  )
) TABLESPACE pg_default;

create index IF not exists bookings_user_id_idx on public.bookings using btree (user_id) TABLESPACE pg_default;


----------------------

create table public.bookmarks (
  user_id text not null,
  tmdb_id integer not null,
  created_at timestamp with time zone not null default now(),
  constraint bookmarks_pkey primary key (user_id, tmdb_id),
  constraint bookmarks_tmdb_id_fkey foreign KEY (tmdb_id) references films (tmdb_id) on delete CASCADE,
  constraint bookmarks_user_id_fkey foreign KEY (user_id) references profiles (id)
) TABLESPACE pg_default;

create index IF not exists idx_bookmarks_tmdb_id on public.bookmarks using btree (tmdb_id) TABLESPACE pg_default;

create index IF not exists idx_bookmarks_user_id on public.bookmarks using btree (user_id) TABLESPACE pg_default;


----------------------

create table public.films (
  tmdb_id integer not null,
  title text not null,
  description text null,
  poster_url text null,
  release_date date null,
  rating double precision null,
  runtime integer null,
  genres jsonb not null default '[]'::jsonb,
  created_at timestamp with time zone not null default now(),
  updated_at timestamp with time zone not null default now(),
  trailer text null,
  starring text null,
  constraint films_pkey primary key (tmdb_id)
) TABLESPACE pg_default;

----------------------

create table public.profiles (
  id text not null,
  name text not null default ''::text,
  email text not null,
  role text not null default 'user'::text,
  created_at timestamp without time zone not null default now(),
  last_login timestamp without time zone null,
  constraint profiles_pkey primary key (id),
  constraint profiles_role_check check ((role = any (array['user'::text, 'admin'::text])))
) TABLESPACE pg_default;

----------------------

create table public.seats (
  id uuid not null default gen_random_uuid (),
  showtime_id uuid not null,
  seat_label text not null,
  status text not null default 'available'::text,
  held_by text null,
  held_until timestamp with time zone null,
  constraint seats_pkey primary key (id),
  constraint seats_showtime_id_seat_label_key unique (showtime_id, seat_label),
  constraint seats_showtime_id_fkey foreign KEY (showtime_id) references showtimes (id) on delete CASCADE,
  constraint seats_status_check check (
    (
      status = any (
        array['available'::text, 'held'::text, 'booked'::text]
      )
    )
  )
) TABLESPACE pg_default;

create index IF not exists seats_showtime_id_status_idx on public.seats using btree (showtime_id, status) TABLESPACE pg_default;

----------------------

create table public.showtimes (
  id uuid not null default gen_random_uuid (),
  tmdb_movie_id integer not null,
  screen text not null,
  starts_at timestamp with time zone not null,
  created_at timestamp with time zone null default now(),
  tier text not null default 'Standard'::text,
  price numeric(10, 2) not null default 0,
  constraint showtimes_pkey primary key (id),
  constraint showtimes_tmdb_movie_id_fkey foreign KEY (tmdb_movie_id) references films (tmdb_id),
  constraint showtimes_tier_check check (
    (
      tier = any (array['Gold'::text, 'Standard'::text])
    )
  )
) TABLESPACE pg_default;