<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'avatar',
        'specialty',
        'bio',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    /* ================= Relationships ================= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* ================= Helpers (اختیاری ولی کاربردی) ================= */

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }
}
