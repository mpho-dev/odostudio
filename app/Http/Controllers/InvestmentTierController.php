<?php

namespace App\Http\Controllers;

use App\Models\InvestmentTier;
use Illuminate\Http\Request;

class InvestmentTierController extends Controller
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
        $tiers = InvestmentTier::orderBy('order')->get();

        return view('admin.investment-tiers.index', compact('tiers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tier = new InvestmentTier;

        return view('admin.investment-tiers.create', compact('tier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tier_label' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_suffix' => 'nullable|string|max:50',
            'badge_label' => 'nullable|string|max:255',
            'is_featured' => 'sometimes|boolean',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        if (isset($validated['features'])) {
            $validated['features'] = array_values(array_filter($validated['features']));
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        InvestmentTier::create($validated);

        return redirect()->route('investment-tiers.index')
            ->with('success', 'Investment tier created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestmentTier $investment_tier)
    {
        return view('admin.investment-tiers.edit', ['tier' => $investment_tier]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvestmentTier $investment_tier)
    {
        $validated = $request->validate([
            'tier_label' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_suffix' => 'nullable|string|max:50',
            'badge_label' => 'nullable|string|max:255',
            'is_featured' => 'sometimes|boolean',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        if (isset($validated['features'])) {
            $validated['features'] = array_values(array_filter($validated['features']));
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $investment_tier->update($validated);

        return redirect()->route('investment-tiers.index')
            ->with('success', 'Investment tier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestmentTier $investment_tier)
    {
        $investment_tier->delete();

        return redirect()->route('investment-tiers.index')
            ->with('success', 'Investment tier deleted.');
    }
}
