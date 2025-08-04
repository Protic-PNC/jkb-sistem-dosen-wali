<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KrsFormat extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'semester',
        'academic_year',
        'file'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function krs()
    {
        return $this->hasMany(Krs::class);
    }
}
