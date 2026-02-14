<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class DashboardMetricsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date', 'before_or_equal:end_date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.date' => 'A data inicial deve ser uma data valida.',
            'start_date.before_or_equal' => 'A data inicial deve ser anterior ou igual a data final.',
            'end_date.date' => 'A data final deve ser uma data valida.',
            'end_date.after_or_equal' => 'A data final deve ser posterior ou igual a data inicial.',
        ];
    }
}
