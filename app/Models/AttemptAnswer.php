<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    use HasFactory;

    /**
     * ✅ DB جدید
     */
    protected $table = 'attempt_answers';

    /**
     * Fillable
     * ------------------------------------------------------------
     * ✅ فیلدهای واقعی DB جدید + legacy
     */
    protected $fillable = [
        'attempt_id',
        'question_id',

        // ✅ DB جدید
        'answer',
        'is_correct',
        'score',

        // ---------------- legacy (فعلاً در DB نیستند)
        'score_awarded',
        'teacher_feedback',
        'graded_by',
        'graded_at',
    ];

    /**
     * Casts
     * ------------------------------------------------------------
     */
    protected $casts = [
        // در SQL نوع longtext بود و json_valid داشتیم
        'answer'        => 'array',  // بهتر از json برای کار با mixed
        'is_correct'    => 'boolean',

        // ✅ DB جدید
        'score'         => 'decimal:2',

        // legacy
        'score_awarded' => 'decimal:2',
        'graded_at'     => 'datetime',
    ];

    // ==========================================================
    // Relationships
    // ==========================================================

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    // ==========================================================
    // Compatibility Accessors (for old code)
    // ==========================================================

    /**
     * ✅ old: score_awarded
     * new DB: score
     */
    public function getScoreAwardedAttribute()
    {
        return $this->attributes['score_awarded']
            ?? $this->attributes['score']
            ?? 0;
    }

    /**
     * ✅ اگر کد قدیمی score_awarded ست کرد،
     * ما روی score ذخیره می‌کنیم
     */
    public function setScoreAwardedAttribute($value)
    {
        $this->attributes['score'] = $value;
        $this->attributes['score_awarded'] = $value; // legacy safe
    }
}
