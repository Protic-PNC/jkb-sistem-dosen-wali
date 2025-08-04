<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'warning_type',
        'reason',
        'date',
        'class_name',
        'entry_year'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
