<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptAnswer extends Model
{
    use HasFactory;

    protected $table = 'attempt_answers';

    protected $fillable = ['attempt_id', 'question_id', 'answer', 'is_correct', 'score'];
}