<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'name',
        'teacher_id',
        'is_active',
        'is_published',
    ];

    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'classroom_student',
            'classroom_id',
            'student_id'
        );
    }
}
