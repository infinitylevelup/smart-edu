<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * User Model (Smart-Edu)
 * - Roles are stored in roles table and role_user pivot (single-role)
 * - NO dependency on users.role column
 *
 * users columns:
 * id, name, email, phone, password, status, is_active, created_at, updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Mass Assignment fields
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'password',
        'status',
        // 'role'  ❌ حذف شد (نقش از pivot خوانده می‌شود)
        'is_active',
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
        'is_active'  => 'boolean',
    ];

    // ==========================================================
    // Role helpers (PIVOT-BASED)
    // ==========================================================

    /**
     * Backward compatible attribute accessor:
     * اگر جایی هنوز $user->role را می‌خواند،
     * از pivot مقدار role را برمی‌گردانیم.
     */
    public function getRoleAttribute($value = null): ?string
    {
        $this->loadMissing('roles');
        return $this->roles->first()?->slug;
    }

    /**
     * single-role primary slug
     */
    public function primaryRole(): ?string
    {
        $this->loadMissing('roles');
        return $this->roles->first()?->slug;
    }

    public function hasRole(string $slug): bool
    {
        $this->loadMissing('roles');
        return $this->roles->contains('slug', $slug);
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isCounselor(): bool
    {
        return $this->hasRole('counselor');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Scope: filter users by role (pivot-based)
     * Usage: User::role('student')->get()
     */
    public function scopeRole(Builder $query, string $slug): Builder
    {
        return $query->whereHas('roles', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }

    // ==========================================================
    // Relationships
    // ==========================================================

    /**
     * Roles (pivot: role_user)
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            "role_user",
            "user_id",
            "role_id"
        );
    }

    /**
     * Classrooms created by this user as teacher
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

    // ==========================================================
    // Boot (auto-generate string id)
    // ==========================================================
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                // اگر می‌خوای id همون شماره موبایل باشه:
                // $user->id = $user->phone;

                // در غیر این صورت UUID:
                $user->id = (string) Str::uuid();
            }
        });
    }
}
