<?php

namespace App\Http\Controllers;

use App\Models\MasterDieset;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class MasterDiesetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', MasterDieset::class);

        $diesets = MasterDieset::paginate(15);

        return view('admin.master-diesets.index', compact('diesets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', MasterDieset::class);

        return view('admin.master-diesets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', MasterDieset::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:master_diesets',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        MasterDieset::create($validated);

        return redirect()->route('master-diesets.index')
            ->with('success', 'Dieset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterDieset $masterDieset): View
    {
        Gate::authorize('view', $masterDieset);

        return view('admin.master-diesets.show', compact('masterDieset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterDieset $masterDieset): View
    {
        Gate::authorize('update', $masterDieset);

        return view('admin.master-diesets.edit', compact('masterDieset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterDieset $masterDieset): RedirectResponse
    {
        Gate::authorize('update', $masterDieset);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:master_diesets,name,' . $masterDieset->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $masterDieset->update($validated);

        return redirect()->route('master-diesets.index')
            ->with('success', 'Dieset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterDieset $masterDieset): RedirectResponse
    {
        Gate::authorize('delete', $masterDieset);

        $masterDieset->delete();

        return redirect()->route('master-diesets.index')
            ->with('success', 'Dieset deleted successfully.');
    }
}