<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guidance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'problem',
        'solution',
        'problem_date',
        'solution_date',
        'is_validated',
        'validation_note',
        'created_by',
        'class_name',
        'entry_year',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
