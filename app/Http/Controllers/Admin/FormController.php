<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FormStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFormRequest;
use App\Http\Requests\Admin\UpdateFormRequest;
use App\Http\Requests\Admin\UpdateFormStatusRequest;
use App\Models\Form;
use App\Services\FormService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FormController extends Controller
{
    public function __construct(
        private readonly FormService $formService,
    ) {}

    public function index(): View
    {
        return view('admin.forms.index', [
            'forms' => $this->formService->paginateForAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('admin.forms.create', [
            'statuses' => $this->formService->getAvailableStatuses(),
        ]);
    }

    public function store(StoreFormRequest $request): RedirectResponse
    {
        $form = $this->formService->create($request->validated(), $request->user());

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Form created successfully.');
    }

    public function show(Form $form): View
    {
        return view('admin.forms.show', [
            'form' => $this->formService->getFormForAdmin($form),
        ]);
    }

    public function edit(Form $form): View
    {
        return view('admin.forms.edit', [
            'form' => $form,
            'statuses' => $this->formService->getAvailableStatuses(),
        ]);
    }

    public function update(UpdateFormRequest $request, Form $form): RedirectResponse
    {
        $this->formService->update($form, $request->validated());

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Form updated successfully.');
    }

    public function destroy(Form $form): RedirectResponse
    {
        $this->formService->delete($form);

        return redirect()
            ->route('admin.forms.index')
            ->with('success', 'Form deleted successfully.');
    }

    public function updateStatus(UpdateFormStatusRequest $request, Form $form): RedirectResponse
    {
        $status = $request->enum('status', FormStatus::class);

        $this->formService->updateStatus($form, $status);

        return redirect()
            ->route('admin.forms.index')
            ->with('success', 'Form status updated successfully.');
    }
}
