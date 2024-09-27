<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $primaryKey = 'sholarship_id';
    protected $fillable = [
        'class_id'
    ];

    public function student_classes()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    public function scholarship_detail()
    {
        return $this->hasMany(ScholarshipDetail::class, 'scholarship_id', 'scholarship_id');
    }
}
