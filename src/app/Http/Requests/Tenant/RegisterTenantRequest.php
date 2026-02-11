<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CnpjRule;

class RegisterTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cnpj' => preg_replace('/\D/', '', $this->cnpj),
        ]);
    }

    public function rules(): array
    {
        return [
            'tenant_name' => 'required|string|max:255',

            'tenant_email' => 'required|email|max:255|unique:tenants,email',

            'cnpj' => [
                'required',
                'unique:tenants,cnpj',
                new CnpjRule(),
            ],

            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'cnpj.unique' => 'Este CNPJ já está cadastrado.',
            'admin_email.unique' => 'Este email de administrador já está em uso.',
            'tenant_email.unique' => 'Este email do tenant já está em uso.'
        ];
    }
}
