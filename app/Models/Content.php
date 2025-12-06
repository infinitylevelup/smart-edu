<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'contents';

    protected $fillable = ['creator_id', 'type', 'title', 'description', 'file_path', 'url', 'section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_id', 'topic_id', 'access_level', 'classroom_id', 'ai_generated_by_session_id', 'is_active'];
}