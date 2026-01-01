<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class DebugOverviewIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_charts_populate_from_debug_logs_table()
    {
        // Enable the debug UI gate
        putenv('TAFELD_DEBUG_UI_ENABLED=true');

        $user = \App\Models\User::factory()->create();
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
            $user->assignRole('admin');
        }

        // Insert debug_logs entries
        $now = now();

        \DB::table('debug_logs')->insert([
            [
                'id' => (string) Str::ulid(),
                'scope' => 'smoke.env',
                'channel' => 'tafeld-debug',
                'level' => 'debug',
                'message' => 'ENV gate test',
                'context' => json_encode(['run_id' => 'R1']),
                'run_id' => (string) Str::ulid(),
                'user_id' => null,
                'created_at' => $now->toDateTimeString(),
            ],
            [
                'id' => (string) Str::ulid(),
                'scope' => 'smoke.global',
                'channel' => 'tafeld-debug',
                'level' => 'debug',
                'message' => 'Global enabled',
                'context' => json_encode([]),
                'run_id' => (string) Str::ulid(),
                'user_id' => null,
                'created_at' => $now->subMinutes(5)->toDateTimeString(),
            ],
        ]);

        $response = $this->actingAs($user)->get('/debug');

        $response->assertStatus(200);
        $response->assertSee('id="chart-logs-level"', false);
        $response->assertSee('id="chart-logs-scope"', false);
        $this->assertStringContainsString('smoke.env', $response->getContent());
        $this->assertStringContainsString('smoke.global', $response->getContent());
    }
}
