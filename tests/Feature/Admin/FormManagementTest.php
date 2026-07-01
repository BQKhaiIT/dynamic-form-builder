<?php

namespace Tests\Feature\Admin;

use App\Enums\FormFieldType;
use App\Enums\FormStatus;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_form(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.forms.store'), [
                'title' => 'Employee Feedback',
                'description' => 'Monthly internal feedback form',
                'status' => FormStatus::Draft->value,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('forms', [
            'title' => 'Employee Feedback',
            'status' => FormStatus::Draft->value,
            'created_by' => $admin->id,
        ]);
    }

    public function test_admin_can_add_select_field_with_options(): void
    {
        $admin = User::factory()->admin()->create();
        $form = Form::create([
            'title' => 'Survey',
            'description' => 'Annual survey',
            'status' => FormStatus::Draft,
            'created_by' => $admin->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.forms.fields.store', $form), [
                'label' => 'Department',
                'type' => FormFieldType::Select->value,
                'required' => '1',
                'sort_order' => 1,
                'options' => [
                    ['label' => 'Engineering', 'value' => 'engineering', 'sort_order' => 1],
                    ['label' => 'HR', 'value' => 'hr', 'sort_order' => 2],
                ],
            ]);

        $response->assertRedirect(route('admin.forms.fields.index', $form));

        $this->assertDatabaseHas('form_fields', [
            'form_id' => $form->id,
            'label' => 'Department',
            'type' => FormFieldType::Select->value,
        ]);

        $this->assertDatabaseHas('field_options', [
            'label' => 'Engineering',
            'value' => 'engineering',
        ]);
    }

    public function test_admin_can_add_text_field_without_select_options(): void
    {
        $admin = User::factory()->admin()->create();
        $form = Form::create([
            'title' => 'Profile',
            'description' => 'Profile form',
            'status' => FormStatus::Draft,
            'created_by' => $admin->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.forms.fields.store', $form), [
                'label' => 'Name',
                'type' => FormFieldType::Text->value,
                'placeholder' => 'Enter your name',
                'required' => '1',
                'sort_order' => 1,
                'options' => [
                    ['label' => '', 'value' => '', 'sort_order' => 0],
                ],
            ]);

        $response->assertRedirect(route('admin.forms.fields.index', $form));

        $this->assertDatabaseHas('form_fields', [
            'form_id' => $form->id,
            'label' => 'Name',
            'type' => FormFieldType::Text->value,
            'placeholder' => 'Enter your name',
        ]);
    }
}
