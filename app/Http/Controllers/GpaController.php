<?php

namespace App\Http\Controllers;

use App\Models\Gpa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GpaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        $gpa = Gpa::with([
            'gpa_cumulative.gpa_semester',
            'gpa_cumulative.student',
            'student_class.program',
        ])->whereHas('student_class', function($query) use ($lecturer) {
            $query->where('academic_advisor_id', $lecturer->lecturer_id);
        })->get();

        $gpaTest = Gpa::with([
            'gpa_cumulative.gpa_semester',
            'gpa_cumulative.student',
            'student_class.program',
        ])->whereHas('student_class', function($query) use ($lecturer) {
            $query->where('academic_advisor_id', $lecturer->lecturer_id);
        })->firstOrFail();
        

        $jumlahSemester = 0;
        if ($gpaTest->student_class && $gpaTest->student_class->program) {
            $jumlahSemester = $gpaTest->student_class->program->degree == 'D-3' ? 6 : 8;
        }

        return view('masterdata.gpas.index', compact('gpa', 'jumlahSemester'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Gpa $gpa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gpa $gpa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gpa $gpa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gpa $gpa)
    {
        //
    }
}
