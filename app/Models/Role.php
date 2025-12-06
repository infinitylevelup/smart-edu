<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id','name','slug','description','is_active'];
}
