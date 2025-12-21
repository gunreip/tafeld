<?php

// tafeld/app/Enums/DebugEvent.php

namespace App\Enums;

enum DebugEvent: string
{
    case Mount     = 'mount';
    case Hydrate   = 'hydrate';
    case Update    = 'update';
    case Validate  = 'validate';
    case Save      = 'save';
    case Delete    = 'delete';
    case Redirect  = 'redirect';
    case Exception = 'exception';
    case Abort     = 'abort';
}
