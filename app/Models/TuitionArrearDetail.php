<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuitionArrearDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'tuition_arrear_details_id';
    protected $fillable = [
        'tuition_arrear_id',
        'student_id',
        'amount'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function tuition_arrear()
    {
        return  $this->belongsTo(TuitionArrear::class, 'tuition_arrear_id', 'tuition_arrear_id');
    }
}
