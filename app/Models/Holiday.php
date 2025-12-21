<?php

// tafeld/app/Models/Holiday.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Holiday extends Model
{
    use HasFactory;
    use HasUlids;

    // Tabelle explizit benennen (ist zwar Standard, aber klarer)
    protected $table = 'holidays';

    // ULID Primary Key (string)
    protected $keyType = 'string';
    public $incrementing = false;

    // Mass Assignment: alles freigeben oder gezielt $fillable nutzen
    protected $guarded = [];

    // Casts (für Komfort)
    protected $casts = [
        'date'               => 'date',
        'is_static'          => 'boolean',
        'is_business_closed' => 'boolean',
    ];
}
