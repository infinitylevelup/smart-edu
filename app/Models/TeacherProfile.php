<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $table = 'teacher_profiles';

    protected $fillable = ['user_id', 'bio', 'main_section_id', 'main_branch_id', 'main_field_id', 'main_subfield_id'];
}