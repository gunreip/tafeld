<?php

// tafeld/app/Models/Country.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Str;

class Country extends Model
{
    use HasUlids;

    protected $fillable = [
        'iso_3166_2',
        'iso_3166_3',
        'name_en',
        'name_de',
        'region',
        'subregion',
        'currency_code',
        'phone_code',
        // Sortier- & Transkriptionsspalten (sprachspezifisch)
        'sort_key_en',
        'sort_key_de',
        'translit_en',
        'translit_de',
    ];

    /*
    |--------------------------------------------------------------------------
    | Automatische Generierung von sort_key_de + translit
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::saving(function ($country) {
            if ($country->isDirty('name_de') || $country->isDirty('name_en')) {

                // sort_key_de erzeugen
                $country->sort_key_de = self::makeSortKeyDe($country->name_de ?? '');

                // transliterations (sprachspezifisch)
                $country->translit_de = self::makeTranslit($country->name_de ?? '');
                $country->translit_en = self::makeTranslit($country->name_en ?? '');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | sort_key_de
    | - Normalisiert für alphabetische Sortierung (deutsche Regeln)
    |--------------------------------------------------------------------------
    */
    public static function makeSortKeyDe(string $value): string
    {
        if (!$value) return '';

        $v = Str::lower($value);

        // Deutsche Sonderzeichen ersetzen
        $v = str_replace(
            ['ä', 'ö', 'ü', 'ß', 'Ä', 'Ö', 'Ü'],
            ['ae', 'oe', 'ue', 'ss', 'ae', 'oe', 'ue'],
            $v
        );

        // Diakritika entfernen
        $v = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $v);

        // Nur a-z + 0-9 behalten
        $v = preg_replace('/[^a-z0-9]/', '', $v);

        return $v ?: '';
    }

    /*
    |--------------------------------------------------------------------------
    | translit (Any → Latin)
    | - Für Suchfunktion, menschenlesbar
    |--------------------------------------------------------------------------
    */
    public static function makeTranslit(string $value): string
    {
        if (!$value) return '';

        // Intl Transliterator (falls installiert)
        if (class_exists('\Transliterator')) {
            $t = \Transliterator::create('Any-Latin; Latin-ASCII');
            if ($t) {
                return Str::lower($t->transliterate($value));
            }
        }

        // Rückfalllösung
        return Str::lower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value)) ?: '';
    }
}
