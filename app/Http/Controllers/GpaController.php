<?php

namespace App\Http\Controllers;

use App\Models\Gpa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\StudentClass;

class GpaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_login = Auth::user();
        $lecturer = $user_login->lecturer;

        
        $gpa = Gpa::with([
            'gpa_cumulative.gpa_semester',
            'gpa_cumulative.student',
            'student_class.program',
            ])->whereHas('student_class', function($query) use ($lecturer) {
                $query->where('academic_advisor_id', $lecturer->lecturer_id);
            })->get();

        // $gpaTest = Gpa::with([
        //     'gpa_cumulative.gpa_semester',
        //     'gpa_cumulative.student',
        //     'student_class.program',
        // ])->whereHas('student_class', function($query) use ($lecturer) {
        //     $query->where('academic_advisor_id', $lecturer->lecturer_id);
        // })->first();


        if ($gpa->isEmpty()) {
            // Koleksi kosong, tidak ada data GPA yang ditemukan
            $jumlahSemester = 0;
        } else {
            foreach ($gpa as $item) {
                $jumlahSemester = 0;
                if ($item->student_class && $item->student_class->program) {
                    $jumlahSemester = $item->student_class->program->degree == 'D-3' ? 6 : 8;
                }
            }
        }
        

        return view('masterdata.gpas.index', compact('gpa', 'jumlahSemester'));
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
