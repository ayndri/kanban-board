<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = Division::orderBy('name')->get();
        return view('divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:divisions',
            'description' => 'nullable|string',
        ]);

        try {
            Division::create($validated);
            return redirect()->route('divisions.index')
                ->with('success', 'Division created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('divisions.index')
                ->with('error', 'Failed to create division.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string',
        ]);

        try {
            $division->update($validated);
            return redirect()->route('divisions.index')
                ->with('success', 'Division updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('divisions.index')
                ->with('error', 'Failed to update division.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        try {
            $division->delete();
            return redirect()->route('divisions.index')
                ->with('success', 'Division deleted successfully.');
        } catch (\Exception $e) {
            // Ini akan error jika divisi terhubung ke employee (Foreign Key)
            return redirect()->route('divisions.index')
                ->with('error', 'Failed to delete division. Make sure it is not linked to any employees.');
        }
    }
}
