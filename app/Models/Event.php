<?php

// tafeld/app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Event extends Model
{
    use HasUlids;

    /**
     * Table associated with this model.
     */
    protected $table = 'events';

    /**
     * Mass assignable fields.
     */
    protected $fillable = [
        'country_region_id',
        'event_type',
        'name_de',
        'name_en',
        'translit_de',
        'translit_en',
        'sort_key_de',
        'sort_key_en',
        'start_date',
        'end_date',
        'description',
    ];

    /**
     * Attribute casts.
     */
    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'event_type'  => \App\Enums\EventType::class,
    ];

    /**
     * Region to which the event belongs.
     */
    public function region()
    {
        return $this->belongsTo(\App\Models\CountryRegion::class, 'country_region_id');
    }

    /**
     * Returns true if this event is a vacation.
     */
    public function isVacation(): bool
    {
        return $this->event_type?->isVacation() ?? false;
    }
}
