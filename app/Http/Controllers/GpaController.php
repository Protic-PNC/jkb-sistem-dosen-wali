<?php

namespace App\Http\Controllers;

use App\Models\Gpa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\StudentClass;
use App\Models\Student;

class GpaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_login = Auth::user();
        $lecturer = $user_login->lecturer;

        $students = Student::with([
            'student_classes.program',
            'gpa_cumulative.gpa',
            'gpa_cumulative.gpa_semester',
        ])->whereHas('student_classes', function($query) use ($lecturer) {
            $query->where('academic_advisor_id', $lecturer->lecturer_id);
        })->get();


        if($lecturer->student_classes->program->degree == 'D3')
        {
            $jumlahSemester = 6;
        }
        else
        {
            $jumlahSemester = 8;
        }
        

         return view('masterdata.gpas.index', compact( 'jumlahSemester', 'students'));
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
