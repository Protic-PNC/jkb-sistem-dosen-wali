<?php

namespace App\Http\Controllers;

use App\Models\Guidance;
use App\Models\GuidanceDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuidanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $userlogin = User::find($id);

        // /** @var \App\Models\User */
        $user = Auth::user();
        if ($userlogin->hasRole('dosenWali')) {
            
            $lecturer = $user->lecturer;
            $lecturerId = $lecturer->lecturer_id;
            //dd($lecturerId);
    
            $guidance = GuidanceDetail::with([
                'student.user',
                'guidance.student_class',
            ])->whereHas('guidance.student_class', function($query) use ($lecturer) {
                $query->where('academic_advisor_id', $lecturer->lecturer_id);
            })->get();
            
        }
        else if($userlogin->hasRole('mahasiswa'))
        {
            $student = $user->student;
            $studentId = $student->student_id;

            //dd($studentId);

            $guidance = GuidanceDetail::with([
                'student.user',
                'guidance.student_class',
            ])->whereHas('student', function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })->get();

        }
        

        return view('masterdata.guidance.index', compact('guidance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $user_id = $id;
        return view('masterdat  a.guidance.create', compact('user_id'));
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
