<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'scholarship_detail_id';
    protected $fillable = [
        'student_id',
        'scholarship_type'
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'scholarship_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}
