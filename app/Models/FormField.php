<?php

namespace App\Models;

use App\Enums\FormFieldType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormField extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'form_id',
        'label',
        'type',
        'placeholder',
        'required',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'type' => FormFieldType::class,
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(FieldOption::class, 'field_id')->orderBy('sort_order')->orderBy('id');
    }

    public function submissionValues(): HasMany
    {
        return $this->hasMany(SubmissionValue::class, 'field_id');
    }

    public function isSelect(): bool
    {
        return $this->type === FormFieldType::Select;
    }
}
