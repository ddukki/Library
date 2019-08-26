<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Library\Book;
use App\Models\Library\Author;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchTerm = '';
        $searchColumn = [];

        return view('library.books.index')
                ->with(compact('searchTerm', 'searchColumn'));
    }

    public function all(Request $request) {
        $searchTerm = $request->searchTerm ?? '';
        $searchColumn = $request->searchColumn ?? [];

        return view('library.books.index')
                ->with(compact('searchTerm', 'searchColumn'));
    }

    public function page($page, Request $request) {
        $orderBy = $request->orderBy ?? 'id';
        $asc = $request->asc ?? 'desc';
        $perPage = $request->perPage ?? 10;
        $searchColumn = $request->searchColumn ?? null;
        $searchTerm = $request->searchTerm ?? null;

        $query = Book::with('authors');
        if ($searchColumn) {
            $searchColumn = explode(',', $searchColumn);
            $concat = 'concat(';
            foreach($searchColumn as $index => $col) {
                $concat = $concat.$col;
                if ($index != count($searchColumn) - 1) {
                    $concat = $concat.'," ",';
                }
            }
            $concat = $concat.')';

            $query = $query->where(DB::raw($concat), 'like', '%'.$searchTerm.'%');
        }

        return response()->json([
            'page' => $query
                    ->orderBy($orderBy, $asc)
                    ->paginate($perPage, ['*'], 'page', $page)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authors = Author::with('books')->paginate(10);
        return view('library.books.create')->with(compact('authors'));
    }

    /**
     * Store a newly created resource in storage. This store must include a book
     * title as well as the authors for the book and the edition that will be
     * added to the book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newBook = $request->book;
        $book = Book::create([
            'title' => $newBook['title'],
        ]);

        // Generate new Authors
        $authors = collect($newBook['authors'])->pluck('id');
        $book->authors()->attach($authors);

        return response()->json([
            'success' => true,
            'added' => $book,
            'message' => 'Book added!'
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $authors = Author::with('books')->paginate(10);
        $book = Book::where('id', $id)->with('authors')->first();
        return view('library.books.edit')->with(compact('book', 'authors'));
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
        $book = Book::where('id', $id)->with('authors')->first();
        $book->title = $request->book['title'];
        $book->save();

        // Get the new list of Authors
        $authors = collect($request->book['authors'])->pluck('id');
        $book->authors()->sync($authors);

        return response()->json([
            'success' => true,
            'updated' => $book,
            'message' => 'Book added!'
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
}
