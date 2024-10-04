<?php

namespace App\Http\Controllers;

use App\Imports\LecturerImport;
use App\Models\Lecturer;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\StudentClass;

use App\Models\Program;

use Maatwebsite\Excel\Facades\Excel;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturers = Lecturer::with(
            [
                'user',
                'program',
                'student_classes',
                'position'
            ]
        )
        ->orderBy('position_id', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
        return view('masterdata.lecturers.index', compact('lecturers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($userId)
    {
        $lecturers = Lecturer::with([
            'user',
            'position',
            'program',
            'student_classes'
        ])->whereHas('position', function($query)
        {
            $query->where('position_name', 'Dosen Wali');
        })->whereDoesntHave('user')->get();

        $positions = Position::all();
        $user = User::find($userId);

        $student_class = StudentClass::all();

        $existinglecturer = Lecturer::where('user_id', $userId)->first();
        if ($existinglecturer) {
            return redirect()->route('masterdata.lecturers.show', $existinglecturer->lecturer_id);
        } else {
            return view('masterdata.lecturers.create', compact('positions', 'student_class', 'user', 'lecturers'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->input('is_new_lecturer') == 1) {
                // Jika ingin menambahkan dosen wali baru
                try {
                    // Buat dosen wali baru
                    $lecturer = new Lecturer();
                    $lecturer->lecturer_name = $request->input('lecturer_name');
                    $lecturer->nidn = $request->input('nidn');
                    $lecturer->nip = $request->input('nip');
                    $lecturer->lecturer_address = $request->input('lecturer_address');
                    $lecturer->lecturer_phone_number = $request->input('lecturer_phone_number');
                    if($request->input('user_id') != null)
                    {
                        $lecturer->user_id = $request->input('user_id');
                    }
                    $lecturer->position_id = $request->input('position_id');

                    // Jika ada file tanda tangan yang diunggah
                    if ($request->hasFile('lecturer_signature')) {
                        $signaturePath = $request->file('lecturer_signature')->store('signatures', 'public');
                        $lecturer->lecturer_signature = $signaturePath;
                    }

                    //dd($lecturer);
                    // Simpan data dosen wali baru ke database
                    $lecturer->save();

                    // Redirect setelah berhasil menyimpan data
                    return redirect()->route('masterdata.lecturers.index')->with('success', 'Data dosen wali berhasil disimpan.');
                } catch (\Exception $e) {
                    return redirect()
                    ->route('masterdata.lecturers.create', 'null')
                    ->withInput()
                    ->with('error', 'System error: ' . $e->getMessage());
                }
            } else {
                // Jika memilih dosen wali yang sudah ada dari dropdown
                $lecturer = Lecturer::where('lecturer_id', $request->input('lecturer_id'))->first();

                if ($lecturer) {
                    // Update user_id jika dosen wali sudah ada
                    $lecturer->update(['user_id' => $request->input('user_id')]);

                    // Redirect setelah berhasil menyimpan data
                    return redirect()->route('masterdata.users.index')->with('success', 'Data dosen wali berhasil diperbarui.');
                } else {
                    // Jika dosen wali tidak ditemukan, tampilkan error
                    return back()->withErrors(['lecturer_id' => 'Dosen wali tidak valid.']);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'System error: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try
        {
            Excel::import(new LecturerImport, $request->file('file'));
            return redirect()->route('masterdata.lecturers.index')->with('success', 'Dosen berhasil diimport');

        } catch (\Exception $e)
        {
            return redirect()
                ->route('masterdata.lecturers.index')
                ->withInput()
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Lecturer $lecturer, $id)
    {
        $lecturer = Lecturer::find($id);
        return view('masterdata.lecturers.show', compact('lecturer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lecturer $lecturer, $id)
    {
        $lecturer = Lecturer::find($id);
        $positions = Position::all();
        return view('masterdata.lecturers.edit', compact('lecturer', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Temukan dosen berdasarkan ID, jika tidak ditemukan lempar error 404
        $lecturer = Lecturer::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'lecturer_name' => 'required|string',
            'nidn' => ['required', 'string', Rule::unique('lecturers', 'nidn')->ignore($lecturer->lecturer_id, 'lecturer_id')],
            'nip' => ['nullable', 'string', Rule::unique('lecturers', 'nip')->ignore($lecturer->lecturer_id, 'lecturer_id')],
            'lecturer_address' => 'required|string',
            'lecturer_phone_number' => 'required|string',
            'position_id' => 'required|exists:positions,position_id',
            'user_id' => ['nullable', Rule::exists('users', 'id')],
            'lecturer_signature' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        // Jika ada file tanda tangan yang diunggah, simpan dan tambahkan ke data yang ter-validasi
        if ($request->hasFile('lecturer_signature')) {
            $signaturePath = $request->file('lecturer_signature')->store('signatures', 'public');
            $validated['lecturer_signature'] = $signaturePath;
        }

        DB::beginTransaction();
        try {
            // Update data dosen dengan data yang sudah divalidasi
            $lecturer->update($validated);

            DB::commit();
            // Redirect setelah update berhasil
            return redirect()->route('masterdata.lecturers.index')->with('success', 'Dosen berhasil diedit');
        } catch (\Exception $e) {
            DB::rollBack();
            // Kembali ke form sebelumnya jika terjadi error, sambil menyimpan inputan
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
        $lecturer = Lecturer::find($id);
        
        try
        {
            if($lecturer->program())
            {
                $lecturer->program()->update(['head_of_program_id' => null]);
            }
            if($lecturer->student_classes())
            {
                $lecturer->student_classes()->update(['academic_advisor_id' => null]);
            }

            if($lecturer->user())
            {
                $lecturer->delete();
                $lecturer->user()->delete();
            }
            else
            {
                $lecturer->delete();
            }

            return redirect()->route('masterdata.lecturers.index')->with('success', 'Lecturer deleted successfully');
        } catch (\Exception $e)
        {
            return redirect()
            ->route('masterdata.lecturers.index')
            ->with('error', 'System error: ' . $e->getMessage());
        }
    }
}
