<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    /**
     * ✅ DB جدید: exam_attempts
     */
    protected $table = 'exam_attempts';

    /**
     * Fillable
     * ------------------------------------------------------------
     * ✅ شامل فیلدهای واقعی DB جدید + فیلدهای legacy
     * تا کدهای قبلی نترکند.
     */
    protected $fillable = [
        'exam_id',
        'student_id',

        // ✅ DB جدید
        'status',
        'started_at',
        'finished_at',
        'total_score',

        // ---------------- legacy (اگر در DB نبودند، نادیده گرفته می‌شوند)
        'submitted_at',
        'answers',
        'percent',
        'score_total',
        'score_obtained',
        'meta',
    ];

    /**
     * Casts
     * ------------------------------------------------------------
     */
    protected $casts = [
        // legacy
        'answers'       => 'array',
        'meta'          => 'array',

        // ✅ DB جدید
        'started_at'    => 'datetime',
        'finished_at'   => 'datetime',

        'total_score'   => 'decimal:2',

        // legacy
        'submitted_at'  => 'datetime',
        'percent'       => 'decimal:2',
        'score_total'   => 'decimal:2',
        'score_obtained'=> 'decimal:2',
    ];

    /**
     * Relationships
     * ------------------------------------------------------------
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class, 'attempt_id');
    }

    // ==========================================================
    // Compatibility Accessors (for old code)
    // ==========================================================

    /**
     * ✅ old: submitted_at
     * new DB doesn't have it, so we infer:
     * - if status != in_progress => treat finished_at as submitted_at
     */
    public function getSubmittedAtAttribute()
    {
        if ($this->attributes['submitted_at'] ?? null) {
            return $this->attributes['submitted_at'];
        }

        return $this->finished_at;
    }

    /**
     * ✅ old: score_obtained
     * new: total_score (but total_score in DB is more like final score)
     * we map score_obtained -> total_score if missing.
     */
    public function getScoreObtainedAttribute()
    {
        return $this->attributes['score_obtained']
            ?? $this->attributes['total_score']
            ?? 0;
    }

    /**
     * ✅ old: score_total
     * no direct column in DB new.
     * if exists in attributes use it, else compute from exam questions.
     */
    public function getScoreTotalAttribute()
    {
        if (isset($this->attributes['score_total'])) {
            return $this->attributes['score_total'];
        }

        return $this->exam
            ? (float) $this->exam->questions()->sum('score')
            : 0;
    }

    /**
     * ✅ old: percent
     * compute percent dynamically if not stored.
     */
    public function getPercentAttribute()
    {
        if (isset($this->attributes['percent'])) {
            return (float) $this->attributes['percent'];
        }

        $obtained = (float) $this->score_obtained;
        $total    = (float) $this->score_total;

        return $total > 0
            ? round(($obtained / $total) * 100, 2)
            : 0;
    }

    // ==========================================================
    // Helpers
    // ==========================================================

    /**
     * ✅ تشخیص اینکه Attempt نهایی است یا نه
     * برای جلوگیری از آزمون مجدد
     */
    public function isFinal(): bool
    {
        return !is_null($this->finished_at)
            || in_array($this->status, ['submitted', 'graded'])
            || !is_null($this->submitted_at);
    }
}
