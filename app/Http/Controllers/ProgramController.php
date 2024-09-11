<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Program;
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
        )->get();

        return view('masterdata.programs.index', compact('program'));
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
        ])->whereHas('position', function($query) {
            $query->where('position_name', 'kaprodi');
        })->whereDoesntHave('program')->get();

        return view('masterdata.programs.edit', compact('kaprodis', 'program'));
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
        //
    }
}
