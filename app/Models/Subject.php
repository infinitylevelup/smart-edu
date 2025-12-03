<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'grade_level',
        'description',
    ];

    protected $casts = [
        'grade_level' => 'string', // اگر تو DB enum/string هست، همین خوبه
    ];

    /* ================= Relationships ================= */

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'subject_id');
    }
}
