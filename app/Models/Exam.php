<?php
// app/Models/Exam.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';

    // id اینکریمنت است (INT) → تنظیم پیش‌فرض لاراول کافی است

    protected $fillable = [
        'user_id',
        'teacher_id',
        'classroom_id',

        'exam_type',

        // برای فرم ساده ویرایش
        'scope',       // classroom / free
        'subject',     // برچسب نمایشی درس (مثلاً عنوان فارسی)
        'level',       // taghviyati / konkur / olympiad

        'title',
        'description',
        'duration_minutes',

        'section_id',
        'grade_id',
        'branch_id',
        'field_id',
        'subfield_id',
        'subject_type_id',

        'total_questions',
        'coefficients',

        'start_at',
        'end_at',

        'shuffle_questions',
        'shuffle_options',
        'ai_assisted',
        'ai_session_id',

        'is_active',
        'is_published',

        'uuid',
    ];

    protected $casts = [
        'duration_minutes'  => 'integer',
        'total_questions'   => 'integer',

        'coefficients'      => 'array',

        'shuffle_questions' => 'boolean',
        'shuffle_options'   => 'boolean',
        'ai_assisted'       => 'boolean',

        'is_active'         => 'boolean',
        'is_published'      => 'boolean',

        'start_at'          => 'datetime',
        'end_at'            => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    // ================== روابط ==================

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(
            Question::class,
            'exam_questions',
            'exam_id',
            'question_id'
        )->withPivot('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class, 'exam_id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'exam_subject',
            'exam_id',
            'subject_id'
        )->withPivot('question_count');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function subfield(): BelongsTo
    {
        return $this->belongsTo(Subfield::class, 'subfield_id');
    }

    public function subjectType(): BelongsTo
    {
        return $this->belongsTo(SubjectType::class, 'subject_type_id');
    }

    public function aiSession(): BelongsTo
    {
        return $this->belongsTo(AiSession::class, 'ai_session_id');
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    // ================== UUID ==================

    protected static function booted()
    {
        static::creating(function ($exam) {
            if (empty($exam->uuid)) {
                $exam->uuid = (string) Str::uuid();
            }
        });
    }



}
