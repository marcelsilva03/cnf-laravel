<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class ResourceImage extends Field
{
    protected string $view = 'forms.components.resource-image';

    public static function make(string $name): static
    {
        $instance = new static($name);
        $instance->viewData(['field' => $name]);
        return $instance;
    }
}
