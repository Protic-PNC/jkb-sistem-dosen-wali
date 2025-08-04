<?php

namespace App\Livewire;

use App\Models\Report;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ReportIndex extends PowerGridComponent
{
    public string $tableName = 'report-index-zdkial-table';

    protected $listeners = [
        'saved' => '$refresh',
        'refresh' => '$refresh',
    ];

    public function setUp(): array
    {
        $this->showCheckBox();

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
        return Report::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('academic_advisor_id')
            ->add('student_class_id')
            ->add('academic_advisor_decree')
            ->add('academic_advisor_name')
            ->add('class_name')
            ->add('entry_year')
            ->add('semester')
            ->add('academic_year')
            ->add('status')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Academic advisor id', 'academic_advisor_id'),
            Column::make('Student class id', 'student_class_id'),
            Column::make('Academic advisor decree', 'academic_advisor_decree')
                ->sortable()
                ->searchable(),

            Column::make('Academic advisor name', 'academic_advisor_name')
                ->sortable()
                ->searchable(),

            Column::make('Class name', 'class_name')
                ->sortable()
                ->searchable(),

            Column::make('Entry year', 'entry_year')
                ->sortable()
                ->searchable(),

            Column::make('Semester', 'semester')
                ->sortable()
                ->searchable(),

            Column::make('Academic year', 'academic_year')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

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

    public function actions(Report $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
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
