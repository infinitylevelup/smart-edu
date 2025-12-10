<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';

    // ✅ id اتواینکریمنت است => اینها نباید باشند
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'section_id',
        'slug',
        'value',
        'name_fa',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'section_id' => 'integer',
        'value'      => 'integer',
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

    public function subjects()
    {
        return $this->hasMany(\App\Models\Subject::class);
    }
}
