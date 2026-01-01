<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Livewire\Debug\Overview;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DebugOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_charts_have_data_attributes()
    {
        // Mock DebugReader to return deterministic data without touching the DB schema
        $mock = \Mockery::mock(\App\Support\TafeldDebug\DebugReader::class);
        $mock->shouldReceive('getScopes')->andReturn([]);

        $mock->shouldReceive('getLogCountLast24h')->andReturn(3);

        $mock->shouldReceive('getLogsByLevel')
            ->andReturn(['info' => 2, 'error' => 1]);

        $mock->shouldReceive('getLogsByScope')
            ->andReturn(['alpha' => 2, 'beta' => 1]);

        $mock->shouldReceive('getLatestLogs')->andReturn([
            ['time' => Carbon::now()->toDateTimeString(), 'level' => 'info', 'scope' => 'alpha', 'message' => 'm1'],
        ]);

        $this->app->instance(\App\Support\TafeldDebug\DebugReader::class, $mock);

        // Enable the debug UI gate and ensure the test user has access
        putenv('TAFELD_DEBUG_UI_ENABLED=true');

        $user = \App\Models\User::factory()->create();
        // If Spatie Role model is available, create/assign 'admin' role
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
            $user->assignRole('admin');
        }

        $response = $this->actingAs($user)->get('/debug');

        $response->assertStatus(200);

        // The canvas elements should have non-empty data-chart attributes (array JSON)
        $response->assertSee('id="chart-logs-level"', false);
        $response->assertSee('id="chart-logs-scope"', false);

        // The data-chart attribute should contain chart objects (we look for the label key in the JSON)
        $this->assertStringContainsString('"label"', $response->getContent());
    }
}
