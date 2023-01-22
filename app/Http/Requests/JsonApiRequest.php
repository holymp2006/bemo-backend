<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

abstract class JsonApiRequest extends FormRequest
{
    abstract public function authorize(): bool;

    /**
     * @return array<string, mixed>
     */
    abstract public function rules(): array;

    protected function prepareForValidation(): void
    {
        $data = $this->data ?? null;

        $factory = $this->container->make(ValidationFactory::class);
        $validator = $this->createDefaultValidator($factory);

        if (is_null($data)) {
            $validator->setRules([]); //to remove any other message from the messageBag
            $validator->errors()->add('data', 'The given data was invalid.');
            throw $this->failedValidation($validator);
        }
        if (!array_key_exists('type', $data)) {
            $validator->setRules([]);
            $validator->errors()->add('type', 'The given data has no type.');
            throw $this->failedValidation($validator);
        }
        if (!array_key_exists('attributes', $data)) {
            $validator->setRules([]);
            $validator->errors()->add('attributes', 'The given data has no attributes.');
            throw $this->failedValidation($validator);
        }
        $dataArray = (array) Arr::except($data, ['attributes']);
        $attributes = (array) $data['attributes'];

        $this->merge(array_merge($dataArray, $attributes));
    }
}
