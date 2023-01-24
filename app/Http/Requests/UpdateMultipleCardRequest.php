<?php

declare(strict_types=1);

namespace App\Http\Requests;

class UpdateMultipleCardRequest extends JsonApiRequest
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
        return $this->mergeMultipleRules([
            'data.*.attributes.column_id' => ['sometimes', 'required', 'string'],
            'data.*.attributes.order' => ['sometimes', 'required', 'integer'],
        ]);
    }
}
