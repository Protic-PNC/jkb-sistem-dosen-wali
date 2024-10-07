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

       // $semester = $request->input('semester');

        $user = Auth::user();
        //dd($user->roles->first()->name);
        if ($user->lecturer) {
            $students = Student::with([
                'student_classes.program',
                'gpa_cumulative.gpa',
                'gpa_cumulative.gpa_semester',
            ])->whereHas('student_classes', function ($query) use ($user) {
                $query->where('academic_advisor_id', $user->lecturer->lecturer_id);
            })->get();

            $semester_gpas = DB::table('gpa_semesters')
                ->join('gpa_cumulatives', 'gpa_cumulatives.gpa_cumulative_id', '=', 'gpa_semesters.gpa_cumulative_id')
                ->join('students', 'students.student_id', '=', 'gpa_cumulatives.student_id')
                ->join('classes', 'students.class_id', '=', 'classes.class_id')
                ->where('classes.academic_advisor_id', $user->lecturer->lecturer_id)
                ->groupBy('gpa_semesters.semester')
                ->select(DB::raw(
                    'gpa_semesters.semester, ROUND(AVG(gpa_semesters.semester_gpa), 2) as avg_gpa,
                    MAX(gpa_semesters.semester_gpa) as max_gpa,
                    MIN(gpa_semesters.semester_gpa) as min_gpa,
                    SUM(CASE WHEN gpa_semesters.semester_gpa < 3.00 THEN 1 ELSE 0 END) as count_below_3,
                    SUM(CASE WHEN gpa_semesters.semester_gpa >= 3.00 THEN 1 ELSE 0 END) as count_above_3,
                    COUNT(gpa_semesters.semester_gpa) as total_students
                '
                ))
                ->get();

            
            $table_data = [];
            foreach ($semester_gpas as $gpa) {
                $percentage_below_3 = $gpa->total_students > 0 ? round(($gpa->count_below_3 / $gpa->total_students) * 100, 2) : 0;
                $percentage_above_3 = $gpa->total_students > 0 ? round(($gpa->count_above_3 / $gpa->total_students) * 100, 2) : 0;

                $table_data[] = [
                    'semester' => 'SMT ' . $gpa->semester,
                    'avg_gpa' => $gpa->avg_gpa,
                    'max_gpa' => $gpa->max_gpa,
                    'min_gpa' => $gpa->min_gpa,
                    'count_below_3' => $gpa->count_below_3,
                    'percentage_below_3' => $percentage_below_3,
                    'count_above_3' => $gpa->count_above_3,
                    'percentage_above_3' => $percentage_above_3,
                ];
            }

            // Mengambil rata-rata cumulative_gpa untuk seluruh mahasiswa
            $avg_cumulative_gpa = DB::table('gpa_cumulatives')
            ->whereIn('student_id', $students->pluck('student_id'))
            ->select(DB::raw('ROUND(AVG(cumulative_gpa), 2) AS avg_cumulative_gpa'))
            ->value('avg_cumulative_gpa');

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

            if($user->lecturer->student_classes->program->degree == 'D3')
            {
                $jumlahSemester = 6;
            }
            else
            {
                $jumlahSemester = 8;
            }
        } else {
            $students = [];
            $semester_gpas = [];
            $chart_data = [];
            $gpa_data = [];
            $categories = [];
            $avg_gpas = [];
            $table_data = [];
            $avg_cumulative_gpa = [];
            $jumlahSemester = 0;
        }

        return view('masterdata.dashboard', compact('jumlahSemester','table_data', 'avg_cumulative_gpa', 'chart_data', 'gpa_data', 'categories', 'students', 'avg_gpas', 'usersCount', 'studentsCount', 'student_classesCount', 'lecturersCount', 'programsCount', 'reportsCount'));
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
