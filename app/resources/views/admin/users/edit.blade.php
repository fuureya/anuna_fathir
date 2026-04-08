@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl">
    <h1 class="text-2xl font-semibold mb-4">Edit Pengguna</h1>
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', data_get($user,'nik')) }}" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Nama Lengkap</label>
                <input type="text" name="fullname" value="{{ old('fullname', data_get($user,'fullname')) }}" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', data_get($user,'email')) }}" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Password (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="mt-1 w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Role</label>
                <select name="role" class="mt-1 w-full border rounded p-2" required>
                    <option value="user" @selected(old('role', data_get($user,'role'))==='user')>User</option>
                    <option value="admin" @selected(old('role', data_get($user,'role'))==='admin')>Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="mt-1 w-full border rounded p-2">
                    <option value="">--</option>
                    <option value="Laki-laki" @selected(old('jenis_kelamin', data_get($user,'jenis_kelamin'))==='Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin', data_get($user,'jenis_kelamin'))==='Perempuan')>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Tanggal Lahir</label>
                <input type="date" name="tempat_tanggal_lahir" value="{{ old('tempat_tanggal_lahir', data_get($user,'tempat_tanggal_lahir') ? \Illuminate\Support\Carbon::parse(data_get($user,'tempat_tanggal_lahir'))->format('Y-m-d') : '') }}" class="mt-1 w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Pendidikan Terakhir</label>
                <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', data_get($user,'pendidikan_terakhir')) }}" class="mt-1 w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', data_get($user,'pekerjaan')) }}" class="mt-1 w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Usia</label>
                <input type="number" name="usia" value="{{ old('usia', data_get($user,'usia')) }}" class="mt-1 w-full border rounded p-2">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium">Alamat</label>
            <textarea name="alamat_tinggal" class="mt-1 w-full border rounded p-2" rows="3">{{ old('alamat_tinggal', data_get($user,'alamat_tinggal')) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Foto Profil</label>
            @if(data_get($user,'profile_picture'))
                <div class="mb-2"><img src="{{ asset(data_get($user,'profile_picture')) }}" alt="Avatar" class="h-16 w-16 rounded-full object-cover"></div>
            @endif
            <input type="file" name="profile_picture" accept="image/*" class="mt-1 w-full">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
