<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $table = 'exam_attempts';

    protected $fillable = ['exam_id', 'student_id', 'started_at', 'finished_at', 'status', 'total_score'];
}