<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected $casts = [
        'is_active'    => 'boolean',
        'is_published' => 'boolean',
    ];

    /**
     * Teacher (owner of classroom)
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Students joined to classroom
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'classroom_student',
            'classroom_id',
            'student_id'
        )->withTimestamps();
    }

    /**
     * Classroom exams
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Helpers / Scopes
     */
    public function scopeActive($q)
    {
        return $q->where(function ($act) {
            $act->whereNull('is_active')->orWhere('is_active', true);
        });
    }

    public function scopePublished($q)
    {
        return $q->where(function ($pub) {
            $pub->whereNull('is_published')->orWhere('is_published', true);
        });
    }
}
