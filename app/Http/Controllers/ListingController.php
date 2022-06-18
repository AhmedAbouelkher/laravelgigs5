<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index()
    {
        $query = Listing::query();
        $listings = $query
            ->when(request('tag'), function ($query, $tag) {
                $query->where('tags', 'LIKE', "%{$tag}%");
            })
            ->when(request('search'), function ($query, $search) {
                $query
                    ->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('tags', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate();
        return view('listings.index', [
            'listings' => $listings
        ]);
    }

    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    // Create new listing view
    public function create()
    {
        return view('listings.create');
    }

    // Store listing
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created successfully!');
    }

    public function edit(Listing $listing)
    {
        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    // Update listing
    public function update(Request $request, Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            return abort(403, 'Unauthorized action.');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return back()->with('message', 'Listing updated successfully!');
    }

    // Delete listing
    public function destroy(Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            return abort(403, 'Unauthorized action.');
        }

        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted successfully!');
    }

    // manage listings
    public function manage()
    {
        $listings = auth()->user()->listings()->latest()->paginate();
        return view('listings.manage', [
            'listings' => $listings
        ]);
    }
}
