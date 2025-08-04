<div class="flex flex-col gap-2">
    @section('main_folder', 'Indeks Prestasi')
    @section('title-page', 'Data Indeks Prestasi')

    @foreach ($studentClass as $class)
    <div class="w-full bg-white border shadow p-4 rounded-lg">
            <livewire:gpas.gpa-table 
                :jumlah-semester="match ($class->program->degree) {'D3' => 6,'D4' => 8,}"
                :detail-report="false" 
                :semester="$class->current_semester ?? null" 
                :class-id="$class->id ?? null" />
    </div>
    @endforeach
</div>