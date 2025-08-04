<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name',
        'degree',
        'head_of_program_id',
    ];

    public function head_of_program()
    {
        return $this->belongsTo(Lecturer::class, 'head_of_program_id');
    }

    public function student_class()
    {
        return $this->hasMany(StudentClass::class);
    }

    public function krs_format()
    {
        return $this->hasMany(KrsFormat::class);
    }
}
