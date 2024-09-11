<?php

namespace App\Http\Controllers;

use App\Models\TuitionArrear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TuitionArrearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        $tuition = TuitionArrear::with([
            'student_class',
            'tuition_arrear_detail.student'
        ])->whereHas('student_class', function ($query) use($lecturer) {
            $query->where('academic_advisor_id', $lecturer->lecturer_id);
        })->get();

        return view('masterdata.tuition_arrears.index', compact('tuition'));
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
    public function show(TuitionArrear $tuitionArrear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TuitionArrear $tuitionArrear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TuitionArrear $tuitionArrear)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TuitionArrear $tuitionArrear)
    {
        //
    }
}
