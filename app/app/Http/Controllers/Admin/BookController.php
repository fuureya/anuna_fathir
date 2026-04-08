<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('created_at', 'desc')->get();
        
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'penulis' => 'required|max:255',
            'penerbit' => 'required|max:255',
            'tahun_terbit' => 'required|digits:4',
            'jumlah_eksemplar' => 'required|integer|min:0',
            'ISBN' => 'required|unique:books,ISBN|max:20',
            'kategori' => 'required|max:100',
            'genre' => 'nullable|max:255',
            'deskripsi' => 'nullable',
            'audience_category' => 'nullable|max:50',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_url' => 'nullable|url',
        ]);

        // Handle cover image
        if ($request->hasFile('cover_file')) {
            $file = $request->file('cover_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('covers'), $filename);
            $validated['cover_image'] = $filename;
        } elseif ($request->filled('cover_url')) {
            // Store URL directly
            $validated['cover_image'] = $request->cover_url;
        }

        // Remove validation-only fields
        unset($validated['cover_file']);
        unset($validated['cover_url']);

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $book = Book::where('buku_id', $id)->firstOrFail();
        
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::where('buku_id', $id)->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|max:255',
            'penulis' => 'required|max:255',
            'penerbit' => 'required|max:255',
            'tahun_terbit' => 'required|digits:4',
            'jumlah_eksemplar' => 'required|integer|min:0',
            'ISBN' => 'required|max:20|unique:books,ISBN,' . $id . ',buku_id',
            'kategori' => 'required|max:100',
            'genre' => 'nullable|max:255',
            'deskripsi' => 'nullable',
            'audience_category' => 'nullable|max:50',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_url' => 'nullable|url',
        ]);

        // Handle cover image
        if ($request->hasFile('cover_file')) {
            // Delete old cover if exists and is a file (not URL)
            if ($book->cover_image && !filter_var($book->cover_image, FILTER_VALIDATE_URL)) {
                if (file_exists(public_path('covers/' . $book->cover_image))) {
                    unlink(public_path('covers/' . $book->cover_image));
                }
            }
            
            $file = $request->file('cover_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('covers'), $filename);
            $validated['cover_image'] = $filename;
        } elseif ($request->filled('cover_url')) {
            // Delete old cover if exists and is a file (not URL)
            if ($book->cover_image && !filter_var($book->cover_image, FILTER_VALIDATE_URL)) {
                if (file_exists(public_path('covers/' . $book->cover_image))) {
                    unlink(public_path('covers/' . $book->cover_image));
                }
            }
            // Store URL directly
            $validated['cover_image'] = $request->cover_url;
        }

        // Remove validation-only fields
        unset($validated['cover_file']);
        unset($validated['cover_url']);

        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $book = Book::where('buku_id', $id)->firstOrFail();
        
        // Delete cover image if exists
        if ($book->cover_image && file_exists(public_path('covers/' . $book->cover_image))) {
            unlink(public_path('covers/' . $book->cover_image));
        }
        
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus!');
    }
}
