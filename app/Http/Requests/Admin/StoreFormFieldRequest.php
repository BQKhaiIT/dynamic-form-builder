<?php

namespace App\Http\Requests\Admin;

use App\Enums\FormFieldType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFormFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('type') !== FormFieldType::Select->value) {
            $this->merge([
                'options' => [],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(FormFieldType::class)],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'options' => ['nullable', 'array'],
            'options.*.label' => ['nullable', 'string', 'max:255'],
            'options.*.value' => ['nullable', 'string', 'max:255'],
            'options.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                if ($this->input('type') !== FormFieldType::Select->value) {
                    return;
                }

                $hasValidOption = collect($this->input('options', []))
                    ->contains(fn (array $option): bool => filled($option['label'] ?? null) && filled($option['value'] ?? null));

                if (! $hasValidOption) {
                    $validator->errors()->add('options', 'At least one option is required for select fields.');
                }
            },
        ];
    }
}
