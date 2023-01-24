<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

abstract class JsonApiRequest extends FormRequest
{
    abstract public function authorize(): bool;

    /**
     * @return array<string, mixed>
     */
    abstract public function rules(): array;

    protected function mergeRules(array $rules): array
    {
        return array_merge($this->getDefaultRules(), $rules);
    }
    protected function getDefaultRules()
    {
        return [
            'data' => 'required|array',
            'data.id' => ($this->method() === 'PATCH') ? 'required|string' : 'string',
            'data.type' => ['required', Rule::in(array_keys(
                config('jsonapi.resources')
            ))],
            'data.attributes' => 'required|array',

            'data.relationships' => 'array',
            'data.relationships.*.data' => 'required|array',

            'data.relationships.*.data.id' => [
                Rule::requiredIf($this->has('data.relationships.*.data.type')),
                'string'
            ],
            'data.relationships.*.data.type' => [
                Rule::requiredIf($this->has('data.relationships.*.data.id')),
                Rule::in(array_keys(config('jsonapi.resources')))
            ],

            'data.relationships.*.data.*.id' => [
                Rule::requiredIf($this->has('data.relationships.*.data.0')),
                'string'
            ],
            'data.relationships.*.data.*.type' => [
                Rule::requiredIf($this->has('data.relationships.*.data.0')),
                Rule::in(array_keys(config('jsonapi.resources')))
            ],
        ];
    }
    protected function mergeMultipleRules(array $rules): array
    {
        return array_merge($this->getDefaultMultipleRules(), $rules);
    }
    protected function getDefaultMultipleRules()
    {
        return [
            'data.*.id' => ($this->method() === 'PATCH') ? 'required|string' : 'string',
            'data.*.type' => ['required', Rule::in(array_keys(
                config('jsonapi.resources')
            ))],
            'data.*.attributes' => 'required|array',
        ];
    }
}
