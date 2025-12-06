<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subfield extends Model
{
    use HasFactory;

    protected $table = 'subfields';

    protected $fillable = ['field_id', 'slug', 'name_fa', 'icon', 'sort_order', 'is_active'];
}