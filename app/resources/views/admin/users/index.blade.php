@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl">👥 Kelola Pengguna</h1>
    
    @if(session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('status') }}</div>
    @endif
    
    <div class="mb-4">
        <a href="{{ route('admin.users.create') }}" class="btn bg-blue-600">+ Tambah Pengguna</a>
    </div>
    
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full text-sm">
            <thead>
                <tr>
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">NAMA</th>
                    <th class="px-3 py-2 text-left">EMAIL</th>
                    <th class="px-3 py-2 text-left">ROLE</th>
                    <th class="px-3 py-2 text-left">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $u->id }}</td>
                        <td class="px-3 py-2">{{ $u->fullname }}</td>
                        <td class="px-3 py-2">{{ $u->email }}</td>
                        <td class="px-3 py-2">{{ $u->role }}</td>
                        <td class="px-3 py-2">
                            <a href="{{ route('admin.users.edit', $u) }}" class="edit-btn">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display: inline;" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
