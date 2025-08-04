<?php

namespace App\Livewire;

use App\Models\Warning;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class WarningTable extends PowerGridComponent
{
    public string $tableName = 'warning-table-o9igju-table';

    protected $listeners = [
        'saved' => '$refresh',
        'deleted' => '$refresh',
        'refresh' => '$refresh',
    ];

    public $showReport = false;
    public $dateStart;
    public $dateEnd;
    public $advisorId;
    public $classFilters;

    public $className;
    public $entryYear;

    public function setUp(): array
    {
        if (!$this->showReport) {
            return [
                PowerGrid::header()
                    ->showSearchInput(),
                PowerGrid::footer()
                    ->showPerPage()
                    ->showRecordCount(),
            ];
        }

        return [];
    }

    public function datasource(): Builder
    {
        $query = Warning::query()->with('student');

        if ($this->showReport == true) {
            $query->whereBetween('date', [$this->dateStart, $this->dateEnd])->where('class_name', $this->className)->where('entry_year', $this->entryYear);
        }

        if ($this->advisorId) {
            $query->where(function ($mainQuery) {
                // Kondisi 1: Mahasiswa yang punya kelas dan dosen wali sesuai
                $mainQuery->whereHas('student.student_class', function ($q) {
                    $q->where('academic_advisor_id', $this->advisorId);
                });

                // Kondisi 2: Mahasiswa tanpa relasi kelas, cocokkan dengan classFilters manual
                $mainQuery->orWhereHas('student', function ($q) {
                    $q->whereNull('student_class_id') // pastikan tidak punya kelas
                    ->where(function ($query) {
                        foreach ($this->classFilters as $filter) {
                            $query->orWhere(function ($subQuery) use ($filter) {
                                $subQuery->where('class_name', $filter['class_name'])
                                    ->where('entry_year', $filter['entry_year']);
                            });
                        }
                    });
                });
            });

            // $query->whereHas('student', function ($q) {
            //     $q->whereHas('student_class', function ($query) {
            //         $query->where('academic_advisor_id', $this->advisorId);
            //     });
            // });
            // $query->where(function ($query) {
            //     foreach ($this->classFilters as $filter) {
            //         $query->orWhere(function ($q) use ($filter) {
            //             $q->where('class_name', $filter['class_name'])
            //             ->where('entry_year', $filter['entry_year']);
            //         });
            //     }
            // });
        }

        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'student' => ['nim'],
            'student.user' => ['name'],
            'student.student_class' => ['class_name']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name', fn($model) => $model->student->user->name)
            ->add('class', fn($model) => $model->class_name)
            ->add('nim', fn($model) => $model->student->nim)
            ->add('warning_type')
            ->add('reason')
            ->add('date')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Kelas', 'class')
                ->searchable(),
            Column::make('NPM', 'nim')
                ->searchable(),
            Column::make('Nama', 'name')
                ->searchable(),
            Column::make('Jenis Peringatan', 'warning_type')
                ->sortable()
                ->searchable(),

            Column::make('Alasan', 'reason')
                ->sortable()
                ->searchable(),

            Column::make('Tanggal', 'date')
                ->sortable()
                ->searchable(),

            Column::action('Action')->hidden(Auth::user()->roles->first()->name !== 'dosenWali')
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

    public function actions(Warning $row): array
    {
        $editButton = Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('edit-warning', ['id' => $row->id]);
        $deleteButton = Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-warning',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Data Peringatan akan dihapus permanen.'
                ]);

        if ($row->student->status == 'active' && $row->class_name == $row->student->student_class->class_name)
        {
            return [$editButton, $deleteButton];
        } else {
            return [];
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
