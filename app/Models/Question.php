<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',

        // ستون واقعی DB
        'question_text',

        'type',
        'score',
        'explanation',

        'options',
        'correct_answer',
        'correct_tf',

        // legacy mcq
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',

        'difficulty',
        'is_active',
    ];

    protected $casts = [
        'options'        => 'array',
        'correct_answer' => 'json',   // mixed
        'correct_tf'     => 'boolean',
        'score'          => 'integer',
        'is_active'      => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // ✅ alias مطمئن برای کل پروژه
    public function getQuestionAttribute()
    {
        return $this->question_text;
    }

    public function setQuestionAttribute($value)
    {
        $this->attributes['question_text'] = $value;
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

}
