<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningPath extends Model
{
    use HasFactory;

    protected $table = 'learning_paths';

    protected $fillable = ['student_id', 'path_type', 'title', 'start_date', 'end_date', 'status', 'generated_by', 'metadata'];
}