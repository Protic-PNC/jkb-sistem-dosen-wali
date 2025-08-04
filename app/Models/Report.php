<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_advisor_id',
        'student_class_id',
        'academic_advisor_decree',
        'academic_advisor_name',
        'class_name',
        'entry_year',
        'semester',
        'academic_year',
        'status',
        'submitted_at',
        'approved_at',
    ];

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function academic_advisor()
    {
        return $this->belongsTo(Lecturer::class, 'academic_advisor_id');
    }

}
