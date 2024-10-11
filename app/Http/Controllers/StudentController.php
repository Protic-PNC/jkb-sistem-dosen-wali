<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Auth;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with([
            'student_classes',
            'user'
        ])
        ->orderBy('student_name', 'asc')
        ->orderBy('class_id', 'asc')
        ->get();
        
        $user = Auth::user();
        if($user->lecturer)
        {
            $students = Student::where('class_id', $user->lecturer->student_classes->class_id)
            ->orderBy('student_name', 'asc')
            ->get();
        }
        return view('masterdata.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($userId)
    {
        $students = Student::all();
        $student_class = StudentClass::all();
        $user = User::find($userId);

        $existingStudent = Student::where('user_id', $userId)->first();
        if ($existingStudent) {
            return redirect()->route('masterdata.students.show', $existingStudent->student_id);
        } else {
            return view('masterdata.students.create', compact('student_class', 'user', 'students'));
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try
        {
            Excel::import(new StudentsImport, $request->file('file'));
            return redirect()->route('masterdata.students.index')->with('success', 'Mahasiswa berhasil diimport');

        } catch (\Exception $e)
        {
            return redirect()
                ->route('masterdata.students.index')
                ->withInput()
                ->with('error', 'System error: ' . $e->getMessage());
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->input('is_new_student') == 1) {
                try{
                        // Buat mahasiswa baru
                    $student = new Student();
                    $student->student_name = $request->input('student_name');
                    $student->nim = $request->input('nim');
                    $student->student_address = $request->input('student_address');
                    $student->student_phone_number = $request->input('student_phone_number');
                    if($request->input('user_id') != 'null')
                    {
                        $student->user_id = $request->input('user_id');
                    }
                    $student->class_id = $request->input('class_id');
                    // Jika ada file tanda tangan diunggah
                    if ($request->hasFile('student_signature')) {
                        $signaturePath = $request->file('student_signature')->store('signatures', 'public');
                        $student->student_signature = $signaturePath;
                    }
                    
                    $student->save();
                    // Redirect atau response setelah berhasil
                    return redirect()->route('masterdata.students.index')->with('success', 'Data mahasiswa berhasil disimpan.');
                } catch (\Exception $e) {
                    return back()->withErrors(['student_id' => 'Mahasiswa tidak valid.']);
                }
            }
            else
            {
                // Cari mahasiswa berdasarkan student_id
                    $student = Student::where('student_id', $request->input('student_id'))->first();
                    // dd($student);
                if ($student) {
                    $student->update(['user_id' => $request->input('user_id')]);
                    // Redirect atau response setelah berhasil
                    return redirect()->route('masterdata.students.index')->with('success', 'Data mahasiswa berhasil disimpan.');
                }
                else
                {
                    return back()->withErrors(['student_id' => 'Mahasiswa tidak valid.']);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'System error: ' . $e->getMessage());
        }

        // // //jika input is_new_student == 1
        // // if ($request->input('is_new_student') == 1) {
        // //      // Buat mahasiswa baru
        //     $student = new Student();
        //     $student->student_name = $request->input('student_name');
        //     $student->nim = $request->input('nim');
        //     $student->student_address = $request->input('student_address');
        //     $student->student_phone_number = $request->input('student_phone_number');
        //     $student->user_id = $request->input('user_id');
            
        //     // Jika ada file tanda tangan diunggah
        //     if ($request->hasFile('student_signature')) {
        //         $signaturePath = $request->file('student_signature')->store('signatures', 'public');
        //         $student->student_signature = $signaturePath;
        //     }
            
        //     $student->save();
        // }
        // else
        // {
        //     // Cari mahasiswa berdasarkan student_id
        //      $student = Student::where('student_id', $request->input('student_id'))->first();
        //     if ($student) {
        //         $student->update(['user_id', $request->input('user_id')]);
        //     }
        //     else
        //     {
        //         return back()->withErrors(['student_id' => 'Mahasiswa tidak valid.']);
        //     }
        // }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student, $id)
    {
        $student_class = StudentClass::all();
        $student = Student::find($id);
        return view('masterdata.students.show', compact('student_class', 'student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student, $id)
    {
        $student_class = StudentClass::all();
        $student = Student::find($id);

        return view('masterdata.students.edit', compact('student_class', 'student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'student_name' => 'required|string',
            'nim' => ['required', 'string', Rule::unique('students', 'nim')->ignore($student->student_id, 'student_id')],
            'student_address' => 'required|string',
            'student_phone_number' => 'required|string',
            'class_id' => 'required|exists:classes,class_id',
            'user_id' => ['nullable', Rule::exists('users', 'id')],
            'student_signature' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        if ($request->hasFile('student_signature')) {
            $signaturePath = $request->file('student_signature')->store('signatures', 'public');
            $validated['student_signature'] = $signaturePath;
        }
        DB::beginTransaction();
        try {
            $student->update($validated);

            DB::commit();
            return redirect()->route('masterdata.students.index')->with('success', 'User Mahasiswa berhasil Diedit');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        //$user = User::find($student->user_id);

        try {
            // Hapus data GPA Cumulative beserta GPA Semester yang terkait
            if ($student->gpa_cumulative) {
                // Hapus semua GPA Semester terkait dengan GPA Cumulative
                $student->gpa_cumulative->gpa_semester()->delete();
                // Hapus GPA Cumulative
                $student->gpa_cumulative()->delete();
            }
    
            // Hapus semua pencapaian yang terkait dengan student
            if ($student->achievement_detail) {
                $student->achievement_detail()->delete();
            }
    
            // Hapus semua peringatan yang terkait dengan student
            if ($student->warning_detail) {
                $student->warning_detail()->delete();
            }
    
            // Hapus semua data beasiswa yang terkait dengan student
            if ($student->scholarship_detail) {
                $student->scholarship_detail()->delete();
            }
    
            // Hapus semua data tunggakan yang terkait dengan student
            if ($student->tuition_arrear_detail) {
                $student->tuition_arrear_detail()->delete();
            }
    
            // Hapus semua data pengunduran diri yang terkait dengan student
            if ($student->student_resignation_detail) {
                $student->student_resignation_detail()->delete();
            }
    
            // Hapus data bimbingan yang terkait dengan student
            if ($student->guidance_detail) {
                $student->guidance_detail()->delete();
            }
    
            // Hapus data student dan user yang terkait
            if ($student->user) {
                $student->delete();
                $student->user()->delete();  // Hapus user terkait
            }
            else
            {
                $student->delete();
            }
            
            return redirect()->route('masterdata.students.index')->with('success', 'Student deleted successfully');
        } catch (\Exception $e)
        {
            return redirect()
            ->back()
            ->with('error', 'System error: ' . $e->getMessage());
        }
    }
}
