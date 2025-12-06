<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiFeedback extends Model
{
    use HasFactory;

    protected $table = 'ai_feedback';

    protected $fillable = ['session_id', 'message_id', 'user_id', 'rating', 'feedback_text'];
}