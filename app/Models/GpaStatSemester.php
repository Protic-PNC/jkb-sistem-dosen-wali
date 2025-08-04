<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpaStatSemester extends Model
{
    use HasFactory;

    protected $fillable = [
        'gpa_stat_id',
        'semester',
        'avg',
        'min',
        'max',
        'below_3',
        'below_3_percent',
        'above_equal_3',
        'above_equal_3_percent',
    ];

    public function gpa_stat()
    {
        return $this->belongsTo(GpaStat::class);
    }
}