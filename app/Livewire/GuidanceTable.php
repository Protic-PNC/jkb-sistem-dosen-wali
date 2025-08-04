<?php

namespace App\Livewire;

use App\Models\Guidance;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class GuidanceTable extends PowerGridComponent
{
    public string $tableName = 'guidance-table-mzvlwl-table';

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
        $query = Guidance::query()->with('student');
        $role = Auth::user()->roles->first()->name;

        if ($this->showReport == true) {
            $query->whereBetween('problem_date', [$this->dateStart, $this->dateEnd])->where('class_name', $this->className)->where('entry_year', $this->entryYear);
        }

        if ($role == 'mahasiswa') {
            $query->where('student_id', Auth::user()->student->id);
        } else if ($role == 'dosenWali') {
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
            ->add('problem')
            ->add('solution')
            ->add('problem_date')
            ->add('solution_date')
            ->add('is_validated')
            ->add('validated_status', function ($row) {
                return match($row->is_validated) {
                    null => '<span class="text-yellow-600">Belum divalidasi</span>',
                    1 => '<span class="text-green-600">Disetujui</span>',
                    0 => '<div>
                            <span class="text-red-600 font-semibold">Ditolak</span><br>
                            <span class="text-gray-600 text-sm italic">Catatan: ' . nl2br(e($row->validation_note)) . '</span>
                        </div>',
                };
            })
            ->add('created_at_formatted', fn ($model) => $model->created_at->format('d-m-Y'))
            ->add('formatted_problem', function ($row) {
                return "<div class='text-sm'>
                            <div class='text-gray-500 mb-1'>Tanggal: " . ($row->problem_date ? date('d-m-Y', strtotime($row->problem_date)) : '-') . "</div>
                            <div>" . nl2br(e($row->problem)) . "</div>
                        </div>";
            })
            ->add('formatted_solution', function ($row) {
                return "<div class='text-sm'>
                            <div class='text-gray-500 mb-1'>Tanggal: " . ($row->solution_date ? date('d-m-Y', strtotime($row->solution_date)) : '-') . "</div>
                            <div>" . nl2br(e($row->solution)) . "</div>
                        </div>";
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Kelas', 'class')
                ->searchable(),
            Column::make('NPM', 'nim')
                ->searchable(),
            Column::make('Nama', 'name')
                ->searchable()
                ->bodyAttribute('w-[300px] whitespace-normal break-words text-wrap text-sm'),
            Column::make('Permasalahan', 'formatted_problem')
                // ->sortable()
                // ->searchable()
                ->headerAttribute('w-[600px]')
                ->bodyAttribute('w-[600px] whitespace-normal break-words text-wrap text-sm'),

            Column::make('Solusi', 'formatted_solution')
                // ->sortable()
                // ->searchable()
                ->headerAttribute('w-[600px]')
                ->bodyAttribute('w-[600px] whitespace-normal break-words text-wrap text-sm'),
            
            Column::make('Status Validasi', 'validated_status')
                ->headerAttribute('w-[400px]')
                ->bodyAttribute('w-[400px] whitespace-normal break-words text-wrap text-sm'),
                // ->sortable()
                // ->searchable(),

            Column::action('Action')->hidden(!in_array(Auth::user()->roles->first()->name, ['dosenWali', 'mahasiswa']))
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

    public function actions(Guidance $row): array
    {
        $editButton = Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('edit-guidance', ['id' => $row->id]);
        $deleteButton = Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-guidance',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Data Bimbingan akan dihapus permanen.'
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
