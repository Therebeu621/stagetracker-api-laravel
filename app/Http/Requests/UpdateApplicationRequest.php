<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company' => ['sometimes', 'string', 'max:255'],
            'position' => ['sometimes', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'in:applied,interview,offer,rejected'],
            'applied_at' => ['nullable', 'date'],
            'next_followup_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
