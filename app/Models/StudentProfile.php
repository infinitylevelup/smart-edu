<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id','first_name','last_name','avatar',
        'gender','birthdate','grade_level','bio'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
