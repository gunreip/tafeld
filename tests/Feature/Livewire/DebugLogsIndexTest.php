<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Debug\Logs\Index as LogsIndex;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DebugLogsIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function seedLogs(array $rows)
    {
        foreach ($rows as $row) {
            $defaults = [
                'id' => (string) Str::ulid(),
                'created_at' => Carbon::now(),
                'message' => 'msg',
                'context' => null,
                'run_id' => (string) Str::ulid(),
            ];

            DB::table('debug_logs')->insert(array_merge($defaults, $row));
        }
    }

    public function test_scope_filter_filters_results()
    {
        $this->seedLogs([
            ['scope' => 'alpha.scope', 'level' => 'info', 'message' => 'alpha-msg'],
            ['scope' => 'beta.scope', 'level' => 'info', 'message' => 'beta-msg'],
        ]);

        Livewire::test(LogsIndex::class)
            ->set('scope', 'alpha')
            ->assertSee('alpha-msg')
            ->assertDontSee('beta-msg');
    }

    public function test_level_filter_filters_results()
    {
        $this->seedLogs([
            ['scope' => 'scope1', 'level' => 'error', 'message' => 'err-msg'],
            ['scope' => 'scope1', 'level' => 'info', 'message' => 'inf-msg'],
        ]);

        Livewire::test(LogsIndex::class)
            ->set('level', 'error')
            ->assertSee('err-msg')
            ->assertDontSee('inf-msg');
    }

    public function test_run_id_filter_filters_results()
    {
        $this->seedLogs([
            ['scope' => 's', 'level' => 'info', 'run_id' => 'RID-123', 'message' => 'rid-msg'],
            ['scope' => 's', 'level' => 'info', 'run_id' => 'RID-456', 'message' => 'other-msg'],
        ]);

        Livewire::test(LogsIndex::class)
            ->set('run_id', 'RID-123')
            ->assertSee('rid-msg')
            ->assertDontSee('other-msg');
    }

    public function test_date_range_filters_results()
    {
        $yesterday = Carbon::now()->subDays(1)->startOfDay()->toDateTimeString();
        $today = Carbon::now()->startOfDay()->toDateTimeString();

        DB::table('debug_logs')->insert([
            'id' => (string) Str::ulid(),
            'scope' => 's',
            'level' => 'info',
            'run_id' => (string) Str::ulid(),
            'message' => 'old-msg',
            'created_at' => Carbon::now()->subDays(2)
        ]);

        DB::table('debug_logs')->insert([
            'id' => (string) Str::ulid(),
            'scope' => 's',
            'level' => 'info',
            'run_id' => (string) Str::ulid(),
            'message' => 'new-msg',
            'created_at' => Carbon::now()
        ]);

        Livewire::test(LogsIndex::class)
            ->set('from', Carbon::now()->subDays(1)->format('d-m-Y'))
            ->set('to', Carbon::now()->addDays(1)->format('d-m-Y'))
            ->assertSee('new-msg')
            ->assertDontSee('old-msg');
    }
}
