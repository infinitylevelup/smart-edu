<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    // ✅ id اتواینکریمنت است => اینها نباید باشند
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'section_id',
        'slug',
        'name_fa',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'section_id' => 'integer',
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
    public function section()
    {
        return $this->belongsTo(\App\Models\Section::class);
    }

    public function fields()
    {
        return $this->hasMany(\App\Models\Field::class);
    }

    public function subjects()
    {
        return $this->hasMany(\App\Models\Subject::class);
    }
}
