<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function eResources(Request $request)
    {
        // Hardcoded e-book data (replace with DB query if needed)
        $books = [
            ['id' => 1, 'title' => "Harry Potter and the Philosopher's Stone", 'image' => 'Harry_potter1.png', 'category' => 'fiction'],
            ['id' => 2, 'title' => 'Harry Potter and the Chamber of Secrets', 'image' => 'Harry_potter2.png', 'category' => 'fiction'],
            ['id' => 3, 'title' => 'Harry Potter and the Prisoner of Azkaban', 'image' => 'Harry_potter3.png', 'category' => 'history'],
            ['id' => 4, 'title' => 'Harry Potter and the Goblet of Fire', 'image' => 'Harry_potter4.png', 'category' => 'education'],
            ['id' => 5, 'title' => 'Harry Potter and the Order of the Phoenix', 'image' => 'Harry_potter5.png', 'category' => 'education'],
        ];

        $category = $request->query('category', 'all');
        $search = trim((string) $request->query('search', ''));

        $filtered = collect($books)->filter(function ($book) use ($category, $search) {
            $matchCategory = ($category === 'all' || $book['category'] === $category);
            $matchSearch = ($search === '' || stripos($book['title'], $search) !== false);
            return $matchCategory && $matchSearch;
        });

        return view('static.e-resources', compact('books', 'filtered', 'category', 'search'));
    }

    public function libraryLocation()
    {
        return view('static.library-location');
    }

    public function literacyAgenda()
    {
        $events = [
            ['id' => 1, 'title' => 'Book Reading Session', 'date' => '2024-06-30', 'description' => "Join us for a book reading session of 'Harry Potter and the Philosopher's Stone'.", 'image' => 'event1.jpg'],
            ['id' => 2, 'title' => 'Author Meet and Greet', 'date' => '2024-07-05', 'description' => 'Meet your favorite authors and get your books signed.', 'image' => 'event2.jpg'],
            ['id' => 3, 'title' => "Children's Storytelling", 'date' => '2024-07-10', 'description' => 'A fun storytelling session for kids.', 'image' => 'event3.jpg'],
        ];

        return view('static.literacy-agenda', compact('events'));
    }

    public function literacyDetail($id)
    {
        $events = [
            1 => ['title' => 'Book Reading Session', 'date' => '2024-06-30', 'description' => "Join us for a book reading session of 'Harry Potter and the Philosopher's Stone'.", 'image' => 'event1.jpg', 'details' => 'This is a detailed description of the Book Reading Session.'],
            2 => ['title' => 'Author Meet and Greet', 'date' => '2024-07-05', 'description' => 'Meet your favorite authors and get your books signed.', 'image' => 'event2.jpg', 'details' => 'This is a detailed description of the Author Meet and Greet.'],
            3 => ['title' => "Children's Storytelling", 'date' => '2024-07-10', 'description' => 'A fun storytelling session for kids.', 'image' => 'event3.jpg', 'details' => 'This is a detailed description of the Children\'s Storytelling event.'],
        ];

        if (!isset($events[$id])) {
            abort(404, 'Event not found.');
        }

        $event = $events[$id];
        return view('static.literacy-detail', compact('event'));
    }

    public function info()
    {
        return view('static.info');
    }
}
