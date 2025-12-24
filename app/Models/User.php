<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * User Model (Smart-Edu)
 * - Roles are stored in roles table and role_user pivot (single-role)
 * - Also supports selected_role column for backward compatibility
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass Assignment fields
     * Now matches the migration structure
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'is_active', // ✅ حالا وجود دارد
        'selected_role', // برای سازگاری
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
        'is_active' => 'boolean', // ✅ حالا می‌توانیم cast کنیم
    ];

    /**
     * Default attribute values
     */
    protected $attributes = [
        'is_active' => true,
        'status' => 'active',
    ];

    // ==========================================================
    // Role helpers (PIVOT-BASED with backward compatibility)
    // ==========================================================

    /**
     * Backward compatible attribute accessor:
     * اول selected_role را چک می‌کند، اگر نبود از pivot می‌خواند
     */
    public function getRoleAttribute($value = null): ?string
    {
        // اول selected_role را برمی‌گرداند (برای سازگاری)
        if ($this->selected_role) {
            return $this->selected_role;
        }

        // اگر selected_role نبود، از pivot می‌خواند
        $this->loadMissing('roles');

        return $this->roles->first()?->slug;
    }

    /**
     * Mutator برای selected_role
     */
    public function setSelectedRoleAttribute($value): void
    {
        $this->attributes['selected_role'] = $value;

        // اگر pivot role هم می‌خواهید خودکار sync شود
        if ($value && ! $this->hasRole($value)) {
            $role = Role::where('slug', $value)->first();
            if ($role) {
                $this->roles()->sync([$role->id]);
            }
        }
    }

    /**
     * single-role primary slug
     */
    public function primaryRole(): ?string
    {
        return $this->role;
    }

    public function hasRole(string $slug): bool
    {
        if ($this->selected_role === $slug) {
            return true;
        }

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
     * Scope: filter users by role (supports both selected_role and pivot)
     */
    public function scopeRole(Builder $query, string $slug): Builder
    {
        return $query->where(function ($q) use ($slug) {
            $q->where('selected_role', $slug)
                ->orWhereHas('roles', function ($q2) use ($slug) {
                    $q2->where('slug', $slug);
                });
        });
    }

    /**
     * Scope: active users
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('status', 'active');
    }

    /**
     * Scope: inactive users
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false)
            ->orWhere('status', '!=', 'active');
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
            'role_user',
            'user_id',
            'role_id'
        );
    }

    /**
     * Assign a role to user
     */
    public function assignRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->first();
        if ($role) {
            // Update both selected_role and pivot
            $this->selected_role = $roleSlug;
            $this->roles()->sync([$role->id]);
            $this->save();
        }
    }

    /**
     * Remove all roles from user
     */
    public function removeRoles(): void
    {
        $this->selected_role = null;
        $this->roles()->detach();
        $this->save();
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
    // Boot (auto-generate uuid)
    // ==========================================================

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = Str::uuid()->toString();
            }

            // Ensure is_active and status are in sync
            if ($user->is_active && ! $user->status) {
                $user->status = 'active';
            } elseif (! $user->is_active && ! $user->status) {
                $user->status = 'inactive';
            }
        });
    }
}
