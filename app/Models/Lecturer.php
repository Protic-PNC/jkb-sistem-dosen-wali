<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'lecturer_id';
    protected $fillable = [
        'nidn',
        'nip',
        'lecturer_name',
        'lecturer_phone_number',
        'lecturer_address',
        'lecturer_signature',
        'position_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    public function program()
    {
        return $this->hasOne(Program::class, 'head_of_program_id', 'lecturer_id');
    }

    public function student_classes()
    {
        return $this->hasOne(StudentClass::class, 'academic_advisor_id', 'lecturer_id');
    }
    
}
