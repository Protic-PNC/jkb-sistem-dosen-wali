<?php

namespace App\Livewire\Gpas;

use App\Models\GpaStat;
use App\Models\Student;
use App\Models\StudentClass;
use Livewire\Component;

class GpaTable extends Component
{
    public $students;
    public $editing = false;
    public $gpaInputs = []; // [student_id][semester] = nilai
    public $ipkResults = [];

    public $classId;
    public $semester;
    public $detailReport;
    public $jumlahSemester;
    public $dateStart;
    public $dateEnd;

    public $studentClass;

    public $chartByClassSemester = [];
    public $stats = [];
    public $stat;

    public function mount($jumlahSemester, $semester, $detailReport = false, $classId, $dateStart = null, $dateEnd = null)
    {
        $this->classId = $classId;
        $this->semester = $semester;
        $this->jumlahSemester = $jumlahSemester;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->detailReport = $detailReport;

        $this->studentClass = StudentClass::find($classId);


        $this->loadStudents();
        $this->loadStatFromDatabase(); // gunakan statistik dari database
    }

    public function loadStudents()
    {
        $this->students = Student::where('student_class_id', $this->classId)
            ->with(['gpa_cumulative.gpa_semester', 'user'])
            ->get();

        foreach ($this->students as $student) {
            if ($student->gpa_cumulative) {
                $ips = [];

                foreach ($student->gpa_cumulative->gpa_semester as $gpa) {
                    $this->gpaInputs[$student->id][$gpa->semester] = $gpa->semester_gpa;
                    if ($gpa->semester <= $this->semester) {
                        $ips[] = $gpa->semester_gpa;
                    }
                }

                // Hitung IPK awal dari semester yang tersedia
                $this->ipkResults[$student->id] = count($ips) ? round(array_sum($ips) / count($ips), 2) : null;
            }
        }
    }


    // public function updatedGpaInputs()
    // {
    //     //loop all inputed semesters data that has changes
    //     foreach ($this->gpaInputs as $studentId => $semesters) {
    //         $ips = array_filter($semesters, fn($val) => $val !== null && $val !== '');
    //         $ips = array_map('floatval', $ips);

    //         $ipk = count($ips) > 0 ? round(array_sum($ips) / count($ips), 2) : null;

    //         //simpan sementara ipk ke array (tanpa simpan ke DB dulu)
    //         $this->ipkResults[$studentId] = $ipk;
    //     }
    // }

    public function loadStatFromDatabase()
    {
        $stat = GpaStat::with('gpa_stat_semester')
            ->where('student_class_id', $this->classId)
            ->first();

        if (!$stat) return;

        $this->stats = [];

        foreach ($stat->gpa_stat_semester as $record) {
            $this->stats[$record->semester] = [
                'total' => $record->total,
                'avg' => $record->avg,
                'min' => $record->min,
                'max' => $record->max,
                'below_3' => $record->below_3,
                'below_3_percent' => $record->below_3_percent,
                'above_equal_3' => $record->above_equal_3,
                'above_equal_3_percent' => $record->above_equal_3_percent,
            ];

            $this->chartByClassSemester[$stat->student_class->class_name][$record->semester] = $record->avg;
        }
    }

    public function startEditing()
    {
        $this->editing = true;
    }

    public function save()
    {
        foreach ($this->gpaInputs as $studentId => $semesters) {
            $student = Student::find($studentId);
            $userName = $student->user->name;
            $gpa_cumulative = $student->gpa_cumulative ?? $student->gpa_cumulative()->create();
            $values = [];

            foreach ($semesters as $semester => $value) {
                // Cek jika ada nilai lebih dari 4
                if (is_numeric($value) && $value > 4) {
                    $this->dispatch('error', message: "Nilai semester $semester untuk mahasiswa dengan nama $userName melebihi 4.0");
                    return;
                }
                // Simpan null jika kosong, tetap buat record-nya
                $gpa = $gpa_cumulative->gpa_semester()->firstOrNew(['semester' => $semester]);
                $gpa->semester_gpa = ($value === '' || $value === null) ? null : (float) $value;
                $gpa->save();
                // Simpan ke array hanya jika value valid
                if (is_numeric($value)) {
                    $values[] = (float) $value;
                }
            }

            
            // Hitung IPK dari array $values yang sudah difilter
            $ipk = count($values) > 0 ? round(array_sum($values) / count($values), 2) : null;
            $gpa_cumulative->cumulative_gpa = $ipk;
            $gpa_cumulative->save();
            // dd($gpa_cumulative->cumulative_gpa, $ipk);
        }

        // Hitung ulang statistik dan simpan
        $this->loadStudents();
        $studentClass = StudentClass::find($this->classId);

        $this->stats = [];

        for ($semester = 1; $semester <= $this->semester; $semester++) {
            $semesterGpas = [];

            foreach ($this->students as $student) {
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
            $aboveOrEqual3 = collect($semesterGpas)->filter(fn($gpa) => $gpa >= 3)->count();

            // dd($total, ($below3/$total) * 100, ($aboveOrEqual3/$total) * 100);

            $this->stats[$semester] = [
                'total' => $total,
                'avg' => $avg,
                'min' => $min,
                'max' => $max,
                'below_3' => $below3,
                'below_3_percent' => $total ? round(($below3 / $total) * 100, 2) : 0,
                'above_equal_3' => $aboveOrEqual3,
                'above_equal_3_percent' => $total ? round(($aboveOrEqual3 / $total) * 100, 2) : 0,
            ];
        }

        // Simpan ke DB
        $this->stat = GpaStat::updateOrCreate(
            ['student_class_id' => $this->classId],
            ['max_semester' => $this->semester]
        );

        $this->stat->gpa_stat_semester()->delete();

        foreach ($this->stats as $semester => $data) {
            $this->stat->gpa_stat_semester()->create([
                'semester' => $semester,
                'total' => $data['total'],
                'avg' => $data['avg'],
                'min' => $data['min'],
                'max' => $data['max'],
                'below_3' => $data['below_3'],
                'below_3_percent' => $data['below_3_percent'],
                'above_equal_3' => $data['above_equal_3'],
                'above_equal_3_percent' => $data['above_equal_3_percent'],
            ]);
        }

        $this->editing = false;
        $this->loadStatFromDatabase();

        $this->dispatch('saved', message: 'Indeks Prestasi berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.gpas.gpa-table');
    }
}
