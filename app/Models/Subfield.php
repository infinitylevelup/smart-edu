<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subfield extends Model
{
    use HasFactory;

    protected $table = 'subfields';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'field_id',
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
    public function field(){ return $this->belongsTo(\App\Models\Field::class); }
    public function subjects(){ return $this->hasMany(\App\Models\Subject::class); }

}
