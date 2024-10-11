<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    protected $fillable = [
        'class_id',
        // 'warning_id',
        // 'gpa_id',
        // 'guidance_id',
        // 'achievement_id',
        // 'scholarship_id',
        // 'student_resignation_id',
        // 'tuition_arrear_id',
        'status',
        'semester',
        'has_acc_academic_advisor',
        'has_acc_head_of_program',
    ];

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    // public function warning()
    // {
    //     return $this->belongsTo(Warning::class);
    // }

    // public function gpa()
    // {
    //     return $this->belongsTo(Gpa::class);
    // }

    // public function guidance()
    // {
    //     return $this->belongsTo(Guidance::class);
    // }

    // public function achievement()
    // {
    //     return $this->belongsTo(Achievement::class);
    // }

    // public function scholarship()
    // {
    //     return $this->belongsTo(Scholarship::class);
    // }

    // public function student_resignation()
    // {
    //     return $this->belongsTo(StudentResignation::class);
    // }

    // public function tuition_arrear()
    // {
    //     return $this->belongsTo(TuitionArrear::class);
    // }

}
