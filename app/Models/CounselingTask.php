<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingTask extends Model
{
    use HasFactory;

    protected $table = 'counseling_tasks';

    protected $fillable = ['title', 'description', 'task_type', 'recommended_minutes', 'is_active'];
}