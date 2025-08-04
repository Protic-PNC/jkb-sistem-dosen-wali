<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Dosen Wali</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            text-align: left;
        }

        .table-container {
            margin: 20px 0;
            width: 100%;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .th,
        .td {
            padding: 8px;
            border: 1px solid black;
            text-align: left;
        }

        th {
            background-color: white;
            font-weight: bold;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
        }

        .content-text {
            font-size: 12px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="content-text">
        <p class="subtitle bold">LAPORAN DOSEN WALI</p>
        <table>
            <tbody>
                <tr class="">
                    <td>Nama Dosen Wali</td>
                    <td class="">:</td>
                    <td class="">{{ $report->academic_advisor_name }}</td>
                </tr>
                <tr class="">
                    <td class="">Program Studi</td>
                    <td class="">:</td>
                    <td class="">
                        {{ $program->degree }}-{{ $program->program_name }}</td>
                </tr>
                <tr class="">
                    <td class="">Nomor SK Dosen Wali</td>
                    <td class="">:</td>
                    <td class="">{{ $report->academic_advisor_decree }}</td>
                </tr>
                <tr class="">
                    <td class="">Kelas/Angkatan</td>
                    <td class="">:</td>
                    <td class="">{{ $report->class_name }}/{{ $report->entry_year }}</td>
                </tr>
                <tr class="">
                    <td class="">Semester</td>
                    <td class="">:</td>
                    <td class="">{{ convertToRoman($semester) }}</td>
                </tr>
                <tr class="">
                    <td class="">Tahun Akademik</td>
                    <td class="">:</td>
                    <td class="">{{ $report->academic_year }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <p class="section-title">Perkembangan Akademis Mahasiswa Perwalian:</p>
        <table class="table">
            <thead>
                <tr>
                    <th rowspan="2" scope="col" class="th center">No</th>
                    <th rowspan="2" scope="col" class="th center">NIM</th>
                    <th rowspan="2" scope="col" class="th center">Nama</th>
                    <th colspan="{{ $jumlahSemester }}" scope="col" class="th center" style="text-align: center">
                        Semester</th>
                    <th rowspan="2" scope="col" class="th center">IPK</th>
                </tr>
                <tr>
                    @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <th scope="col" class="th center">{{ convertToRoman($i) }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td scope="row" class="td">{{ $loop->iteration }}</td>
                        <td scope="row" class="td">{{ $student->nim }}</td>
                        <td scope="row" class="td">{{ $student->user->name ?? '-' }}</td>

                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                            <td scope="row" class="td center">
                                @if ($i <=  $report->semester)
                                    {{ $gpaInputs[$student->id][$i] ?? '-' }}
                                @endif
                            </td>
                        @endfor

                        <td scope="row" class="td center font-semibold">
                            {{ $ipkResults[$student->id] ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    @if(isset($chartBase64))
        <div class="table-container">
            <p class="section-title">Grafik IP Rata-rata per Semester</p>
            <img src="{{ $chartBase64 }}" alt="Chart" style="width: 90%; height: auto;" />
        </div>
    @endif

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th rowspan="2" scope="col" class="th center">
                        Keterangan
                    </th>
                    <th colspan="{{ $jumlahSemester }}" scope="col" class="center th">
                        Semester
                    </th>
                </tr>
                <tr>
                    @for ($i = 0; $i < $jumlahSemester; $i++)
                        <th scope="col" class="th center">
                            {{ convertToRoman($i+1) }}
                        </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row" class="th">
                        IPS Rata-rata
                    </th>
                    @for ($i = 0; $i < $jumlahSemester; $i++)
                        @php $semesterKey = 'SMT ' . ($i + 1); @endphp
                        <td class="td center">
                            @if ($i < $semester)
                                {{ $table_data[$semesterKey]['avg'] ?? '-' }}
                            @endif
                        </td>
                    @endfor
                </tr>
                <tr>
                    <th scope="row" class="th">
                        IPS Tertinggi
                    </th>
                    @for ($i = 0; $i < $jumlahSemester; $i++)
                        @php $semesterKey = 'SMT ' . ($i + 1); @endphp
                        <td class="td center">
                            @if ($i < $semester)
                                {{ $table_data[$semesterKey]['max'] ?? '-' }}
                            @endif
                        </td>
                    @endfor
                </tr>
                <tr>
                    <th scope="row" class="th">
                        IPS Terendah
                    </th>
                    @for ($i = 0; $i < $jumlahSemester; $i++)
                        @php $semesterKey = 'SMT ' . ($i + 1); @endphp
                        <td class="td center">
                            @if ($i < $semester)
                                {{ $table_data[$semesterKey]['min'] ?? '-' }}
                            @endif
                        </td>
                    @endfor
                </tr>
                <tr>
                    <th scope="row" class="th">
                        IPS < 3.00 </th>
                    @for ($i = 0; $i < $jumlahSemester; $i++)
                        @php $semesterKey = 'SMT ' . ($i + 1); @endphp
                        <td class="td center">
                            @if ($i < $semester)
                                {{ $table_data[$semesterKey]['below_3'] ?? '-' }}
                            @endif
                        </td>
                    @endfor
                </tr>

                <tr>
                    <th scope="row" class="th">
                        % IPS < 3.00 </th>
                    @for ($i = 0; $i < $jumlahSemester; $i++)
                        @php $semesterKey = 'SMT ' . ($i + 1); @endphp
                        <td class="td center">
                            @if ($i < $semester)
                                {{ $table_data[$semesterKey]['below_3_percent'] . '%' ?? '-' }}
                            @endif
                        </td>
                    @endfor
                </tr>
                <tr>
                    <th scope="row" class="th">
                        IPS >= 3.00
                    </th>
                    @for ($i = 0; $i < $jumlahSemester; $i++)
                        @php $semesterKey = 'SMT ' . ($i + 1); @endphp
                        <td class="td center">
                            @if ($i < $semester)
                                {{ $table_data[$semesterKey]['above_equal_3'] ?? '-' }}
                            @endif
                        </td>
                    @endfor
                </tr>
                <tr>
                    <th scope="row" class="th">
                        % IPS >= 3.00
                    </th>
                    @for ($i = 0; $i < $jumlahSemester; $i++)
                        @php $semesterKey = 'SMT ' . ($i + 1); @endphp
                        <td class="td center">
                            @if ($i < $semester)
                                {{ $table_data[$semesterKey]['above_equal_3_percent'] . '%' ?? '-' }}
                            @endif
                        </td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <p class="section-title">Data mahasiswa mengundurkan diri/Drop Out :
        </p>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="td">No</th>
                    <th scope="col" class="td">Nama Mahasiswa</th>
                    <th scope="col" class="td">UD/DO</th>
                    <th scope="col" class="td">SK Penetapan</th>
                    <th scope="col" class="td">Alasan</th>
                </tr>
            </thead>
            <tbody>
                @if ($resignations)
                    @if ($resignations->isEmpty())
                        <tr>
                            <td scope="row" colspan="5" class="td">
                                Tidak ada data pengunduran diri mahasiswa</td>
                        </tr>
                    @else
                        @foreach ($resignations as $resign)
                            <tr>
                                <td scope="row" class="td">
                                    {{ $loop->iteration }}</td>
                                <td scope="row" class="td">
                                    {{ $resign->student->user->name }}</td>
                                <td scope="row" class="td">
                                    {{ $resign->resignation_type }}</td>
                                <td scope="row" class="td">
                                    {{ $resign->decree_number }}</td>
                                <td scope="row" class="td">
                                    {{ $resign->reason }}</td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td scope="row" colspan="5" class="td">
                            Tidak ada data pengunduran diri mahasiswa</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>


    <div class="table-container">
        <p class="section-title">Mahasiswa penerima beasiswa/peninjauan ulangan UKT</p>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="td">No</th>
                    <th scope="col" class="td">NIM</th>
                    <th scope="col" class="td">Nama</th>
                    <th scope="col" class="td">Jenis Beasiswa</th>
                </tr>
            </thead>
            <tbody>
                @if ($scholarships)
                    @if ($scholarships->isEmpty())
                        <tr>
                            <td scope="row" colspan="4" class="td">
                                Tidak ada data beasiswa</td>
                        </tr>
                    @else
                        @foreach ($scholarships as $scholarship)
                            <tr>
                                <td scope="row" class="td">
                                    {{ $loop->iteration }}</td>
                                <td scope="row" class="td">
                                    {{ $scholarship->student->nim }}</td>
                                <td scope="row" class="td">
                                    {{ $scholarship->student->user->name }}</td>
                                <td scope="row" class="td">
                                    {{ $scholarship->scholarship_type }}</td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td scope="row" colspan="4" class="td">
                            Tidak ada data beasiswa</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <p class="section-title">Mahasiswa Berprestasi dan Keaktifan Organisasi</p>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="td">No</th>
                    <th scope="col" class="td">NPM</th>
                    <th scope="col" class="td">Nama</th>
                    <th scope="col" class="td">Jenis Prestasi/Organisasi</th>
                    <th scope="col" class="td">Tingkat</th>
                </tr>
            </thead>
            <tbody>
                @if ($achievements)
                    @if ($achievements->isEmpty())
                        <tr>
                            <td scope="row" colspan="5" class="td">
                                Tidak ada data prestasi</td>
                        </tr>
                    @else
                        @foreach ($achievements as $achievement)
                            <tr>
                                <td scope="row" class="td">
                                    {{ $loop->iteration }}</td>
                                <td scope="row" class="td">
                                    {{ $achievement->student->nim }}</td>
                                <td scope="row" class="td">
                                    {{ $achievement->student->user->name }}</td>
                                <td scope="row" class="td">
                                    {{ $achievement->achievement_type }}</td>
                                <td scope="row" class="td">
                                    {{ $achievement->level }}</td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td scope="row" colspan="4" class="td">
                            Tidak ada data prestasi</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <p class="section-title">Surat Peringatan</p>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="td">No</th>
                    <th scope="col" class="td">NIM</th>
                    <th scope="col" class="td">Nama</th>
                    <th scope="col" class="td">Jenis Peringatan</th>
                    <th scope="col" class="td">Alasan</th>
                </tr>
            </thead>
            <tbody>
                @if ($warnings)
                    @if ($warnings->isEmpty())
                        <tr>
                            <td scope="row" colspan="5" class="td">
                                Tidak ada data peringatan</td>
                        </tr>
                    @else
                        @foreach ($warnings as $warning)
                            <tr>
                                <td scope="row" class="td">
                                    {{ $loop->iteration }}</td>
                                <td scope="row" class="td">
                                    {{ $warning->student->nim }}</td>
                                <td scope="row" class="td">
                                    {{ $warning->student->user->name }}</td>
                                <td scope="row" class="td">
                                    {{ $warning->warning_type }}</td>
                                <td scope="row" class="td">
                                    {{ $warning->reason }}</td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td scope="row" colspan="4" class="td">
                            Tidak ada data peringatan</td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

    <div class="table-container">
        <p class="section-title">Tunggakan UKT</p>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="td">No</th>
                    <th scope="col" class="td">NPM</th>
                    <th scope="col" class="td">Nama</th>
                    <th scope="col" class="td">Jumlah Tunggakan</th>
                </tr>
            </thead>
            <tbody>
                @if ($arrears)
                    @if ($arrears->isEmpty())
                        <tr>
                            <td scope="row" colspan="4" class="td">
                                Tidak ada data Tunggakan</td>
                        </tr>
                    @else
                        @foreach ($arrears as $arrear)
                            <tr>
                                <td scope="row" class="td">
                                    {{ $loop->iteration }}</td>
                                <td scope="row" class="td">
                                    {{ $arrear->student->nim }}</td>
                                <td scope="row" class="td">
                                    {{ $arrear->student->user->name }}</td>
                                <td scope="row" class="td">
                                    Rp. {{ number_format($arrear->amount, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td scope="row" colspan="4" class="td">
                            Tidak ada data tunggakan ukt</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <p class="section-title">Bimbingan Perwalian</p>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="td">No</th>
                    <th scope="col" class="td">NPM</th>
                    <th scope="col" class="td">Nama</th>
                    <th scope="col" class="td">Permasalahan</th>
                    <th scope="col" class="td">Solusi</th>
                    {{-- <th scope="col" class="td">Tanggal</th> --}}
                </tr>
            </thead>
            <tbody>
                @if ($guidances)
                    @if ($guidances->isEmpty())
                        <tr>
                            <td scope="row" colspan="6" class="td">
                                Tidak ada data bimbingan</td>
                        </tr>
                    @else
                        @foreach ($guidances as $guidance)
                            <tr>
                                <td scope="row" class="td">
                                    {{ $loop->iteration }}</td>
                                <td scope="row" class="td">
                                    {{ $guidance->student->nim }}</td>
                                <td scope="row" class="td">
                                    {{ $guidance->student->user->name }}</td>
                                <td scope="row" class="td">
                                    {{ $guidance->problem_date }}
                                    <br> 
                                    {{ $guidance->problem }}
                                </td>
                                <td scope="row" class="td">
                                    {{ $guidance->solution_date }}
                                    <br>
                                    {{ $guidance->solution }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td scope="row" colspan="6" class="td">
                            Tidak ada data bimbingan</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <br><br>
    <table style="width: 100%; text-align: center; margin-top: 40px;">
        <tr>
            <td style="width: 50%;">
                Mengetahui,<br>
                Ketua Program Studi<br>
                Cilacap, {{ $report->approved_at ? \Carbon\Carbon::parse($report->approved_at)->translatedFormat('d F Y') : '' }} <br><br>
                @if(!empty($kaprodiQrTempPath))
                    <img src="{{ public_path('storage/' . $kaprodiQrTempPath) }}" alt="ttd kaprodi" style="width: auto; height: 120px;"><br>
                @else
                    <br><br><br><br><br><br><br><br>
                @endif
                <strong>{{ $report->student_class->program->head_of_program->user->name ?? '' }}</strong><br>
                NIDN. {{ $report->student_class->program->head_of_program->nidn ?? '' }}
            </td>

            <td style="width: 50%;">
                Dosen Wali<br>
                Cilacap, {{ $report->submitted_at ? \Carbon\Carbon::parse($report->submitted_at)->translatedFormat('d F Y') : '' }} <br><br>
                @if(!empty($advisorQrTempPath))
                    <img src="{{ public_path('storage/' . $advisorQrTempPath) }}" alt="ttd dosen wali" style="width: auto; height: 120px;"><br><br>
                @else
                    <br><br><br><br><br><br><br><br><br>
                @endif
                <strong>{{ $report->academic_advisor_name ?? '' }}</strong><br>
                NIDN. {{ $report->academic_advisor->nidn ?? '' }}
            </td>
        </tr>
    </table>

</body>
</html>
