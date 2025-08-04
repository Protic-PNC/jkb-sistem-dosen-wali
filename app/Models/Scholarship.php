<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'scholarship_type',
        'semester',
        'class_name',
        'entry_year'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
