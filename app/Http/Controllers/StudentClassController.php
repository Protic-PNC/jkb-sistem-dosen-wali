<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

class StudentClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studentClass = StudentClass::with([
            'program',
            'academic_advisor',
        ])
        ->orderBy('status', 'asc')
        ->orderBy('program_id', 'asc')
        ->orderBy('class_name', 'asc')
        ->get();

        $yearHasChanged = $this->checkAcademicYearChange();

        return view('masterdata.student_classes.index', compact('studentClass', 'yearHasChanged'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = Program::all();
        $lecturers = Lecturer::with([
            'position'
        ])->whereHas('position', function($query) {
            $query->where('position_name', 'Dosen Wali');
        })->get();

        return view('masterdata.student_classes.create', compact('programs', 'lecturers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek apakah input manual atau generate otomatis
        if ($request->is_add_manual == 1) {
            // Insert data manual ke database
            StudentClass::create([
                'program_id' => $request->program_id,
                'academic_year' => $request->academic_year_manual,
                'class_name' => $request->class_name, // Kode kelas dari input manual
                'academic_advisor_id' => $request->academic_advisor_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } else {

            // Ambil prodi berdasarkan id
            $program = Program::find($request->program_id);

            // Generate kode prodi (ambil awalan huruf dari setiap kata di nama prodi)
            $program_code = $this->generateProgramCode($program->program_name);

            // Hitung tahun sekarang - tahun akademik dengan penyesuaian untuk awal tahun akademik (Juli/Agustus)
            $current_year = Carbon::now()->year;
            $current_month = Carbon::now()->month;
            $academic_year_select = $request->academic_year_select;

            // Jika bulan sekarang setelah Juli, tambah 1 ke perbedaan tahun
            $year_diff = $current_year - $academic_year_select;
            if ($current_month >= 8) {
                $year_diff += 1;
            }

            if($year_diff > 0)
            {
                $status = 'active';
                $graduated_at = null;
                // Cek batas maksimal untuk jenjang D3 atau D4
                if ($program->degree == 'D3' && $year_diff > 3) {
                    $status = 'graduated';
                    $graduated_at = now();
                    $year_diff = 3;
                    // return back()->withErrors('Tahun akademik tidak valid untuk D3, year_diff: '.$year_diff . ' academic_year_select: '. $academic_year_select . ' current_year: '. $current_year. ' current_month: '. $current_month);
                } elseif ($program->degree == 'D4' && $year_diff > 4) {
                    // return back()->withErrors('Tahun akademik tidak valid untuk D4');
                    $status = 'graduated';
                    $graduated_at = now();
                    $year_diff = 4;
                }
            }
            else
            {
                return back()->withErrors('Tahun akademik tidak valid, harus kurang dari ' . $current_year);
            }

            // Ambil kelas yang sudah ada di program dan tahun akademik yang sama
            $existingClasses = StudentClass::where('program_id', $request->program_id)
            ->where('academic_year', $academic_year_select)
            ->orderBy('class_name', 'desc')
            ->first();

             // Tentukan huruf kelas selanjutnya
            $lastClassLetter = 'A'; // Default jika tidak ada kelas sebelumnya
            if ($existingClasses) {
                // Ambil huruf terakhir dari class_name, misal "TI-2B", ambil "B"
                $lastClassLetter = substr($existingClasses->class_name, -1);
                // Ubah ke huruf selanjutnya (misal, B -> C)
                $lastClassLetter = chr(ord($lastClassLetter) + 1);
            }

            // Looping berdasarkan jumlah kelas
            $total_classes = $request->total_classes;
            $classes = [];
            for ($i = 0; $i < $total_classes; $i++) {
                 // Generate kode kelas (urutan abjad dari lastClassLetter)
                 $class_letter = chr(ord($lastClassLetter) + $i);

                // Format kelas: kode_prodi-tahun-urutan
                $class_name = "{$program_code}-{$year_diff}{$class_letter}";

                // Insert ke database
                $classes[] = [
                    'program_id' => $program->program_id,
                    'class_name' => $class_name,
                    'academic_year' => $academic_year_select,
                    'status' => $status,
                    'graduated_at' => $graduated_at,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Simpan ke database sekaligus
            StudentClass::insert($classes);
        }

        return redirect()->route('masterdata.student_classes.index')->with('success', 'Data kelas berhasil disimpan.');
    }

    //untuk generate nama prodi menjadi kode prodi
    private function generateProgramCode($program_name)
    {
        $words = explode(' ', $program_name);
        $code = '';
    
        foreach ($words as $word) {
            // Pastikan kata tidak kosong
            if (!empty($word)) {
                $code .= strtoupper($word[0]); // Ambil huruf pertama tiap kata
            }
        }
    
        return $code;
    }

    private function checkAcademicYearChange()
    {
        if(StudentClass::exists())
            {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;

                $lastUpdated = StudentClass::latest('updated_at')->first()->updated_at;
                $lastAcademicYear = Carbon::parse($lastUpdated)->year;

                if($currentMonth >= 8 && $currentYear > $lastAcademicYear)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
    }

    public function updateClassAutomatic()
    {
        //Ambil kelas  yang belum lulus
        $classes = StudentClass::where('status', 'active')->get();

        try
        {
            foreach($classes as $class)
            {
                //ambil kode prodi dan tingkat
                $program_code = substr($class->class_name, 0, strpos($class->class_name, '-'));
                $level = (int)substr($class->class_name, strpos($class->class_name, '-') + 1, 1);

                //cek prodi dan tingkat maksimal
                $max_level = $class->program->degree == 'D3' ? 3 : 4;

                if ($level < $max_level)
                {
                    $new_class_name = $program_code . '-' . ($level + 1) . substr($class->class_name, -1);
                    $class->class_name = $new_class_name;
                    $class->save(); 
                }
                else
                {
                    $class->status = 'graduated';
                    $class->graduated_at = now();
                    $class->save();
                }
            }
            return redirect()->route('masterdata.student_classes.index')->with('success', 'Data kelas berhasil diupdate otomatis.');
        }catch(\Exception $e) {
            return redirect()
            ->route('masterdata.student_classes.index')
            ->with('error', 'System error: ' . $e->getMessage());
        }

    }
    

    /**
     * Display the specified resource.
     */
    public function show(StudentClass $studentClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentClass $studentClass)
    {
        $lecturers = Lecturer::whereHas('position', function($query) {
            $query->where('position_name', 'Dosen Wali');
        })
        ->whereDoesntHave('student_classes')
        ->get();

        $programs = Program::all();

        return view('masterdata.student_classes.edit', compact('studentClass', 'lecturers', 'programs'));
    }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, StudentClass $studentClass)
        {
            $program = Program::find($request->input('program_id'));

            $program_code = $this->generateProgramCode($program->program_name);

            // dd($program_code);

            // Hitung perbedaan tahun (tahun sekarang - tahun akademik yang diinput)
            $new_academic_year = $request->academic_year;
            $current_year = Carbon::now()->year;
            $current_month = Carbon::now()->month;

            $year_diff = $current_year - $new_academic_year;
            if ($current_month >= 8) {
                $year_diff += 1;
            }

            if ($year_diff > 0) {
                $status = 'active';
                $graduated_at = null;

                // Cek batas maksimal untuk D3 atau D4
                if ($program->degree == 'D3' && $year_diff > 3) {
                    $status = 'graduated';
                    $graduated_at = now();
                    $year_diff = 3;
                } elseif ($program->degree == 'D4' && $year_diff > 4) {
                    $status = 'graduated';
                    $graduated_at = now();
                    $year_diff = 4;
                }
            } else {
                return back()->withErrors('Tahun akademik tidak valid, harus kurang dari ' . $current_year);
            }

            // Update data kelas
            try {
                DB::beginTransaction();

                // Generate kode kelas baru jika prodi atau tahun akademik berubah
                if ($studentClass->academic_year != $new_academic_year || $studentClass->program_id != $request->program_id) {
                    // Tentukan huruf kelas
                    $existingClasses = StudentClass::where('program_id', $request->program_id)
                        ->where('academic_year', $new_academic_year)
                        ->orderBy('class_name', 'desc')
                        ->first();

                    $lastClassLetter = 'A'; // Default jika tidak ada kelas sebelumnya
                    if ($existingClasses) {
                        $lastClassLetter = substr($existingClasses->class_name, -1);
                        $lastClassLetter = chr(ord($lastClassLetter) + 1);
                    }

                    // Generate nama kelas baru
                    $class_name = "{$program_code}-{$year_diff}{$lastClassLetter}";

                    $studentClass->update([
                        'program_id' => $request->program_id,
                        'academic_year' => $new_academic_year,
                        'class_name' => $class_name,
                        'academic_advisor_id' => $request->academic_advisor_id,
                        'status' => $status,
                        'graduated_at' => $graduated_at,
                        'updated_at' => now(),
                    ]);
                } else {
                    // Update hanya untuk nama kelas atau advisor jika prodi dan tahun tidak berubah
                    $studentClass->update([
                        'class_name' => $request->class_name,
                        'academic_advisor_id' => $request->academic_advisor_id,
                        'status' => $status,
                        'graduated_at' => $graduated_at,
                        'updated_at' => now(),
                    ]);
                }

                DB::commit();
                return redirect()->route('masterdata.student_classes.index')->with('success', 'Data kelas berhasil diperbarui.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'System error: ' . $e->getMessage());
            }
            
        }

    /**
     * Remove the specified resource from storage.
     */

     public function destroy(StudentClass $studentClass)
     {
         // Kumpulkan semua relasi yang ada
         $relatedEntities = [];
     
         if ($studentClass->student()->exists()) {
             $relatedEntities[] = 'students';
         }
         if ($studentClass->gpa()->exists()) {
             $relatedEntities[] = 'GPA records';
         }
         if ($studentClass->guidance()->exists()) {
             $relatedEntities[] = 'guidance records';
         }
         if ($studentClass->warning()->exists()) {
             $relatedEntities[] = 'warnings';
         }
         if ($studentClass->scholarship()->exists()) {
             $relatedEntities[] = 'scholarships';
         }
         if ($studentClass->tuition_arrear()->exists()) {
             $relatedEntities[] = 'tuition arrears';
         }
         if ($studentClass->student_resignation()->exists()) {
             $relatedEntities[] = 'student resignations';
         }
         if ($studentClass->achievement()->exists()) {
             $relatedEntities[] = 'achievements';
         }
         if ($studentClass->report()->exists()) {
             $relatedEntities[] = 'reports';
         }
     
         // Jika ada relasi yang ditemukan, kembalikan error
         if (!empty($relatedEntities)) {
             $relations = implode(', ', $relatedEntities);
             return back()->withErrors(['error' => "Cannot delete the class because it has related records: {$relations}."]);
         }
         else
         {
            try
            {
                // Jika tidak ada relasi, hapus kelas
                $studentClass->delete();
            
                return redirect()->route('masterdata.student_classes.index')->with('success', 'Class deleted successfully.');
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with('error', 'System error: ' . $e->getMessage());
            }
         } 
     }
     


    public function deleteAll(Request $request)
    {
            // Dapatkan semua ID yang dipilih
            $classIds = $request->input('ids');

            try {
                // Lakukan penghapusan
                StudentClass::whereIn('class_id', $classIds)->delete();

                // Jika berhasil, kirim respon sukses
                return redirect()->route('masterdata.student_classes.index')
                    ->with('success', 'Kelas yang dipilih berhasil dihapus.');

            } catch (\Illuminate\Database\QueryException $e) {

                // Tangkap error lainnya dan tampilkan pesan default
                return redirect()
                ->route('masterdata.student_classes.index')
                ->with('error', 'System error : ' . $e->getMessage());
            }
    }
}
