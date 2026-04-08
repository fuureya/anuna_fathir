<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create()
    {
        return view('reviews.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_quality' => 'required|string',
            'book_availability' => 'required|string',
            'book_collection' => 'required|string',
        ]);

        // Simple sentiment heuristic similar to legacy script
        $sentiment = function(string $text): string {
            $positive = ['memuaskan','ramah','berpengetahuan','baik','nyaman','hebat','puas'];
            $negative = ['buruk','jelek','kecewa','masalah','kurang','minim','sulit','mengecewakan'];
            $t = mb_strtolower(preg_replace('/[^\w\s]/u','',$text));
            $words = preg_split('/\s+/',$t,-1,PREG_SPLIT_NO_EMPTY);
            $score = 0; $neg = false;
            foreach ($words as $w) {
                if ($w === 'tidak') { $neg = true; continue; }
                if (in_array($w,$positive)) { $score += $neg ? -1 : 1; }
                if (in_array($w,$negative)) { $score += $neg ? 1 : -1; }
                $neg = false;
            }
            return $score > 0 ? 'Positif' : ($score < 0 ? 'Negatif' : 'Netral');
        };

        Review::create([
            'service_quality' => $data['service_quality'],
            'book_availability' => $data['book_availability'],
            'book_collection' => $data['book_collection'],
            'service_quality_sentiment' => $sentiment($data['service_quality']),
            'book_availability_sentiment' => $sentiment($data['book_availability']),
            'book_collection_sentiment' => $sentiment($data['book_collection']),
        ]);

        return redirect()->route('reviews.create')->with('status','Ulasan Anda telah berhasil disimpan!');
    }
}
