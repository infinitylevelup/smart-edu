<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';

    // ✅ ID auto-increment است - تنظیمات پیش‌فرض لاراول
    // public $incrementing = true; // پیش‌فرض
    // protected $keyType = 'int';  // پیش‌فرض

    protected $fillable = [
        'uuid',
        'branch_id',
        'slug',
        'name_fa',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'branch_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
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
    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }

    public function subfields()
    {
        return $this->hasMany(\App\Models\Subfield::class);
    }

    public function subjects()
    {
        return $this->hasMany(\App\Models\Subject::class);
    }
}
