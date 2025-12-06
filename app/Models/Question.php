<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    // ✅ UUID PK
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'creator_id',
        'section_id',
        'grade_id',
        'branch_id',
        'field_id',
        'subfield_id',
        'subject_id',
        'topic_id',
        'difficulty',
        'question_type',
        'content',
        'options',
        'correct_answer',
        'explanation',
        'ai_generated_by_session_id',
        'ai_confidence',
        'is_active'
    ];

    protected static function booted()
    {
        static::creating(function ($q) {
            if (empty($q->id)) {
                $q->id = (string) Str::uuid();
            }
        });
    }
}
