<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Program;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $usersCount = User::count();
        $studentsCount = Student::count();
        $student_classesCount = StudentClass::count();
        $programsCount = Program::count();
        $lecturersCount = Lecturer::count();
        $reportsCount = Report::count();

        $semester = $request->input('semester');

        $user = Auth::user();
        //dd($user->roles->first()->name);
        $students = Student::with([
            'student_classes.program',
            'gpa_cumulative.gpa',
            'gpa_cumulative.gpa_semester',
        ])->whereHas('student_classes', function ($query) use ($user) {
            $query->where('academic_advisor_id', $user->lecturer->lecturer_id);
        })->when($semester, function ($query) use ($semester) {
            return $query->whereHas('gpa_cumulative', function ($q) use ($semester) {
                $q->where('semester', $semester);
            });
        })
            ->get();

        $semester_gpas = DB::table('gpa_semesters')
            ->join('gpa_cumulatives', 'gpa_cumulatives.gpa_cumulative_id', '=', 'gpa_semesters.gpa_cumulative_id')
            ->join('students', 'students.student_id', '=', 'gpa_cumulatives.student_id')
            ->join('classes', 'students.class_id', '=', 'classes.class_id')
            ->where('classes.academic_advisor_id', $user->lecturer->lecturer_id)
            ->groupBy('gpa_semesters.semester')
            ->select(DB::raw('gpa_semesters.semester, ROUND(AVG(gpa_semesters.semester_gpa), 2) as avg_gpa'))
            ->get();

        $chart_data = [];
        foreach ($semester_gpas as $gpa) {
            $chart_data[] = [
                'x' => 'Semester ' . $gpa->semester,
                'y' => $gpa->avg_gpa,
            ];
        }
            


        // Prepare chart data
        $gpa_data = $students->map(function ($student) {
            return $student->gpa_cumulative->cumulative_gpa;
        });

        $categories = $students->map(function ($student) {
            return $student->student_classes->class_name;
        });

        // Mengambil rata-rata cumulative_gpa dengan 2 angka desimal
        $avg_gpas = DB::table('gpa_cumulatives')
            ->select(DB::raw('ROUND(AVG(cumulative_gpa), 2) AS avg_cumulative_gpa'))
            ->value('avg_cumulative_gpa');

        return view('masterdata.dashboard', compact('chart_data','gpa_data', 'categories', 'students', 'avg_gpas', 'usersCount', 'studentsCount', 'student_classesCount', 'lecturersCount', 'programsCount', 'reportsCount'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
