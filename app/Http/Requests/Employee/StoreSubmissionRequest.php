<?php

namespace App\Http\Requests\Employee;

use App\Enums\FormFieldType;
use App\Enums\FormStatus;
use App\Models\Form;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        /** @var Form $form */
        $form = $this->route('form');
        $form->loadMissing('fields.options');

        $rules = [
            'fields' => ['required', 'array'],
        ];

        if ($form->status !== FormStatus::Active) {
            return $rules;
        }

        foreach ($form->fields as $field) {
            $fieldRules = [$field->required ? 'required' : 'nullable'];

            switch ($field->type) {
                case FormFieldType::Number:
                    $fieldRules[] = 'numeric';
                    break;
                case FormFieldType::Date:
                    $fieldRules[] = 'date';
                    break;
                case FormFieldType::Color:
                    $fieldRules[] = 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/';
                    break;
                case FormFieldType::Select:
                    $fieldRules[] = Rule::in($field->options->pluck('value')->all());
                    break;
                case FormFieldType::Text:
                    $fieldRules[] = 'string';
                    break;
            }

            $rules['fields.'.$field->id] = $fieldRules;
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'fields' => $this->input('fields', []),
        ]);
    }
}
