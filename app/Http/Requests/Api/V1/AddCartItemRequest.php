<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'string'],
            'variant_id' => ['nullable', 'string'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }
}
