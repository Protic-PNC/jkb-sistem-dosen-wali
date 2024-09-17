<x-app-layout>
    

    @section('content')

        @role('admin')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                        <dl class="grid max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-6 dark:text-white sm:p-8">
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
                                <dd class="text-gray-500 dark:text-gray-400">Laporan Perwalian</dd>
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
                        document.querySelectorAll('[role="tabpanel"]').forEach((panel) => {
                            panel.classList.add('hidden');
                        });
            
                        // Remove hidden class from the tab panel that corresponds to the clicked tab
                        const target = document.querySelector(tab.dataset.tabsTarget);
                        target.classList.remove('hidden');
            
                        // Update aria-selected on the buttons
                        tabs.forEach((t) => t.setAttribute('aria-selected', 'false'));
                        tab.setAttribute('aria-selected', 'true');
                    });
                });
            
                // Handling mobile select
                const mobileSelect = document.getElementById('tabs');
                mobileSelect.addEventListener('change', function () {
                    document.querySelectorAll('[role="tabpanel"]').forEach((panel) => {
                        panel.classList.add('hidden');
                    });
                    const selectedTab = mobileSelect.value.toLowerCase();
                    const target = document.getElementById(selectedTab);
                    target.classList.remove('hidden');
                });
            });
            </script>
            
        @endrole

        @role('dosenWali')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        {{ __("Role Dosen Wali") }}
                    </div>
                </div>
            </div>
        </div>
        @endrole
        
        @role('mahasiswa')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        {{ __("Role Mahasiswa") }}
                    </div>
                </div>
            </div>
        </div>
        @endrole
    @endsection

    


</x-app-layout>
