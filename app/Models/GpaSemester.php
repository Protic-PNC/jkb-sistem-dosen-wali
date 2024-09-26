<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpaSemester extends Model
{
    use HasFactory;

    protected $primaryKey = 'gpa_semesters_id';
    protected $fillable = [
        'gpa_cumulative_id',
        'semester',
        'semester_gpa',
    ];

    public function gpa_cumulative()
    {
        return $this->belongsTo(GpaCumulative::class, 'gpa_cumulative_id', 'gpa_cumulative_id');
    }
}
