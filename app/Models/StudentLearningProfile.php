<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLearningProfile extends Model
{
    use HasFactory;

    protected $table = 'student_learning_profiles';

    protected $fillable = ['student_id', 'preferred_style', 'pace_level', 'study_time_per_day', 'goals_json', 'counselor_notes', 'ai_summary'];
}