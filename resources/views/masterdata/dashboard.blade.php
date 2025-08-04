<x-app-layout>

    @section('content') @section('breadcrumb', 'Dashboard')
    <div class="flex flex-wrap -mx-3">
        @role('admin')
        <!-- card1 -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div
                class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-sm">Users</p>
                                <h5 class="mb-0 font-bold">
                                    {{ $usersCount }}
                                    {{-- <span class="leading-normal text-sm font-weight-bolder text-lime-500">+3%</span> --}}
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div
                                class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                <i class="ni leading-none ni-world text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- card2 -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div
                class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-sm">Students</p>
                                <h5 class="mb-0 font-bold">
                                    {{ $studentsCount }}
                                    {{-- <span class="leading-normal text-sm font-weight-bolder text-lime-500">+55%</span> --}}
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div
                                class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                <i class="ni leading-none ni-money-coins text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- card3 -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div
                class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-sm">Class</p>
                                <h5 class="mb-0 font-bold">
                                    {{ $student_classesCount }}
                                    {{-- <span class="leading-normal text-red-600 text-sm font-weight-bolder">-2%</span> --}}
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div
                                class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                <i class="ni leading-none ni-paper-diploma text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- card4 -->
        <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
            <div
                class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-sm">Dosen</p>
                                <h5 class="mb-0 font-bold">
                                    {{ $lecturersCount }}
                                    {{-- <span class="leading-normal text-sm font-weight-bolder text-lime-500">+5%</span> --}}
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div
                                class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                <i class="ni leading-none ni-cart text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- card5 -->
        <div class="w-full max-w-full px-3 my-6 sm:w-1/2 sm:flex-none xl:w-1/4">
            <div
                class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-sm">Laporan</p>
                                <h5 class="mb-0 font-bold">
                                    {{ $reportsCount }}
                                    {{-- <span class="leading-normal text-sm font-weight-bolder text-lime-500">+5%</span> --}}
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div
                                class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                <i class="ni leading-none ni-cart text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole
    {{-- @role('admin')
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="stats" role="tabpanel"
                            aria-labelledby="stats-tab">
                            <dl
                                class="grid max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-6 dark:text-white sm:p-8">
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ $usersCount }}</dt>
<dd class="text-gray-500 dark:text-gray-400">Pengguna</dd>
</div>
<div class="flex flex-col items-center justify-center">
<dt class="mb-2 text-3xl font-extrabold">{{ $programsCount }}</dt>
<dd class="text-gray-500 dark:text-gray-400">Prodi</dd>
</div>
<div class="flex flex-col items-center justify-center">
<dt class="mb-2 text-3xl font-extrabold">{{ $student_classesCount }}</dt>
<dd class="text-gray-500 dark:text-gray-400">Kelas</dd>
</div>
<div class="flex flex-col items-center justify-center">
<dt class="mb-2 text-3xl font-extrabold">{{ $studentsCount }}</dt>
<dd class="text-gray-500 dark:text-gray-400">Mahasiswa</dd>
</div>
<div class="flex flex-col items-center justify-center">
<dt class="mb-2 text-3xl font-extrabold">{{ $lecturersCount }}</dt>
<dd class="text-gray-500 dark:text-gray-400">Dosen</dd>
</div>
<div class="flex flex-col items-center justify-center">
<dt class="mb-2 text-3xl font-extrabold">{{ $reportsCount }}</dt>
<dd class="text-gray-500 dark:text-gray-400">Laporan</dd>
</div>
</dl>
</div>
</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
// Find all buttons with data-tabs-target attribute
const tabs = document.querySelectorAll('[data-tabs-target]');

// Add click event for each tab button
tabs.forEach((tab) => {
tab.addEventListener('click', () => {
// Hide all tab panels
document
.querySelectorAll('[role="tabpanel"]')
.forEach((panel) => {
panel
    .classList
    .add('hidden');
});

// Remove hidden class from the tab panel that corresponds to the clicked tab
const target = document.querySelector(tab.dataset.tabsTarget);
target
.classList
.remove('hidden');

// Update aria-selected on the buttons
tabs.forEach((t) => t.setAttribute('aria-selected', 'false'));
tab.setAttribute('aria-selected', 'true');
});
});

// Handling mobile select
const mobileSelect = document.getElementById('tabs');
mobileSelect.addEventListener('change', function () {
document
.querySelectorAll('[role="tabpanel"]')
.forEach((panel) => {
panel
.classList
.add('hidden');
});
const selectedTab = mobileSelect
.value
.toLowerCase();
const target = document.getElementById(selectedTab);
target
.classList
.remove('hidden');
});
});
</script>
@endrole --}} @role('dosenWali')
<div
class="max-w-sm text-bold text-center mb-4 mt-5 w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
Kelas
{{ Auth::user()->lecturer->student_classes->class_name ?? '-'}}
</div>

