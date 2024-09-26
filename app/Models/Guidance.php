<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guidance extends Model
{
    use HasFactory;

    protected $primaryKey = 'guidance_id';
    protected $fillable = [
        'class_id'
    ];

    public function student_class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    public function guidance_detail()
    {
        return $this->hasMany(GuidanceDetail::class, 'guidance_id', 'guidance_id');
    }
}
