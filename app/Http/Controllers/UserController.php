<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
        {
            $user = User::orderBy('created_at', 'desc')->get();
            return view('masterdata.users.index', compact('user'));
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = Role::all();
        return view('masterdata.users.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi array dari input 'users'
        $request->validate([
            'users.*.name' => 'required',
            'users.*.email' => 'required',
            'users.*.password' => 'required',
            'users.*.role' => 'required',
            'users.*.avatar' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        try {
            DB::beginTransaction();

            // Loop melalui setiap user yang di-input dalam form
            foreach ($request->users as $userData) {
                $data = [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => bcrypt($userData['password']),
                ];

                // Proses avatar jika ada
                if (isset($userData['avatar'])) {
                    $avatarPath = $userData['avatar']->store('avatars', 'public');
                    $data['avatar'] = $avatarPath;
                }

                // Simpan user
                $user = User::create($data);

                // Assign role ke user
                $user->assignRole($userData['role']);
            }

            DB::commit();

            return redirect()->route('masterdata.users.index')->with('success', 'Semua user berhasil disimpan :)');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('masterdata.users.index')
                ->with('error', 'System error : ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $role = Role::all();
        return view('masterdata.users.edit', compact('user', 'role'));        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable',
            'role' => 'required|exists:roles,name',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name' => $validate['name'],
            'email' => $validate['email'],
        ];

        if (!empty($validate['password'])) {
            $data['password'] = bcrypt($validate['password']);
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        try {
            DB::beginTransaction();

            $user->update($data);

            // Update user role
            $user->syncRoles([$validate['role']]);

            DB::commit();

            return redirect()->route('masterdata.users.index')->with('success', 'User berhasil diperbarui :)');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try{

            if ($user->student()) {
                $user->student()->update(['user_id' => null]);
            }
            if($user->lecturer())
            {
                $user->lecturer()->update(['user_id' => null]);
            }


            $user->delete();
            return redirect()->back()->with('success','User berhasil dihapus :)');
        }
        catch(\Exception $e){
            DB::rollBack();

            return redirect()->back()->withErrors('System error: ' . $e->getMessage());
        }
    }
}
