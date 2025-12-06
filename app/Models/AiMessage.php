<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiMessage extends Model
{
    use HasFactory;

    protected $table = 'ai_messages';

    protected $fillable = ['session_id', 'sender_type', 'content', 'tokens_in', 'tokens_out', 'safety_flags'];
}