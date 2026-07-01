<?php

namespace App\Services;

use App\Enums\FormStatus;
use App\Models\Form;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class FormService
{
    public function getAdminDashboardMetrics(): array
    {
        return [
            'total_forms' => Form::count(),
            'active_forms' => Form::where('status', FormStatus::Active)->count(),
            'total_submissions' => Submission::count(),
        ];
    }

    public function paginateForAdmin(int $perPage = 10): LengthAwarePaginator
    {
        return Form::query()
            ->with(['creator'])
            ->withCount(['fields', 'submissions'])
            ->latest()
            ->paginate($perPage);
    }

    public function getFormForAdmin(Form $form): Form
    {
        return $form->load([
            'creator',
            'fields.options',
            'submissions.user',
        ]);
    }

    public function create(array $data, User $user): Form
    {
        return Form::create([
            ...$data,
            'created_by' => $user->id,
        ]);
    }

    public function update(Form $form, array $data): Form
    {
        $form->update($data);

        return $form->refresh();
    }

    public function updateStatus(Form $form, FormStatus $status): Form
    {
        if ($status === FormStatus::Active && ! $form->fields()->exists()) {
            throw ValidationException::withMessages([
                'status' => 'A form must contain at least one field before it can be activated.',
            ]);
        }

        $form->update([
            'status' => $status,
        ]);

        return $form->refresh();
    }

    public function delete(Form $form): void
    {
        $form->delete();
    }

    public function getActiveForms(int $perPage = 12): LengthAwarePaginator
    {
        return Form::query()
            ->active()
            ->withCount('fields')
            ->latest()
            ->paginate($perPage);
    }

    public function getActiveFormForEmployee(Form $form): Form
    {
        abort_unless($form->status === FormStatus::Active, 404);

        return $form->load('fields.options');
    }

    public function getAvailableStatuses(): array
    {
        return FormStatus::cases();
    }
}
