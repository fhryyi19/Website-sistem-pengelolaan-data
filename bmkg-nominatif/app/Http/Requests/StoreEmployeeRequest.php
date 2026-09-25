<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('nip')) {
            $this->merge([
                'nip' => preg_replace('/\D/', '', (string) $this->nip),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nip'                  => ['required', 'string', 'digits:18', Rule::unique('employees', 'nip')->whereNull('deleted_at')],
            'full_name'            => ['required', 'string', 'max:150'],
            'prefix_title'         => ['nullable', 'string', 'max:50'],
            'suffix_title'         => ['nullable', 'string', 'max:50'],
            'birth_place'          => ['required', 'string', 'max:100'],
            'birth_date'           => ['required', 'date', 'before:today'],
            'gender_id'            => ['required', 'exists:genders,id'],
            'religion_id'          => ['required', 'exists:religions,id'],
            'marital_status_id'    => ['required', 'exists:marital_statuses,id'],
            'address'              => ['nullable', 'string', 'max:500'],
            'phone'                => ['nullable', 'string', 'max:25'],
            'email'                => ['nullable', 'email', 'max:150', Rule::unique('employees', 'email')->whereNull('deleted_at')],
            'employment_status_id' => ['required', 'exists:employment_statuses,id'],
            'work_unit_id'         => ['required', 'exists:work_units,id'],
            'rank_id'              => ['nullable', 'exists:ranks,id'],
            'rank_effective_date'  => ['nullable', 'date'],
            'rank_decree_number'   => ['nullable', 'string', 'max:100'],
            'rank_decree_date'     => ['nullable', 'date'],
            'position_id'          => ['nullable', 'exists:positions,id'],
            'position_effective_date' => ['nullable', 'date'],
            'position_decree_number'  => ['nullable', 'string', 'max:100'],
            'position_decree_date'    => ['nullable', 'date'],
            // Pendidikan Formal Terakhir (opsional, terhubung ke employee_educations)
            'education_id'         => ['nullable', 'exists:educations,id'],
            'institution_name'     => ['nullable', 'string', 'max:200'],
            'major'                => ['nullable', 'string', 'max:150'],
            'year_graduated'       => ['nullable', 'numeric', 'digits:4', 'lte:' . date('Y')],
            'certificate_number'   => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nip.required'               => 'NIP wajib diisi.',
            'nip.digits'                 => 'NIP harus terdiri dari 18 digit angka.',
            'nip.unique'                 => 'NIP sudah terdaftar di sistem.',
            'full_name.required'         => 'Nama lengkap wajib diisi.',
            'birth_place.required'       => 'Tempat lahir wajib diisi.',
            'birth_date.required'        => 'Tanggal lahir wajib diisi.',
            'birth_date.before'          => 'Tanggal lahir tidak valid.',
            'gender_id.required'         => 'Jenis kelamin wajib dipilih.',
            'religion_id.required'       => 'Agama wajib dipilih.',
            'marital_status_id.required' => 'Status perkawinan wajib dipilih.',
            'employment_status_id.required' => 'Status kepegawaian wajib dipilih.',
            'work_unit_id.required'      => 'Unit kerja wajib dipilih.',
            'email.email'                => 'Format email tidak valid.',
            'email.unique'               => 'Email sudah terdaftar.',
        ];
    }
}
