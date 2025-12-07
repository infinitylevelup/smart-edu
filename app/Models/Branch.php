<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Barnch extends Model
{
    use HasFactory;

    protected $table = 'branches'; // Adjusted to match the context of the file

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['slug', 'name_fa', 'sort_order', 'is_active'];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->id)) {
                $m->id = (string) Str::uuid();
            }
        });
    }
}
