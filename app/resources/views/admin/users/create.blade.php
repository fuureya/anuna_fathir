@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl">
    <h1 class="text-2xl font-semibold mb-4">Tambah Pengguna</h1>
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">NIK</label>
                <input type="text" name="nik" value="{{ old('nik') }}" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Nama Lengkap</label>
                <input type="text" name="fullname" value="{{ old('fullname') }}" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Role</label>
                <select name="role" class="mt-1 w-full border rounded p-2" required>
                    <option value="user" @selected(old('role')==='user')>User</option>
                    <option value="admin" @selected(old('role')==='admin')>Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="mt-1 w-full border rounded p-2">
                    <option value="">--</option>
                    <option value="Laki-laki" @selected(old('jenis_kelamin')==='Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin')==='Perempuan')>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Tanggal Lahir</label>
                <input type="date" name="tempat_tanggal_lahir" value="{{ old('tempat_tanggal_lahir') }}" class="mt-1 w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Pendidikan Terakhir</label>
                <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}" class="mt-1 w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" class="mt-1 w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Usia</label>
                <input type="number" name="usia" value="{{ old('usia') }}" class="mt-1 w-full border rounded p-2">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium">Alamat</label>
            <textarea name="alamat_tinggal" class="mt-1 w-full border rounded p-2" rows="3">{{ old('alamat_tinggal') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Foto Profil</label>
            <input type="file" name="profile_picture" accept="image/*" class="mt-1 w-full">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
