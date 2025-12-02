<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id','title','subject','grade','description','join_code','is_active'
    ];

    // معلمِ کلاس
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // دانش‌آموزهای کلاس (pivot: classroom_student)
    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'classroom_student',
            'classroom_id',
            'student_id'
        )->withTimestamps();
    }

    // آزمون‌های کلاس
    public function exams()
    {
        return $this->hasMany(Exam::class, 'classroom_id');
    }

    protected static function booted()
    {
        static::creating(function ($classroom) {
            if (empty($classroom->join_code)) {
                $classroom->join_code = strtoupper(str()->random(6));
            }
        });
    }
}
