<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'section_id',
        'slug',
        'value',      // ✅ حتماً اینجا باشد
        'name_fa',
        'sort_order',
        'is_active',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->id)) {
                $m->id = (string) Str::uuid();
            }
        });
    }

    // روابط
    public function section(){ return $this->belongsTo(\App\Models\Section::class); }
    public function subjects(){ return $this->hasMany(\App\Models\Subject::class); }

}
