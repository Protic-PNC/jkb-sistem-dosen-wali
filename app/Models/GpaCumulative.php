<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpaCumulative extends Model
{
    use HasFactory;

    protected $fillable = [
        'gpa_id',
        'student_id',
        'cumulative_gpa',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function gpa_semester()
    {
        return $this->hasMany(GpaSemester::class);
    }
}
