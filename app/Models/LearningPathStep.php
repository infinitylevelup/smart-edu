<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningPathStep extends Model
{
    use HasFactory;

    protected $table = 'learning_path_steps';

    protected $fillable = ['learning_path_id', 'step_type', 'order_index', 'section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_id', 'topic_id', 'content_id', 'exam_id', 'counseling_task_id', 'estimated_minutes', 'required_mastery', 'status', 'due_date'];
}