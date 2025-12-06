<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';

    protected $fillable = ['teacher_id', 'classroom_id', 'exam_type', 'title', 'description', 'duration_minutes', 'section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_type_id', 'total_questions', 'coefficients', 'start_at', 'end_at', 'shuffle_questions', 'shuffle_options', 'ai_assisted', 'ai_session_id', 'is_active', 'is_published'];
}