<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Siwali') }}</title>
        <link rel="icon" href="{{ asset('tefa.png') }}" type="image/png" sizes="16">  

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- <style>
            [wire\:cloak] {
                display: none !important;
            }
        </style> --}}
        {{-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> --}}
    </head>



    <body
        x-data="{ sidebarToggle: false }"
        x-init="
            ['saved', 'deleted', 'error', 'savedTb1', 'savedTb2', 'deletedTb1', 'deletedTb2'].forEach(eventName => {
                window.addEventListener(eventName, event => {
                    const config = {
                        saved:   { icon: 'success', title: 'Sukses!' },
                        savedTb1:   { icon: 'success', title: 'Sukses!' },
                        savedTb2:   { icon: 'success', title: 'Sukses!' },
                        deleted: { icon: 'success', title: 'Dihapus!' },
                        deletedTb1: { icon: 'success', title: 'Dihapus!' },
                        deletedTb2: { icon: 'success', title: 'Dihapus!' },
                        error:   { icon: 'error',   title: 'Gagal!' }
                    }[eventName];

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: config.icon,
                        title: config.title + ' - ' + (event.detail.message ?? 'Operasi berhasil.'),
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: false,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                });
            });
        "
        class="font-sans antialiased">
        <div class="flex h-screen overflow-hidden">
            
            <livewire:layout.sidebar>

            <div class="flex flex-col flex-1 overflow-x-hidden">

                <livewire:layout.navigation />

                <main class="flex-1 overflow-y-auto">
                    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6 ">
                        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                            <nav>
                              <ol class="flex items-center gap-1.5 text-sm text-gray-500 ">
                                <li>
                                  <a
                                    class="@yield('addClass') inline-flex items-center gap-1.5"
                                    href="{{ route('dashboard') }}" wire:navigate>
                                    Dashboard
                                    <svg
                                      class="stroke-current"
                                      width="17"
                                      height="16"
                                      viewBox="0 0 17 16"
                                      fill="none"
                                      xmlns="http://www.w3.org/2000/svg"
                                    >
                                      <path
                                        d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                                        stroke=""
                                        stroke-width="1.2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                      />
                                    </svg>
                                  </a>
                                </li>
                                @if(View::hasSection('main_folder'))
                                    <li @if (View::hasSection('sub_folder')) class="inline-flex items-center gap-1.5" @endif>
                                        @if(View::hasSection('main_folder-link'))
                                            <a href="@yield('main_folder-link')" wire:navigate.hover>@yield('main_folder')</a>
                                        @else
                                            @yield('main_folder')
                                        @endif

                                        @if (View::hasSection ('sub_folder'))
                                            <svg
                                            class="stroke-current"
                                            width="17"
                                            height="16"
                                            viewBox="0 0 17 16"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                                >
                                                <path
                                                    d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                                                    stroke=""
                                                    stroke-width="1.2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                        @endif
                                    </li>
                                    <li>
                                        @yield('sub_folder')    
                                    </li>
                                @endif
                              </ol>
                            </nav>
                        </div>
                        @if (!request()->routeIs('dashboard'))  
                            <div class="flex justify-between items-center w-full mb-2">
                                <div class="text-yellow-500 font-bold text-3xl">
                                    @yield('title-page')
                                </div>
                                <div>
                                    <button
                                        onclick="Livewire.dispatch('refresh')"
                                        class="p-2 border rounded hover:bg-gray-200 flex justify-between items-center gap-2">
                                        <span class="text-sm">Refresh tabel</span>
                                        <svg class="size-4 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.651 7.65a7.131 7.131 0 0 0-12.68 3.15M18.001 4v4h-4m-7.652 8.35a7.13 7.13 0 0 0 12.68-3.15M6 20v-4h4"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                        {{ $slot }}
                    </div>
                </main>
            </div>
</div>

        @livewireScripts
        @stack('scripts')

        {{-- sidebar script --}}
        <script>
            function toggleDropdown() {
                const dropdown = document.getElementById('dropdown-pages');
                const arrowIcon = document.getElementById('arrowIcon');

                dropdown.classList.toggle('hidden');
                arrowIcon.classList.toggle('rotate-180');
            }
            
            function toggleReportDropdown() {
                const dropdown = document.getElementById('dropdown-report-pages');
                const arrowIcon = document.getElementById('arrowReportIcon');

                dropdown.classList.toggle('hidden');
                arrowIcon.classList.toggle('rotate-180');
            }
        </script>

        <script>
            // Target sidebar
            const sidebar = document.getElementById('sidebar');

            // Simpan posisi scroll sebelum berpindah halaman
            document.addEventListener('livewire:navigating', () => {
                if (sidebar) {
                    localStorage.setItem('sidebar-scroll', sidebar.scrollTop);
                }
            });

            // Kembalikan posisi scroll setelah halaman selesai dimuat
            document.addEventListener('livewire:navigated', () => {
                if (sidebar) {
                    const scroll = localStorage.getItem('sidebar-scroll');
                    if (scroll !== null) {
                        sidebar.scrollTop = scroll;
                    }
                }
            });
        </script>
        
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function registerDeleteHandler() {
                document.removeEventListener('click', handleDeleteClick); // Bersihkan handler ganda
                document.addEventListener('click', handleDeleteClick);
            }

            function handleDeleteClick(e) {
                const button = e.target.closest('button[data-confirm-delete]');
                if (button) {
                    e.preventDefault();

                    const id = button.getAttribute('data-id');
                    const event = button.getAttribute('data-event');
                    const title = button.getAttribute('data-title') || 'Yakin ingin menghapus?';
                    const text = button.getAttribute('data-text') || 'Data akan dihapus secara permanen.';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch(event, { id: id });
                        }
                    });
                }
            }

            // Saat DOM pertama kali dimuat
            document.addEventListener('DOMContentLoaded', registerDeleteHandler);

            // Saat Livewire selesai navigasi atau render ulang
            document.addEventListener('livewire:load', registerDeleteHandler);
            document.addEventListener('livewire:navigated', registerDeleteHandler);
        </script>

        <script>
            document.addEventListener("livewire:navigated", () => {
                // Reset toggle jika diperlukan
                if (Alpine?.store('sidebar')) {
                    Alpine.store('sidebar').open = false;
                }
            });
        </script>

        <script>
              document.addEventListener('alpine:init', () => {
                  Alpine.store('sidebar', {
                      open: false,
                      toggle() {
                          this.open = !this.open;
                      }
                  });
              });
        </script>

        <script>
            Livewire.on('saved', ({ message }) => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Sukses! - ' + (message ?? 'Operasi berhasil.'),
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: false,
                });
            });
        </script>
    </body>
</html>
