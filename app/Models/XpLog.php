<?php
// app/Models/XpLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XpLog extends Model
{
    protected $fillable = [
        'student_id',
        'source_type',
        'source_id',
        'xp_amount',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
