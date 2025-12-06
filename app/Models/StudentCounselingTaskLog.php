<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCounselingTaskLog extends Model
{
    use HasFactory;

    protected $table = 'student_counseling_task_logs';

    protected $fillable = ['student_id', 'counseling_task_id', 'done_at', 'self_rating', 'notes'];
}