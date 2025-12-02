<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    /**
     * Fillable
     * ------------------------------------------------------------
     * ✅ باید تمام فیلدهایی که در کنترلر submit/start آپدیت می‌شن
     * اینجا باشند وگرنه ذخیره نمی‌شن.
     */
    protected $fillable = [
        'exam_id',
        'student_id',

        // legacy JSON answers
        'answers',

        // وضعیت attempt
        'status',
        'started_at',
        'submitted_at',
        'finished_at',

        // نمره‌ها و درصد
        'score',
        'percent',
        'score_total',
        'score_obtained',

        // meta (اگر بعداً خواستی)
        'meta',
    ];

    /**
     * Casts
     * ------------------------------------------------------------
     */
    protected $casts = [
        'answers'      => 'array',
        'meta'         => 'array',
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
        'finished_at'  => 'datetime',
        'score'        => 'decimal:2',
        'percent'      => 'decimal:2',
        'score_total'  => 'integer',
        'score_obtained'=> 'integer',
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
        return $this->hasMany(AttemptAnswer::class);
    }

    /**
     * Helpers
     * ------------------------------------------------------------
     * ✅ تشخیص اینکه این Attempt نهایی است یا نه
     * برای جلوگیری از آزمون مجدد
     */
    public function isFinal(): bool
    {
        return !is_null($this->finished_at)
            || in_array($this->status, ['submitted', 'graded'])
            || !is_null($this->submitted_at);
    }
}
