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
        $books = Book::with('authors');

        $searchTitle = $request->title ?? null;

        if (isset($searchTitle)) {
            $books = $books->where('title', 'like', '%'.$searchTitle.'%');
        }

        $books = $books->paginate(20);

        return $books;
    }

    public function all() {
        return view('library.books.index');
    }

    public function page($page, Request $request) {
        $orderBy = $request->orderBy ?? 'id';
        $asc = $request->asc ?? 'desc';
        $perPage = $request->perPage ?? 5;
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
    public function store(Request $request, AuthorController $authorController)
    {
        $book = Book::create([
            'title' => $request->title,
        ]);

        // Generate new Authors
        $authorResponse = $authorController->store($request);
        $newAuthors = collect($authorResponse->getData()->added)->pluck('id');
        $existingAuthors = collect($request->existingAuthors)->pluck('id');
        $book->authors()->attach($newAuthors);
        $book->authors()->attach($existingAuthors);

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
        //
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
