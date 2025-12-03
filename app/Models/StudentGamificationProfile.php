<?php
// app/Models/StudentGamificationProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGamificationProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'total_xp',
        'level',
        'current_streak',
        'longest_streak',
        'last_activity_at',
    ];

    protected $casts = [
        'total_xp'         => 'integer',
        'level'            => 'integer',
        'current_streak'   => 'integer',
        'longest_streak'   => 'integer',
        'last_activity_at' => 'datetime',
    ];

    /* ================= Relationships ================= */

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /* ================= Helpers (اختیاری ولی مفید) ================= */

    public function addXp(int $xp): void
    {
        $this->increment('total_xp', $xp);
    }

    public function touchActivity(): void
    {
        $this->last_activity_at = now();
        $this->save();
    }
}
