<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpaStat extends Model
{
    use HasFactory;

    protected $fillable= [
        'student_class_id',
        'max_semester',
    ];

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function gpa_stat_semester()
    {
        return $this->hasMany(GpaStatSemester::class);
    }
}
