<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class StudentClass extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'program_id',
        'academic_advisor_id',
        'academic_advisor_decree',
        'class_name',
        'entry_year',
        'status',
        'graduated_at',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class,'academic_advisor_id');
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function gpa_cumulative()
    {
        return $this->hasOne(GpaCumulative::class);
    }

    public function guidance()
    {
        return $this->hasOne(Guidance::class);
    }

    public function warning()
    {
        return $this->hasOne(Warning::class);
    }

    public function scholarship()
    {
        return $this->hasOne(Scholarship::class);
    }

    public function tuition_arrear()
    {
        return $this->hasOne(TuitionArrear::class);
    }

    public function student_resignation()
    {
        return $this->hasOne(StudentResignation::class);
    }
    
    public function achievement()
    {
        return $this->hasOne(Achievement::class);
    }

    public function report()
    {
        return $this->hasMany(Report::class);
    }


    public function getCurrentSemesterAttribute()
    {
        return self::calculateCurrentSemester($this->entry_year);
    }

    public static function calculateCurrentSemester($entryYear)
    {
        $now = Carbon::now();
        $yearDiff = $now->year - $entryYear;

        if ($now->month >= 8) {
            $yearDiff++;
            return ($yearDiff * 2) - 1;
        }

        return $yearDiff * 2;
    }
}
