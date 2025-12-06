<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectType extends Model
{
    use HasFactory;

    protected $table = 'subject_types';

    protected $fillable = ['slug', 'name_fa', 'coefficient', 'weight_percent', 'default_question_count', 'color', 'icon', 'sort_order', 'is_active'];
}