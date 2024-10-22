<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentClass;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Warning;
use App\Models\WarningDetail;

use App\Models\Gpa;
use App\Models\GpaCumulative;
use App\Models\GpaSemester;

use App\Models\Scholarship;;

use App\Models\ScholarshipDetail;

use App\Models\TuitionArrear;
use App\Models\TuitionArrearDetail;

use App\Models\StudentResignation;
use App\Models\StudentResignationDetail;

use App\Models\Achievement;
use App\Models\AchievementDetail;
use App\Models\Guidance;
use App\Models\GuidanceDetail;

use App\Models\Student;
use App\Models\Lecturer;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jumlahSemester = 0;
        $usedSemesters = [];
        $studentClass = null;
        $currentSemester = 0;

        $user = User::find(Auth::user()->id);

        if ($user->hasRole('admin')) {
            $reports = Report::orderBy('semester', 'asc')->get();
            $studentClass = StudentClass::all();
        } else if ($user->hasRole('dosenWali')) {
            $reports = Report::where('class_id', $user->lecturer->student_classes->class_id)->orderBy('semester', 'asc')->get();

            if ($user->lecturer->student_classes->program->degree == 'D3') {
                $jumlahSemester = 6;
            } else {
                $jumlahSemester = 8;
            }

            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            $year_diff = $currentYear - $user->lecturer->student_classes->academic_year;

            //mulai semester ganjil (tahun ajaran baru)
            if ($currentMonth >= 8) {
                $year_diff++;

                $currentSemester = ($year_diff * 2) - 1;
            } else {
                $currentSemester = ($year_diff * 2);
            }

            $usedSemesters = $reports->pluck('semester')->toArray();
            //$studentClass = StudentClass::where('class_id', $user->lecturer->student_classes->class_id)->first();
        } else if ($user->hasRole('kaprodi')) {
            $reports = Report::whereHas('student_class', function ($query) use ($user) {
                $query->where('program_id', $user->lecturer->program->program_id);
            })
                ->orderBy('semester', 'asc')
                ->get();

            // dd($reports);
            $studentClass = StudentClass::all();
        }
        //dd($usedSemesters);

        return view('masterdata.reports.index', compact('currentSemester', 'reports', 'jumlahSemester', 'usedSemesters', 'studentClass'));
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
        $user = User::find(Auth::user()->id);
        try {
            if ($user->hasRole('dosenWali')) {
                $class = StudentClass::find($user->lecturer->student_classes->class_id);
            } else {
                $class = StudentClass::find($request->input('student_class'));
            }

            if ($request->input('pilih-semester')) {

                $semester = $request->input('pilih-semester');
                $report = Report::create([
                    'class_id' => $class->class_id,
                    'semester' => $semester,
                ]);
            } else {
                $awal_semester = $request->input('dari-semester');
                $akhir_semester = $request->input('sampai-semester');
                $jumlahSemester = $akhir_semester - $awal_semester + 1;

                DB::transaction(function () use ($class, $awal_semester, $akhir_semester) {
                    for ($i = $awal_semester; $i <= $akhir_semester; $i++) {
                        Report::create([
                            'class_id' => $class->class_id,
                            'semester' => $i,
                        ]);
                    }
                });
            }
            dd($semester, $awal_semester, $jumlahSemester);
            return redirect()->route('masterdata.reports.index')->with('success', 'Laporan berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.reports.index')
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $report = Report::find($id);
        $semester = $report->semester;
        $class = StudentClass::find($report->class_id);


        if ($class->program->degree == 'D3') {
            $jumlahSemester = 6;
        } else {
            $jumlahSemester = 8;
        }

        //menentukan tahun berdasarkan $semester
        $tahun = $report->student_class->academic_year + intdiv($semester, 2);

        // Tentukan rentang bulan dan tahun berdasarkan ganjil/genap
        if ($semester % 2 == 1) {
            // Semester ganjil
            $bulanAwal = 8;
            $bulanAkhir = 1;
            $tahunAkhir = $tahun + 1; // Ganjil mencakup dua tahun ajaran
        } else {
            // Semester genap
            $bulanAwal = 2;
            $bulanAkhir = 7;
            $tahunAkhir = $tahun; // Genap ada dalam satu tahun ajaran
        }

        // Tentukan tanggal awal dan akhir untuk pemfilteran berdasarkan created_at
        $tanggalAwal = Carbon::create($tahun, $bulanAwal, 1)->startOfMonth();
        $tanggalAkhir = Carbon::create($tahunAkhir, $bulanAkhir, 1)->endOfMonth();

        $students = Student::with([
            'student_classes.program',
            'gpa_cumulative.gpa',
            'gpa_cumulative.gpa_semester' => function ($query) use ($semester) {
                $query->whereBetween('semester', [1, $semester]);
            }
        ])->whereHas('student_classes', function ($query) use ($class) {
            $query->where('class_id', $class->class_id);
        })->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
            // Mahasiswa aktif atau tidak aktif setelah rentang semester
            $query->where('status', 'active')
                ->orWhere('inactive_at', '>', $tanggalAkhir)
                ->orWhereNull('inactive_at'); // Jika tidak ada tanggal inactive
        })
            ->get();

        //mengambil data peringatan
        $warnings = Warning::where('class_id', $class->class_id)->first();
        $warningDetail = null;
        if ($warnings) {
            $warningDetail = WarningDetail::where('warning_id', $warnings->warning_id)
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }

        $guidances = Guidance::where('class_id', $class->class_id)->first();
        $guidanceDetail = null;
        if ($guidances) {
            $guidanceDetail = GuidanceDetail::where('guidance_id', $guidances->guidance_id)
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }


        //mengambil data penerima beasiswa
        $scholarships = Scholarship::where('class_id', $class->class_id)->first();
        $scholarshipDetail = null;
        if ($scholarships) {
            $scholarshipDetail = ScholarshipDetail::where('scholarship_id', $scholarships->scholarship_id)
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }

        //mengambil data tunggakan ukt
        $tuition_arrears = TuitionArrear::where('class_id', $class->class_id)->first();
        $tuition_arrearDetail = null;
        if ($tuition_arrears) {
            $tuition_arrearDetail = TuitionArrearDetail::where('tuition_arrear_id', $tuition_arrears->tuition_arrear_id)
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }

        //mengambil data siswa yang mengundurkan diri
        $student_resignations = StudentResignation::where('class_id', $class->class_id)->first();
        $student_resignationDetail = null;
        if ($student_resignations) {
            $student_resignationDetail = StudentResignationDetail::where('student_resignation_id', $student_resignations->student_resignation_id)
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }

        //mengambil data siswa berprestasi atau keaktifan organisasi
        $achievements = Achievement::where('class_id', $class->class_id)->first();
        $achievementDetail = null;
        if ($achievements) {
            $achievementDetail = AchievementDetail::where('achievement_id', $achievements->achievement_id)
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }


        //untuk chart
        $lecturer = Lecturer::find($report->student_class->academic_advisor->lecturer_id);
        if ($lecturer) {
            $studentsChart = Student::with([
                'student_classes.program',
                'gpa_cumulative.gpa',
                'gpa_cumulative.gpa_semester',
            ])->whereHas('student_classes', function ($query) use ($lecturer) {
                $query->where('academic_advisor_id', $lecturer->lecturer_id);
            })->get();

            $semester_gpas = DB::table('gpa_semesters')
                ->join('gpa_cumulatives', 'gpa_cumulatives.gpa_cumulative_id', '=', 'gpa_semesters.gpa_cumulative_id')
                ->join('students', 'students.student_id', '=', 'gpa_cumulatives.student_id')
                ->join('classes', 'students.class_id', '=', 'classes.class_id')
                // ->join('programs', 'program.program_id', '=', 'classes.program_id')
                ->where('classes.academic_advisor_id', $lecturer->lecturer_id)
                ->whereBetween('gpa_semesters.semester', [1, $semester])
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

            if ($lecturer->student_classes->program->degree == 'D3') {
                $jumlahSemester = 6;
            } else {
                $jumlahSemester = 8;
            }

            // Mengambil rata-rata cumulative_gpa untuk seluruh mahasiswa
            $avg_cumulative_gpa = DB::table('gpa_cumulatives')
                ->whereIn('student_id', $studentsChart->pluck('student_id'))
                ->select(DB::raw('ROUND(AVG(cumulative_gpa), 2) AS avg_cumulative_gpa'))
                ->value('avg_cumulative_gpa');

            $chart_data = [];
            for ($i = 1; $i <= $jumlahSemester; $i++) {
                // Search for the corresponding GPA data for the current semester
                $gpa_for_semester = $semester_gpas->firstWhere('semester', $i);

                // If GPA data is found, use it; otherwise, default to 0 for avg_gpa
                $avg_gpa = $gpa_for_semester ? $gpa_for_semester->avg_gpa : 0;

                // Add data to the chart array
                $chart_data[] = [
                    'x' => 'Semester ' . $i,
                    'y' => $avg_gpa,
                ];
            }
            // Prepare chart data
            $gpa_data = $studentsChart->map(function ($student) {
                return $student->gpa_cumulative->cumulative_gpa ?? 0;
            });

            $categories = $studentsChart->map(function ($student) {
                return $student->student_classes->class_name;
            });

            // Mengambil rata-rata cumulative_gpa dengan 2 angka desimal
            $avg_gpas = DB::table('gpa_cumulatives')
                ->select(DB::raw('ROUND(AVG(cumulative_gpa), 2) AS avg_cumulative_gpa'))
                ->value('avg_cumulative_gpa');
        } else {
            $studentsChart = [];
            $semester_gpas = [];
            $chart_data = [];
            $gpa_data = [];
            $categories = [];
            $avg_gpas = [];
            $table_data = [];
            $avg_cumulative_gpa = [];
            $jumlahSemester = 0;
        }



        return view('masterdata.reports.show', compact('studentsChart', 'semester_gpas', 'chart_data', 'gpa_data', 'categories', 'avg_gpas', 'table_data', 'avg_cumulative_gpa', 'report', 'students', 'jumlahSemester', 'class', 'semester', 'warningDetail', 'guidanceDetail', 'scholarshipDetail', 'tuition_arrearDetail', 'student_resignationDetail', 'achievementDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $report = Report::find($id);

        //dd($request->update_type);
        try {

            if ($request->update_type == 'acc_academic_advisor') {
                $report->update(
                    ['has_acc_academic_advisor' => 1],
                );
            }
            if ($request->update_type == 'reject_academic_advisor') {
                $report->update(
                    ['has_acc_academic_advisor' => 0],
                );
            }
            else if ($request->update_type == 'acc_head_of_program') {
                $report->update(
                    ['has_acc_head_of_program' => 1]
                );
            }
            else if ($request->update_type == 'reject_head_of_program') {
                $report->update(
                    ['has_acc_head_of_program' => 0]
                );
            }

            return redirect()->route('masterdata.reports.index')
                ->with('success', 'status dosen wali berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.reports.index')
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $report = Report::find($id);

        try {
            $report->delete();
            return redirect()->route('masterdata.reports.index')
                ->with('success', 'laporan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.reports.index')
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }
}
