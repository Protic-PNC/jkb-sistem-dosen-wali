<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Program;
use App\Models\Report;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usersCount = User::count();
        $studentsCount = Student::count();
        $student_classesCount = StudentClass::count();
        $programsCount = Program::count();
        $lecturersCount = Lecturer::count();
        $reportsCount = Report::count();

        return view('masterdata.dashboard', compact('usersCount', 'studentsCount', 'student_classesCount', 'lecturersCount', 'programsCount', 'reportsCount'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
