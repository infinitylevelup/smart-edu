<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiArtifact extends Model
{
    use HasFactory;

    protected $table = 'ai_artifacts';

    protected $fillable = ['session_id', 'artifact_type', 'title', 'body', 'linked_table', 'linked_id', 'status', 'reviewer_id'];
}