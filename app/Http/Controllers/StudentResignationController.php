<?php

namespace App\Http\Controllers;

use App\Models\StudentResignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentResignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        $resignation = StudentResignation::with([
            'student_class',
            'student_resignation_detail.student'
        ])->whereHas('student_class', function ($query) use($lecturer) {
            $query->where('academic_advisor_id', $lecturer->lecturer_id);
        })->get();

        return view('masterdata.student_resignation.index', compact('resignation'));
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
    public function show(StudentResignation $studentResignation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentResignation $studentResignation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentResignation $studentResignation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentResignation $studentResignation)
    {
        //
    }
}
