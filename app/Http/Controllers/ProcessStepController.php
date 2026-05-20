<?php

namespace App\Http\Controllers;

use App\Models\ProcessStep;
use Illuminate\Http\Request;

class ProcessStepController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $processSteps = ProcessStep::orderBy('display_order')->get();

        return view('admin.process-steps.index', compact('processSteps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.process-steps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|unique:process_steps,step_number,NULL,id,deleted_at,NULL',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'display_order' => 'nullable|integer',
        ]);

        ProcessStep::create($validated);

        return redirect()->route('process-steps.index')->with('success', 'Process step created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProcessStep $processStep)
    {
        return view('admin.process-steps.edit', compact('processStep'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProcessStep $processStep)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|unique:process_steps,step_number,'.$processStep->id.',id,deleted_at,NULL',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'display_order' => 'nullable|integer',
        ]);

        $processStep->update($validated);

        return redirect()->route('process-steps.index')->with('success', 'Process step updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProcessStep $processStep)
    {
        $processStep->delete();

        return redirect()->route('process-steps.index')->with('success', 'Process step deleted successfully.');
    }
}
