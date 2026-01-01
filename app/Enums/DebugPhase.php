<?php

// tafeld/app/Enums/DebugPhase.php

namespace App\Enums;

enum DebugPhase: string
{
    case Start = 'start';
    case Step  = 'step';
    case End   = 'end';
    case Skip  = 'skip';
    case Fail  = 'fail';
    case Auto  = 'auto';
}
