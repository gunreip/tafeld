<?php

// tafeld/app/Support/Holidays/HolidayProviderInterface.php

namespace App\Support\Holidays;

interface HolidayProviderInterface
{
    /**
     * @return HolidayDefinition[]
     */
    public function generateForYear(int $year): array;
}
