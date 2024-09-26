<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gpa extends Model
{
    use HasFactory;

    protected $primaryKey = 'gpa_id';

    protected $fillable = [
        'class_id'
    ];

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    public function gpa_cumulative()
    {
        return $this->hasMany(GpaCumulative::class, 'gpa_id', 'gpa_id');
    }
}
