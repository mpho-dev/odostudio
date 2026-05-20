<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
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
        $testimonials = Testimonial::orderBy('order')->get();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $testimonial = new Testimonial;

        return view('admin.testimonials.create', compact('testimonial'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_initials' => 'nullable|string|max:5',
            'event_label' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'quote' => 'required|string',
            'is_featured' => 'sometimes|boolean',
            'order' => 'nullable|integer',
        ]);

        if (empty($validated['client_initials']) && ! empty($validated['client_name'])) {
            $validated['client_initials'] = mb_substr($validated['client_name'], 0, 1);
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        Testimonial::create($validated);

        return redirect()->route('testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_initials' => 'nullable|string|max:5',
            'event_label' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'quote' => 'required|string',
            'is_featured' => 'sometimes|boolean',
            'order' => 'nullable|integer',
        ]);

        if (empty($validated['client_initials']) && ! empty($validated['client_name'])) {
            $validated['client_initials'] = mb_substr($validated['client_name'], 0, 1);
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $testimonial->update($validated);

        return redirect()->route('testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('testimonials.index')
            ->with('success', 'Testimonial deleted.');
    }
}
