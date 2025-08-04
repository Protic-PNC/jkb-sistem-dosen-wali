<?php

namespace App\Livewire;

use App\Models\Krs;
use App\Models\KrsFormat;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class KrsTable extends PowerGridComponent
{
    public string $tableName = 'krs-table-nrqd4o-table';

    public int $krsFormatId;
    public $krsFormat;
    // public string $krsFormatName;

    public $krs;

    protected $listeners = [
        'savedTb2' => '$refresh',
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
        $role = Auth::user()->roles->first()->name;
        $this->krsFormat = KrsFormat::find($this->krsFormatId);

        // Pastikan $this->krsFormat tidak null
        if (!$this->krsFormat) {
            return Krs::query()->whereRaw('1 = 0'); // return kosong jika format tidak ditemukan
        }

        $krs = Krs::query()
            ->with('krs_format', 'student')
            ->where('krs_format_id', $this->krsFormatId)
            ->whereHas('krs_format', function ($query) {
                $query->where('semester', $this->krsFormat->semester);
            });

        if ($role == 'mahasiswa') {
            $krs->where('student_id', Auth::user()->student->id);
        }

        if ($role == 'dosenWali') {
            $krs->whereHas('student', function ($query) {
                $query->whereHas('student_class', function ($q) {
                    $q->where('academic_advisor_id', Auth::user()->lecturer->id);
                });
            });
        }

        return $krs;
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('student.user.name')
            ->add('student.student_class.class_name')
            ->add('student.student_class.entry_year')
            ->add('krs_format.semester')
            ->add('file_link', fn ($krs) => 
                '<a href="' . asset('storage/' . $krs->file) . '" target="_blank" class="text-blue-600 underline">Lihat KRS</a>'
            )
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Nama', 'student.user.name'),
            Column::make('Kelas', 'student.student_class.class_name'),
            Column::make('Angkatan', 'student.student_class.entry_year'),
            Column::make('Semester', 'krs_format.semester'),
            Column::make('File', 'file_link'),

            Column::action('Action')->hidden(Auth::user()->roles->first()->name !== 'mahasiswa')
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

    public function actions(Krs $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->dispatch('edit-krs', ['id' => $row->id]),
            Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-krs',
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
