<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiAgent extends Model
{
    use HasFactory;

    protected $table = 'ai_agents';

    protected $fillable = ['slug', 'name_fa', 'role_type', 'model_provider', 'model_name', 'system_prompt', 'config', 'is_active'];
}