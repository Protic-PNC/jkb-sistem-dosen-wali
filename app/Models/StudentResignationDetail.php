<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResignationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_resignation_id',
        'student_id',
        'resignation_type',
        'decree_number',
        'reason'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function student_resignation()
    {
        return $this->belongsTo(StudentResignation::class, 'student_resignation_id', 'student_resignation_id');
    }
}
