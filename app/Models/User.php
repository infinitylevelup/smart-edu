<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;

/**
 * مدل کاربر اصلی سیستم
 * - هم دانش‌آموز و هم معلم از همین مدل استفاده می‌کنند.
 * - نقش (role) در زمان ثبت‌نام/OTP انتخاب می‌شود.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * فیلدهای قابل پر شدن (Mass Assignment)
     * اگر در نسخه قبلی فیلدهای بیشتری داشتی، همینجا نگه دار/اضافه کن.
     */
    protected $fillable = [
        'phone',
        'email',
        'password',
        'name',
        'role',               // student | teacher | admin
        'otp_code',
        'otp_expires_at',
        'role_selected_at',
        'is_active',
    ];

    /**
     * فیلدهایی که نباید در خروجی‌های JSON نمایش داده شوند
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    /**
     * Cast ها برای تبدیل خودکار نوع داده‌ها
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at'    => 'datetime',
        'role_selected_at'  => 'datetime',
        'is_active'         => 'boolean',
    ];

    // ==========================================================
    // Relationships
    // ==========================================================

    /**
     * کلاس‌هایی که این کاربر به عنوان معلم ساخته است.
     */
    public function taughtClassrooms()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    /**
     * کلاس‌هایی که این کاربر (دانش‌آموز) عضو آن‌هاست.
     * pivot: classroom_student (student_id, classroom_id)
     */
    public function classrooms()
    {
        return $this->belongsToMany(
            Classroom::class,
            'classroom_student',
            'student_id',
            'classroom_id'
        )->withTimestamps();
    }
    /**
     * کلاس‌هایی که معلم ساخته/مالک آن‌هاست
     * Teacher side -> hasMany
     */
    public function teachingClassrooms()
    {
        return $this->hasMany(
            Classroom::class,
            'teacher_id' // ستون teacher_id در جدول classrooms
        );
    }
    // ----------------------------------------------------------
    // بخش‌های قبلی شما (حفظ شده برای رفرنس)
    // اگر لازم شد فعالشون کنیم، همینجا انجام می‌دیم.
    // ----------------------------------------------------------

    // public function classrooms()
    // {
    //     return $this->belongsToMany(Classroom::class, 'classroom_user')
    //         ->withPivot('role')
    //         ->withTimestamps();
    // }

    // public function students()
    // {
    //     return $this->hasManyThrough(
    //         User::class,
    //         ClassroomUser::class,
    //         'classroom_id',
    //         'id',
    //         'id',
    //         'user_id'
    //     )->where('role', 'student')->distinct();
    // }

    public function studentProfile()
{
    return $this->hasOne(\App\Models\StudentProfile::class, 'user_id');
}

// نقش‌های کاربر
public function roles()
{
    return $this->belongsToMany(Role::class, 'role_user');
}

public function hasRole(string $role): bool
{
    return $this->roles()->where('name', $role)->exists();
}


}
