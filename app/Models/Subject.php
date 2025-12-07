<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    public $incrementing = false;
    protected $keyType = 'string';

    public function section()     { return $this->belongsTo(\App\Models\Section::class); }
    public function grade()       { return $this->belongsTo(\App\Models\Grade::class); }
    public function branch()      { return $this->belongsTo(\App\Models\Branch::class); }
    public function field()       { return $this->belongsTo(\App\Models\Field::class); }
    public function subfield()    { return $this->belongsTo(\App\Models\Subfield::class); }
    public function subjectType() { return $this->belongsTo(\App\Models\SubjectType::class); }

    protected $fillable = [
        'id',
        'title_fa',
        'slug',
        'code',
        'hours',
        'sort_order',
        'is_active',
        'section_id',
        'grade_id',
        'branch_id',
        'field_id',
        'subfield_id',
        'subject_type_id',
    ];




    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->id)) {
                $m->id = (string) Str::uuid();
            }
        });
    }



    // exams pivot
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_subject', 'subject_id', 'exam_id');
    }

}
