<div class="w-full">
    <div class="w-full flex justify-between items-center gap-2">
        <div>
            <span class="w-full font-bold text-xl">Perkembangan Akademik Mahasiswa Kelas {{ $studentClass->class_name ?? '' }}</span>
        </div>
        @if (Auth::user()->roles->first()->name == 'dosenWali')
            <button
                @if ($editing)
                    wire:click="save"
                    wire:target="save"
                    wire:loading.attr="disabled"
                    wire:loading.remove
                    {{ $text = "Simpan Perubahan" }}
                @else
                    wire:click="startEditing"
                    {{ $text = "Edit Nilai" }}
                @endif
                class="flex items-center gap-2 text-yellow-500 px-3 py-2 text-xs font-semibold border border-yellow-500 hover:bg-gray-100 rounded-lg mb-2"
            >
                {{ $text }}
            </button>
            @if ($editing)
                <button wire:loading wire:target='save' wire:cloak disabled type="button" class="hidden text-yellow-500 px-3 py-2 text-xs font-semibold border border-yellow-500 hover:bg-gray-100 rounded-lg mb-2">
                    <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                    Loading...
                </button>
            @endif
        @endif
    </div>

    <div class="flex flex-col gap-2">
        <div class="overflow-x-auto">
            <table class="w-full border text-sm text-left">
                <thead>
                    <tr class="bg-gray-100">
                        <th rowspan="2" class="border px-4 py-2">NIM</th>
                        <th rowspan="2" class="border px-4 py-2">Nama</th>
                        <th colspan="{{ $jumlahSemester }}" class="border px-4 py-2 text-center">Semester</th>
                        <th rowspan="2" class="border px-4 py-2">IPK</th>
                    </tr>
                    <tr class="bg-gray-100">
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <th class="border px-4 py-2 text-center">{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ $student->nim }}</td>
                        <td class="border px-4 py-2">{{ $student->user->name ?? '-' }}</td>
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <td class="border px-2 py-1 text-center">
                            @if ($i <= $semester)
                                @php
                                    $shouldDisable = false;
                                    if ($detailReport) {
                                        $shouldDisable = $i != $semester || ($student->status != 'active' && $student->inactive_at <= $dateEnd);
                                    } else {
                                        $shouldDisable = $student->status != 'active';
                                    }
                                @endphp
                                @if ($editing)
                                    <input
                                        @disabled($shouldDisable)
                                        type="number"
                                        step="0.01"
                                        max="4"
                                        min="2"
                                        wire:model="gpaInputs.{{ $student->id }}.{{ $i }}"
                                        @class([
                                            'w-16', 'border', 'rounded', 'text-center',
                                            'bg-gray-200' => $shouldDisable
                                        ])
                                    />
                                @else
                                    {{ $gpaInputs[$student->id][$i] ?? '-' }}
                                @endif
                            @endif
                        </td>
                        @endfor
                        <td class="border px-2 py-1 text-center font-semibold">
                            {{ $ipkResults[$student->id] ?? '-' }}
                            {{-- {{ $student->gpa_cumulative->cumulative_gpa ?? '-' }} --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $jumlahSemester + 3 }}" class="border px-4 py-2 text-center">Tidak ada data mahasiswa</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border text-sm text-left">
                <thead>
                    <tr class="bg-gray-100">
                        <th rowspan="2" class="border px-4 py-2">Keterangan</th>
                        <th colspan="{{ $jumlahSemester }}" class="border px-4 py-2 text-center">Semester</th>
                    </tr>
                    <tr class="bg-gray-100">
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <th class="border px-4 py-2 text-center">{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">IP Rata-rata</td>
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <td class="border px-2 py-1 text-center">
                            @if ($i <= $semester)
                                {{ $stats[$i]['avg'] ?? '-' }}
                            @endif
                        </td>
                        @endfor
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">IPS Tertinggi</td>
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <td class="border px-2 py-1 text-center">
                            @if ($i <= $semester)
                                {{ $stats[$i]['max'] ?? '-' }}
                            @endif
                        </td>
                        @endfor
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">IPS Terendah</td>
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <td class="border px-2 py-1 text-center">
                            @if ($i <= $semester)
                                {{ $stats[$i]['min'] ?? '-' }}
                            @endif
                        </td>
                        @endfor
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">IPS < 3.00</td>
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <td class="border px-2 py-1 text-center">
                            @if ($i <= $semester)
                                {{ $stats[$i]['below_3'] ?? '-' }}
                            @endif
                        </td>
                        @endfor
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">% IPS < 3.00</td>
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <td class="border px-2 py-1 text-center">
                            @if ($i <= $semester)
                                @if (isset($stats[$i]))
                                    {{ $stats[$i]['below_3_percent'] }}%
                                @else
                                    -
                                @endif
                            @endif
                        </td>
                        @endfor
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">IPS >= 3.00</td>
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <td class="border px-2 py-1 text-center">
                            @if ($i <= $semester)
                                {{ $stats[$i]['above_equal_3'] ?? '-' }}
                            @endif
                        </td>
                        @endfor
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">% IPS >= 3.00</td>
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                        <td class="border px-2 py-1 text-center">
                            @if ($i <= $semester)
                                @if (isset($stats[$i]))
                                    {{ $stats[$i]['above_equal_3_percent'] }}%
                                @else
                                    -
                                @endif
                            @endif
                        </td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
        @if ($detailReport)
            <div class="mt-6 w-full">
                <h3 class="text-lg font-bold mb-2">Grafik IP Rata-rata per Semester</h3>
                <div class="w-full" style="height: 300px;">
                    <div id="gpaChart" class="w-full h-full" wire:ignore></div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Google Charts Script -->
@if ($detailReport)
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", { packages: ['corechart'] });

        google.charts.setOnLoadCallback(() => {
            setTimeout(drawChart, 100);
        });

        function drawChart() {
            const container = document.getElementById("gpaChart");
            if (!container) return;

            const data = new google.visualization.DataTable();
            data.addColumn('string', 'Semester');
            data.addColumn('number', 'IPK');
            data.addColumn({ type: 'string', role: 'style' });

            data.addRows([
                @php
                    $rows = [];
                    for ($i = 1; $i <= $jumlahSemester; $i++) {
                        $ipk = isset($stats[$i]['avg']) ? floatval($stats[$i]['avg']) : 0;
                        $color = $ipk === 'null' ? '#dddddd' : '#76A7FA'; // abu-abu jika tidak ada data
                        if ($i <= $semester) {
                            $rows[] = '["Semester ' . $i . '", ' . $ipk . ', "' . $color . '"]';
                        } else {
                            $rows[] = '["Semester ' . $i . '", '. 0 . ', "' . $color .'"]';
                        }
                    }
                @endphp
                {!! implode(',', $rows) !!}

            ]);

            const view = new google.visualization.DataView(data);
            view.setColumns([
                0,
                1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                },
                2
            ]);

            const options = {
                title: "Rata-rata IPK",
                height: 300,
                bar: { groupWidth: "60%" },
                legend: { position: "none" },
                vAxis: { minValue: 0, maxValue: 4 },
                chartArea: { width: '90%', height: '70%' }
            };

            window.gpaChartInstance = new google.visualization.ColumnChart(container);
            window.gpaChartInstance.draw(view, options);

            if (typeof ResizeObserver !== 'undefined') {
                const resizeObserver = new ResizeObserver(() => {
                    window.gpaChartInstance.draw(view, options);
                });
                resizeObserver.observe(container);
            }
        }

        window.addEventListener('resize', drawChart);
    </script>
@endif