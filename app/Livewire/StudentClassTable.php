<?php

namespace App\Livewire;

use App\Models\StudentClass;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class StudentClassTable extends PowerGridComponent
{
    public string $tableName = 'student-class-table-ix5vsj-table';

    public int $programId;
    public string $programName;

    protected $listeners = ['saved' => '$refresh', 'refresh' => '$refresh',];

    public function setUp(): array
    {

        return [
            // PowerGrid::header(),
            //     // ->showSearchInput(),
            // PowerGrid::footer()
            //     ->showPerPage()
            //     ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return StudentClass::query()->where('program_id', $this->programId);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('academic_advisor_decree')
            ->add('class_name')
            ->add('entry_year')
            ->add('status');
    }

    public function columns(): array
    {
        return [

            Column::make('Nama', 'class_name')
                ->sortable()
                ->searchable()
                ->bodyAttribute('whitespace-normal break-words'),

            Column::make('Angkatan', 'entry_year')
                ->sortable()
                ->searchable()
                ->bodyAttribute('whitespace-normal break-words'),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable()
                ->bodyAttribute('whitespace-normal break-words'),

            Column::action('Aksi')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('graduated_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(StudentClass $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('edit-student-class', ['id' => $row->id]),
            Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-student-class',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Kelas akan dihapus permanen.'
                    ])
        ];
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
