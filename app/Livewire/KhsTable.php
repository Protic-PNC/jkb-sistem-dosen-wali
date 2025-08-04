<?php

namespace App\Livewire;

use App\Models\Khs;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class KhsTable extends PowerGridComponent
{
    public string $tableName = 'khs-table-vchqmo-table';

    protected $listeners = [
        'saved' => '$refresh',
        'refresh' => '$refresh',
    ];

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function header(): array
    {
        $buttonDelete = Button::add('delete-multiple')
                ->slot('Hapus yang Dipilih')
                ->id('delete-selected')
                ->class('bg-red-600 text-white px-3 py-2 rounded')
                ->dispatch('delete-multiple-khs', ['ids' => $this->checkboxValues]);
        if ($this->checkboxValues) {
            return [
                $buttonDelete,
            ];
        }
        
        return [
            // $buttonDelete
        ];
    }

    public function datasource(): Builder
    {
        $khs = Khs::query()->with('student.user');
        $role = Auth::user()->roles->first()->name;

        if ($role == 'mahasiswa') {
            $khs = $khs->where('student_id', Auth::user()->student->id);
        } else if ($role == 'dosenWali') {
            // $khs = $khs->whereHas('student', function ($query) {
            //     $query->where('student_class_id', Auth::user()->lecturer->student_class->id);
            // });
            $khs = $khs->whereHas('student', function ($query) {
                $query->whereHas('student_class', function ($q) {
                    $q->where('academic_advisor_id', Auth::user()->lecturer->id);
                });
            });
        } else if ($role == 'kaprodi') {
            $khs = $khs->whereHas('student.student_class.program', function ($query) {
                $query->where('head_of_program_id', Auth::user()->lecturer->id);
            });
        }

        return $khs;
    }

    public function relationSearch(): array
    {
        return [
            
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('student_id')
            ->add('student.user.name')
            ->add('student.student_class.class_name')
            ->add('student.student_class.entry_year')
            ->add('semester')
            ->add('file_link', fn ($khs) => 
                '<a href="' . asset('storage/' . $khs->file) . '" target="_blank" class="text-blue-600 underline">Lihat KHS</a>'
            )
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Nama', 'student.user.name'),
            Column::make('Kelas', 'student.student_class.class_name'),
            Column::make('Angkatan', 'student.student_class.entry_year'),
            Column::make('Semester', 'semester')
                ->sortable()
                ->searchable(),

            Column::make('File', 'file_link')
                ->sortable()
                ->searchable(),

            Column::action('Action')->hidden(Auth::user()->roles->first()->name != 'admin')
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

    public function actions(Khs $row): array
    {
        return [
           Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('edit-khs', ['id' => $row->id]),
            Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-khs',
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
