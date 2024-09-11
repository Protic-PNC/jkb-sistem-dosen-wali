<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $primaryKey = 'student_id';
    protected $table = 'students';

    protected $fillable=[
        'user_id',
        'class_id',
        'student_phone_number',
        'nim',
        'student_name',
        'student_address',
        'student_signature'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }    

    public function student_classes()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    public function gpa_cumulative()
    {
        return $this->hasOne(GpaCumulative::class, 'student_id', 'student_id');
    }

    public function achievement_detail()
    {
        return $this->hasMany(AchievementDetail::class, 'student_id', 'student_id');
    }
    
    public function warning_detail()
    {
        return $this->hasMany(WarningDetail::class, 'student_id', 'student_id');
    }
    
    public function scholarship_detail()
    {
        return $this->hasMany(ScholarshipDetail::class, 'student_id', 'student_id');
    }
    
    public function tuition_arrear_detail()
    {
        return $this->hasMany(TuitionArrearDetail::class, 'student_id', 'student_id');
    }
    
    public function student_resignation_detail()
    {
        return $this->hasMany(StudentResignationDetail::class, 'student_id', 'student_id');
    }
    
    public function guidance_detail()
    {
        return $this->hasOne(GuidanceDetail::class, 'student_id', 'student_id');
    }
    
}