<div class="w-full mb-4 bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
{{-- <p class="text-2xl font-bold text-gray-700 dark:text-white">Perkembangan Akademis Mahasiswa Perwalian</p> --}}
<div class="overflow-x-auto">
<table
class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
<thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
<tr>
<th rowspan="2" scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
Keterangan
</th>
<th
colspan="{{ $jumlahSemester }}"
scope="col"
class="text-center px-6 py-3 bg-gray-50 dark:bg-gray-800">
Semester
</th>
</tr>
<tr>
@for ($i = 0; $i < $jumlahSemester; $i++)
<th scope="col" class="px-6 py-3">
{{ $i + 1 }}
</th>
@endfor
</tr>
</thead>
<tbody>
<tr class="border-b border-gray-200 dark:border-gray-700">
<th
scope="row"
class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
IPS Rata-rata
</th>
@for ($i = 0; $i < $jumlahSemester; $i++)
<td class="px-6 py-4">{{ $table_data[$i]['avg_gpa'] ?? '-' }}</td>
@endfor
</tr>
<tr class="border-b border-gray-200 dark:border-gray-700">
<th
scope="row"
class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
IPS Tertinggi
</th>
@for ($i = 0; $i < $jumlahSemester; $i++)
<td class="px-6 py-4">{{ $table_data[$i]['max_gpa'] ?? '-' }}</td>
@endfor
</tr>
<tr class="border-b border-gray-200 dark:border-gray-700">
<th
scope="row"
class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
IPS Terendah
</th>
@for ($i = 0; $i < $jumlahSemester; $i++)
<td class="px-6 py-4">{{ $table_data[$i]['min_gpa'] ?? '-' }}</td>
@endfor
</tr>
<tr class="border-b border-gray-200 dark:border-gray-700">
<th
scope="row"
class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
IPS < 3.00
</th>
@for ($i = 0; $i < $jumlahSemester; $i++)
<td class="px-6 py-4">{{ $table_data[$i]['count_below_3'] ?? '-' }}</td>
@endfor
</tr>

<tr>
<th
scope="row"
class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
% IPS < 3.00
</th>
@for ($i = 0; $i < $jumlahSemester; $i++)
<td class="px-6 py-4">
@if (isset($table_data[$i]['percentage_below_3']))
{{ $table_data[$i]['percentage_below_3'] }}% @else - @endif
</td>
@endfor
</tr>
<tr>
<th
scope="row"
class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
IPS >= 3.00
</th>
@for ($i = 0; $i < $jumlahSemester; $i++)
<td class="px-6 py-4">{{ $table_data[$i]['count_above_3'] ?? '-' }}</td>
@endfor
</tr>
<tr>
<th
scope="row"
class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
% IPS >= 3.00
</th>
@for ($i = 0; $i < $jumlahSemester; $i++)
<td class="px-6 py-4">
@if (isset($table_data[$i]['percentage_above_3']))
{{ $table_data[$i]['percentage_above_3'] }}% @else - @endif
</td>
@endfor
</tr>
</tbody>
</table>
</div>
</div>
{{-- <div class="flex flex-col md:flex-row gap-4"> --}}

<div
class="max-w-sm w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
<div
class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
<div class="flex items-center">
<div
class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">
<svg
class="w-6 h-6 text-gray-500 dark:text-gray-400"
aria-hidden="true"
xmlns="http://www.w3.org/2000/svg"
fill="currentColor"
viewBox="0 0 20 19">
<path
d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z"/>
</svg>
</div>
<div>
<h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1">
{{ $students->count() }}</h5>
<p class="text-sm font-normal text-gray-500 dark:text-gray-400">Mahasiswa kelas
{{ $students->first()->student_classes->class_name ?? '-' }}</p>
</div>
</div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<dl class="flex items-center">
<dt class="text-gray-500 dark:text-gray-400 text-sm font-normal me-1">Rata-rata IPK:</dt>
<dd class="text-gray-900 text-sm dark:text-white font-semibold">{{ $avg_gpas }}</dd>
</dl>
</div>

<div id="column-chart"></div>
</div>
{{-- </div> --}}

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const options = {
colors: ["#1A56DB"],
series: [
{
name: "Rata-rata IPS",
color: "#1A56DB",
data: @json($chart_data)
}
],
chart: {
type: "bar",
height: "320px"
},
plotOptions: {
bar: {
horizontal: false,
columnWidth: "70%",
borderRadius: 8,
borderRadiusApplication: "end"
}
},
xaxis: {
labels: {
style: {
fontFamily: "Inter, sans-serif",
cssClass: 'text-xs font-normal fill-gray-500'
}
}
}
}

const chart = new ApexCharts(document.getElementById("column-chart"), options);
chart.render();
</script>
@endrole @role('mahasiswa')
<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 text-gray-900">
{{ __('Role Mahasiswa') }}
</div>
</div>
</div>
</div>
@endrole @role('kaprodi')
<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 text-gray-900">
{{ __('Role kaprodi') }}
</div>
</div>
</div>
</div>
@endrole @endsection

</x-app-layout>