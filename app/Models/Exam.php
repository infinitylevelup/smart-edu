<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'subject_id',
        'title',
        'description',
        'level',
        'duration',
        'start_at',
        'is_published',

        // ✅ NEW (برای فاز scope)
        'scope',
        'is_active',
    ];
    protected $casts = [
        'is_published' => 'boolean',
        'is_active'    => 'boolean',  // ✅ اضافه شد
        'start_at'     => 'datetime',
    ];



    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }
}
