<?php

namespace App\Http\Controllers;

use App\Models\TuitionArrear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Student;
use App\Models\TuitionArrearDetail;

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
        ])->whereHas('student_class', function ($query) use ($lecturer) {
            $query->where('academic_advisor_id', $lecturer->lecturer_id);
        })->get();

        return view('masterdata.tuition_arrears.index', compact('tuition'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $students = Student::where('class_id', $user->lecturer->student_classes->class_id)->get();

        return view('masterdata.tuition_arrears.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        
        try {
            $tuitionArrear = TuitionArrear::firstOrCreate(['class_id' => $user->lecturer->student_classes->class_id]);

            $tuitionArrearDetail = new TuitionArrearDetail();
            $tuitionArrearDetail->tuition_arrear_id = $tuitionArrear->tuition_arrear_id;
            $tuitionArrearDetail->student_id = $request->input('student_id');
            $tuitionArrearDetail->amount = $request->input('amount');

            $tuitionArrearDetail->save();

            return redirect()->route('masterdata.tuition_arrears.index')->with('success', 'Tunggakan UKT berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.tuition_arrears.index')->with('error', ' System error: ' . $e->getMessage());
        }
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
    public function edit($id)
    {
        $tuitionArrearDetail = TuitionArrearDetail::find($id);

        return view('masterdata.tuition_arrears.edit', compact('tuitionArrearDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tuitionArrearDetail = TuitionArrearDetail::find($id);

        $validated = $request->validate([
            'amount' => 'required',
        ]);

        try {
            $tuitionArrearDetail->update($validated);

            return redirect()->route('masterdata.tuition_arrears.index')->with('success', 'Tunggakan ' . $tuitionArrearDetail->student->student_name . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->route('masterdata.tuition_arrears.index')
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tuitionArrearDetail = TuitionArrearDetail::find($id);

        try {
            
            $tuitionArrearDetail->delete();

            return redirect()->route('masterdata.tuition_arrears.index')->with('success', 'Tunggakan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->route('masterdata.tuition_arrears.index')
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }
}
