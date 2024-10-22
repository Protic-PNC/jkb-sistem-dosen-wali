<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
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

use App\Models\Lecturer;
use Illuminate\Support\Facades\Session;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Student;

class ExportController extends Controller
{
    public function exportPdf($id, Request $request)
    {
        // Retrieve saved chart image from session
        $chartImage = Session::get("chartImage_$id");

        // dd($chartImage);

        //$chartImage = $request->input('chartImage'); // menerima base64 chart image

        $report = Report::find($id);
        $semester = $report->semester;
        $entryYear = $report->student_class->entry_year;
        $class = StudentClass::find($report->class_id);

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

            //menentukan academic_year
            if($semester %2 == 1) {
                $firstYear = $entryYear + ($semester - 1) / 2; 
            }
            else
            {
                $firstYear = $entryYear + ($semester - 2) / 2;
            }
            $secondYear = $firstYear + 1;

            $academicYear = $firstYear . '-' . $secondYear;

            
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

            if($lecturer->student_classes->program->degree == 'D3')
            {
                $jumlahSemester = 6;
            }
            else
            {
                $jumlahSemester = 8;
            }

            // Mengambil rata-rata cumulative_gpa untuk seluruh mahasiswa
            $avg_cumulative_gpa = DB::table('gpa_cumulatives')
            ->whereIn('student_id', $studentsChart->pluck('student_id'))
            ->select(DB::raw('ROUND(AVG(cumulative_gpa), 2) AS avg_cumulative_gpa'))
            ->value('avg_cumulative_gpa');

            $chart_data = [];
            for($i =1; $i <= $semester; $i++)
            {
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

        //dd($chart_data, $semester_gpas);


        if($class->program->degree == 'D3')
        {
            $jumlahSemester = 6;
        }
        else
        {
            $jumlahSemester = 8;
        }

        //menentukan tahun berdasarkan $semester
        $tahun = $report->student_class->entry_year + intdiv($semester, 2);

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
                $query->whereBetween('semester', [1,$semester]);
            }
        ])->whereHas('student_classes', function($query) use ($class) {
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
        if($warnings)
        {
            $warningDetail = WarningDetail::where('warning_id', $warnings->warning_id)
                                        ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                                        ->get();
        }

        $guidances = Guidance::where('class_id', $class->class_id)->first();
        $guidanceDetail = null;
        if($guidances)
        {
            $guidanceDetail = GuidanceDetail::where('guidance_id', $guidances->guidance_id)
                                    ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                                    ->get();
        }


        //mengambil data penerima beasiswa
        $scholarships = Scholarship::where('class_id', $class->class_id)->first();
        $scholarshipDetail = null;
        if($scholarships)
        {
            $scholarshipDetail = ScholarshipDetail::where('scholarship_id', $scholarships->scholarship_id)
                                    ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                                    ->get();
        }

        //mengambil data tunggakan ukt
        $tuition_arrears = TuitionArrear::where('class_id', $class->class_id)->first();
        $tuition_arrearDetail = null;
        if($tuition_arrears)
        {
            $tuition_arrearDetail = TuitionArrearDetail::where('tuition_arrear_id', $tuition_arrears->tuition_arrear_id)
                                    ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                                    ->get();
        }

        //mengambil data siswa yang mengundurkan diri
        $student_resignations = StudentResignation::where('class_id', $class->class_id)->first();
        $student_resignationDetail = null;
        if($student_resignations)
        {
            $student_resignationDetail = StudentResignationDetail::where('student_resignation_id', $student_resignations->student_resignation_id)
                                    ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                                    ->get();
        }


        //mengambil data siswa berprestasi atau keaktifan organisasi
        $achievements = Achievement::where('class_id', $class->class_id)->first();
        $achievementDetail = null;
        if($achievements)
        {
            $achievementDetail = AchievementDetail::where('achievement_id', $achievements->achievement_id)
                                    ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                                    ->get();
        }

        // dd($student_resignationDetail);

        $pdf = Pdf::loadView('masterdata.reports.pdf', compact('academicYear','chartImage','studentsChart', 'semester_gpas', 'chart_data', 'gpa_data', 'categories', 'avg_gpas', 'table_data', 'avg_cumulative_gpa','students', 'jumlahSemester', 'class', 'semester', 'warningDetail', 'guidanceDetail', 'scholarshipDetail', 'tuition_arrearDetail', 'student_resignationDetail', 'achievementDetail'));
         // Return the PDF for download
        return $pdf->stream('report_' . $id . '.pdf');
    }

    public function saveChartImage(Request $request, $id)
    {
        // Decode base64 image data
        $chartImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->chartImage));
        // Store image temporarily (you can also use a file or cache storage)
        Session::put("chartImage_$id", $chartImage);
        return response()->json(['status' => 'Image saved']);
    }







    public function exportWord($id)
    {
        
    }
}
