<?php
// app/Models/StudentGamificationProfile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGamificationProfile extends Model
{
    protected $fillable = [
        'student_id',
        'total_xp',
        'level',
        'current_streak',
        'longest_streak',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
