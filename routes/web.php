<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GpaController;
use App\Http\Controllers\GuidanceController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\WarningController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\TuitionArrearController;
use App\Http\Controllers\StudentResignationController;
use App\Models\StudentResignation;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/dashboard', DashboardController::class)->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('masterdata')->name('masterdata.')->group(function() {
        Route::resource('users', UserController::class)->middleware('role:admin');
        Route::resource('programs', ProgramController::class)->middleware('role:admin');
        Route::resource('student_classes', StudentClassController::class)->middleware('role:admin');
        Route::resource('positions', PositionController::class)->middleware('role:admin');

        Route::get('/students/index', [StudentController::class, 'index'])->name('students.index');
        
        
        Route::get('/lecturers/index', [LecturerController::class, 'index'])->name('lecturers.index');


        Route::get('/gpas/index', [GpaController::class, 'index'])->name('gpas.index');
        

        Route::get('/guidance/index', [GuidanceController::class, 'index'])->name('guidances.index');
        
        
        Route::get('/achievement/index', [AchievementController::class, 'index'])->name('achievements.index');
        
        
        Route::get('/warning/index', [WarningController::class, 'index'])->name('warnings.index');
        
        
        Route::get('/scholarship/index', [ScholarshipController::class, 'index'])->name('scholarships.index');
        
        
        Route::get('/tuition_arrears/index', [TuitionArrearController::class, 'index'])->name('tuition_arrears.index');
        
        
        Route::get('/student_resignation/index', [StudentResignationController::class, 'index'])->name('student_resignations.index');

        
        Route::get('/reports/index', [ReportController::class, 'index'])->name('reports.index');
    });
});

require __DIR__.'/auth.php';

