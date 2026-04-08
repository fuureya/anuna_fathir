@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl">⭐ Kelola Ulasan</h1>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('status') }}</div>
    @endif

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full text-sm">
            <thead>
                <tr>
                    <th class="px-3 py-2 text-left">TANGGAL</th>
                    <th class="px-3 py-2 text-left">PELAYANAN</th>
                    <th class="px-3 py-2 text-left">KETERSEDIAAN</th>
                    <th class="px-3 py-2 text-left">KOLEKSI</th>
                    <th class="px-3 py-2 text-left">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $r)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $r->created_at }}</td>
                        <td class="px-3 py-2">{{ \Illuminate\Support\Str::limit($r->service_quality, 120) }} <span class="text-xs text-gray-500">({{ $r->service_quality_sentiment }})</span></td>
                        <td class="px-3 py-2">{{ \Illuminate\Support\Str::limit($r->book_availability, 120) }} <span class="text-xs text-gray-500">({{ $r->book_availability_sentiment }})</span></td>
                        <td class="px-3 py-2">{{ \Illuminate\Support\Str::limit($r->book_collection, 120) }} <span class="text-xs text-gray-500">({{ $r->book_collection_sentiment }})</span></td>
                        <td class="px-3 py-2">
                            <form action="{{ route('admin.reviews.destroy', $r) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus ulasan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">Belum ada ulasan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>
@endsection
