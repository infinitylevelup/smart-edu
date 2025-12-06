<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = ['section_id', 'slug', 'name_fa', 'sort_order', 'is_active'];
}
