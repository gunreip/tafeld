<?php

// tafeld/app/Services/AppSettingResolver.php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\AppUserSetting;
use Illuminate\Support\Facades\Cache;

class AppSettingResolver
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(
            "app_setting:global:{$key}",
            fn() => AppSetting::query()
                ->where('key', $key)
                ->value('value') ?? $default
        );
    }

    public function getForUser(?string $userUlid, string $key, mixed $default = null): mixed
    {
        if ($userUlid !== null) {
            $userValue = Cache::rememberForever(
                "app_setting:user:{$userUlid}:{$key}",
                fn() => AppUserSetting::query()
                    ->where('user_ulid', $userUlid)
                    ->where('key', $key)
                    ->value('value')
            );

            if ($userValue !== null) {
                return $userValue;
            }
        }

        return $this->get($key, $default);
    }

    public function forget(string $key, ?string $userUlid = null): void
    {
        Cache::forget("app_setting:global:{$key}");

        if ($userUlid !== null) {
            Cache::forget("app_setting:user:{$userUlid}:{$key}");
        }
    }
}
