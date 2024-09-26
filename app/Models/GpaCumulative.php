<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpaCumulative extends Model
{
    use HasFactory;

    protected $primaryKey = 'gpa_cumulative_id';
    protected $fillable = [
        'gpa_id',
        'student_id',
        'cumulative_gpa',
    ];

    public function gpa()
    {
        return $this->belongsTo(Gpa::class, 'gpa_id', 'gpa_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function gpa_semester()
    {
        return $this->hasMany(GpaSemester::class, 'gpa_cumulative_id', 'gpa_cumulative_id');
    }
}
