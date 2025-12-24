<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    // ✅ ID auto-increment است - تنظیمات پیش‌فرض لاراول
    // public $incrementing = true; // پیش‌فرض
    // protected $keyType = 'int';  // پیش‌فرض

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
        'section_id' => 'integer',
        'grade_id' => 'integer',
        'branch_id' => 'integer',
        'field_id' => 'integer',
        'subfield_id' => 'integer',
        'subject_type_id' => 'integer',
        'hours' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    // ================== Accessors ==================

    /**
     * نام کامل درس شامل پایه و شاخه
     */
    public function getFullNameAttribute(): string
    {
        $parts = [];
        if ($this->grade) {
            $parts[] = $this->grade->name_fa;
        }
        if ($this->branch) {
            $parts[] = $this->branch->name_fa;
        }
        $parts[] = $this->title_fa;

        return implode(' - ', $parts);
    }

    /**
     * مدت زمان درس به صورت فرمت شده
     */
    public function getHoursFormattedAttribute(): string
    {
        return $this->hours.' ساعت';
    }

    /**
     * وضعیت فعال بودن به فارسی
     */
    public function getActiveStatusAttribute(): string
    {
        return $this->is_active ? 'فعال' : 'غیرفعال';
    }

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

    // classrooms pivot
    public function classrooms()
    {
        return $this->belongsToMany(
            Classroom::class,
            'classroom_subject',
            'subject_id',
            'classroom_id'
        )->withTimestamps();
    }

    public function getSectionAttribute()
    {
        return $this->grade?->section;
    }

    public function modules()
    {
        return $this->hasMany(\App\Models\Module::class)->orderBy('sort_order');
    }
}
