@extends('layouts.app-public')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-4">E-Resources / Book Collection</h1>

    <form action="{{ route('static.eresources') }}" method="GET" class="flex flex-wrap gap-2 mb-4">
        <label for="category">Category:</label>
        <select name="category" id="category" class="border rounded p-2">
            <option value="all" @selected($category==='all')>All</option>
            <option value="fiction" @selected($category==='fiction')>Fiction</option>
            <option value="non-fiction" @selected($category==='non-fiction')>Non-Fiction</option>
            <option value="history" @selected($category==='history')>History</option>
            <option value="education" @selected($category==='education')>Education</option>
        </select>
        <label for="search">Search:</label>
        <input type="text" name="search" id="search" value="{{ $search }}" class="border rounded p-2" />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <h2 class="text-xl font-semibold mb-4">{{ ucfirst($category === 'all' ? 'All' : $category) }} Books</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($filtered as $book)
            <div class="text-center">
                <a href="{{ route('books.show', $book['id']) }}">
                    <img src="{{ asset('covers/'.$book['image']) }}" alt="{{ $book['title'] }}" class="w-full h-48 object-cover rounded" />
                </a>
                <p class="text-sm mt-2">{{ $book['title'] }}</p>
            </div>
        @empty
            <p class="col-span-full text-gray-600">No books found matching your criteria.</p>
        @endforelse
    </div>
</div>
@endsection
