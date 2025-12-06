<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselorProfile extends Model
{
    use HasFactory;

    protected $table = 'counselor_profiles';

    protected $fillable = ['user_id', 'focus_area', 'main_section_id', 'main_branch_id'];
}