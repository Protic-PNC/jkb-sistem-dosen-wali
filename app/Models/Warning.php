<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    use HasFactory;

    protected $primaryKey = 'warning_id';
    protected $fillable = [
        'class_id'
    ];

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    public function warning_detail()
    {
        return $this->hasMany(WarningDetail::class, 'warning_id', 'warning_id');
    }

    // public function report()
    // {
    //     return $this->hasOne(Report::class);
    // }
}
