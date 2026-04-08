<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'jenis_kelamin' => ['nullable','in:Laki-laki,Perempuan'],
            'alamat_tinggal' => ['nullable','string','max:1000'],
            'pekerjaan' => ['nullable','string','max:255'],
            'tempat_tanggal_lahir' => ['nullable','date'],
            'pendidikan_terakhir' => ['nullable','string','max:255'],
            'usia' => ['nullable','integer','min:0','max:120'],
            'profile_picture' => ['nullable','file','mimes:jpg,jpeg,png,gif','max:2048'],
        ];
    }
}
