<?php

namespace App\Livewire\Reports;

use App\Models\Lecturer;
use App\Models\Report;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

use App\Helpers\ReportExportHelper;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;

class Show extends Component
{
    public $reportId;
    public $report;
    public $reportStatus;
    public $reportSubmittedAt;
    public $reportApprovedAt;

    public $currentSemester;
    public $studentClass;
    public $jumlahSemester;
    public $semester;
    public $program;
    public $degree;
    public $user;
    public $lecturer;

    public $dateStart;
    public $dateEnd;

    public $chartBase64;

    #[Title('Detail Laporan')]

    public function mount($id)
    {
        $this->report = Report::find($id);

        if (!$this->report) {
            abort(404);
        }

        $this->user = User::find($this->report->academic_advisor->user->id);
        $this->lecturer = Lecturer::find($this->user->lecturer->id);
        $this->reportId = $id;
        $this->reportStatus = $this->report->status;
        $this->reportSubmittedAt = $this->report->submitted_at
                                ? Carbon::parse($this->report->submitted_at)->translatedFormat('d F Y')
                                : null;
        $this->reportApprovedAt = $this->report->approved_at
                                ? Carbon::parse($this->report->approved_at)->translatedFormat('d F Y')
                                : null;


        $this->program = $this->report->student_class->program ?? null;

        $this->studentClass = StudentClass::find($this->report->student_class_id ?? null);
        $this->degree = $this->program->degree ?? null;
        $this->jumlahSemester = match ($this->degree) {
            'D3' => 6,
            'D4' => 8,
            default => null
        };

        $this->semester = $this->report->semester ?? null;
        $this->currentSemester = $this->studentClass->current_semester ?? null;

        // ===== Menentukan $dateStart dan $dateEnd =====
        $semester = $this->semester;
        $entryYear = $this->studentClass->entry_year;
        $tahun = $entryYear + intdiv($semester, 2);

        if ($semester % 2 == 1) {
            // Semester ganjil: Agustus - Januari
            $bulanAwal = 8;
            $bulanAkhir = 1;
            $tahunAkhir = $tahun + 1;
        } else {
            // Semester genap: Februari - Juli
            $bulanAwal = 2;
            $bulanAkhir = 7;
            $tahunAkhir = $tahun;
        }

        $this->dateStart = Carbon::create($tahun, $bulanAwal, 1)->startOfMonth();
        $this->dateEnd = Carbon::create($tahunAkhir, $bulanAkhir, 1)->endOfMonth();
        
    }

    public function updatedReportStatus($value)
    {
        $report = Report::find($this->reportId);
        if ($report) {
            $report->status = $value;
            $report->submitted_at = $value === 'submitted' ? now() : null;
            $report->save();

            // Update tanggal setelah perubahan status
            $this->reportSubmittedAt = $report->submitted_at
                ? Carbon::parse($report->submitted_at)->translatedFormat('d F Y')
                : null;

            $message = 'Status laporan berhasil diperbarui.';

            $this->dispatch('saved', message:$message);
        }
    }

    public function approve()
    {
        if ($this->report->status === 'submitted') {
            $this->report->update(['status' => 'approved', 'approved_at' => now()]);
            $this->reportStatus = $this->report->status;

            $this->reportApprovedAt = $this->report->approved_at
                ? Carbon::parse($this->report->approved_at)->translatedFormat('d F Y')
                : null;

            $message = 'Laporan berhasil disetujui.';
            $this->dispatch('saved', message:$message);
        }
    }

    public function cancelApproval()
    {
        if ($this->report->status === 'approved') {
            $this->report->update(['status' => 'submitted', 'approved_at' => null]);
            $this->reportStatus = $this->report->status;

            $this->reportApprovedAt = $this->report->approved_at
                ? Carbon::parse($this->report->approved_at)->translatedFormat('d F Y')
                : null;

            $message = 'Approval laporan berhasil dibatalkan.';
            $this->dispatch('saved', message:$message);
        }
    }
    
    protected function deleteTempFiles(string $folderName): void
    {
        $path = public_path("storage/{$folderName}");
        if (!is_dir($path)) return;

        foreach (glob($path . '/*') as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }


    public function exportPdf()
    {
        // dd($this->chartBase64);

        $reportData = ReportExportHelper::prepareAdvisorReportData(
            $this->report,
            $this->studentClass,
            $this->program,
            $this->semester,
            $this->jumlahSemester,
            $this->dateStart,
            $this->dateEnd,
        );

        // Gabungkan chart
        $reportData['chartBase64'] = $this->chartBase64;

        $pdf = ReportExportHelper::generateAdvisorReportPDF($reportData);

        // Simpan path QR untuk dihapus nanti
        $qrPaths = $reportData['qrPaths'] ?? [];

        return response()->streamDownload(function () use ($pdf, $qrPaths) {
            $pdfOutput = $pdf->output(); // simpan isi PDF ke variabel
            echo $pdfOutput;

            /// Hapus seluruh file di folder temp_qr dan temp_chart
            $this->deleteTempFiles('temp_qr');
            $this->deleteTempFiles('temp_chart');
        }, 'laporan_dosen_wali_' . Str::slug($this->report->class_name) . '_semester_' . $this->semester . '.pdf');

    }


    public function exportWord()
    {
        // if (empty($this->chartBase64)) {
        //     $this->dispatch('error', message: 'Grafik belum siap, silakan tunggu sebentar...');
        //     return;
        // }

        $outputPath = ReportExportHelper::generateAdvisorReportWord(
            array_merge(
                ReportExportHelper::prepareAdvisorReportData(
                    $this->report,
                    $this->studentClass,
                    $this->program,
                    $this->semester,
                    $this->jumlahSemester,
                    $this->dateStart,
                    $this->dateEnd,
                ),
                ['chartBase64' => $this->chartBase64]
            )
        );


        // Daftarkan fungsi penghapusan file setelah script selesai
        register_shutdown_function(function () {
            $this->deleteTempFiles('temp_qr');
            $this->deleteTempFiles('temp_chart');
        });

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }


    public function render()
    {
        return view('livewire.reports.show');
    }
}
