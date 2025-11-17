<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Person extends Model
{
    use HasUlids, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
    ];

    protected $casts = [
        'first_name' => 'encrypted',
        'last_name'  => 'encrypted',
        'email'      => 'encrypted',
        'phone'      => 'encrypted',
    ];

    protected static $logAttributes = ['first_name', 'last_name', 'email', 'phone'];
    protected static $logName = 'persons';

    // getActivitylogOptions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('persons')
            ->logOnly(['first_name', 'last_name', 'email', 'phone'])
            ->setDescriptionForEvent(fn(string $eventName) => "Person {$eventName}");
    }

    // country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
