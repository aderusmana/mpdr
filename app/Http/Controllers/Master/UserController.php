<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use App\Models\Master\Position;
use App\Models\Master\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ConditionQueryTrait;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ConditionQueryTrait;

    public function __construct()
    {
        $this->middleware('permission:view user', ['only' => ['index']]);
        $this->middleware('permission:create user', ['only' => ['create','store']]);
        $this->middleware('permission:update user', ['only' => ['update','edit']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('department', 'position', 'section', 'roles');
            if ($request->has('searchBuilder')) {
                $this->applySearchBuilder($query, $request->input('searchBuilder'));
            }
            return DataTables::of($query)->toJson();
        }

        // Dialog Sweet Alert
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('page.master.users.index');
    }

    public function getUsersData()
    {
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::select('name')->get()->pluck('name')->toArray();
        $sections = Section::all();
        $users = User::with('department','position','section','roles')->get();

        return response()->json(['users' => $users, 'departments' => $departments, 'positions' => $positions, 'roles' => $roles, 'sections' => $sections]);
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('page.master.users.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|max:20',
                'nik' => 'required|string|max:6|unique:users,nik',
                'roles' => 'required',
                'position_id' => 'required',
                'section_id' => 'required',
                'department_id' => 'required',
                'status' => 'required',
                'atasan_nik' => 'nullable|string|exists:users,nik'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nik' => $request->nik,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'section_id' => $request->section_id,
                'password' => Hash::make($request->password),
                'status' => $request->status,
                'atasan_nik' => $request->atasan_nik
            ]);

            $user->syncRoles($request->roles);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'User created successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Failed to create user: ' . $e->getMessage()], 500);
        }
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        return view('master.user.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles
        ]);
    }

    public function update(Request $request, User $user)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'password' => 'nullable|string|min:8|max:20',
                'roles' => 'required',
                'section_id' => 'required|exists:sections,id',
                'position_id' => 'required|exists:positions,id',
                'department_id' => 'required|exists:departments,id',
                'status' => 'required|string|in:Active,Vacant|max:10',
                'atasan_nik' => 'nullable|string|exists:users,nik'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'nik' => $request->nik,
                'section_id' => $request->section_id,
                'position_id' => $request->position_id,
                'department_id' => $request->department_id,
                'status' => $request->status,
                'atasan_nik' => $request->atasan_nik
            ];

            if(!empty($request->password)){
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $user->syncRoles($request->roles);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'User updated successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Failed to update user: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();
            return response()->json(['status' => 'success', 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete user: ' . $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete('user_avatars/' . $user->avatar);
            }

            $extension = $request->avatar->getClientOriginalExtension();
            $avatarName = ($user->nik ?: $user->id) . '.' . $extension;

            if (Storage::disk('public')->exists('user_avatars/' . $avatarName)) {
                Storage::disk('public')->delete('user_avatars/' . $avatarName);
            }

            $request->avatar->storeAs('uploads/user_avatars', $avatarName, 'public');
            $user->avatar = $avatarName;
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($user->banner) {
                Storage::disk('public')->delete('uploads/user_banners/' . $user->banner);
            }

            $extension = $request->banner->getClientOriginalExtension();
            $bannerName = 'banner_' . ($user->nik ?: $user->id) . '.' . $extension;

            $request->banner->storeAs('uploads/user_banners', $bannerName, 'public');
            $user->banner = $bannerName;
        }

        $user->save();

        Alert::toast('Profile updated successfully!', 'success');
        return redirect()->route('profile.edit');
    }
}
