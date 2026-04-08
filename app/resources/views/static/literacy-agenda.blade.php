@extends('layouts.app-public')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-4">Literacy Agenda</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($events as $event)
            <div class="bg-white shadow rounded overflow-hidden">
                <a href="{{ route('static.literacy.detail', $event['id']) }}">
                    <img src="{{ asset('images/'.$event['image']) }}" alt="{{ $event['title'] }}" class="w-full h-48 object-cover" />
                    <div class="p-4">
                        <h2 class="text-lg font-semibold">{{ $event['title'] }}</h2>
                        <p class="text-sm text-gray-600">{{ $event['date'] }}</p>
                        <p class="text-sm mt-2">{{ $event['description'] }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
