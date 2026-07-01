<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FormFieldType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFormFieldRequest;
use App\Http\Requests\Admin\UpdateFormFieldRequest;
use App\Models\Form;
use App\Models\FormField;
use App\Services\FieldService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FormFieldController extends Controller
{
    public function __construct(
        private readonly FieldService $fieldService,
    ) {}

    public function index(Form $form): View
    {
        return view('admin.fields.index', [
            'form' => $form->load('fields.options'),
        ]);
    }

    public function create(Form $form): View
    {
        return view('admin.fields.create', [
            'form' => $form,
            'fieldTypes' => FormFieldType::cases(),
        ]);
    }

    public function store(StoreFormFieldRequest $request, Form $form): RedirectResponse
    {
        $this->fieldService->create($form, $request->validated());

        return redirect()
            ->route('admin.forms.fields.index', $form)
            ->with('success', 'Field created successfully.');
    }

    public function show(Form $form, FormField $field): View
    {
        $this->fieldService->ensureBelongsToForm($form, $field);

        return view('admin.fields.show', [
            'form' => $form,
            'field' => $field->load('options'),
        ]);
    }

    public function edit(Form $form, FormField $field): View
    {
        $this->fieldService->ensureBelongsToForm($form, $field);

        return view('admin.fields.edit', [
            'form' => $form,
            'field' => $field->load('options'),
            'fieldTypes' => FormFieldType::cases(),
        ]);
    }

    public function update(UpdateFormFieldRequest $request, Form $form, FormField $field): RedirectResponse
    {
        $this->fieldService->update($form, $field, $request->validated());

        return redirect()
            ->route('admin.forms.fields.index', $form)
            ->with('success', 'Field updated successfully.');
    }

    public function destroy(Form $form, FormField $field): RedirectResponse
    {
        $this->fieldService->delete($form, $field);

        return redirect()
            ->route('admin.forms.fields.index', $form)
            ->with('success', 'Field deleted successfully.');
    }
}
