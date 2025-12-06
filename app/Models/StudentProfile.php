<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $table = 'student_profiles';

    protected $fillable = ['user_id', 'section_id', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'national_code'];
}