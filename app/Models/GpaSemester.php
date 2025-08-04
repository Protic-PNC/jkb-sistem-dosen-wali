<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpaSemester extends Model
{
    use HasFactory;

    protected $fillable = [
        'gpa_cumulative_id',
        'semester',
        'semester_gpa',
    ];

    public function gpa_cumulative()
    {
        return $this->belongsTo(GpaCumulative::class);
    }
}
