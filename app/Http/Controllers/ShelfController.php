<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Library\Shelf;

class ShelfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shelves = Shelf::where('user_id', Auth::user()->id)->get();
        return response()->json([
            'shelves' => $shelves,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('library.shelves.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Shelf::create([
            'name' => $request->shelf_name,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shelf = Shelf::where('id', $id)
                ->with('editions')
                ->with('editions.book')
                ->with('editions.book.authors')
                ->first();
        return view('library.shelves.show')->with(compact('shelf'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shelf = Shelf::where('id', $id)->first();
        return view('library.shelves.edit')->with(compact('shelf'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shelf = Shelf::where('id', $id)->first();
        $shelf->name = $request->shelf['name'];
        $shelf->save();

        return response()->json([
            'success' => true,
            'updated' => $shelf,
            'message' => 'Shelf updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function user()
    {
        $user = Auth::user();
        $shelves = $user->shelves;

        return response()->json([
            'success' => true,
            'shelves' => $shelves,
        ]);
    }
}
