<?php

namespace App\Livewire;

use App\Models\KrsFormat;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class KrsFormatTable extends PowerGridComponent
{
    public string $tableName = 'krs-format-table-ytkmbq-table';

    protected $listeners = [
        'savedTb1' => '$refresh', 
        'deletedTb1' => '$refresh',
        'refresh' => '$refresh',
    ];

    public $role;
    public $krsFormat;

    public function setUp(): array
    {
        $this->showCheckBox();

        $this->role = Auth::user()->roles->first()->name;

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function getSemesterRange()
    {

    }

    public function datasource(): Builder
    {
        $user = Auth::user();
        $role = $user->roles->first()->name;

        // Default query
        $query = KrsFormat::query();

        if ($role == 'mahasiswa') {
            $student = $user->student;
            $studentClass = $student->student_class;
            $program = $studentClass->program;

            $entryYear = $studentClass->entry_year;
            $programId = $program->id;
            $degree = $program->degree;

            // Ambil semua data sesuai program
            $allKrs = KrsFormat::where('program_id', $programId)
                ->orderBy('academic_year')
                ->get();

            // Filter manual
            $filteredIds = [];

            foreach ($allKrs as $krs) {
                [$startYear, ] = explode('/', $krs->academic_year);
                $yearDiff = (int)$startYear - (int)$entryYear;

                if ($yearDiff < 0) continue;

                $semesterGanjil = $yearDiff * 2 + 1;
                $semesterGenap  = $semesterGanjil + 1;

                $maxSemester = $degree === 'D3' ? 6 : 8;

                if (
                    ($krs->semester == $semesterGanjil || $krs->semester == $semesterGenap) &&
                    $krs->semester <= $maxSemester
                ) {
                    $filteredIds[] = $krs->id;
                }
            }

            // Kembalikan query untuk ID yang cocok
            return KrsFormat::whereIn('id', $filteredIds);
        }

        // Jika Kaprodi
        if ($role == 'kaprodi') {
            $programId = $user->lecturer->program->id;
            return KrsFormat::where('program_id', $programId)
                ->orderBy('academic_year');
        }

        // Default (admin, dll)
        return $query;
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('program_name', fn ($krs_format) =>
                $krs_format->program->degree . ' ' . $krs_format->program->program_name
            )
            ->add('semester')
            ->add('academic_year')
            ->add('file_link', fn ($krs_format) => 
                '<a href="' . asset('storage/' . $krs_format->file) . '" target="_blank" class="text-blue-600 underline">Unduh format KRS</a>'
            )
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Prodi', 'program_name'),
            Column::make('Semester', 'semester')
                ->sortable()
                ->searchable(),

            Column::make('Tahun Akademik', 'academic_year')
                ->sortable()
                ->searchable(),

            Column::make('File', 'file_link'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(KrsFormat $row): array
    {
        $edit = Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('edit-format', ['id' => $row->id]);

        $delete = Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-format',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Prodi akan dihapus permanen.'
                ]);

        $formatSelect = Button::add('class')
                ->slot('Pilih Format')
                ->id()
                ->class('focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('formatSelected', ['krsFormatId' => $row->id, 'semester' => $row->semester, 'academicYear' => $row->academic_year]);

        if ($this->role == 'admin') {
            return [
                $edit,
                $delete,
                $formatSelect
            ];
        } else {
            return [
                $formatSelect
            ];
        }
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
