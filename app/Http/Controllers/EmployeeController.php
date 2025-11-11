<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    /**
     * Menampilkan halaman daftar karyawan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::all();
        $divisions = Division::all();
        $users = User::with(['employee.division', 'role'])->latest()->get();

        $statusDefinitions = [
            'to-do'       => 'To Do',
            'in-progress' => 'In Progress',
            'in-review'   => 'In Review',
            'completed'   => 'Completed'
        ];

        $statuses = [];
        foreach ($statusDefinitions as $slug => $title) {
            $statuses[] = ['id' => $slug, 'title' => $title];
        }

        $allUsersForDropdown = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'photo' => $user->photo_url ?? 'https://placehold.co/32x32/E91E63/FFFFFF?text=' . $user->name[0]
            ];
        });

        return view('employees.index', compact(
            'users',
            'roles',
            'divisions',
            'statuses',
            'allUsersForDropdown'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => ['required', 'confirmed', Password::min(8)],
            'role_id'   => 'required|integer|exists:roles,id',

            'division_id' => 'required|integer|exists:divisions,id',
            'job_title'   => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:255',
        ]);

        $user = null;

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role_id'   => $request->role_id,
            ]);

            $user->employee()->create([
                'division_id' => $request->division_id,
                'job_title'   => $request->job_title,
                'phone'       => $request->phone,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to create employee.', 'error' => $e->getMessage()], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create employee: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Employee created successfully!',
                'user' => $user->load(['employee.division', 'role'])
            ], 201);
        }
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($employee->id),
            ],
            'role_id' => 'required|integer|exists:roles,id',
            'division_id' => 'required|integer|exists:divisions,id',
            'job_title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            $employee->update([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'role_id'   => $validated['role_id'],
            ]);

            $employee->employee()->updateOrCreate(
                ['user_id' => $employee->id],
                [
                    'division_id' => $validated['division_id'],
                    'job_title'   => $validated['job_title'],
                    'phone'       => $validated['phone'],
                ]
            );

            DB::commit();

            return redirect()->route('employees.index')
                ->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('employees.index')
                ->with('error', 'Failed to update employee. Please try again.');
        }
    }

    public function destroy(User $employee)
    {
        try {
            $employee->delete();
        } catch (\Exception $e) {
            return redirect()->route('employees.index')
                ->with('error', 'Failed to delete employee. It might be linked to other data.');
        }

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
