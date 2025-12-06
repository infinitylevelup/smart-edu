<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicAssessment extends Model
{
    use HasFactory;

    protected $table = 'academic_assessments';

    protected $fillable = ['student_id', 'section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_id', 'topic_id', 'assessment_type', 'score_percent', 'mastery_level', 'weaknesses', 'strengths', 'taken_at'];
}