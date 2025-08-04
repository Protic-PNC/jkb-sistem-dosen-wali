<?php

namespace App\Livewire;

use App\Models\Program;
use App\Models\Report;
use App\Models\StudentClass;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ReportTable extends PowerGridComponent
{
    public string $tableName = 'report-table-d4j74u-table';

    protected $listeners = ['saved', '$refresh', 'refresh' => '$refresh',];

    public string $role;

    public $program;
    public $jumlahSemester;

    public $programId;

    //  public function hydrate(): void
    // {
    //     $this->program = Program::find($this->programId);
    // }

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
        PowerGrid::header(),
                // ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): \Illuminate\Support\Collection
    {
        $this->role = Auth::user()->roles->first()->name;

        if ($this->role === 'jurusan') {
            $studentClasses = StudentClass::query()
                ->where('program_id', $this->programId)
                ->where('status', 'active')
                ->with([
                    'report' => fn ($query) => $query->latest(),
                    'lecturer.user'
                ])
                ->get();

            return $studentClasses->flatMap(function ($class) {
                return collect(range(1, $class->current_semester))->map(function ($semester) use ($class) {
                    $copy = clone $class;
                    $copy->semester = $semester;
                    $report = $class->report->where('semester', $semester)->first();
                    $status = $report?->status ?? 'belum dibuat';
                    $copy->status = $status;
                    $copy->id = $report?->id ?? null;
                    return $copy;
                });
            })->sortBy([
                ['class_name', 'asc'],
                ['semester', 'asc'],
            ])->values();
        }



        // role selain jurusan
        $reports = Report::with('academic_advisor', 'student_class');

        if ($this->role === 'kaprodi') {
            $programId = Auth::user()->lecturer->program->id;
            $reports->whereIn('status', ['submitted', 'approved'])
                ->whereHas('student_class', function ($query) use ($programId) {
                    $query->where('program_id', $programId);
                });
        } elseif ($this->role === 'dosenWali') {
            $reports->where('academic_advisor_id', Auth::user()->lecturer->id);
        }

        return $reports->get();
    }



    public function relationSearch(): array
    {
        return [
            // 'academic_advisor' => ['nim'],
            'academic_advisor.user' => ['name'],
            'student_class' => ['class_name']
        ];
    }

    public function fields(): PowerGridFields
    {
        $fields = PowerGrid::fields()
            ->add('id')
            ->add('academic_advisor_id')
            ->add('student_class_id')
            ->add('academic_advisor_decree')
            ->add('academic_advisor_name')
            ->add('class_name', fn ($report) => optional($report->student_class)->class_name)
            ->add('entry_year')
            ->add('semester')
            ->add('academic_year')
            ->add('status')
            ->add('advisor_name', fn ($report) => optional($report->academic_advisor)->user->name)
            ->add('created_at')
            ->add('status_badge', function ($row) {
                return match ($row->status) {
                    'draft' => '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Draf</span>',
                    'submitted' => '<span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Dikirim</span>',
                    'approved' => '<span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Disetujui</span>',
                    default => '-',
                };
            });

        // Tambahkan field untuk setiap semester
        if ($this->role === 'jurusan') {
            $fields = PowerGrid::fields()
                ->add('class_name', fn($class) => $class->class_name)
                ->add('entry_year', fn($class) => $class->entry_year)
                ->add('advisor_name', fn($class) => optional($class->lecturer)->user->name)
                ->add('status_badge', function ($row) {
                    return match ($row->status) {
                        'draft' => '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Draf</span>',
                        'submitted' => '<span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Menunggu verifikasi kaprodi</span>',
                        'approved' => '<span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Disetujui</span>',
                        default => '<span class="text-gray-500">Belum dibuat</span>',
                    };
                })
                // ->add('semester')
                // ->add('status_badge', function ($row) {
                //     return match ($row->report->where('semester', $row->semester)->first()->status) {
                //         'draft' => '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Draf</span>',
                //         'submitted' => '<span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Menunggu verifikasi kaprodi</span>',
                //         'approved' => '<span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Disetujui</span>',
                //         default => 'belum dibuat',
                //     };
                // });
                ->add('semester');

            // for ($i = 1; $i <= $this->jumlahSemester; $i++) {
            //     $fields->add("semester_$i", function ($class) use ($i) {
            //         // Jika semester lebih besar dari semester aktif kelas, tidak tampilkan apa-apa
            //         if ($i > $class->current_semester) {
            //             return '<span class="text-gray-400 text-sm italic">-</span>';
            //         }
            //         $report = $class->report->firstWhere('semester', $i);

            //         if (!$report) {
            //             return '<span class="text-gray-400 text-sm italic">Belum ada</span>';
            //         }

            //         $badge = match ($report->status) {
            //             'draft' => '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded-sm">Draf</span>',
            //             'submitted' => '<span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-sm ">Menunggu verifikasi</span>',
            //             'approved' => '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded-sm">Disetujui</span>',
            //             default => '-',
            //         };

            //         $link = route('jurusan-reports.show', ['id' => $report->id]);

            //         return <<<HTML
            //             <div class="flex flex-col gap-1 text-center">
            //                 $badge
            //                 <a href="$link" class="text-xs text-blue-600 hover:underline">Detail</a>
            //             </div>
            //         HTML;
            //     });
            // }
        }
        return $fields;
    }


    public function columns(): array
    {
            $advisorField = Column::make('Dosen Wali', 'advisor_name')
                ->sortable()
                ->searchable();
    
    
            $statusField = Column::make('Status', 'status_badge')
                ->sortable()
                ->searchable();

            $classField = Column::make('Kelas', 'class_name')
                ->sortable()
                ->searchable();
            
            $entryYearField = Column::make('Tahun masuk', 'entry_year')
                ->sortable()
                ->searchable();

            $semesterField = Column::make('Semester', 'semester')
                ->sortable()
                ->searchable();

            $actionField = Column::action('Action');

            if (Auth::user()->roles->first()->name === 'jurusan') {
                $columns = [
                    Column::make('Kelas', 'class_name')->sortable()->searchable(),
                    Column::make('Angkatan', 'entry_year')->sortable()->searchable(),
                    Column::make('Nama Dosen', 'advisor_name')->searchable(),
                    Column::make('Semester', 'semester')->sortable()->searchable(),
                    Column::make('Status Laporan', 'status_badge')->searchable(),
                ];

                // for ($i = 1; $i <= $this->jumlahSemester; $i++) {
                //     $columns[] = Column::make("SMT $i", "semester_$i");
                // }

                $columns[] = Column::action('Action');

                return $columns;
            } else if (Auth::user()->roles->first()->name == 'kaprodi' || Auth::user()->roles->first()->name == 'admin') {
                return [$classField, $entryYearField, $semesterField, $statusField, $actionField];
            } else if (Auth::user()->roles->first()->name == 'dosenWali') {
                return [$classField, $entryYearField, $semesterField, $statusField, $actionField];
            } else {
                return [];
            }
        
    }

    public function filters(): array
    {
        if ($this->role !== 'jurusan') {
            return [];
        }

        return [
            // Filter kelas
            Filter::select('class_name', 'class_name')
                ->dataSource(
                    \App\Models\StudentClass::query()
                        ->where('program_id', $this->programId)
                        ->where('status', 'active')
                        ->get()
                )
                ->optionLabel('class_name')
                ->optionValue('class_name'),

            // Filter status
            Filter::select('status_badge', 'status')
                ->dataSource(collect([
                    ['value' => 'draft', 'label' => 'Draf'],
                    ['value' => 'submitted', 'label' => 'Dikirim'], 
                    ['value' => 'approved', 'label' => 'Disetujui'],
                    ['value' => 'belum dibuat', 'label' => 'Belum dibuat'],
                ]))
                ->optionLabel('label')
                ->optionValue('value'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions($row): array
    {
        $role = Auth::user()->roles->first()->name;

        // Untuk jurusan: tampilkan tombol buat laporan jika belum ada
        if ($role === 'jurusan') {
            if ($row->id) {
                $show = Button::add('show')
                    ->slot('Detail')
                    ->id()
                    ->class('focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2')
                    ->route($role . '-reports.show', ['id' => $row->id])
                    ->attributes([
                        'wire:navigate.hover' => true,
                    ]);
                return [$show];
            } else {
                return [];
            }
        }

        // Untuk role lain tetap gunakan Report
        $edit = Button::add('edit')
            ->slot('Edit')
            ->id()
            ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2')
            ->dispatch('edit-report', ['id' => $row->id]);

        $delete = Button::add('delete')
            ->slot('Hapus')
            ->id()
            ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2')
            ->attributes([
                'data-confirm-delete' => 'true',
                'data-event' => 'delete-report',
                'data-id' => $row->id,
                'data-title' => 'Apakah kamu yakin?',
                'data-text' => 'Laporan ini akan dihapus permanen.',
            ]);

        $show = Button::add('show')
            ->slot('Detail')
            ->id()
            ->class('focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2')
            ->route($role . '-reports.show', ['id' => $row->id])
            ->attributes([
                'wire:navigate.hover' => true,
            ]);

        if ($role === 'dosenWali') {
            return [$delete, $show];
        } elseif (in_array($role, ['kaprodi', 'admin'])) {
            return [$show];
        }

        return [];
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
