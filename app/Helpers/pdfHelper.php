<?php

namespace App\Helpers;

use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;

class PdfHelper
{
    /**
     * Membagi file PDF menjadi array path halaman per mahasiswa.
     *
     * @param string $pdfPath path lengkap file
     * @param string $outputDir direktori output (dalam storage/app)
     * @return array daftar path halaman hasil split
     */
    public static function splitPdf($pdfPath, $outputDir)
    {
        $pdfPath = realpath($pdfPath);

        if (!$pdfPath || !file_exists($pdfPath)) {
            throw new \Exception("File PDF tidak ditemukan atau tidak valid: $pdfPath");
        }

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $tempPdf = new Fpdi();
        $pageCount = $tempPdf->setSourceFile($pdfPath); // Hitung jumlah halaman

        $outputPaths = [];

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf = new Fpdi();
            $pdf->AddPage();

            // Inisialisasi file sumber untuk setiap instance baru
            $pdf->setSourceFile($pdfPath);

            $tplIdx = $pdf->importPage($pageNo);
            $pdf->useTemplate($tplIdx);

            $outputPath = $outputDir . "/page_{$pageNo}.pdf";
            $pdf->Output($outputPath, 'F');

            $outputPaths[] = $outputPath;
        }

        return $outputPaths;
    }

}


?>