<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychoAssessment extends Model
{
    use HasFactory;

    protected $table = 'psycho_assessments';

    protected $fillable = ['student_id', 'assessment_type', 'value', 'notes', 'measured_at', 'source'];
}