<?php

namespace App\Helpers;

use App\Models\Student;
use App\Models\StudentResignation;
use App\Models\Scholarship;
use App\Models\Achievement;
use App\Models\GpaStat;
use App\Models\Warning;
use App\Models\TuitionArrear;
use App\Models\Guidance;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReportExportHelper
{
    public static function generateTemporaryQr(string $signaturePath): ?string
    {
        if (!$signaturePath) {
            return null;
        }

        $ip = getHostByName(getHostName()); // atau sesuaikan dengan IP server
        $qrContent = "http://$ip:8000/storage/$signaturePath";

        $fileName = 'qr_' . uniqid() . '.png';
        $fullPath = storage_path("app/public/temp_qr/$fileName");

        if (!Storage::disk('public')->exists('temp_qr')) {
            Storage::disk('public')->makeDirectory('temp_qr');
        }

        QrCode::format('png')->size(300)->generate($qrContent, $fullPath);

        return 'temp_qr/' . $fileName; // path relatif dari storage/app/public
    }

    public static function prepareAdvisorReportData($report, $studentClass, $program, $semester, $jumlahSemester, $dateStart, $dateEnd)
    {
        $report = Report::find($report->id);

        // GPA & IPK
        $students = Student::where('student_class_id', $studentClass->id)
            ->with(['gpa_cumulative.gpa_semester', 'user'])
            ->get();

        $gpaInputs = [];
        $ipkResults = [];

        foreach ($students as $student) {
            if ($student->gpa_cumulative) {
                $ips = [];
                foreach ($student->gpa_cumulative->gpa_semester as $gpa) {
                    $gpaInputs[$student->id][$gpa->semester] = $gpa->semester_gpa;
                    if ($gpa->semester <= $report->semester) {
                        $ips[] = $gpa->semester_gpa;
                    }
                }

                $ipkResults[$student->id] = count($ips) ? round(array_sum($ips) / count($ips), 2) : null;
            }
        }

        $lecturerId = $report->academic_advisor_id;

        
        $semester_gpas = DB::table('gpa_semesters')
                ->join('gpa_cumulatives', 'gpa_cumulatives.id', '=', 'gpa_semesters.gpa_cumulative_id')
                ->join('students', 'students.id', '=', 'gpa_cumulatives.student_id')
                ->join('student_classes', 'students.student_class_id', '=', 'student_classes.id')
                // ->join('programs', 'program.id', '=', 'student_classes.program_id')
                ->where('student_classes.academic_advisor_id', $lecturerId)
                ->whereBetween('gpa_semesters.semester', [1, $semester])
                ->groupBy('gpa_semesters.semester')
                ->select(DB::raw(
                    'gpa_semesters.semester,
                    gpa_semesters.semester, ROUND(AVG(gpa_semesters.semester_gpa), 2) as avg_gpa,
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

            $table_data["SMT " . $gpa->semester] = [
                'avg' => $gpa->avg_gpa,
                'max' => $gpa->max_gpa,
                'min' => $gpa->min_gpa,
                'below_3' => $gpa->count_below_3,
                'below_3_percent' => $percentage_below_3,
                'above_equal_3' => $gpa->count_above_3,
                'above_equal_3_percent' => $percentage_above_3,
            ];
        }

        $stat = GpaStat::with('gpa_stat_semester')
            ->where('student_class_id', $studentClass->id)
            ->first();

        $stats = [];

        if ($stat) {
            foreach ($stat->gpa_stat_semester as $record) {
                $semesterKey = 'SMT ' . $record->semester;

                $stats[$semesterKey] = [
                    'avg' => $record->avg,
                    'min' => $record->min,
                    'max' => $record->max,
                    'below_3' => $record->below_3,
                    'below_3_percent' => $record->below_3_percent,
                    'above_equal_3' => $record->above_equal_3,
                    'above_equal_3_percent' => $record->above_equal_3_percent,
                ];
            }
        }

        $advisorSignaturePath = $report->submitted_at ? $report->academic_advisor->lecturer_signature : null;
        $kaprodiSignaturePath = $report->approved_at
            ? $report->student_class->program->head_of_program->lecturer_signature
            : null;

        $advisorQrTempPath = $advisorSignaturePath ? self::generateTemporaryQr($advisorSignaturePath) : null;
        $kaprodiQrTempPath = $kaprodiSignaturePath ? self::generateTemporaryQr($kaprodiSignaturePath) : null;

        return [
            'report' => $report,
            'students' => $students,
            'gpaInputs' => $gpaInputs,
            'ipkResults' => $ipkResults,
            'studentClass' => $studentClass,
            'program' => $program,
            'semester' => $semester,
            'jumlahSemester' => $jumlahSemester,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'table_data' => $stats,
            'resignations' => StudentResignation::with('student')->whereBetween('date', [$dateStart, $dateEnd])->get(),
            'scholarships' => Scholarship::with('student')->where('semester', $semester)->where('class_name', $report->class_name)->where('entry_year', $report->entry_year)->get(),
            'achievements' => Achievement::with('student')->where('semester', $semester)->where('class_name', $report->class_name)->where('entry_year', $report->entry_year)->get(),
            'warnings' => Warning::with('student')->whereBetween('date', [$dateStart, $dateEnd])->where('class_name', $report->class_name)->where('entry_year', $report->entry_year)->get(),
            'arrears' => TuitionArrear::with('student')->where('semester', $semester)->where('class_name', $report->class_name)->where('entry_year', $report->entry_year)->get(),
            'guidances' => Guidance::with('student')->whereBetween('problem_date', [$dateStart, $dateEnd])->where('class_name', $report->class_name)->where('entry_year', $report->entry_year)->where('is_validated', true)->get(),
            'qrPaths' => array_filter([
                $advisorQrTempPath ? storage_path('app/public/' . $advisorQrTempPath) : null,
                $kaprodiQrTempPath ? storage_path('app/public/' . $kaprodiQrTempPath) : null,
            ]),
        ];
    }    

    public static function generateAdvisorReportPDF(array $data)
    {
        $report = $data['report'];

        $advisorSignaturePath = $report->submitted_at ? $report->academic_advisor->lecturer_signature : null;
        $advisorQrTempPath = $advisorSignaturePath ? self::generateTemporaryQr($advisorSignaturePath) : null;

        $kaprodiSignaturePath = $report->approved_at
            ? $report->student_class->program->head_of_program->lecturer_signature
            : null;
        $kaprodiQrTempPath = $kaprodiSignaturePath ? self::generateTemporaryQr($kaprodiSignaturePath) : null;

        // Tambahkan ke $data
        $data['advisorQrTempPath'] = $advisorQrTempPath;
        $data['kaprodiQrTempPath'] = $kaprodiQrTempPath;

        return Pdf::loadView('livewire.reports.pdf', $data)->setPaper('A4');
    }

    public static function generateAdvisorReportWord(array $data): string
    {
        // dd($data['warnings']);

        if ($data['jumlahSemester'] == 6) {
            $templatePath = storage_path('app/templates/laporan_D3.docx');
        } else {
            $templatePath = storage_path('app/templates/laporan_D4.docx');
        }

        $outputPath = storage_path("app/reports/laporan_dosen_wali_{$data['report']->class_name}_{$data['semester']}.docx");

        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValues([
            'nama_dosen_wali' => $data['report']->academic_advisor_name,
            'nidn_dosen_wali' => $data['report']->academic_advisor->nidn,
            'nama_kaprodi' => $data['report']->student_class->program->head_of_program->user->name,
            'nidn_kaprodi' => $data['report']->student_class->program->head_of_program->nidn,
            'prodi' => $data['program']->program_name,
            'sk_dosen_wali' => $data['report']->academic_advisor_decree,
            'semester' => $data['semester'],
            'kelas' => $data['report']->class_name,
            'angkatan' => $data['studentClass']->entry_year,
            'tahun_akademik' => $data['report']->academic_year,
        ]);

        // Clone baris mahasiswa
        $students = $data['students'];
        $gpaInputs = $data['gpaInputs'];
        $ipkResults = $data['ipkResults'];
        $jumlahSemester = $data['jumlahSemester'];
        $report = $data['report'];

        $templateProcessor->cloneRow('nimGpa', count($students));

        foreach ($students as $index => $student) {
            $row = $index + 1;

            $templateProcessor->setValue("nimGpa#{$row}", $student->nim);
            $templateProcessor->setValue("nameGpa#{$row}", $student->user->name ?? '-');

            for ($smt = 1; $smt <= $jumlahSemester; $smt++) {
                $nilai = $gpaInputs[$student->id][$smt] ?? '-';
                if ($smt <= $report->semester) {
                    $templateProcessor->setValue("smt{$smt}#{$row}", $nilai);
                } else {
                    $templateProcessor->setValue("smt{$smt}#{$row}", '');
                }
            }

            $templateProcessor->setValue("ipk#{$row}", $ipkResults[$student->id] ?? '-');
        }


        $resignations = $data['resignations'];
        $templateProcessor->cloneRow('noResign', max(count($resignations), 1));

        if (count($resignations)) {
            foreach($resignations as $index => $resignation) {
                $row = $index + 1;
                $templateProcessor->setValue("noResign#{$row}", $index + 1 ?? '');
                // $templateProcessor->setValue("nimResign#{$row}", $resignation->student->nim);
                $templateProcessor->setValue("nameResign#{$row}", $resignation->student->user->name ?? '');
                $templateProcessor->setValue("typeResign#{$row}", $resignation->resignation_type ?? '');
                $templateProcessor->setValue("decreeResign#{$row}", $resignation->decree_number ?? '');
                $templateProcessor->setValue("reasonResign#{$row}", $resignation->reason ?? '');
            }
        } else {
            $templateProcessor->setValue("noResign#1", '');
            $templateProcessor->setValue("nameResign#1", '');
            $templateProcessor->setValue("typeResign#1", '');
            $templateProcessor->setValue("decreeResign#1", '');
            $templateProcessor->setValue("reasonResign#1", '');
        }


        $scholarships = $data['scholarships'];
        $templateProcessor->cloneRow('noScholar', max(count($scholarships), 1));

        if (count($scholarships)) {
            foreach($scholarships as $index => $scholarship) {
                $row = $index + 1;
                $templateProcessor->setValue("noScholar#{$row}", $index + 1);
                $templateProcessor->setValue("nimScholar#{$row}", $scholarship->student->nim);
                $templateProcessor->setValue("nameScholar#{$row}", $scholarship->student->user->name);
                $templateProcessor->setValue("typeScholar#{$row}", $scholarship->scholarship_type);
            }
        } else {
            $templateProcessor->setValue("noScholar#1", '');
            $templateProcessor->setValue("nimScholar#1", '');
            $templateProcessor->setValue("nameScholar#1", '');
            $templateProcessor->setValue("typeScholar#1", '');
        }


        $achievements = $data['achievements'];
        $templateProcessor->cloneRow('noAchiev', max(count($achievements), 1));

        if (count($achievements)) {
            foreach($achievements as $index => $achievement) {
                $row = $index + 1;
                $templateProcessor->setValue("noAchiev#{$row}", $index + 1);
                $templateProcessor->setValue("nimAchiev#{$row}", $achievement->student->nim);
                $templateProcessor->setValue("nameAchiev#{$row}", $achievement->student->user->name);
                $templateProcessor->setValue("typeAchiev#{$row}", $achievement->achievement_type);
                $templateProcessor->setValue("levelAchiev#{$row}", $achievement->level);
            }
        } else {
            $templateProcessor->setValue("noAchiev#1", '');
            $templateProcessor->setValue("nimAchiev#1", '');
            $templateProcessor->setValue("nameAchiev#1", '');
            $templateProcessor->setValue("typeAchiev#1", '');
            $templateProcessor->setValue("levelAchiev#1", '');
        }


        $warnings = $data['warnings'];
        $templateProcessor->cloneRow('noWarn', max(count($warnings), 1));

        if (count($warnings)) {
            foreach($warnings as $index => $warning) {
                $row = $index + 1;
                $templateProcessor->setValue("noWarn#{$row}", $index + 1);
                $templateProcessor->setValue("nimWarn#{$row}", $warning->student->nim);
                $templateProcessor->setValue("nameWarn#{$row}", $warning->student->user->name);
                $templateProcessor->setValue("typeWarn#{$row}", $warning->warning_type);
                $templateProcessor->setValue("reasonWarn#{$row}", $warning->reason);
            }
        } else {
            $templateProcessor->setValue("noWarn#1", '');
            $templateProcessor->setValue("nimWarn#1", '');
            $templateProcessor->setValue("nameWarn#1", '');
            $templateProcessor->setValue("typeWarn#1", '');
            $templateProcessor->setValue("reasonWarn#1", '');
        }
        

        $arrears = $data['arrears'];
        $templateProcessor->cloneRow('noArrear', max(count($arrears), 1));

        if (count($arrears)) {
            foreach($arrears as $index => $arrear) {
                $row = $index + 1;
                $templateProcessor->setValue("noArrear#{$row}", $index + 1);
                $templateProcessor->setValue("nimArrear#{$row}", $arrear->student->nim);
                $templateProcessor->setValue("nameArrear#{$row}", $arrear->student->user->name);
                $templateProcessor->setValue("amountArrear#{$row}", $arrear->amount);
            }
        } else {
            $templateProcessor->setValue("noArrear#1", '');
            $templateProcessor->setValue("nimArrear#1", '');
            $templateProcessor->setValue("nameArrear#1", '');
            $templateProcessor->setValue("amountArrear#1", '');
        }


        $guidances = $data['guidances'];
        $templateProcessor->cloneRow('noGuidance', max(count($guidances), 1));

        if (count($guidances)) {
            foreach($guidances as $index => $guidance) {
                $row = $index + 1;
                $templateProcessor->setValue("noGuidance#{$row}", $index + 1);
                $templateProcessor->setValue("nimGuidance#{$row}", $guidance->student->nim);
                $templateProcessor->setValue("nameGuidance#{$row}", $guidance->student->user->name);
                $templateProcessor->setValue("problemGuidance#{$row}", $guidance->problem);
                $templateProcessor->setValue("solutionGuidance#{$row}", $guidance->solution);
            }
        } else {
            $templateProcessor->setValue("noGuidance#1", '');
            $templateProcessor->setValue("nimGuidance#1", '');
            $templateProcessor->setValue("nameGuidance#1", '');
            $templateProcessor->setValue("problemGuidance#1", '');
            $templateProcessor->setValue("solutionGuidance#1", '');
        }

        //data statistik
        $stats = $data['table_data']; // data dari GpaStat::with()

        $statItems = [
            ['label' => 'IPS Rata-rata', 'key' => 'avg'],
            ['label' => 'IPS Tertinggi', 'key' => 'max'],
            ['label' => 'IPS Terendah', 'key' => 'min'],
            ['label' => 'IPS < 3.00', 'key' => 'below_3'],
            ['label' => '% IPS < 3.00', 'key' => 'below_3_percent'],
            ['label' => 'IPS ≥ 3.00', 'key' => 'above_equal_3'],
            ['label' => '% IPS ≥ 3.00', 'key' => 'above_equal_3_percent'],
        ];

        for ($row = 0; $row < count($statItems); $row++) {
            // $label = $statItems[$row]['label'];
            $statKey = $statItems[$row]['key'];

            // $templateProcessor->setValue("statLabel" . ($row + 1), $label);

            for ($sem = 1; $sem <= $data['jumlahSemester']; $sem++) {
                $semesterKey = 'SMT ' . $sem;
                $value = isset($stats[$semesterKey][$statKey]) ? $stats[$semesterKey][$statKey] : '-';

                // Tambahkan tanda persen jika key mengandung "_percent"
                if (str_contains($statKey, 'percent') && $value !== '-') {
                    $value .= '%';
                }

                if ($sem <= $data['semester']) {
                    $templateProcessor->setValue("stat" . ($row + 1) . "_" . $sem, $value);
                } else {
                    $templateProcessor->setValue("stat" . ($row + 1) . "_" . $sem, '');
                }
            }
        }


        // ========================
        // TANDA TANGAN DOSEN WALI
        // ========================
        $advisorSignaturePath = $data['report']->submitted_at ? $data['report']->academic_advisor->lecturer_signature : null;
        $advisorQrTempPath = $advisorSignaturePath ? self::generateTemporaryQr($advisorSignaturePath) : null;

        if ($advisorSignaturePath) {
            $advisorAbsolutePath = storage_path('app/public/' . $advisorQrTempPath);

            if (file_exists($advisorAbsolutePath)) {
                $templateProcessor->setImageValue('advisorSignature', [
                    'path' => $advisorAbsolutePath,
                    'width' => 100,
                    'height' => 100,
                    'ratio' => true,
                ]);
            } else {
                $templateProcessor->setValue('advisorSignature', '');
            }
        } else {
            $templateProcessor->setValue('advisorSignature', '');
        }


        // ========================
        // TANDA TANGAN KAPRODI
        // ========================
        $headOfProgramSignaturePath = $data['report']->approved_at ? $data['report']->student_class->program->head_of_program->lecturer_signature : null;
        $headOfProgramQrTempPath = $headOfProgramSignaturePath ? self::generateTemporaryQr($headOfProgramSignaturePath) : null;

        if ($headOfProgramSignaturePath) {
            $headOfProgramAbsolutePath = storage_path('app/public/' . $headOfProgramQrTempPath);

            if (file_exists($headOfProgramAbsolutePath)) {
                $templateProcessor->setImageValue('headOfProgramSignature', [
                    'path' => $headOfProgramAbsolutePath,
                    'width' => 100,
                    'height' => 100,
                    'ratio' => true,
                ]);
            } else {
                $templateProcessor->setValue('headOfProgramSignature', '');
            }
        } else {
            $templateProcessor->setValue('headOfProgramSignature', '');
        }


        //TANGGAL UNTUK TTD DOSEN WALI DAN KAPRODI
        $templateProcessor->setValue('advisorDate', $data['report']->submitted_at ? Carbon::parse($data['report']->submitted_at)->translatedFormat('d F Y') : '');
        $templateProcessor->setValue('headOfProgramDate', $data['report']->approved_at ? Carbon::parse($data['report']->approved_at)->translatedFormat('d F Y') : '');

        // dd($data['chartBase64']);

        // Tangani chartBase64 jika ada
        if (!empty($data['chartBase64'])) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['chartBase64']));

            $tempPath = storage_path('app/public/temp_chart/temp_chart_' . uniqid() . '.png');
            file_put_contents($tempPath, $imageData);

            if (file_exists($tempPath)) {
                $templateProcessor->setImageValue('chartImage', [
                    'path' => $tempPath,
                    'width' => 500, // atau sesuaikan
                    'height' => 250,
                    'ratio' => true,
                ]);
            } else {
                $templateProcessor->setValue('chartImage', '');
            }
        } else {
            $templateProcessor->setValue('chartImage', '');
        }        

        $templateProcessor->saveAs($outputPath);

        // Hapus QR dosen wali jika ada
        if (!empty($advisorQrTempPath) && file_exists(storage_path('app/public/' . $advisorQrTempPath))) {
            unlink(storage_path('app/public/' . $advisorQrTempPath));
        }
        if (!empty($headOfProgramQrTempPath) && file_exists(storage_path('app/public/' . $headOfProgramQrTempPath))) {
            unlink(storage_path('app/public/' . $headOfProgramQrTempPath));
        }

        return $outputPath;
    }
}
