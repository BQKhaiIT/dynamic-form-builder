<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreSubmissionRequest;
use App\Models\Form;
use App\Services\FormService;
use App\Services\SubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FormController extends Controller
{
    public function __construct(
        private readonly FormService $formService,
        private readonly SubmissionService $submissionService,
    ) {}

    public function index(): View
    {
        return view('employee.forms.index', [
            'forms' => $this->formService->getActiveForms(),
        ]);
    }

    public function show(Form $form): View
    {
        return view('employee.forms.show', [
            'form' => $this->formService->getActiveFormForEmployee($form),
        ]);
    }

    public function store(StoreSubmissionRequest $request, Form $form): RedirectResponse
    {
        $form = $this->formService->getActiveFormForEmployee($form);

        $this->submissionService->create(
            $form,
            $request->user(),
            $request->validated('fields')
        );

        return redirect()
            ->route('employee.forms.index')
            ->with('success', 'Form submitted successfully.');
    }
}
