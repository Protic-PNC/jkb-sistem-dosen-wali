<?php

namespace App\Http\Controllers;

use App\Models\StudentResignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Student;
use App\Models\StudentResignationDetail;
use App\Models\TuitionArrearDetail;

use Carbon\Carbon;

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

        return view('masterdata.student_resignations.index', compact('resignation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $students = Student::where('class_id', $user->lecturer->student_classes->class_id)->get();

        return view('masterdata.student_resignations.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        try
        {
            $studentResignation = StudentResignation::firstOrCreate(['class_id' => $user->lecturer->student_classes->class_id]);
            
            $student = Student::find($request->input('student_id'));
            $student->update([
                'status' => 'non-active',
                'inactive_at' => Carbon::now()
            ],
            );

            //dd($student->inactive_at);

            $studentResignationDetail = new StudentResignationDetail();
            $studentResignationDetail->student_resignation_id = $studentResignation->student_resignation_id;
            $studentResignationDetail->student_id = $student->student_id;
            $studentResignationDetail->resignation_type = $request->input('resignation_type');
            $studentResignationDetail->decree_number = $request->input('decree_number');
            $studentResignationDetail->reason = $request->input('reason');

            $studentResignationDetail->save();

            return redirect()->route('masterdata.student_resignations.index')->with('success', 'Pengunduran diri mahasiswa berhasil ditambah!');
        }catch(\Exception $e)
        {
            return redirect()->route('masterdata.student_resignations.index')->with('error', $e->getMessage());
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
        $studentResignationDetail = StudentResignationDetail::find($id);

        return view('masterdata.student_resignations.edit', compact('studentResignationDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $studentResignationDetail = StudentResignationDetail::find($id);

        $validated = $request->validate([
            'resignation_type' => 'required',
            'decree_number' => 'required',
            'reason' => 'required',
        ]);

        try {
            $studentResignationDetail->update($validated);

            return redirect()->route('masterdata.student_resignations.index')->with('success', 'Undur diri ' . $studentResignationDetail->student->student_name . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->route('masterdata.student_resignations.index')
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $studentResignationDetail = StudentResignationDetail::find($id);

        try {
            $student = Student::find($studentResignationDetail->student_id);

            $student->update(
                ['status' => 'active'],
                ['inactive_at' => null],
            );
            
            $studentResignationDetail->delete();

            return redirect()->route('masterdata.student_resignations.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->route('masterdata.student_resignations.index')
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }
}
