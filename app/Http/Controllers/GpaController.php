<?php

namespace App\Http\Controllers;

use App\Models\Gpa;
use App\Models\GpaCumulative;
use App\Models\GpaSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\StudentClass;
use App\Models\Student;
use Illuminate\Support\Carbon;

class GpaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_login = Auth::user();
        $lecturer = $user_login->lecturer;

        $students = Student::with([
            'student_classes.program',
            'gpa_cumulative.gpa',
            'gpa_cumulative.gpa_semester',
        ])->whereHas('student_classes', function($query) use ($lecturer) {
            $query->where('academic_advisor_id', $lecturer->lecturer_id);
        })->get();

        $studentClass = StudentClass::where('academic_advisor_id', $lecturer->lecturer_id)->first();


        if($lecturer->student_classes->program->degree == 'D3')
        {
            $jumlahSemester = 6;
        }
        else
        {
            $jumlahSemester = 8;
        }
        

         return view('masterdata.gpas.index', compact( 'jumlahSemester', 'students', 'studentClass'));
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
    public function edit(Gpa $gpa, $classId)
    {
        $studentClass = StudentClass::find($classId);

        $students = Student::with([
            'student_classes.program',
            'gpa_cumulative.gpa',
            'gpa_cumulative.gpa_semester',
        ])->whereHas('student_classes', function($query) use ($studentClass) {
            $query->where('class_id', $studentClass->class_id);
        })->get();

        if($studentClass->program->degree == 'D3')
        {
            $jumlahSemester = 6;
        }
        else
        {
            $jumlahSemester = 8;
        }

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $year_diff = $currentYear - $studentClass->academic_year;

        //mulai semester ganjil (tahun ajaran baru)
        if($currentMonth >= 8)
        {
            $year_diff++;

            $currentSemester = ($year_diff * 2)-1;
        }
        else
        {
            $currentSemester = ($year_diff * 2);
        }

        return view('masterdata.gpas.edit', compact('jumlahSemester', 'students', 'currentSemester', 'studentClass'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $classId)
    {
        try
        {
            $studentClass = StudentClass::find($classId);

            $ipsData = $request->input('ips');

            //dd($ipsData);

            $gpa = Gpa::firstOrCreate(['class_id' => $studentClass->class_id]);

            foreach ($ipsData as $nim => $semesters)
            {
                $student = Student::where('nim', $nim)->first();

                if($student)
                {
                    $cumulative_gpa = GpaCumulative::firstOrCreate([
                        'gpa_id' => $gpa->gpa_id,
                        'student_id' => $student->student_id,
                    ]);

                    foreach($semesters as $semester => $ips)
                    {
                        //Simpan data IPS untuk tiap semester
                        GpaSemester::updateOrCreate(
                            [
                                'gpa_cumulative_id' => $cumulative_gpa->gpa_cumulative_id,
                                'semester' => $semester,
                            ],
                            [
                                'semester_gpa' => $ips !== null ? $ips : null
                            ]
                        );
                    }

                    //hitung rata-rata gpa dengan memfilter data yang tidak null
                    $averageGpa = GpaSemester::where('gpa_cumulative_id', $cumulative_gpa->gpa_cumulative_id)
                    //->whereNotNull('semester_gpa')
                    ->avg('semester_gpa');

                    //simpan nilai rata-rata ke cumulative gpa
                    $cumulative_gpa->cumulative_gpa = $averageGpa;
                    $cumulative_gpa->save();
                }
            }

            return redirect()->route('masterdata.gpas.index')->with('success', 'ips berhasil diedit!');
        } catch(\Exception $e)
        {
            return redirect()
                ->route('masterdata.gpas.index')
                ->with('error', 'System error : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gpa $gpa)
    {
        //
    }
}
