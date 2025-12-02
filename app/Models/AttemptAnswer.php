<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer',
        'is_correct',
        'score_awarded',

        // برای تشریحی‌ها (آینده)
        'teacher_feedback',
        'graded_by',
        'graded_at',
    ];

    protected $casts = [
        'answer'        => 'json',     // mixed
        'is_correct'    => 'boolean',
        'score_awarded' => 'integer',
        'graded_at'     => 'datetime',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
