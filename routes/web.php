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

        //Route::post('student_classes/update', [StudentClassController::class, 'updateClassAutomatic'])->name('student_classes.updateautomatic');
        Route::resource('positions', PositionController::class)->middleware('role:admin');

        Route::get('/students/index', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/show/{userId}', [StudentController::class, 'show'])->name('students.show');
        Route::get('/students/create/{userId}', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students/store/{id}', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/edit/{id}', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/update/{id}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/destroy/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
        
        Route::post('/student_classes/destroy', [StudentClassController::class, 'deleteAll'])->name('student_classes.destroy.selected');
        
        Route::get('/lecturers/index', [LecturerController::class, 'index'])->name('lecturers.index');
        Route::get('/lecturers/show/{userId}', [LecturerController::class, 'show'])->name('lecturers.show');
        Route::get('/lecturers/create/{userId}', [LecturerController::class, 'create'])->name('lecturers.create');
        Route::post('/lecturers/store/{id}', [LecturerController::class, 'store'])->name('lecturers.store');
        Route::get('/lecturers/edit/{id}', [LecturerController::class, 'edit'])->name('lecturers.edit');
        Route::put('/lecturers/update/{id}', [LecturerController::class, 'update'])->name('lecturers.update');
        Route::delete('/lecturers/destroy/{id}', [LecturerController::class, 'destroy'])->name('lecturers.destroy');


        Route::get('/gpas/index', [GpaController::class, 'index'])->name('gpas.index');
        

        Route::get('/guidance/index/{id}', [GuidanceController::class, 'index'])->name('guidances.index');
        Route::get('/guidance/create/{id}', [GuidanceController::class, 'create'])->name('guidances.create');
        
        
        Route::get('/achievement/index', [AchievementController::class, 'index'])->name('achievements.index');
        
        
        Route::get('/warning/index', [WarningController::class, 'index'])->name('warnings.index');
        
        
        Route::get('/scholarship/index', [ScholarshipController::class, 'index'])->name('scholarships.index');
        
        
        Route::get('/tuition_arrears/index', [TuitionArrearController::class, 'index'])->name('tuition_arrears.index');
        
        
        Route::get('/student_resignation/index', [StudentResignationController::class, 'index'])->name('student_resignations.index');

        
        Route::get('/reports/index', [ReportController::class, 'index'])->name('reports.index');
    });
});

require __DIR__.'/auth.php';

