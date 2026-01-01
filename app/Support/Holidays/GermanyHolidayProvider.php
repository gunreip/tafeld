<?php

// tafeld/app/Support/Holidays/GermanyHolidayProvider.php

namespace App\Support\Holidays;

use DateTime;

class GermanyHolidayProvider implements HolidayProviderInterface
{
    public function generateForYear(int $year): array
    {
        $holidays = [];

        // Feste Feiertage
        foreach ($this->staticHolidays($year) as $h) {
            $holidays[] = $h;
        }

        // Bewegliche Feiertage
        $easter = $this->calculateEaster($year);

        foreach ($this->dynamicHolidays($year, $easter) as $h) {
            $holidays[] = $h;
        }

        return $holidays;
    }

    // ----------------------------------------------------
    // Feste Feiertage
    // ----------------------------------------------------
    protected function staticHolidays(int $year): array
    {
        $data = [
            ['01-01', 'Neujahr'],
            ['05-01', 'Tag der Arbeit'],
            ['10-03', 'Tag der Deutschen Einheit'],
            ['12-25', '1. Weihnachtsfeiertag'],
            ['12-26', '2. Weihnachtsfeiertag'],
        ];

        $result = [];

        foreach ($data as [$md, $name]) {
            $result[] = new HolidayDefinition(
                date: "{$year}-{$md}",
                name_de: $name,
                region_code: null,
                is_static: true,
                display_date: true,
                confession: null,
            );
        }

        return $result;
    }

    // ----------------------------------------------------
    // Bewegliche Feiertage
    // ----------------------------------------------------
    protected function dynamicHolidays(int $year, DateTime $easter): array
    {
        $result = [];

        $karfreitag       = $this->shift($easter, -2);
        $ostermontag      = $this->shift($easter, +1);
        $himmelfahrt      = $this->shift($easter, +39);
        $pfingstmontag    = $this->shift($easter, +50);
        $fronleichnam     = $this->shift($easter, +60);

        // Bundesweit
        $result[] = new HolidayDefinition($karfreitag,    'Karfreitag', null, false, true, 'christian');
        $result[] = new HolidayDefinition($ostermontag,   'Ostermontag', null, false, true, 'christian');
        $result[] = new HolidayDefinition($himmelfahrt,   'Christi Himmelfahrt', null, false, true, 'christian');
        $result[] = new HolidayDefinition($pfingstmontag, 'Pfingstmontag', null, false, true, 'christian');

        // Fronleichnam – regional
        $fronRegionen = ['BW', 'BY', 'HE', 'NW', 'RP', 'SL'];
        foreach ($fronRegionen as $r) {
            $result[] = new HolidayDefinition($fronleichnam, 'Fronleichnam', $r, false, true, 'catholic');
        }

        // Reformationstag – regional
        $refRegionen = ['BB', 'MV', 'SN', 'ST', 'TH', 'BE', 'HB', 'HH', 'NI', 'SH'];
        foreach ($refRegionen as $r) {
            $result[] = new HolidayDefinition("{$year}-10-31", 'Reformationstag', $r, false, true, 'protestant');
        }

        // Allerheiligen – regional
        $allerheiligenRegionen = ['BW', 'BY', 'NW', 'RP', 'SL'];
        foreach ($allerheiligenRegionen as $r) {
            $result[] = new HolidayDefinition("{$year}-11-01", 'Allerheiligen', $r, false, true, 'catholic');
        }

        // Neue religiöse Sonntage
        $ostersonntag    = $this->shift($easter, 0);
        $pfingstsonntag  = $this->shift($easter, +49);

        $result[] = new HolidayDefinition($ostersonntag,   'Ostersonntag', null, false, true, 'christian');
        $result[] = new HolidayDefinition($pfingstsonntag, 'Pfingstsonntag', null, false, true, 'christian');

        // Karnevalstage (Rheinland)
        $weiberfastnacht  = $this->shift($easter, -52);
        $rosenmontag      = $this->shift($easter, -48);
        $veilchendienstag = $this->shift($easter, -47);
        $aschermittwoch   = $this->shift($easter, -46);

        $result[] = new HolidayDefinition($weiberfastnacht,  'Weiberfastnacht', null, false, true, 'none');
        $result[] = new HolidayDefinition($rosenmontag,      'Rosenmontag', null, false, true, 'none');
        $result[] = new HolidayDefinition($veilchendienstag, 'Veilchendienstag', null, false, true, 'none');
        $result[] = new HolidayDefinition($aschermittwoch,   'Aschermittwoch', null, false, true, 'none');

        return $result;
    }

    // ----------------------------------------------------
    // Hilfsfunktionen
    // ----------------------------------------------------
    protected function calculateEaster(int $year): DateTime
    {
        // Klassischer Meeus/Jones/Butcher Algorithmus
        $a = $year % 19;
        $b = intdiv($year, 100);
        $c = $year % 100;
        $d = intdiv($b, 4);
        $e = $b % 4;
        $f = intdiv($b + 8, 25);
        $g = intdiv($b - $f + 1, 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intdiv($c, 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intdiv($a + 11 * $h + 22 * $l, 451);
        $month = intdiv($h + $l - 7 * $m + 114, 31);
        $day   = (($h + $l - 7 * $m + 114) % 31) + 1;

        return new DateTime("{$year}-{$month}-{$day}");
    }

    protected function shift(DateTime $base, int $days): string
    {
        $clone = clone $base;
        $clone->modify("{$days} days");
        return $clone->format('Y-m-d');
    }
}
