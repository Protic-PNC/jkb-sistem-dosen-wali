<?php

namespace App\Livewire;

use App\Models\Program;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\Rule; 
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ProgramTable extends PowerGridComponent
{
    public string $tableName = 'program-table-o76ums-table';

    protected $listeners = ['saved' => '$refresh', 'refresh' => '$refresh',];

    public function setUp(): array
    {

        return [
            // PowerGrid::header()
            
        ];
    }

    public function datasource(): Builder
    {
        return Program::query()->with('head_of_program.user');
    }

    public function relationSearch(): array
    {
        return [
            'head_of_program.user' => [
                'name'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('program_name')
            ->add('degree')
            ->add('full_program_name', function ($program) {
                return $program->degree . ' ' . $program->program_name;
            })
            ->add('head_of_program.user.name')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Prodi', 'full_program_name')
                ->headerAttribute('w-24')
                ->bodyAttribute('w-32 whitespace-normal break-words text-wrap text-sm'),

            Column::make('Kaprodi', 'head_of_program.user.name')
                ->headerAttribute('w-24')
                ->bodyAttribute('w-32 whitespace-normal break-words text-wrap text-sm'),

            Column::action('Aksi')
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

    public function actions(Program $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('edit-program', ['id' => $row->id]),
            Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-program',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Prodi akan dihapus permanen.'
                ]),
            Button::add('class')
                ->slot('Pilih prodi')
                ->id()
                ->class('focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('programSelected', ['programId' => $row->id, 'programName' => $row->degree . ' ' . $row->program_name])
        ];
    }

    
    // public function actionRules($row): array
    // {
    //    return [
    //         // Hide button edit for ID 1
    //         // Rule::button('edit')
    //         //     ->when(fn($row) => $row->id === 1)
    //         //     ->hide(),
    //         // Rule::rows()
    //         //       ->setAttribute('class', '!bg-red-500')
                    
    //     ];
    // }
}
