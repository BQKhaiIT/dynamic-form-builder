<?php

namespace App\Services;

use App\Enums\FormFieldType;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Support\Facades\DB;

class FieldService
{
    public function create(Form $form, array $data): FormField
    {
        return DB::transaction(function () use ($form, $data): FormField {
            $field = $form->fields()->create($this->extractFieldData($data));

            $this->syncOptions($field, $data['options'] ?? []);

            return $field->load('options');
        });
    }

    public function update(Form $form, FormField $field, array $data): FormField
    {
        $this->ensureBelongsToForm($form, $field);

        return DB::transaction(function () use ($field, $data): FormField {
            $field->update($this->extractFieldData($data));

            $this->syncOptions($field, $data['options'] ?? []);

            return $field->refresh()->load('options');
        });
    }

    public function delete(Form $form, FormField $field): void
    {
        $this->ensureBelongsToForm($form, $field);

        $field->delete();
    }

    public function ensureBelongsToForm(Form $form, FormField $field): void
    {
        abort_unless($field->form_id === $form->id, 404);
    }

    private function extractFieldData(array $data): array
    {
        return [
            'label' => $data['label'],
            'type' => $data['type'],
            'placeholder' => $data['placeholder'] ?? null,
            'required' => (bool) ($data['required'] ?? false),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }

    private function syncOptions(FormField $field, array $options): void
    {
        if ($field->type !== FormFieldType::Select) {
            $field->options()->delete();

            return;
        }

        $field->options()->delete();

        $cleanOptions = collect($options)
            ->filter(fn (array $option): bool => filled($option['label'] ?? null) && filled($option['value'] ?? null))
            ->values()
            ->map(fn (array $option, int $index): array => [
                'label' => $option['label'],
                'value' => $option['value'],
                'sort_order' => $option['sort_order'] ?? $index,
            ])
            ->all();

        if ($cleanOptions === []) {
            return;
        }

        $field->options()->createMany($cleanOptions);
    }
}
