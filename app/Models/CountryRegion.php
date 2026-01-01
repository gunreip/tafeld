<?php

// tafeld/app/Models/CountryRegion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryRegion extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'country_id',
        'iso_3166_2',
        'iso_3166_3',
        'name_de',
        'name_en',
        'fullname_de',
        'fullname_en',
        'translit_de',
        'translit_en',
        'sort_key_de',
        'sort_key_en',
    ];

    /**
     * Region gehört zu einem Land (z. B. DE → NRW).
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
