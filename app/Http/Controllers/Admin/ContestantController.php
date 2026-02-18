<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contestant;
use Illuminate\Http\Request;

class ContestantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contestants = Contestant::latest()->paginate(10);
        return view('admin.contestants.index', compact('contestants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.contestants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'team_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Contestant::create($validated);

        return redirect()->route('contestants.index')
            ->with('success', 'Peserta berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contestant $contestant)
    {
        return view('admin.contestants.show', compact('contestant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contestant $contestant)
    {
        return view('admin.contestants.edit', compact('contestant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contestant $contestant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'team_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $contestant->update($validated);

        return redirect()->route('contestants.index')
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contestant $contestant)
    {
        $contestant->delete();

        return redirect()->route('contestants.index')
            ->with('success', 'Peserta berhasil dihapus.');
    }
}
