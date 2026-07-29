<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'alpha_dash', 'max:50', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Nama role wajib diisi.',
            'name.unique'     => 'Nama role sudah terdaftar.',
            'name.alpha_dash' => 'Nama role hanya boleh huruf, angka, strip, dan garis bawah.',
        ];
    }
}
