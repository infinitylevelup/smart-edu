<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XpLog extends Model
{
    protected $fillable = [
        'student_id',
        'source_type',   // exam | quiz | streak | ...
        'source_id',     // id مربوط به همون source
        'xp_amount',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'source_id'  => 'integer',
        'xp_amount'  => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
