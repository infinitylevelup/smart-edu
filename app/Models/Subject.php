<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    // ✅ id اتواینکریمنت است => اینها نباید باشند
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'uuid',
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

    protected $casts = [
        'section_id'      => 'integer',
        'grade_id'        => 'integer',
        'branch_id'       => 'integer',
        'field_id'        => 'integer',
        'subfield_id'     => 'integer',
        'subject_type_id' => 'integer',
        'hours'           => 'integer',
        'sort_order'      => 'integer',
        'is_active'       => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            // ✅ فقط uuid بساز، نه id
            if (empty($m->uuid)) {
                $m->uuid = (string) Str::uuid();
            }
        });
    }

    // روابط
    public function section()
    {
        return $this->belongsTo(\App\Models\Section::class);
    }

    public function grade()
    {
        return $this->belongsTo(\App\Models\Grade::class);
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }

    public function field()
    {
        return $this->belongsTo(\App\Models\Field::class);
    }

    public function subfield()
    {
        return $this->belongsTo(\App\Models\Subfield::class);
    }

    public function subjectType()
    {
        return $this->belongsTo(\App\Models\SubjectType::class);
    }

    // exams pivot
    public function exams()
    {
        return $this->belongsToMany(
            Exam::class,
            'exam_subject',
            'subject_id',
            'exam_id'
        );
    }
    public function getSectionAttribute()
    {
            return $this->grade?->section;
    }
}
