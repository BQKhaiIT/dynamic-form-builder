<?php

namespace Database\Seeders;

use App\Enums\FormFieldType;
use App\Enums\FormStatus;
use App\Enums\UserRole;
use App\Models\Form;
use App\Models\Submission;
use App\Models\SubmissionValue;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::where('role', UserRole::Employee)->get();

        $forms = Form::with('fields.options')
            ->where('status', FormStatus::Active)
            ->get();

        foreach ($employees as $employee) {

            foreach ($forms as $form) {

                // Khoảng 75% nhân viên sẽ gửi form
                if (! fake()->boolean(75)) {
                    continue;
                }

                $submission = Submission::create([
                    'form_id' => $form->id,
                    'submitted_by' => $employee->id,
                    'submitted_at' => fake()->dateTimeBetween('-30 days'),
                ]);

                foreach ($form->fields as $field) {

                    SubmissionValue::create([
                        'submission_id' => $submission->id,
                        'field_id' => $field->id,
                        'value' => $this->generateValue($field),
                    ]);
                }
            }
        }
    }

    private function generateValue($field): ?string
    {
        return match ($field->type) {

            FormFieldType::Text => fake()->sentence(),

            FormFieldType::Number => (string) fake()->numberBetween(1, 10),

            FormFieldType::Date => fake()->date(),

            FormFieldType::Color => fake()->safeHexColor(),

            FormFieldType::Select => optional(
                $field->options->random()
            )->value,

            default => null,
        };
    }
}
