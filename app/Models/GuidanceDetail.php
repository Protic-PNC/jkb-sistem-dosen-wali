<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuidanceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'guidance_id',
        'student_id',
        'problem',
        'solution',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function guidance()
    {
        return $this->belongsTo(Guidance::class, 'guidance_id', 'guidance_id');
    }
}
