<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    // id اتواینکریمنت است → هیچ تنظیم خاصی لازم نیست

    protected $fillable = [
        'uuid',
        'slug',
        'name_fa',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            // uuid اجباری و unique است → حتماً بساز
            if (empty($m->uuid)) {
                $m->uuid = (string) Str::uuid();
            }
        });
    }

    // روابط
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
