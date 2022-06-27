<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Models\Listing;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags = Tag::orderBy('name')->get();
        $listings = Listing::with('tags')->get();


        if ($request->has('s')) {
            $query = strtolower($request->get('s'));

            $listings = $listings->filter(function ($listing) use ($query) {
                if (Str::contains(strtolower($listing->title), $query)) {
                    return true;
                }
                if (Str::contains(strtolower($listing->company), $query)) {
                    return true;
                }
                if (Str::contains(strtolower($listing->location), $query)) {
                    return true;
                }
                return false;
            });
        }
        if ($request->has('tag')) {
            $tag = $request->get('tag');
            $listings = $listings->filter(function ($listing) use ($tag) {
                return $listing->tags->contains('slug', $tag);
            });
        }

        return view('listings.index', compact('listings', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreListingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreListingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function show(Listing $listing)
    {
        return view('listings.show', compact('listing'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function edit(Listing $listing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateListingRequest $request
     * @param \App\Models\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateListingRequest $request, Listing $listing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Listing $listing)
    {
        //
    }

    public function apply(Listing $listing, Request $request)
    {
        $listing->clicks()->create([
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip()
        ]);
        return redirect()->to($listing->apply_link);
    }
}
