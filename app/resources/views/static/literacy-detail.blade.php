@extends('layouts.app-public')

@section('content')
<div class="container mx-auto px-4 max-w-3xl">
    <div class="bg-white shadow rounded p-6">
        <img src="{{ asset('images/'.$event['image']) }}" alt="{{ $event['title'] }}" class="w-full rounded mb-4" />
        <h1 class="text-2xl font-semibold mb-2">{{ $event['title'] }}</h1>
        <p class="mb-2"><strong>Date:</strong> {{ $event['date'] }}</p>
        <p class="mb-2"><strong>Description:</strong> {{ $event['description'] }}</p>
        <p class="mt-4">{{ $event['details'] }}</p>
    </div>
</div>
@endsection
