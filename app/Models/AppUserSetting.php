<?php

// tafeld/app/Models/AppUserSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class AppUserSetting extends Model
{
    use HasUlids;

    protected $primaryKey = 'ulid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_ulid',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];
}
