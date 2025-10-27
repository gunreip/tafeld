<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(fn($model) => $model->ulid = (string) Str::ulid());
    }

    // Verschlüsselung beim Setzen
    public function setEncryptedDataAttribute($value)
    {
        $this->attributes['encrypted_data'] = Crypt::encryptString(json_encode($value));
    }

    // Entschlüsselung beim Lesen
    public function getEncryptedDataAttribute($value)
    {
        return json_decode(Crypt::decryptString($value), true);
    }
}
