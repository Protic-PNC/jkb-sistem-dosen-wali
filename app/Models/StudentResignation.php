<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResignation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'resignation_type',
        'decree_number',
        'reason',
        'date'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
