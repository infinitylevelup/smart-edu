<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    protected $fillable = ['module_id', 'title', 'sort_order'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
