<?php

namespace App\Helpers;

class SemesterGpaStatsHelper
{
    
    public static function calculateSemesterGpaStats($students, $maxSemester)
    {
        $stats = [];

        for ($semester = 1; $semester <= $maxSemester; $semester++) {
            $semesterGpas = [];

            foreach ($students as $student) {
                if ($student->gpa_cumulative) {
                    $gpa = $student->gpa_cumulative->gpa_semester
                        ->firstWhere('semester', $semester);
                    
                    if ($gpa && $gpa->semester_gpa !== null) {
                        $semesterGpas[] = $gpa->semester_gpa;
                    }
                }
            }

            $total = count($semesterGpas);
            $avg = $total ? round(array_sum($semesterGpas) / $total, 2) : null;
            $min = $total ? round(min($semesterGpas), 2) : null;
            $max = $total ? round(max($semesterGpas), 2) : null;
            $below3 = collect($semesterGpas)->filter(fn($gpa) => $gpa < 3)->count();
            $aboveOrEqual3 = $total - $below3;

            $stats[$semester] = [
                'total' => $total,
                'avg' => $avg,
                'min' => $min,
                'max' => $max,
                'below_3' => $below3,
                'below_3_percent' => $total ? round(($below3 / $total) * 100, 2) : 0,
                'above_equa_3' => $aboveOrEqual3,
                'above_equa_3_percent' => $total ? round(($aboveOrEqual3 / $total) * 100, 2) : 0,
            ];
        }

        return $stats;
    }

}

?>