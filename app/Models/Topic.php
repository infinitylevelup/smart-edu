<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'subject_id',
        'title_fa',
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

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
