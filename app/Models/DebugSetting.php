<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class DebugSetting extends Model
{
    use HasUlids;

    protected $fillable = [
        'enabled',
        'reset_on_run',
        'channels',
        'scope_key',
    ];

    protected $casts = [
        'enabled'      => 'boolean',
        'reset_on_run' => 'boolean',
        'channels'     => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        \Log::debug('[TRACE_MODEL] DebugSetting::boot()', [
            'table'  => (new self)->getTable(),
            'caller' => __METHOD__,
        ]);
    }
}
