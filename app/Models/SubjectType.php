<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubjectType extends Model
{
    use HasFactory;

    protected $table = 'subject_types';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
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
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'subject_type_id');
    }
}
