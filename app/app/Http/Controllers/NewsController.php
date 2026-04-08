<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display listing of all news (berita)
     */
    public function index(Request $request)
    {
        $query = News::published()->berita()->latest('published_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate(9);
        $featuredNews = News::published()->berita()->featured()->latest('published_at')->first();

        return view('news.index', compact('news', 'featuredNews'));
    }

    /**
     * Display listing of all agenda
     */
    public function agenda(Request $request)
    {
        $query = News::published()->agenda();

        // Filter: upcoming or past
        if ($request->get('filter') === 'past') {
            $query->where('event_date', '<', now()->toDateString())->latest('event_date');
        } else {
            $query->where('event_date', '>=', now()->toDateString())->orderBy('event_date', 'asc');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $agendas = $query->paginate(9);

        // Get upcoming agenda count
        $upcomingCount = News::published()->agenda()
            ->where('event_date', '>=', now()->toDateString())
            ->count();

        return view('news.agenda', compact('agendas', 'upcomingCount'));
    }

    /**
     * Display a specific news/agenda
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)->published()->firstOrFail();

        // Get related news
        $relatedNews = News::published()
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }
}
