<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'subject_id',

        'title',
        'description',
        'level',        // taghviyati | konkur | olympiad
        'duration',     // minutes
        'start_at',

        'scope',        // free | classroom
        'is_published',
        'is_active',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_active'    => 'boolean',
        'start_at'     => 'datetime',
        'duration'     => 'integer',
    ];

    /* ================= Relationships ================= */

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    /* ================= Helpers / Scopes ================= */

    public function scopeActive($q)
    {
        return $q->where(function ($act) {
            $act->whereNull('is_active')->orWhere('is_active', true);
        });
    }

    public function scopePublished($q)
    {
        return $q->where(function ($pub) {
            $pub->whereNull('is_published')->orWhere('is_published', true);
        });
    }

    public function isFree(): bool
    {
        return $this->scope === 'free';
    }

    public function isClassroom(): bool
    {
        return $this->scope === 'classroom';
    }
}
