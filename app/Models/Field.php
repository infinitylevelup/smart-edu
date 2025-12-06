<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';

    protected $fillable = ['branch_id', 'slug', 'name_fa', 'icon', 'sort_order', 'is_active'];
}