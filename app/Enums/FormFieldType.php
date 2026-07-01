<?php

namespace App\Enums;

enum FormFieldType: string
{
    case Text = 'text';
    case Number = 'number';
    case Date = 'date';
    case Color = 'color';
    case Select = 'select';

    public function label(): string
    {
        return match ($this) {
            self::Text => 'Text',
            self::Number => 'Number',
            self::Date => 'Date',
            self::Color => 'Color',
            self::Select => 'Select',
        };
    }
}
