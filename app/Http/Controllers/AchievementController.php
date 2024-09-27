<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\AchievementDetail;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Student;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var App\Model\User */

        $user = Auth::user();

        if($user->hasRole('dosenWali'))
        {
            $lecturer = $user->lecturer;
    
            $achievement = Achievement::with([
                'student_class',
                'achievement_detail.student'
            ])->whereHas('student_class', function($query) use ($lecturer) {
                $query->where('academic_advisor_id', $lecturer->lecturer_id);
            })->get();
        }
        else if($user->hasRole('mahasiswa'))
        {
            $student = $user->student;

            $achievement = Achievement::with([
                'student_class',
                'achievement_detail.student'
            ])->whereHas('achievement_detail.student', function($query) use ($student) {
                $query->where('student_id', $student->student_id);
            })->get();
        }


        return view('masterdata.achievements.index', compact('achievement'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $user = User::find($id);

        $students = Student::where('class_id', $user->lecturer->student_classes->class_id)->get();
        
        return view('masterdata.achievements.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        //$student = Student::where('student_id', $request->input('student_id'))->first();
        $achievement = Achievement::firstOrCreate(['class_id' => $user->lecturer->student_classes->class_id]);

        try
        {
            $achievementDetail = new AchievementDetail();
            $achievementDetail->achievement_id = $achievement->achievement_id;
            $achievementDetail->student_id = $request->input('student_id');
            $achievementDetail->achievement_type = $request->input('achievement_type');
            $achievementDetail->level = $request->input('level');

            $achievementDetail->save();

            return redirect()
                    ->route('masterdata.achievements.index', $user->id)
                    ->with('success', 'Pencapaian berhasil ditambah!');
        } catch (\Exception $e){
            return redirect()
                    ->route('masterdata.achievements.index', $user->id)
                    ->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Achievement $achievement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Achievement $achievement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Achievement $achievement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Achievement $achievement)
    {
        //
    }
}
