<?php

namespace App\Models;
use App\Enums\QuestionType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    // ✅ ID auto-increment است - تنظیمات پیش‌فرض لاراول
    // public $incrementing = true; // پیش‌فرض
    // protected $keyType = 'int';  // پیش‌فرض

    protected $fillable = [
        'uuid',
        'exam_id',

        'user_id',
        'creator_id',

        'section_id',
        'grade_id',
        'branch_id',
        'field_id',
        'subfield_id',
        'subject_id',
        'topic_id',

        'difficulty',
        'score',

        'question_type',
        'content',

        'options',
        'correct_answer',

        'explanation',

        'ai_generated_by_session_id',
        'ai_confidence',

        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array',
        'ai_confidence' => 'float',
        'is_active' => 'boolean',
        'question_type' => QuestionType::class,
    ];

    protected static function booted()
    {
        static::creating(function ($q) {
            // ✅ فقط uuid بساز، نه id
            if (empty($q->uuid)) {
                $q->uuid = (string) Str::uuid();
            }

            // user_id
            if (empty($q->user_id) && auth::check()) {
                $q->user_id = auth::id();
            }

            // creator_id
            if (empty($q->creator_id) && auth::check()) {
                $q->creator_id = auth::id();
            }

            // active
            if ($q->is_active === null) {
                $q->is_active = true;
            }
        });
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
