<?php

namespace App\Livewire;

use App\Models\StudentResignation;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ResignationTable extends PowerGridComponent
{
    public string $tableName = 'resignation-table-cdghph-table';

    protected $listeners = ['saved' => '$refresh', 'deleted' => '$refresh', 'refresh' => '$refresh',];

    public $showReport = false;
    public $dateStart;
    public $dateEnd;
    public $advisorId;

    public function setUp(): array
    {

        if (!$this->showReport) {
            return [
                PowerGrid::header(),
                PowerGrid::footer()
                    ->showPerPage()
                    ->showRecordCount(),
            ];
        }

        return [];
    }

    public function datasource(): Builder
    {
        $query = StudentResignation::query()->with('student');
        
        if ($this->showReport == true) {
            $query->whereBetween('date', [$this->dateStart, $this->dateEnd]);
        }

        if ($this->advisorId) {
            $query->whereHas('student', function ($q) {
                $q->whereHas('student_class', function ($query) {
                    $query->where('academic_advisor_id', $this->advisorId);
                });
            });
        }

        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'student' => [
                'user.name'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('student.user.name')
            ->add('resignation_type')
            ->add('decree_number')
            ->add('reason')
            ->add('date')
            ->add('created_at');
            // ->add('created_at_formatted', fn ($model) => $model->created_at->format('d-m-Y'));
    }
 
    public function columns(): array
    {
        $columns = [
            Column::make('Nama', 'student.user.name')
                ->sortable()
                ->searchable(),

            Column::make('Jenis', 'resignation_type')
                ->sortable()
                ->searchable(),

            Column::make('SK Penetapan', 'decree_number')
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

        return $columns;
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

    public function actions(StudentResignation $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('edit-resignation', ['id' => $row->id]),
            Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-resignation',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Prodi akan dihapus permanen.'
                ]),
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
