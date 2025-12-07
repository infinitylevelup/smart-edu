<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'branch_id',
        'slug',
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
    public function branch(){ return $this->belongsTo(\App\Models\Branch::class); }
    public function subfields(){ return $this->hasMany(\App\Models\Subfield::class); }
    public function subjects(){ return $this->hasMany(\App\Models\Subject::class); }

}
