<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subfield extends Model
{
    use HasFactory;

    protected $table = 'subfields';

    // ✅ id اتواینکریمنت است => اینها نباید باشند
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'field_id',
        'slug',
        'name_fa',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'field_id'   => 'integer',
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
    public function field()
    {
        return $this->belongsTo(\App\Models\Field::class);
    }

    public function subjects()
    {
        return $this->hasMany(\App\Models\Subject::class);
    }
}
