<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\DefaultShowtime;
use App\Models\Film;
use App\Models\Seat;
use App\Models\Showtime;
use Tests\TestCase;

class AdminPanelSmokeTest extends TestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = AdminUser::query()->firstOrFail();
    }

    protected function assertAdminPage(string $url): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get($url)
            ->assertStatus(200);
    }

    public function test_panel_pages_render(): void
    {
        $this->assertAdminPage('/admin');
        $this->assertAdminPage('/admin/films');
        $this->assertAdminPage('/admin/showtimes');
        $this->assertAdminPage('/admin/default-showtimes');
        $this->assertAdminPage('/admin/bookings');
        $this->assertAdminPage('/admin/seats');
        $this->assertAdminPage('/admin/profiles');
        $this->assertAdminPage('/admin/bookmarks');
    }

    public function test_every_showtime_has_48_unique_seats(): void
    {
        $counts = Seat::query()
            ->select('showtime_id')
            ->groupBy('showtime_id')
            ->get('showtime_id');

        $this->assertGreaterThan(0, $counts->count(), 'No showtimes with seats found');

        foreach ($counts->pluck('showtime_id') as $showtimeId) {
            $this->assertSame(
                48,
                Seat::query()->where('showtime_id', $showtimeId)->count(),
                "Showtime {$showtimeId} should have 48 seats",
            );
            $this->assertSame(
                48,
                Seat::query()->where('showtime_id', $showtimeId)->distinct()->count('seat_label'),
                "Showtime {$showtimeId} should have 48 distinct labels",
            );
        }
    }

    public function test_core_tables_are_reachable(): void
    {
        $this->assertTrue((new Film())->getTable() === 'films');
        $this->assertTrue((new Showtime())->getTable() === 'showtimes');
        $this->assertTrue((new DefaultShowtime())->getTable() === 'default_showtimes');
        $this->assertInstanceOf(\Illuminate\Contracts\Auth\Authenticatable::class, $this->admin);
    }
}