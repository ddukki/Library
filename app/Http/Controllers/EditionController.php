<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Library\Edition;
use App\Models\Library\Shelf;

class EditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bookID = $request->book['id'];
        $newEdition = $request->edition;
        $edition = Edition::create([
            'book_id' => $bookID,
            'name' => $newEdition['name'],
            'location_type_id' => $newEdition['type']['id'],
            'location_size' => $newEdition['size'],
        ]);
        $edition->load('location_type');
        $edition->load('shelves');

        return response()->json([
            'success' => true,
            'added' => $edition,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $scopeForUser = function($q) {
            $q->where('user_id', auth()->user()->id);
        };

        $edition = Edition::where('id', $id)
                ->with('book')
                ->with('book.authors')
                ->with(['progress' => $scopeForUser, 'quotes' => $scopeForUser])
                ->first();
        $progress = $edition->progress;
        $allProgress = array();
        foreach($progress as $p) {
            array_push($allProgress, array($p->location_start, $p->location_end));
        }

        $allProgress = $this->mergeRanges($allProgress);

        return view('library.editions.show')->with(compact('edition', 'allProgress'));
    }

    public function shelve(Edition $edition, Shelf $shelf) {
        $edition->shelves()->attach($shelf);

        return response()->json([
            'success' => true,
        ]);
    }

    public function unshelve(Edition $edition, Shelf $shelf) {
        $edition->shelves()->detach($shelf);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $newEdition = $request->edition;

        $edition = Edition::where('id', $id)->first();
        $edition->name = $newEdition['name'];
        $edition->location_type_id = $newEdition['location_type']['id'];
        $edition->location_size = $newEdition['location_size'];
        $edition->save();

        return response()->json([
            'success' => true,
            'updated' => $edition,
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
        $edition = Edition::where('id', $id)->first();
        $edition->delete();

        return response()->json([], 204);
    }

    private function mergeRanges($data)
    {
        usort($data, function($a, $b) {
            return $a[0] - $b[0];
        });

        $n = 0; $len = count($data);
        for ($i = 1; $i < $len; ++$i) {
            if ($data[$i][0] > $data[$n][1] + 1) {
                $n = $i;
            }
            else {
                if ($data[$n][1] < $data[$i][1]) {
                    $data[$n][1] = $data[$i][1];
                }
                unset($data[$i]);
            }
        }

        return array_values($data);
    }
}
