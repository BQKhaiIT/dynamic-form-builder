<?php

namespace Tests\Feature\Employee;

use App\Enums\FormFieldType;
use App\Enums\FormStatus;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_view_active_forms_only(): void
    {
        $employee = User::factory()->employee()->create();
        $admin = User::factory()->admin()->create();

        Form::create([
            'title' => 'Active Form',
            'description' => 'Visible',
            'status' => FormStatus::Active,
            'created_by' => $admin->id,
        ]);

        Form::create([
            'title' => 'Draft Form',
            'description' => 'Hidden',
            'status' => FormStatus::Draft,
            'created_by' => $admin->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->get(route('employee.forms.index'));

        $response->assertOk();
        $response->assertSee('Active Form');
        $response->assertDontSee('Draft Form');
    }

    public function test_employee_can_submit_a_response_to_an_active_form(): void
    {
        $employee = User::factory()->employee()->create();
        $admin = User::factory()->admin()->create();

        $form = Form::create([
            'title' => 'Equipment Request',
            'description' => 'Request business equipment',
            'status' => FormStatus::Active,
            'created_by' => $admin->id,
        ]);

        $textField = $form->fields()->create([
            'label' => 'Item Name',
            'type' => FormFieldType::Text,
            'required' => true,
            'sort_order' => 1,
        ]);

        $selectField = $form->fields()->create([
            'label' => 'Priority',
            'type' => FormFieldType::Select,
            'required' => true,
            'sort_order' => 2,
        ]);

        $selectField->options()->createMany([
            ['label' => 'High', 'value' => 'high', 'sort_order' => 1],
            ['label' => 'Medium', 'value' => 'medium', 'sort_order' => 2],
        ]);

        $response = $this
            ->actingAs($employee)
            ->post(route('employee.forms.submit', $form), [
                'fields' => [
                    $textField->id => 'Laptop Dock',
                    $selectField->id => 'high',
                ],
            ]);

        $response->assertRedirect(route('employee.forms.index'));

        $this->assertDatabaseHas('submissions', [
            'form_id' => $form->id,
            'submitted_by' => $employee->id,
        ]);

        $this->assertDatabaseHas('submission_values', [
            'field_id' => $textField->id,
            'value' => 'Laptop Dock',
        ]);

        $this->assertDatabaseHas('submission_values', [
            'field_id' => $selectField->id,
            'value' => 'high',
        ]);
    }
}
