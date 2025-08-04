<?php

namespace App\Livewire;

use App\Models\Achievement;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AchievementTable extends PowerGridComponent
{
    public string $tableName = 'achievement-table-qiwaha-table';

    protected $listeners = [
        'saved' => '$refresh',
        'deleted' => '$refresh',
        'refresh' => '$refresh'
    ];

    public $showReport = false;
    public $dateStart;
    public $dateEnd;
    public $semester;
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
        $query = Achievement::query()->with('student');

        if ($this->showReport == true) {
            $query->where('semester', $this->semester)->where('class_name', $this->className)->where('entry_year', $this->entryYear);
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

            // $query->where('class_name', $this->className)->where('entry_year', $this->entryYear);
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
            ->add('student.nim')
            ->add('student.user.name')
            ->add('name', fn($model) => $model->student->user->name)
            ->add('class', fn($model) => $model->class_name)
            ->add('nim', fn($model) => $model->student->nim)
            ->add('achievement_type')
            ->add('level')
            ->add('semester')
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
            Column::make('Jenis Prestasi atau Organisasi', 'achievement_type')
                ->sortable()
                ->searchable(),

            Column::make('Tingkat', 'level')
                ->sortable()
                ->searchable(),

            Column::make('Semester', 'semester')
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

    public function actions(Achievement $row): array
    {
        $editButton = Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('edit-scholarship', ['id' => $row->id]);
        $deleteButton = Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-scholarship',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Data Pencapaian akan dihapus permanen.'
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
