<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Khs extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'semester',
        'file',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
