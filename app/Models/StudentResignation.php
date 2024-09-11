<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResignation extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id'
    ];

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    public function student_resignation_detail()
    {
        return $this->hasMany(StudentResignationDetail::class, 'student_resignation_id', 'student_resignation_id');
    }
}
