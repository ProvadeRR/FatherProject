<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceGenerationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'number' => ['required', 'integer'],
            'amount' => ['required', 'integer'],
            'date' => ['required', 'string'],
            'template' => ['required', 'string'],
        ];
    }
}
