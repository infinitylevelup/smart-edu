<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGoal extends Model
{
    use HasFactory;

    protected $table = 'student_goals';

    protected $fillable = ['student_id', 'goal_type', 'title', 'description', 'target_date', 'status', 'priority', 'related_subject_id', 'related_topic_id'];
}