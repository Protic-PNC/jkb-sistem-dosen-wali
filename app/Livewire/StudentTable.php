<?php

namespace App\Livewire;

use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class StudentTable extends PowerGridComponent
{
    public string $tableName = 'student-table-zztq4a-table';
    // public string $tableName = 'actions-from-view-table';
    

    // refresh table setelah action student dijalankan (create, update, delete):
    protected $listeners = ['saved' => '$refresh', 'refresh' => '$refresh',];

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

    public function datasource(): Builder
    {
        return Student::query()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('student_classes', 'students.student_class_id', '=', 'student_classes.id')
            ->select(
                'students.*',
                'users.name as user_name',
                'users.email as user_email',
                'student_classes.class_name as class_name'
            )->latest();
    }

    public function relationSearch(): array
    {
        return [
            'user' => ['name', 'email'],
            'student_class' => ['class_name']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('user_id')
            ->add('student_phone_number')
            ->add('nim')
            ->add('user.name')
            ->add('user.email')
            ->add('user_name')
            ->add('user_email')
            ->add('class_name')
            ->add('student_class.class_name')
            ->add('student_signature')
            ->add('inactive_at_formatted', fn (Student $model) => Carbon::parse($model->inactive_at)->format('d/m/Y'))
            ->add('created_at')
            ->add('status', function ($row) {
                return match ($row->status) {
                    'active' => '<span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Aktif</span>',
                    'graduated' => '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Lulus</span>',
                    'dropout' => '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Drop Out</span>',
                    'resign' => '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Undur Diri</span>',
                    'academic_leave' => '<span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Cuti</span>',
                    default => '-',
                };
            });
    }

    public function columns(): array
    {
        return [

            Column::make('NIM', 'nim')
                ->sortable()
                ->searchable(),

            Column::make('Nama', 'user_name')
                ->sortable(fn () => 'users.name')
                ->searchable(),

            Column::make('Email', 'user_email')
                ->sortable(fn () => 'users.email')
                ->searchable(),

            Column::make('Kelas', 'class_name')
                ->sortable(fn () => 'student_classes.class_name')
                ->searchable(),


            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::action('Aksi')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('inactive_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Student $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2')
                ->dispatch('edit-student', ['id' => $row->id]),
            Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-student',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Prodi akan dihapus permanen.'
                ]),
        ];
    }
}
