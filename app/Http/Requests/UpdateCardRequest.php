<?php

declare(strict_types=1);

namespace App\Http\Requests;

class UpdateCardRequest extends JsonApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->mergeRules([
            'data.attributes.title' => ['sometimes', 'required', 'string', 'max:255'],
            'data.attributes.description' => ['sometimes', 'required', 'string'],
            'data.attributes.position' => ['sometimes', 'required', 'integer'],
        ]);
    }
}
