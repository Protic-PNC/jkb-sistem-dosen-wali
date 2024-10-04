<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $program = Program::with(
            'head_of_program'
        )
        ->orderBy('created_at', 'desc')
        ->get();

        return view('masterdata.programs.index', compact('program'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kaprodis = Lecturer::with([
            'position',
            'program'
        ])->whereHas('position', function($query) {
            $query->where('position_name', 'kaprodi');
        })
        ->whereDoesntHave('program')
        ->get();

        return view('masterdata.programs.create', compact('kaprodis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $program = new Program();
            $program->program_name = $request->program_name;
            $program->degree = $request->degree;
            $program->head_of_program_id = $request->head_of_program_id ?? null;
            $program->save();

            return redirect()->route('masterdata.programs.index')->with('success', 'Data prodi berhasil disimpan.');
        }catch(\Exception $e)
        {
            return back()->withErrors('Data gagal, error :' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        $kaprodis = Lecturer::with([
            'position',
            'program'
        ])
        ->whereHas('position', function($query) {
            $query->where('position_name', 'kaprodi');
        })
        // Filter agar dosen tidak memiliki relasi dengan program lain kecuali program yang sedang diedit
        ->whereDoesntHave('program', function($query) use ($program) {
            $query->where('program_id', '!=', $program->program_id);
        })
        ->orWhereHas('program', function($query) use ($program) {
            // Allow the kaprodi if they are only related to the current program
            $query->where('program_id', $program->program_id);
        })
        ->get();
        

        return view('masterdata.programs.edit', compact('kaprodis'), [
            'program'=> $program
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id); // Cari program berdasarkan ID

        // Validasi input
        $validated = $request->validate([
            'program_name' => 'required|string',
            'degree' => 'required|string|in:D3,D4',  // Validasi jenjang (D3 atau D4)
            'head_of_program_id' => ['nullable', Rule::exists('lecturers', 'lecturer_id')],  // Pastikan kaprodi valid
        ]);

        DB::beginTransaction(); // Mulai transaksi database
        try {
            // Update data program dengan data yang sudah divalidasi
            $program->update($validated);

            DB::commit(); // Commit transaksi jika sukses
            return redirect()->route('masterdata.programs.index')->with('success', 'Program berhasil diedit');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        try
        {
            DB::beginTransaction();

            if($program->classes()->exists())
            {
                $classes = StudentClass::where('program_id', $program->program_id)->get();
                foreach($classes as $class)
                {
                    $class->delete();
                }
            }
            $program->delete();

            DB::commit();
            return redirect()->back()->with('success','Prodi berhasil dihapus :)');
        }catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->back()->withErrors('System error: ' . $e->getMessage());
        }
    }
}
