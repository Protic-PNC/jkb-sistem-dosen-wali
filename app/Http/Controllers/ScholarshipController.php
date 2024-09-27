<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\ScholarshipDetail;
use App\Models\Student;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ScholarshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $scholarships = Scholarship::with([
            'student_classes',
            'scholarship_detail.student'
        ])->whereHas('student_classes', function ($query) use ($user) {
            $query->where('academic_advisor_id', $user->lecturer->lecturer_id);
        })->get();

        return view('masterdata.scholarships.index', compact('scholarships'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $students = Student::where('class_id', $user->lecturer->student_classes->class_id)->get();

        return view('masterdata.scholarships.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $scholarship = Scholarship::firstOrCreate(['class_id'=> $user->lecturer->student_classes->class_id]);

        try {
            $scholarshipDetail = new ScholarshipDetail();

            $scholarshipDetail->scholarship_id = $scholarship->scholarship_id;
            $scholarshipDetail->student_id = $request->input('student_id');
            $scholarshipDetail->scholarship_type = $request->input('scholarship_type');

            $scholarshipDetail->save();

            return redirect()->route('masterdata.scholarships.index')->with('success', 'Beasiswa berhasil');
        } catch (\Exception $e) {

            return redirect()->route('masterdata.scholarships.index')->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $scholarshipDetail = ScholarshipDetail::find($id);

        return view('masterdata.scholarships.edit', compact('scholarshipDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $scholarshipDetail = ScholarshipDetail::find($id);

        $validated = $request->validate([
            'scholarship_type' => 'required',
        ]);

        try {
            $scholarshipDetail->update($validated);

            return redirect()->route('masterdata.scholarships.index')->with('success', 'Beasiswa berhasil diperbbarui!');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.scholarships.index')->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $scholarshipDetail = ScholarshipDetail::find($id);

        try {
            $scholarshipDetail->delete();
            return redirect()->route('masterdata.scholarships.index')->with('success', 'Beasiswa berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.scholarships.index')->with('error', 'System error: ' . $e->getMessage());
        }
    }
}
