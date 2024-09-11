<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuitionArrear extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id'
    ];

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    public function tuition_arrear_detail()
    {
        return $this->hasMany(TuitionArrearDetail::class, 'tuition_arrear_id', 'tuition_arrear_id');
    }
}
