<?php

// tafeld/app/Models/DebugLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class DebugLog extends Model
{
    use HasUlids;

    /**
     * Debug-Logs sind append-only → keine updated_at-Spalte.
     */
    public $timestamps = false;

    protected $table = 'debug_logs';

    protected $fillable = [
        'run_id',
        'scope',
        'level',
        'message',
        'context',
        'user_id',
        'created_at',   // erlaubt manuelles Setzen
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
