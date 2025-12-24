<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['subject_id', 'title', 'sort_order'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function competencies()
    {
        return $this->hasMany(Competency::class)->orderBy('sort_order');
    }
}
