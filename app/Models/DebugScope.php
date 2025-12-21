<?php

// tafeld/app/Models/DebugScope.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class DebugScope extends Model
{
    use HasUlids;

    protected $fillable = [
        'scope_key',
        'enabled',
        'runtime_killable',
        'file_path',
        'options',
    ];

    protected $casts = [
        'enabled'           => 'boolean',
        'runtime_killable'  => 'boolean',
        'options'           => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        \Log::debug('[TRACE_MODEL] DebugScope::boot()', [
            'table'  => (new self)->getTable(),
            'caller' => __METHOD__,
        ]);
    }
}
