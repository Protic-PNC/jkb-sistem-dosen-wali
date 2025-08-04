<div>
    {{-- @role('admin') --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">
        @role('admin')
        <div class="p-4 bg-white border rounded-lg">
            <div class="flex justify-start items-start gap-2">
                <div class="">
                    <svg class="size-14 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span>Pengguna</span>
                    <span class="text-2xl font-bold">{{ $users }}</span>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white border rounded-lg">
            <div class="flex justify-start items-start gap-2">
                <div class="">
                    <svg class="size-14 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 2c-1.10457 0-2 .89543-2 2v4c0 .55228.44772 1 1 1s1-.44772 1-1V4h12v7h-2c-.5523 0-1 .4477-1 1v2h-1c-.5523 0-1 .4477-1 1s.4477 1 1 1h5c.5523 0 1-.4477 1-1V3.85714C20 2.98529 19.3667 2 18.268 2H6Z"/>
                        <path d="M6 11.5C6 9.567 7.567 8 9.5 8S13 9.567 13 11.5 11.433 15 9.5 15 6 13.433 6 11.5ZM4 20c0-2.2091 1.79086-4 4-4h3c2.2091 0 4 1.7909 4 4 0 1.1046-.8954 2-2 2H6c-1.10457 0-2-.8954-2-2Z"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span>Dosen</span>
                    <span class="text-2xl font-bold">{{ $lecturers }}</span>
                </div>
            </div>
        </div>
        @endrole
        @role(['admin', 'kaprodi', 'jurusan', 'mahasiswa'])
        <div class="p-4 bg-white border rounded-lg">
            <div class="flex justify-start items-start gap-2">
                <div class="">
                    <svg class="size-14 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.4472 2.10557c-.2815-.14076-.6129-.14076-.8944 0L5.90482 4.92956l.37762.11119c.01131.00333.02257.00687.03376.0106L12 6.94594l5.6808-1.89361.3927-.13363-5.6263-2.81313ZM5 10V6.74803l.70053.20628L7 7.38747V10c0 .5523-.44772 1-1 1s-1-.4477-1-1Zm3-1c0-.42413.06601-.83285.18832-1.21643l3.49538 1.16514c.2053.06842.4272.06842.6325 0l3.4955-1.16514C15.934 8.16715 16 8.57587 16 9c0 2.2091-1.7909 4-4 4-2.20914 0-4-1.7909-4-4Z"/>
                        <path d="M14.2996 13.2767c.2332-.2289.5636-.3294.8847-.2692C17.379 13.4191 19 15.4884 19 17.6488v2.1525c0 1.2289-1.0315 2.1428-2.2 2.1428H7.2c-1.16849 0-2.2-.9139-2.2-2.1428v-2.1525c0-2.1409 1.59079-4.1893 3.75163-4.6288.32214-.0655.65589.0315.89274.2595l2.34883 2.2606 2.3064-2.2634Z"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span>
                        Mahasiswa <br>
                        @role('mahasiswa')
                        Kelas {{ $studentClass->class_name }}
                        @endrole
                        {{-- @role('dosenWali')
                        Kelas {{ $studentClass->class_name }}
                        @endrole --}}
                    </span>
                    <span class="text-2xl font-bold">{{ $students }}</span>
                </div>
            </div>
        </div>
        @endrole
        @role('dosenWali')
        @foreach ($studentClasses as $class)
        <div class="p-4 bg-white border rounded-lg">
            <div class="flex justify-start items-start gap-2">
                <div class="">
                    <svg class="size-14 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.4472 2.10557c-.2815-.14076-.6129-.14076-.8944 0L5.90482 4.92956l.37762.11119c.01131.00333.02257.00687.03376.0106L12 6.94594l5.6808-1.89361.3927-.13363-5.6263-2.81313ZM5 10V6.74803l.70053.20628L7 7.38747V10c0 .5523-.44772 1-1 1s-1-.4477-1-1Zm3-1c0-.42413.06601-.83285.18832-1.21643l3.49538 1.16514c.2053.06842.4272.06842.6325 0l3.4955-1.16514C15.934 8.16715 16 8.57587 16 9c0 2.2091-1.7909 4-4 4-2.20914 0-4-1.7909-4-4Z"/>
                        <path d="M14.2996 13.2767c.2332-.2289.5636-.3294.8847-.2692C17.379 13.4191 19 15.4884 19 17.6488v2.1525c0 1.2289-1.0315 2.1428-2.2 2.1428H7.2c-1.16849 0-2.2-.9139-2.2-2.1428v-2.1525c0-2.1409 1.59079-4.1893 3.75163-4.6288.32214-.0655.65589.0315.89274.2595l2.34883 2.2606 2.3064-2.2634Z"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span>
                        Mahasiswa <br>
                        Kelas {{ $class->class_name }}
                    </span>
                    <span class="text-2xl font-bold">{{ $class->student->count() }}</span>
                </div>
            </div>
        </div>            
        @endforeach
        @endrole
        @role(['admin', 'dosenWali', 'kaprodi', 'jurusan'])
        <div class="p-4 bg-white border rounded-lg">
            <div class="flex justify-start items-start gap-2">
                <div class="">
                    <svg class="size-14 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm-1 9a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2Zm2-5a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1Zm4 4a1 1 0 1 0-2 0v3a1 1 0 1 0 2 0v-3Z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span>Laporan</span>
                    <span class="text-2xl font-bold">{{ $reports }}</span>
                </div>
            </div>
        </div>
        @endrole
    </div>
    
    {{-- @role('dosenWali')
    <!-- Chart Container -->
    <div class="p-4 bg-white border rounded-lg mb-4">
        <h2 class="text-xl font-semibold mb-2">Grafik IPS Mahasiswa Perwalian</h2>

        <!-- Chart Wrapper -->
        <div class="w-full overflow-x-auto">
            <!-- Chart ID -->
            <div id="gpaChart" class="min-w-[600px] h-[400px]" wire:ignore></div>
        </div>
    </div>

    <!-- Google Charts Script -->
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
    @endrole --}}

    {{-- @endrole --}}
</div>