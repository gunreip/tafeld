<?php

// tafeld/app/Models/UiPreference.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class UiPreference extends Model
{
    use HasUlids;

    protected $table = 'ui_preferences';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'scope',
        'key',
        'value',
    ];

    /**
     * FK → users.ulid
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'ulid'
        );
    }
}
