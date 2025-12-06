<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDailyLog extends Model
{
    use HasFactory;

    protected $table = 'student_daily_logs';

    protected $fillable = ['student_id', 'log_date', 'study_minutes', 'mood', 'stress_level', 'sleep_hours', 'free_text'];
}