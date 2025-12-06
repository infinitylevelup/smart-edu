<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classrooms';

    protected $fillable = ['teacher_id', 'title', 'description', 'section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_type_id', 'classroom_type', 'join_code', 'is_active', 'metadata'];
}