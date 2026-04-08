<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Search by title
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereIn('kategori', (array) $request->category);
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->whereIn('genre', (array) $request->genre);
        }

        $books = $query->get();
        
        return view('books.index', compact('books'));
    }

    public function show($id)
    {
        $book = Book::where('buku_id', $id)->firstOrFail();
        
        return view('books.show', compact('book'));
    }
}
