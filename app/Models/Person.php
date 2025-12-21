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
        'first_name_sort_key',
        'first_name_translit',
        'last_name_sort_key',
        'last_name_translit',

        'street',
        'house_number',
        'country_id',
        'zipcode',
        'city',

        'nationality_id',

        'birthdate',
        'employment_start',
        'employment_end',

        'mobile_country_id',
        'mobile_area',
        'mobile_number',

        'phone_country_id',
        'phone_area',
        'phone_number',

        'email_local',
        'email_domain',
    ];

    protected $casts = [
        'mobile_number' => 'encrypted',
        'phone_number'  => 'encrypted',
    ];

    protected static $logAttributes = [
        'first_name',
        'last_name',
        'email_local',
        'email_domain',
        'mobile_number',
        'phone_number',
    ];

    protected static $logName = 'persons';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('persons')
            ->logOnly([
                'first_name',
                'last_name',
                'email_local',
                'email_domain',
                'mobile_number',
                'phone_number',
            ])
            ->setDescriptionForEvent(fn(string $eventName) => "Person {$eventName}");
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }

    public function mobileCountry()
    {
        return $this->belongsTo(Country::class, 'mobile_country_id');
    }

    public function phoneCountry()
    {
        return $this->belongsTo(Country::class, 'phone_country_id');
    }

    protected static function booted()
    {
        static::saving(function ($person) {
            if ($person->isDirty('first_name')) {
                $original = $person->first_name;
                $person->first_name_sort_key = self::makeSortKeyDe($original);
                $person->first_name_translit  = self::makeTranslit($original);
            }

            if ($person->isDirty('last_name')) {
                $original = $person->last_name;
                $person->last_name_sort_key = self::makeSortKeyDe($original);
                $person->last_name_translit  = self::makeTranslit($original);
            }
        });
    }

    public static function makeSortKeyDe(string $value): string
    {
        if (!$value) return '';

        $v = mb_strtolower($value, 'UTF-8');

        $v = str_replace(
            ['ä', 'ö', 'ü', 'ß', 'Ä', 'Ö', 'Ü'],
            ['ae', 'oe', 'ue', 'ss', 'ae', 'oe', 'ue'],
            $v
        );

        $v = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $v);

        $v = preg_replace('/[^a-z0-9]/', '', $v);

        return $v ?: '';
    }

    public static function makeTranslit(string $value): string
    {
        if (!$value) return '';

        if (class_exists('\Transliterator')) {
            $t = \Transliterator::create('Any-Latin; Latin-ASCII');
            if ($t) {
                return strtolower($t->transliterate($value));
            }
        }

        return strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value)) ?: '';
    }
}
