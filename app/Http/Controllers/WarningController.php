<?php

namespace App\Http\Controllers;

use App\Models\Warning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        $warning = Warning::with([
            'warning_detail.student',
            'student_class',
        ])->whereHas('student_class', function($query) use ($lecturer){
            $query->where('academic_advisor_id', $lecturer->lecturer_id);
        })->get();

        return view('masterdata.warning.index', compact('warning'));
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
    public function show(Warning $warning)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warning $warning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warning $warning)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warning $warning)
    {
        //
    }
}
