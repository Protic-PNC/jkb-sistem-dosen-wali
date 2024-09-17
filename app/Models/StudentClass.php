<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $primaryKey = 'class_id';
    protected $fillable = [
        'program_id',
        'academic_advisor_id',
        'class_name',
        'academic_year',
        'status',
        'graduated_at',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function academic_advisor()
    {
        return $this->belongsTo(Lecturer::class,'academic_advisor_id', 'lecturer_id');
    }

    public function student()
    {
        return $this->hasMany(Student::class, 'class_id', 'class_id');
    }

    

    public function gpa()
    {
        return $this->hasOne(Gpa::class, 'class_id', 'class_id');
    }

    public function guidance()
    {
        return $this->hasOne(Guidance::class, 'class_id', 'class_id');
    }

    public function warning()
    {
        return $this->hasOne(Warning::class, 'class_id', 'class_id');
    }

    public function scholarship()
    {
        return $this->hasOne(Scholarship::class, 'class_id', 'class_id');
    }

    public function tuition_arrear()
    {
        return $this->hasOne(TuitionArrear::class, 'class_id', 'class_id');
    }

    public function student_resignation()
    {
        return $this->hasOne(StudentResignation::class, 'class_id', 'class_id');
    }
    
    public function achievement()
    {
        return $this->hasOne(Achievement::class, 'class_id', 'class_id');
    }

    public function report()
    {
        return $this->hasMany(Report::class, 'class_id', 'class_id');
    }
}
