@php
    $fieldModel = $field ?? null;
    $optionRows = old('options', $fieldModel?->options?->map(fn ($option) => [
        'label' => $option->label,
        'value' => $option->value,
        'sort_order' => $option->sort_order,
    ])->all() ?? [['label' => '', 'value' => '', 'sort_order' => 0]]);
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="label" class="form-label">Label</label>
                <input id="label" type="text" name="label" value="{{ old('label', $fieldModel->label ?? '') }}" class="form-control" required>
                @error('label')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="type" class="form-label">Type</label>
                <select id="type" name="type" class="form-select" required data-field-type>
                    @foreach ($fieldTypes as $fieldType)
                        <option value="{{ $fieldType->value }}" @selected(old('type', $fieldModel?->type?->value) === $fieldType->value)>
                            {{ $fieldType->label() }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-8">
                <label for="placeholder" class="form-label">Placeholder</label>
                <input id="placeholder" type="text" name="placeholder" value="{{ old('placeholder', $fieldModel->placeholder ?? '') }}" class="form-control">
                @error('placeholder')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="sort_order" class="form-label">Sort Order</label>
                <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $fieldModel->sort_order ?? 0) }}" class="form-control">
                @error('sort_order')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-check mt-3">
            <input id="required" type="checkbox" name="required" value="1" class="form-check-input" @checked(old('required', $fieldModel->required ?? false))>
            <label for="required" class="form-check-label">Required field</label>
        </div>

        <div class="mt-4" data-options-section>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="h5 mb-1">Select Options</h2>
                    <p class="text-muted small mb-0">Used only when the field type is select.</p>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-add-option>Add Option</button>
            </div>

            <div class="d-grid gap-3" data-option-list>
                @foreach ($optionRows as $index => $option)
                    <div class="row g-2 align-items-end option-row">
                        <div class="col-md-5">
                            <label class="form-label">Option Label</label>
                            <input type="text" name="options[{{ $index }}][label]" value="{{ $option['label'] ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Option Value</label>
                            <input type="text" name="options[{{ $index }}][value]" value="{{ $option['value'] ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Sort</label>
                            <input type="number" name="options[{{ $index }}][sort_order]" value="{{ $option['sort_order'] ?? $index }}" min="0" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger w-100" data-remove-option>&times;</button>
                        </div>
                    </div>
                @endforeach
            </div>

            @error('options')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
            @error('options.*.label')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
            @error('options.*.value')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.forms.fields.index', $form) }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        </div>
    </div>
</div>

<template id="option-row-template">
    <div class="row g-2 align-items-end option-row">
        <div class="col-md-5">
            <label class="form-label">Option Label</label>
            <input type="text" data-name="label" class="form-control">
        </div>
        <div class="col-md-5">
            <label class="form-label">Option Value</label>
            <input type="text" data-name="value" class="form-control">
        </div>
        <div class="col-md-1">
            <label class="form-label">Sort</label>
            <input type="number" min="0" data-name="sort_order" class="form-control">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger w-100" data-remove-option>&times;</button>
        </div>
    </div>
</template>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const typeSelect = document.querySelector('[data-field-type]');
            const optionsSection = document.querySelector('[data-options-section]');
            const optionList = document.querySelector('[data-option-list]');
            const optionTemplate = document.querySelector('#option-row-template');
            const addOptionButton = document.querySelector('[data-add-option]');

            if (!typeSelect || !optionsSection || !optionList || !optionTemplate || !addOptionButton) {
                return;
            }

            const syncOptionsVisibility = () => {
                const isSelect = typeSelect.value === 'select';

                optionsSection.classList.toggle('d-none', !isSelect);

                optionList.querySelectorAll('input').forEach((input) => {
                    input.disabled = !isSelect;
                });
            };

            const bindRemoveButtons = () => {
                optionList.querySelectorAll('[data-remove-option]').forEach((button) => {
                    button.onclick = () => {
                        button.closest('.option-row')?.remove();
                    };
                });
            };

            const createOptionRow = () => {
                const index = optionList.querySelectorAll('.option-row').length;
                const fragment = optionTemplate.content.cloneNode(true);

                fragment.querySelectorAll('[data-name]').forEach((input) => {
                    input.name = `options[${index}][${input.dataset.name}]`;

                    if (input.dataset.name === 'sort_order') {
                        input.value = index;
                    }
                });

                optionList.appendChild(fragment);
                bindRemoveButtons();
            };

            typeSelect.addEventListener('change', syncOptionsVisibility);
            addOptionButton.addEventListener('click', createOptionRow);

            bindRemoveButtons();
            syncOptionsVisibility();
        });
    </script>
@endpush
