<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubjectType extends Model
{
    use HasFactory;

    protected $table = 'subject_types';

    // ✅ id اتواینکریمنت است => اینها نباید باشند
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'slug',
        'name_fa',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
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
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'subject_type_id');
    }
}
