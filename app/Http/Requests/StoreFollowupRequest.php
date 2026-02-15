<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFollowupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:email,call,linkedin'],
            'done_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
