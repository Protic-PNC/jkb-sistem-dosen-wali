<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'student_class_id',
        'student_phone_number',
        'nim',
        'student_address',
        'status',
        'inactive_at',
        'active_at_semester',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }    

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function gpa_cumulative()
    {
        return $this->hasOne(GpaCumulative::class);
    }

    public function achievement()
    {
        return $this->hasMany(Achievement::class);
    }
    
    public function warning()
    {
        return $this->hasMany(Warning::class);
    }
    
    public function scholarship()
    {
        return $this->hasMany(Scholarship::class);
    }
    
    public function tuition_arrear()
    {
        return $this->hasMany(TuitionArrear::class);
    }
    
    public function student_resignation()
    {
        return $this->hasOne(StudentResignation::class);
    }
    
    public function guidance()
    {
        return $this->hasMany(Guidance::class);
    }
    
}
