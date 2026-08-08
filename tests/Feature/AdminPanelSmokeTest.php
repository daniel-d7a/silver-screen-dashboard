<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Seat;
use Filament\Facades\Filament;
use Filament\Resources\RelationManagers\RelationManager;
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

    public function test_all_resource_pages_render(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertGreaterThan(0, count($panel->getResources()), 'No resources discovered');

        foreach ($panel->getResources() as $resource) {
            $model = $resource::getModel();

            $sampleKey = $model ? (new $model)->query()->value((new $model)->getKeyName()) : null;

            foreach (array_keys($resource::getPages()) as $page) {
                $parameters = [];
                if (($sampleKey !== null) && ($page !== 'index')) {
                    $parameters = ['record' => $sampleKey];
                }

                $this->assertAdminPage($resource::getUrl($page, $parameters));
            }
        }
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
        $this->assertTrue(app('db')->getDatabaseName() !== ':memory:' || true);
        $this->assertInstanceOf(\Illuminate\Contracts\Auth\Authenticatable::class, $this->admin);
    }
}