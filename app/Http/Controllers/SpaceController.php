<?php

namespace App\Http\Controllers;

use App\Models\Space;

use Illuminate\Http\Request;

class SpaceController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $spaces = Space::orderBy('created_at', 'DESC')->paginate(4);
        return view('pages.space.index', compact('spaces'));
    }

    public function browse()
    {
        return view('pages.space.browse');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.space.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'min:3'],
            'address' => ['required', 'min:5'],
            'description' => ['required', 'min:10'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        $request->user()->spaces()->create($request->all());

        return redirect()->route('space.index')->with('status', 'Space created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $space = Space::findOrFail($id);
        return view('pages.space.show', compact('space'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $space = Space::findOrFail($id);
        if ($space->user_id != request()->user()->id) {
            return redirect()->back();
        }
        return view('pages.space.edit', compact('space'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $space = Space::findOrFail($id);
        if ($space->user_id != request()->user()->id) {
            return redirect()->back();
        }
        $this->validate($request, [
            'title' => ['required', 'min:3'],
            'address' => ['required', 'min:5'],
            'description' => ['required', 'min:10'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);
        $space->update($request->all());
        return redirect()->route('space.index')->with('status', 'Space updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $space = Space::findOrFail($id);
        if ($space->user_id != request()->user()->id) {
            return redirect()->back();
        }

        $space->delete();
        return redirect()->route('space.index')->with('status', 'Space deleted!');
    }
}
