<?php

// tafeld/app/Services/AppSettingResolver.php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\AppUserSetting;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

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

    /**
     * Set or update a user-specific app setting.
     *
     * - writes into app_user_settings
     * - invalidates user cache
     * - logs change into tafeld.activity_log
     */
    public function setForUser(
        string $actorUserUlid,
        string $targetUserUlid,
        string $key,
        mixed $value
    ): void {
        $oldValue = AppUserSetting::query()
            ->where('user_ulid', $targetUserUlid)
            ->where('key', $key)
            ->value('value');

        AppUserSetting::updateOrCreate(
            [
                'user_ulid' => $targetUserUlid,
                'key'       => $key,
            ],
            [
                'value' => $value,
            ]
        );

        // cache invalidation (user scope only)
        $this->forget($key, $targetUserUlid);

        // activity log
        activity()
            ->causedBy(
                User::query()->where('ulid', $actorUserUlid)->first()
            )
            ->event('app_setting.updated')
            ->withProperties([
                'scope'            => 'user',
                'key'              => $key,
                'target_user_ulid' => $targetUserUlid,
                'old'              => $oldValue,
                'new'              => $value,
            ])
            ->log('App user setting updated');
    }

    /**
     * Set or update a global app setting.
     *
     * - writes into app_settings
     * - invalidates global cache
     * - logs change into tafeld.activity_log
     * - protected by Gate (admin / superadmin)
     */
    public function setGlobal(
        string $actorUserUlid,
        string $key,
        mixed $value
    ): void {
        $actor = User::query()->where('ulid', $actorUserUlid)->first();

        if (! $actor || Gate::forUser($actor)->denies('app-settings.set-global')) {
            throw new AuthorizationException('Not allowed to set global app settings.');
        }

        $oldValue = AppSetting::query()
            ->where('key', $key)
            ->value('value');

        AppSetting::updateOrCreate(
            [
                'key' => $key,
            ],
            [
                'value' => $value,
            ]
        );

        // cache invalidation (global only)
        $this->forget($key);

        // activity log
        activity()
            ->causedBy($actor)
            ->event('app_setting.updated')
            ->withProperties([
                'scope' => 'global',
                'key'   => $key,
                'old'   => $oldValue,
                'new'   => $value,
            ])
            ->log('App global setting updated');
    }
}
