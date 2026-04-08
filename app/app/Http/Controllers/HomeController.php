<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\News;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('created_at', 'desc')->limit(12)->get();
        
        // Get latest news (berita) - max 4 (1 featured + 3 side news)
        $latestNews = News::published()
            ->berita()
            ->latest('published_at')
            ->limit(4)
            ->get();
        
        // Get upcoming agenda - max 3
        $upcomingAgenda = News::published()
            ->agenda()
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();
        
        return view('home', compact('books', 'latestNews', 'upcomingAgenda'));
    }
}
