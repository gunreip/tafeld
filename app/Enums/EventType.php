<?php

// tafeld/app/Enums/EventType.php

namespace App\Enums;

enum EventType: string
{
    case School      = 'school';
    case Business    = 'business';
    case Supplier    = 'supplier';
    case Personal    = 'personal';
    case Maintenance = 'maintenance';
    case Other       = 'other';

    /**
     * Returns true if this type represents any kind of vacation-like event.
     */
    public function isVacation(): bool
    {
        return in_array($this, [
            self::School,
            self::Business,
        ], true);
    }
}
