<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'krs_format_id',
        'file',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function krs_format()
    {
        return $this->belongsTo(KrsFormat::class);
    }
}
