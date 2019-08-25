<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Library\Book;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('library.books.create');
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

        foreach($newAuthors as $newAuthor) {
            $book->authors()->attach($newAuthor);
        }

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
