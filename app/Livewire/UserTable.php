<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'user-table-7d02mn-table';

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
        // $query = User::query()
        //     ->whereDoesntHave('roles', function($query) {
        //         $query->where('name', 'admin');
        //     })
        //     ->with('roles');

        // // Tambahkan sort dinamis dari frontend (Livewire, DataTables, dst)
        // if (request()->has('sort_by') && request()->has('direction')) {
        //     $query->orderBy(request('sort_by'), request('direction'));
        // }

        // // Jika tidak ada sorting, kamu bisa kasih default
        // else {
        //     $query->orderBy('name', 'asc'); // atau apapun default kamu
        // }

        // return $query;

        // $filters = $this->filters;

        return User::query()
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            // ->when($filters['role_names'] ?? false, function ($query) use ($filters) {
            //     $query->whereHas('roles', function ($q) use ($filters) {
            //         $q->where('name', $filters['role']);
            //     });
            // })
            ->with('roles');

        
        // return User::query()
        //     // ->whereDoesntHave('roles', function($query) {
        //     //     return $query->where('name', 'admin');
        //     // })
        //     ->with('roles')
        //     ->leftJoin('model_has_roles', function ($join) {
        //         $join->on('users.id', '=', 'model_has_roles.model_id')
        //             ->where('model_has_roles.model_type', '=', User::class);
        //     })
        //     ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
        //     ->select('users.*', DB::raw('GROUP_CONCAT(roles.name SEPARATOR ", ") as role_names'))
        //     ->groupBy('users.id')
        //     ->latest();
        // return User::query()
        //     ->with('roles')
        //     ->leftJoin('model_has_roles', function ($join) {
        //         $join->on('users.id', '=', 'model_has_roles.model_id')
        //             ->where('model_has_roles.model_type', '=', User::class);
        //     })
        //     ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
        //     ->select([
        //         'users.id',
        //         'users.name',
        //         'users.email',
        //         'users.created_at',
        //         DB::raw('GROUP_CONCAT(roles.name SEPARATOR ", ") as role_names'),
        //     ])
        //     ->groupBy('users.id', 'users.name', 'users.email', 'users.created_at')
        //     ->orderBy('users.created_at', 'desc');
    }

    public function relationSearch(): array
    {
        return [
            'roles' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('roles.name')
            ->add('role_names', fn (User $user) => $user->roles->pluck('name')->join(', ')) // Tambahkan ini
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Nama', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Created At', 'created_at')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Role', 'role_names')
                // ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::selectRelation('roles', 'name', 'Role')
            //     ->dataSource(collect([
            //         ['value' => 'mahasiswa', 'label' => 'Mahasiswa'],
            //         ['value' => 'dosenWali', 'label' => 'Dosen Wali'],
            //         ['value' => 'kaprodi', 'label' => 'Kaprodi'],
            //         ['value' => 'kajur', 'label' => 'Kajur'],
            //     ]))
            //     ->optionValue('value')
            //     ->optionLabel('label')

        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(User $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900')
                ->dispatch('edit-user', ['id' => $row->id]),
            Button::add('delete')
                ->slot('Hapus')
                ->id()
                ->class('focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900')
                ->attributes([
                        'data-confirm-delete' => 'true',
                        'data-event' => 'delete-user',
                        'data-id' => $row->id,
                        'data-title' => 'Apakah kamu yakin?',
                        'data-text' => 'Pengguna ini akan dihapus permanen.'
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
