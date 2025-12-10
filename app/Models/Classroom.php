<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use HasFactory;

    protected $table = "classrooms";

    // ✅ id اینکریمنت است → حالت پیش‌فرض لاراول

    protected $fillable = [
        "uuid",
        "teacher_id",
        "title",
        "description",

        "section_id",
        "grade_id",
        "branch_id",
        "field_id",
        "subfield_id",
        "subject_type_id",
        "subject_id",

        "classroom_type",
        "join_code",
        "is_active",
        "metadata",
    ];

    protected $casts = [
        "is_active" => "boolean",
        "metadata"  => "array",
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];

    // ==========================================================
    // Relationships
    // ==========================================================

    /** Teacher who owns the classroom */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, "teacher_id");
    }

    /**
     * All classroom members (students + teachers)
     * pivot: classroom_user(classroom_id, user_id)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            "classroom_user",
            "classroom_id",
            "user_id"
        );
    }

    /** Backward-compatible alias */
    public function members(): BelongsToMany
    {
        return $this->users();
    }

    /**
     * Classroom students
     * pivot classroom_user has no role column
     * so we filter through roles relation on User.
     */
    public function students(): BelongsToMany
    {
        return $this->users()
            ->whereHas("roles", fn ($q) => $q->where("slug", "student"));
    }

    /** Classroom teachers (future multi-teacher support) */
    public function teachers(): BelongsToMany
    {
        return $this->users()
            ->whereHas("roles", fn ($q) => $q->where("slug", "teacher"));
    }

    /** Exams belonging to this classroom */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, "classroom_id");
    }

    /**
     * Subjects attached to this classroom
     * pivot: classroom_subject(classroom_id, subject_id)
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            "classroom_subject",
            "classroom_id",
            "subject_id"
        );
    }

    // --- Taxonomy relations ---

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, "section_id");
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, "grade_id");
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, "branch_id");
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, "field_id");
    }

    public function subfield(): BelongsTo
    {
        return $this->belongsTo(Subfield::class, "subfield_id");
    }

    public function subjectType(): BelongsTo
    {
        return $this->belongsTo(SubjectType::class, "subject_type_id");
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, "subject_id");
    }

    // ==========================================================
    // Boot / Mutators
    // ==========================================================

    protected static function booted()
    {
        static::creating(function ($classroom) {

            // ✅ uuid اجباری و unique
            if (empty($classroom->uuid)) {
                $classroom->uuid = (string) Str::uuid();
            }

            // ✅ join_code unique
            if (empty($classroom->join_code)) {
                do {
                    $code = strtoupper(str()->random(6));
                } while (self::where("join_code", $code)->exists());

                $classroom->join_code = $code;
            }

            // ✅ اگر نوع کلاس نفرستادی، پیش‌فرض
            if (empty($classroom->classroom_type)) {
                $classroom->classroom_type = "single";
            }
        });
    }
}
