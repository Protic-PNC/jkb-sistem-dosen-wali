<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarningDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'warning_detail_id';
    protected $fillable = [
        'warning_id',
        'student_id',
        'warning_type',
        'reason',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function warning()
    {
        return $this->belongsTo(Warning::class, 'warning_id', 'warning_id');
    }
}
