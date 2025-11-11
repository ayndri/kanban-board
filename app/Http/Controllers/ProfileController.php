<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        $user = Auth::user();
        $divisions = Division::orderBy('name')->get();

        return view('profile.edit', compact('user', 'divisions'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'division_id' => 'nullable|integer|exists:divisions,id',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($request->hasFile('photo')) {
                if ($user->photo) {
                    Storage::delete('public/' . $user->photo);
                }
                $path = $request->file('photo')->store('user-photos', 'public');
                $userData['photo'] = $path;
            }

            $user->update($userData);
            $user->employee()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $validated['phone'],
                    'job_title' => $validated['job_title'],
                    'division_id' => $validated['division_id'],
                ]
            );

            DB::commit();

            return redirect()->route('profile')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('profile')->with('error', 'Failed to update profile.');
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->numbers(),
            ],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile')
                ->with('error_password', 'Your current password does not match.');
        }

        try {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->route('profile')
                ->with('success_password', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('profile')
                ->with('error_password', 'Failed to update password. Please try again.');
        }
    }
}
