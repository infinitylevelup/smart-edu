<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',

        // legacy JSON answers
        'answers',

        // status/time
        'status',
        'started_at',
        'submitted_at',
        'finished_at',

        // scoring
        'score',
        'percent',
        'score_total',
        'score_obtained',

        // meta
        'meta',
    ];

    protected $casts = [
        // ستون legacy
        'answers'        => 'array',
        'meta'           => 'array',

        'started_at'     => 'datetime',
        'submitted_at'   => 'datetime',
        'finished_at'    => 'datetime',

        'score'          => 'decimal:2',
        'percent'        => 'decimal:2',

        'score_total'    => 'integer',
        'score_obtained' => 'integer',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * ⚠️ توجه:
     * این رابطه با ستون JSON هم‌نام است (answers).
     * موقع eager load مشکلی نیست،
     * برای دسترسی به ستون legacy از $attempt->answers استفاده می‌شود،
     * برای دسترسی به رابطه از $attempt->getRelation('answers') یا $attempt->answers()->... استفاده کن.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    public function isFinal(): bool
    {
        return !is_null($this->finished_at)
            || in_array($this->status, ['submitted', 'graded'])
            || !is_null($this->submitted_at);
    }
}
