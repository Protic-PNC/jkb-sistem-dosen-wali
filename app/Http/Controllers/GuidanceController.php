<?php

namespace App\Http\Controllers;

use App\Models\Guidance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuidanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        $guidance = Guidance::with([
            'guidance_detail.student',
            'student_class',
        ])->whereHas('student_class', function($query) use ($lecturer) {
            $query->where('academic_advisor_id', $lecturer->lecturer_id);
        })->get();

        return view('masterdata.guidance.index', compact('guidance'));
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
    public function show(Guidance $guidance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guidance $guidance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guidance $guidance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guidance $guidance)
    {
        //
    }
}
