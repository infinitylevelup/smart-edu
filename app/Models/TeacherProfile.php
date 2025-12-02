<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id','first_name','last_name','avatar',
        'specialty','bio'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

