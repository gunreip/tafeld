<?php

// tafeld/app/Enums/CountryWorkArea.php

namespace App\Enums;

enum CountryWorkArea: string
{
    case EU_EEA_SWISS = 'EU_EEA_SWISS';
    case PRIVILEGED = 'PRIVILEGED';
    case THIRD_COUNTRY = 'THIRD_COUNTRY';
}
