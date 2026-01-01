<?php

// tafeld/app/Models/User.php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;     // ✅ hier
use Spatie\Permission\Traits\HasRoles;
// use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    // use TwoFactorAuthenticatable;

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ulid',     // ULID muss persistierbar sein
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Automatische ULID-Erzeugung beim Erstellen eines Users.
     */
    protected static function booted()
    {
        static::creating(function ($user) {

            if (empty($user->ulid)) {
                $generated = (string) \Illuminate\Support\Str::ulid();
                $user->ulid = $generated;
            }
        });
    }
}
