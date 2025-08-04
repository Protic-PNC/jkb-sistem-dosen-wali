<?php

namespace App\Livewire;

use App\Models\Lecturer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class LecturerTable extends PowerGridComponent
{
    public string $tableName = 'lecturer-table-qpuzdd-table';

    //refresh table setelah action lecturer dijalankan (create, update, delete)
    protected $listeners = ['saved' => '$refresh', 'refresh' => '$refresh',];

    public function setUp(): array
    {

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Lecturer::query()
        ->select('lecturers.*', 'users.name as user_name')
        ->join('users', 'users.id', '=', 'lecturers.user_id')->latest();
    }

    public function relationSearch(): array
    {
        return [
            'user' => [
                'name'
            ],
        ];
    }


    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('nidn')
            ->add('nip')
            ->add('lecturer_phone_number')
            ->add('user.name');
    }

    public function columns(): array
    {
        return [
            Column::make('NIDN', 'nidn')
                ->sortable()
                ->searchable(),

            Column::make('NIP', 'nip')
                ->sortable()
                ->searchable(),
                
            Column::make('Nama', 'user_name')
                ->sortable()
                ->searchable(),

            // Column::make('Jabatan', 'role_name')
            //     ->sortable()
            //     ->searchable(),


            Column::make('No HP', 'lecturer_phone_number')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    private function formatRoleName(?string $role): string
    {
        if (!$role) return '-';

        // Pisahkan berdasarkan huruf kapital, lalu kapitalisasi tiap kata
        return collect(preg_split('/(?=[A-Z])/', $role))
            ->filter()
            ->map(fn($word) => ucfirst($word))
            ->implode(' ');
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

    public function actions(Lecturer $row): array
    {
        return [
           Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5')
                ->dispatch('edit-lecturer', ['id' => $row->id]),
            Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-lecturer',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Dosen akan dihapus permanen.'
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
