<?php

// tafeld/app/Support/Holidays/HolidayDefinition.php

namespace App\Support\Holidays;

class HolidayDefinition
{
    public function __construct(
        public string $date,          // Y-m-d
        public string $name_de,
        public ?string $region_code,  // null = bundesweit
        public bool $is_static,
        public bool $display_date = true,
        public ?string $confession = null,
    ) {}
}
