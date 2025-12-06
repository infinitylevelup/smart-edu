<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * User Model (Smart-Edu)
 * - Aligned with new users table (no role column)
 * - Roles are stored in role_user pivot (single-role system)
 *
 * users columns:
 * id, name, email, phone, password, status, created_at, updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass Assignment fields
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==========================================================
    // Relationships
    // ==========================================================

    /**
     * Roles relation (pivot: role_user)
     * Note: role_user has no timestamps in DB, so no withTimestamps()
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_user',
            'user_id',
            'role_id'
        );
    }

    /**
     * Current role accessor (single-role system)
     * Returns roles.slug or null
     */
    public function getRoleAttribute(): ?string
    {
        // prefer loaded relation if exists
        if ($this->relationLoaded('roles')) {
            return $this->roles->first()?->slug;
        }

        return $this->roles()->pluck('slug')->first();
    }

    /**
     * Quick helpers
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isCounselor(): bool
    {
        return $this->role === 'counselor';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Scope: filter users by role slug (replaces where('role', ...))
     * Usage: User::role('student')->get()
     */
    public function scopeRole(Builder $query, string $slug): Builder
    {
        return $query->whereHas('roles', fn ($q) => $q->where('slug', $slug));
    }

    /**
     * Classrooms created by this user as teacher
     * (Teacher dashboard expects teachingClassrooms())
     */
    public function teachingClassrooms(): HasMany
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    /**
     * Backward compatible alias for old code.
     */
    public function taughtClassrooms(): HasMany
    {
        return $this->teachingClassrooms();
    }

    /**
     * Classrooms the user joined as student
     * pivot: classroom_user (classroom_id, user_id)
     */
    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Classroom::class,
            'classroom_user',
            'user_id',
            'classroom_id'
        );
    }

    /**
     * Student profile
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    /**
     * Teacher profile
     */
    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class, 'user_id');
    }

    /**
     * Counselor profile (optional)
     */
    public function counselorProfile(): HasOne
    {
        return $this->hasOne(CounselorProfile::class, 'user_id');
    }

    /**
     * Exams created by this user as teacher
     */
    public function examsCreated(): HasMany
    {
        return $this->hasMany(Exam::class, 'teacher_id');
    }
}
