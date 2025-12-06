<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSession extends Model
{
    use HasFactory;

    protected $table = 'ai_sessions';

    protected $fillable = ['ai_agent_id', 'user_id', 'context_type', 'section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_id', 'topic_id', 'classroom_id', 'exam_id', 'started_at', 'ended_at', 'metadata'];
}