<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Otp extends Model
{
    use HasFactory;

    protected $table = "otps";

    // ✅ چون uuid هست
    public $incrementing = false;
    protected $keyType = "string";

    protected $fillable = [
        "id",
        "phone",
        "code",
        "token",
        "type",
        "attempts",
        "verified",
        "expires_at",
        "verified_at",
    ];

    protected $casts = [
        "expires_at"  => "datetime",
        "verified_at" => "datetime",
    ];

    // ✅ auto fill id + type
    protected static function booted()
    {
        static::creating(function ($otp) {
            if (empty($otp->id)) {
                $otp->id = (string) Str::uuid();
            }

            if (empty($otp->type)) {
                $otp->type = "login";
            }

            if (!isset($otp->attempts)) {
                $otp->attempts = 0;
            }

            if (!isset($otp->verified)) {
                $otp->verified = false;
            }
        });
    }
}
