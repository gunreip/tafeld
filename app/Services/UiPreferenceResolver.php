<?php

// tafeld/app/Services/UiPreferenceResolver.php

namespace App\Services;

use App\Models\UiPreference;

class UiPreferenceResolver
{
    /**
     * Resolve a UI preference value.
     *
     * Resolution order:
     * 1. User-specific preference
     * 2. Global default (user_id = NULL)
     * 3. Provided fallback
     */
    public function resolve(
        string $scope,
        string $key,
        ?string $userUlid = null,
        ?string $fallback = null
    ): ?string {
        // 1) User-specific override
        if ($userUlid !== null) {
            $userPref = UiPreference::query()
                ->where('user_id', $userUlid)
                ->where('scope', $scope)
                ->where('key', $key)
                ->value('value');

            if ($userPref !== null) {
                return $userPref;
            }
        }

        // 2) Global default
        $globalPref = UiPreference::query()
            ->whereNull('user_id')
            ->where('scope', $scope)
            ->where('key', $key)
            ->value('value');

        if ($globalPref !== null) {
            return $globalPref;
        }

        // 3) Hard fallback
        return $fallback;
    }
}
