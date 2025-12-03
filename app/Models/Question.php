<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'subject_id',

        // ستون واقعی DB
        'question_text',

        'type',            // mcq | true_false | fill_blank | essay
        'score',
        'explanation',

        // json options / correct for new system
        'options',         // array/json for mcq
        'correct_answer',  // json/array for fill_blank / mcq multi
        'correct_tf',      // boolean for true_false

        // legacy mcq
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',  // a|b|c|d

        'difficulty',      // easy|medium|hard  (سیدر average => medium)
        'is_active',
    ];

    protected $casts = [
        'options'        => 'array',
        'correct_answer' => 'array',   // بهتر از json چون توی کنترلر/بلید با آرایه کار می‌کنی
        'correct_tf'     => 'boolean',
        'score'          => 'integer',
        'is_active'      => 'boolean',
        'difficulty'     => 'string',
    ];

    /* ================= Relationships ================= */

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /* ================= Alias / Accessors ================= */

    // ✅ alias مطمئن برای کل پروژه (q->question)
    public function getQuestionAttribute()
    {
        return $this->question_text;
    }

    public function setQuestionAttribute($value)
    {
        $this->attributes['question_text'] = $value;
    }

    /* ================= Difficulty Normalizer ================= */

    /**
     * ✅ حل مشکل truncate difficulty در سیدر:
     * اگر سیدر average زد، خودکار medium ذخیره می‌کنیم.
     */
    public function setDifficultyAttribute($value)
    {
        $v = strtolower((string)$value);

        if ($v === 'average') $v = 'medium';
        if (!in_array($v, ['easy', 'medium', 'hard'])) $v = 'medium';

        $this->attributes['difficulty'] = $v;
    }

    /**
     * برای اطمینان اگر مقدار null بود.
     */
    public function getDifficultyAttribute($value)
    {
        return $value ?: 'medium';
    }
}
