<?php

namespace App\Providers;

use App\Models\Guidance;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        setlocale(LC_TIME, 'id_ID.utf8'); // untuk fungsi strftime, dsb
        Carbon::setLocale('id'); // untuk translatedFormat()

        View::composer('*', function ($view) {
            $allPendingCount = 0;
            if (Auth::check() && Auth::user()->roles->first()->name == 'kaprodi') {
                $pendingReportsCount = Report::where('status', 'submitted')
                                            ->whereHas('student_class', function ($q) {
                                                $q->where('program_id', Auth::user()->lecturer->program->id);
                                            })->count();
                $allPendingCount = $allPendingCount + $pendingReportsCount;
                $view->with('pendingReportsCount', $pendingReportsCount);
            }

            if (Auth::check() && Auth::user()->roles->first()->name == 'dosenWali') {
                $pendingReportsCount = Report::where('status', 'draft')
                                            ->where('academic_advisor_id', Auth::user()->lecturer->id)
                                            ->count();
                $view->with('pendingReportsCount', $pendingReportsCount);
                
                $pendingGuidancesCount = Guidance::where('is_validated', null)
                                            ->orWhere('is_validated', 0)
                                            ->orWhere('solution', null)
                                            ->whereHas('student.student_class', function($q) {
                                                $q->where('academic_advisor_id', Auth::user()->lecturer->id);
                                            })
                                            ->count();
                $view->with('pendingGuidancesCount', $pendingGuidancesCount);

                $allPendingCount = $allPendingCount + $pendingReportsCount + $pendingGuidancesCount;
            }

            if (Auth::check() && Auth::user()->roles->first()->name == 'mahasiswa') {
                $pendingGuidancesCount = Guidance::where('is_validated', null)
                                            ->orWhere('is_validated', 0)
                                            ->where('student_id', Auth::user()->student->id)
                                            ->count();

                $pendingGuidancesCount = Guidance::where('student_id', Auth::user()->student->id)
                                            ->where(fn($q) => $q->whereNull('is_validated')->orWhere('is_validated', 0))
                                            ->count();
                $view->with('pendingGuidancesCount', $pendingGuidancesCount);
            }

            $view->with('allPendingCount', $allPendingCount);
        });
    }
}
