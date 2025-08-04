<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nidn',
        'nip',
        'lecturer_phone_number',
        'lecturer_address',
        'lecturer_signature',
        // 'lecturer_qr_signature',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->hasOne(Program::class, 'head_of_program_id');
    }

    public function student_class()
    {
        return $this->hasMany(StudentClass::class, 'academic_advisor_id');
    }
    
}
