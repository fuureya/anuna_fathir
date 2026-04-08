@extends('layouts.admin')

@section('title', 'Kelola Buku')

@section('content')
<div class="p-6">
    <h1 class="text-2xl">📚 Kelola Buku</h1>

    <div class="mb-4">
        <a href="{{ route('admin.books.create') }}" class="btn bg-blue-600">+ Tambah Buku Baru</a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full text-sm">
            <thead>
                <tr>
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">COVER</th>
                    <th class="px-3 py-2 text-left">JUDUL</th>
                    <th class="px-3 py-2 text-left">PENULIS</th>
                    <th class="px-3 py-2 text-left">KATEGORI</th>
                    <th class="px-3 py-2 text-left">ISBN</th>
                    <th class="px-3 py-2 text-left">STOK</th>
                    <th class="px-3 py-2 text-left">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $book->buku_id }}</td>
                        <td class="px-3 py-2">
                            @if($book->cover_image && filter_var($book->cover_image, FILTER_VALIDATE_URL))
                                <img src="{{ $book->cover_image }}" 
                                    alt="{{ $book->judul }}" 
                                    style="width: 50px; height: 70px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            @elseif($book->cover_image)
                                <img src="{{ asset('covers/' . $book->cover_image) }}" 
                                    alt="{{ $book->judul }}" 
                                    style="width: 50px; height: 70px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            @else
                                <img src="{{ asset('covers/default.jpg') }}" 
                                    alt="{{ $book->judul }}" 
                                    style="width: 50px; height: 70px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ $book->judul }}</td>
                        <td class="px-3 py-2">{{ $book->penulis }}</td>
                        <td class="px-3 py-2">{{ $book->kategori }}</td>
                        <td class="px-3 py-2">{{ $book->ISBN }}</td>
                        <td class="px-3 py-2">{{ $book->jumlah_eksemplar }}</td>
                        <td class="px-3 py-2">
                            <a href="{{ route('admin.books.edit', $book->buku_id) }}" class="edit-btn">Edit</a>
                            <form action="{{ route('admin.books.destroy', $book->buku_id) }}" 
                                  method="POST" 
                                  style="display: inline;"
                                  onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-4 text-center text-gray-500">Belum ada data buku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
