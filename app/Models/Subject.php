<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = ['title_fa', 'code', 'hours', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_type_id', 'description', 'is_active'];
}