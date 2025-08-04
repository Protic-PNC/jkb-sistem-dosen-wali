<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

Route::get('/', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('users', App\Livewire\Masterdata\Users\Index::class)->name('users.index');
        Route::get('programs', App\Livewire\Masterdata\ProgramClass\Index::class)->name('programs.index');
        Route::get('lecturers', App\Livewire\Masterdata\Lecturers\Index::class)->name('lecturers.index');
        Route::get('students', App\Livewire\Masterdata\Students\Index::class)->name('students.index');
        Route::get('students', App\Livewire\Masterdata\Students\Index::class)->name('students.index');

        Route::get('admin-reports', App\Livewire\Reports\Index::class)->name('admin-reports.index');
        Route::get('admin-report/{id}', App\Livewire\Reports\Show::class)->name('admin-reports.show');
        
        Route::get('admin-khs', App\Livewire\Khs\Index::class)->name('admin-khs.index');
        Route::get('admin-krs', App\Livewire\Krs\Index::class)->name('admin-krs.index');
    });

    Route::middleware('role:dosenWali')->group(function () {
        Route::get('dosen-wali-reports', App\Livewire\Reports\Index::class)->name('dosenWali-reports.index');
        Route::get('dosen-wali-report/{id}', App\Livewire\Reports\Show::class)->name('dosenWali-reports.show');

        Route::get('achievements', App\Livewire\Achievements\Index::class)->name('achievements.index');
        Route::get('gpas', App\Livewire\Gpas\Index::class)->name('gpas.index');
        Route::get('dosen-wali-khs', App\Livewire\Khs\Index::class)->name('dosenWali-khs.index');
        Route::get('dosen-wali-krs', App\Livewire\Krs\Index::class)->name('dosenWali-krs.index');
        Route::get('resignations', App\Livewire\Resignations\Index::class)->name('resignations.index');
        Route::get('scholarships', App\Livewire\Scholarships\Index::class)->name('scholarships.index');
        Route::get('tuition-arrears', App\Livewire\TuitionArrears\Index::class)->name('tuition-arrears.index');
        Route::get('warnings', App\Livewire\Warnings\Index::class)->name('warnings.index');
        // Route::get('guidances', App\Livewire\Guidances\Index::class)->name('guidances.index');
    });

    Route::middleware('role:dosenWali|mahasiswa')->group(function () {
        Route::get('guidances', App\Livewire\Guidances\Index::class)->name('guidances.index');
    });
    
    Route::middleware('role:mahasiswa')->group(function () {
        // Route::get('students/guidances', App\Livewire\Guidances\Index::class)->name('guidances.index');
        Route::get('mahasiswa-khs', App\Livewire\Khs\Index::class)->name('mahasiswa-khs.index');
        Route::get('mahasiswa-krs', App\Livewire\Krs\Index::class)->name('mahasiswa-krs.index');
    });

    Route::middleware('role:kaprodi')->group(function () {
        Route::get('kaprodi-reports', App\Livewire\Reports\Index::class)->name('kaprodi-reports.index');
        Route::get('kaprodi-report/{id}', App\Livewire\Reports\Show::class)->name('kaprodi-reports.show');
        
        Route::get('kaprodi-khs', App\Livewire\Khs\Index::class)->name('kaprodi-khs.index');
        Route::get('kaprodi-krs', App\Livewire\Krs\Index::class)->name('kaprodi-krs.index');
    });

    Route::middleware('role:jurusan')->group(function () {
        Route::get('jurusan-reports', App\Livewire\Reports\Index::class)->name('jurusan-reports.index');
        Route::get('jurusan-report/{id}', App\Livewire\Reports\Show::class)->name('jurusan-reports.show');
        
        Route::get('jurusan-khs', App\Livewire\Khs\Index::class)->name('jurusan-khs.index');
        Route::get('jurusan-krs', App\Livewire\Krs\Index::class)->name('jurusan-krs.index');
    });

});

require __DIR__.'/auth.php';
