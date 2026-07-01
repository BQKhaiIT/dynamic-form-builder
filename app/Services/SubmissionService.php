<?php

namespace App\Services;

use App\Enums\FormFieldType;
use App\Enums\FormStatus;
use App\Models\Form;
use App\Models\FormField;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubmissionService
{
    public function create(Form $form, User $user, array $values): Submission
    {
        $form->loadMissing('fields.options');

        if ($form->status !== FormStatus::Active) {
            throw ValidationException::withMessages([
                'form' => 'This form is not available for submission.',
            ]);
        }

        if ($form->fields->isEmpty()) {
            throw ValidationException::withMessages([
                'form' => 'This form has no fields yet and cannot be submitted.',
            ]);
        }

        return DB::transaction(function () use ($form, $user, $values): Submission {
            $submission = $form->submissions()->create([
                'submitted_by' => $user->id,
                'submitted_at' => Carbon::now(),
            ]);

            foreach ($form->fields as $field) {
                $value = $values[$field->id] ?? null;

                $this->validateFieldValue($field, $value);

                $submission->values()->create([
                    'field_id' => $field->id,
                    'value' => is_array($value) ? json_encode($value, JSON_THROW_ON_ERROR) : $value,
                ]);
            }

            return $submission->load(['values.field', 'form', 'user']);
        });
    }

    private function validateFieldValue(FormField $field, mixed $value): void
    {
        if ($field->required && blank($value)) {
            throw ValidationException::withMessages([
                'fields.'.$field->id => $field->label.' is required.',
            ]);
        }

        if (blank($value)) {
            return;
        }

        if ($field->type === FormFieldType::Select) {
            $allowedValues = $field->options->pluck('value')->all();

            if (! in_array($value, $allowedValues, true)) {
                throw ValidationException::withMessages([
                    'fields.'.$field->id => 'Invalid selection for '.$field->label.'.',
                ]);
            }
        }
    }
}
