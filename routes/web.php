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
        Route::get('/gpas/edit/{classId}', [GpaController::class, 'edit'])->name('gpas.edit');
        Route::put('/gpas/update/{id}', [GpaController::class, 'update'])->name('gpas.update');
        

        Route::get('/guidances/index/{id}', [GuidanceController::class, 'index'])->name('guidances.index');
        Route::get('/guidances/create/{id}', [GuidanceController::class, 'create'])->name('guidances.create');
        Route::post('/guidances/store/{classId}', [GuidanceController::class, 'store'])->name('guidances.store');
        Route::put('/guidances/update/{id}', [GuidanceController::class, 'update'])->name('guidances.update');
        Route::get('/guidances/edit/{id}', [GuidanceController::class, 'edit'])->name('guidances.edit');
        Route::delete('/guidances/destroy/{id}', [GuidanceController::class, 'destroy'])->name('guidances.destroy');
        
        
        Route::get('/achievement/index', [AchievementController::class, 'index'])->name('achievements.index');
        Route::get('/achievement/create/{id}', [AchievementController::class, 'create'])->name('achievements.create');
        Route::post('/achievement/store', [AchievementController::class, 'store'])->name('achievements.store');
        Route::get('/achievement/edit/{id}', [AchievementController::class, 'edit'])->name('achievements.edit');
        Route::put('/achievement/update/{id}', [AchievementController::class, 'update'])->name('achievements.update');
        Route::delete('/achievement/destroy/{id}', [AchievementController::class, 'destroy'])->name('achievements.destroy');
        
        
        Route::get('/warning/index', [WarningController::class, 'index'])->name('warnings.index');
        Route::get('/warning/create', [WarningController::class, 'create'])->name('warnings.create');
        Route::post('/warning/store', [WarningController::class, 'store'])->name('warnings.store');
        Route::get('/warning/edit/{id}', [WarningController::class, 'edit'])->name('warnings.edit');
        Route::put('/warning/update/{id}', [WarningController::class, 'update'])->name('warnings.update');
        Route::delete('/warning/destroy/{id}', [WarningController::class, 'destroy'])->name('warnings.destroy');
        
        
        Route::get('/scholarship/index', [ScholarshipController::class, 'index'])->name('scholarships.index');
        
        
        Route::get('/tuition_arrears/index', [TuitionArrearController::class, 'index'])->name('tuition_arrears.index');
        
        
        Route::get('/student_resignation/index', [StudentResignationController::class, 'index'])->name('student_resignations.index');

        
        Route::get('/reports/index', [ReportController::class, 'index'])->name('reports.index');
    });
});

require __DIR__.'/auth.php';

