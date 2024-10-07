<?php

namespace App\Http\Controllers;

use App\Models\Guidance;
use App\Models\GuidanceDetail;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

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
        

        return view('masterdata.guidances.index', compact('guidance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::find(Auth::user()->id);

        if($user->hasRole('dosenWali'))
        {
            $student_class = StudentClass::where('academic_advisor_id', $user->lecturer->lecturer_id)->first();
            $students = Student::where('class_id', $student_class->class_id)->get();
        }
        else if($user->hasRole('mahasiswa'))
        {
            $student_class = null;
            $students = Student::where('user_id', $user->id)->first();
        }


        return view('masterdata.guidances.create', compact('students', 'student_class'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $classId)
    {

        $user = Auth::user();
        //$student = Student::where('student_id', $request->input('student_id'))->first();
        $guidance = Guidance::firstOrCreate(['class_id' => $classId]);

        try
        {
            $guidanceDetail = new GuidanceDetail();
            $guidanceDetail->guidance_id = $guidance->guidance_id;
            $guidanceDetail->student_id = $request->input('student_id') ?? $user->student->student_id;
            $guidanceDetail->problem = $request->input('problem');
            $guidanceDetail->solution = $request->input('solution') ?? null;

            $guidanceDetail->save();

            return redirect()
                    ->route('masterdata.guidances.index', $user->id)
                    ->with('success', 'Bimbingan berhasil dibuat!');
        } catch (\Exception $e){
            return redirect()
                    ->route('masterdata.guidances.index', $user->id)
                    ->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $guidanceDetail = GuidanceDetail::find($id);

        return view('masterdata.guidances.edit', compact('guidanceDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $guidanceDetail = GuidanceDetail::find($id);

       // dd($guidanceDetail);

        $validated = $request->validate([
            'problem' => 'required',
            'solution' => 'required',
        ]);

        try
        {
            $guidanceDetail->update($validated);
            return redirect()->route('masterdata.guidances.index', Auth::user()->id)->with('success', 'Bimbingan berhasil diperbarui!');
        }catch (\Exception $e)
        {
            return redirect()
                    ->route('masterdata.guidances.index', Auth::user()->id)
                    ->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       $guidanceDetail = GuidanceDetail::find($id);

       try
       {
        $guidanceDetail->delete();

        return redirect()->route('masterdata.guidances.index', Auth::user()->id)->with('success', 'Bimbingan berhasil dihapus');
       }catch (\Exception $e)
       {
        return redirect()->route('masterdata.guidances.index', Auth::user()->id)->with('error', 'System error: '.$e->getMessage());
       }
    }
}
