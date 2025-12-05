<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * فیلدهای قابل پر شدن
     * نکته مهم: چون قبلاً خطای "Unknown column name" داشتی،
     * اگر ستون name در جدول users نداری، اینجا هم نباید باشه.
     * (اگر بعداً ستون name اضافه کردی، دوباره به fillable برگردون)
     */
    protected $fillable = [
        'phone',
        'email',
        'password',

        // اگر ستون name داری uncomment کن:
        // 'name',

        'role',               // student | teacher | admin
        'otp_code',
        'otp_expires_at',
        'role_selected_at',
        'is_active',
        'last_login_at',      // اگر توی مایگریشن هست
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at'    => 'datetime',
        'role_selected_at'  => 'datetime',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
    ];

    /* ==========================================================
       Relationships
    ========================================================== */

    /**
     * کلاس‌هایی که این کاربر به عنوان معلم ساخته.
     */
    public function taughtClassrooms(): HasMany
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    /**
     * کلاس‌هایی که این کاربر (دانش‌آموز) عضو آن‌هاست.
     * pivot: classroom_student (classroom_id, student_id)
     */
    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Classroom::class,
            'classroom_student',
            'student_id',
            'classroom_id'
        )->withTimestamps();
    }

    /**
     * پروفایل دانش‌آموز
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    /**
     * پروفایل معلم
     */
    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class, 'user_id');
    }
    // کلاس‌هایی که این کاربر (معلم) ساخته/تدریس می‌کند
    public function teachingClassrooms()
    {
        return $this->hasMany(\App\Models\ClassRoom::class, 'teacher_id');
    }


    /**
     * پروفایل گیمیفیکیشن دانش‌آموز
     */
    public function gamificationProfile(): HasOne
    {
        return $this->hasOne(StudentGamificationProfile::class, 'student_id');
    }

    /**
     * نقش‌های چندگانه (اگر role_user داری)
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole(string $role): bool
    {
        // هم با نقش string ستونی کار می‌کنه، هم با roles()
        if (!empty($this->role) && $this->role === $role) {
            return true;
        }

        return $this->roles()->where('name', $role)->exists();
    }

    /* ==========================================================
       Helpers
    ========================================================== */

    /**
     * نام نمایشی کاربر:
     * اگر ستون name نداری، از پروفایل‌ها می‌سازه.
     */
    public function getDisplayNameAttribute(): string
    {
        // اگر ستون name داری:
        if (!empty($this->attributes['name'] ?? null)) {
            return $this->attributes['name'];
        }

        if ($this->role === 'teacher' && $this->teacherProfile) {
            return trim(($this->teacherProfile->first_name ?? '') . ' ' . ($this->teacherProfile->last_name ?? ''));
        }

        if ($this->role === 'student' && $this->studentProfile) {
            return trim(($this->studentProfile->first_name ?? '') . ' ' . ($this->studentProfile->last_name ?? ''));
        }

        return $this->phone ?? 'User';
    }
}
