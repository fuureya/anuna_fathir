<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => ['required','string','max:255'],
            'fullname' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique(User::class, 'email')],
            'password' => ['required','string','min:6'],
            'role' => ['required', Rule::in(['user','admin'])],
            'jenis_kelamin' => ['nullable','in:Laki-laki,Perempuan'],
            'alamat_tinggal' => ['nullable','string'],
            'pekerjaan' => ['nullable','string','max:255'],
            'tempat_tanggal_lahir' => ['nullable','date'],
            'pendidikan_terakhir' => ['nullable','string','max:255'],
            'usia' => ['nullable','integer','min:0','max:120'],
            'profile_picture' => ['nullable','file','mimes:jpg,jpeg,png,gif','max:2048'],
        ]);

        // Handle avatar using Laravel Storage (more secure)
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads', $filename, 'public');
            $data['profile_picture'] = 'storage/uploads/' . $filename;
        }

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')->with('status', 'Pengguna dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nik' => ['required','string','max:255'],
            'fullname' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique(User::class, 'email')->ignore($user->id)],
            'password' => ['nullable','string','min:6'],
            'role' => ['required', Rule::in(['user','admin'])],
            'jenis_kelamin' => ['nullable','in:Laki-laki,Perempuan'],
            'alamat_tinggal' => ['nullable','string'],
            'pekerjaan' => ['nullable','string','max:255'],
            'tempat_tanggal_lahir' => ['nullable','date'],
            'pendidikan_terakhir' => ['nullable','string','max:255'],
            'usia' => ['nullable','integer','min:0','max:120'],
            'profile_picture' => ['nullable','file','mimes:jpg,jpeg,png,gif','max:2048'],
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        } else {
            unset($data['password']);
        }

        // Handle avatar using Laravel Storage (more secure)
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads', $filename, 'public');
            $data['profile_picture'] = 'storage/uploads/' . $filename;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'Pengguna diperbarui.');
    }

    public function destroy(User $user)
    {
        // Prevent self deletion via admin panel
        if (Auth::id() === $user->id) {
            return back()->with('status', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return back()->with('status', 'Pengguna dihapus.');
    }
}
