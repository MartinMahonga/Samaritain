<?php

use DistortedFusion\BladeForms\Components;

return [
    /*
    |--------------------------------------------------------------------------
    | Component prefix
    |--------------------------------------------------------------------------
    |
    | A prefix that should be applied to all registered components. This can
    | be used to prevent collisions between identically named components.
    |
    | Example with a prefix of `df`:
    | - Without: <x-form-input />
    | - With:    <x-df-form-input />
    |
    */

    'prefix' => null,

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | All components, including their backing class, that should be
    | registered during runtime.
    |
    */

    'components' => [
        'form-checkbox' => Components\Checkbox::class,
        'form-errors' => Components\Errors::class,
        'form-grid-column' => 'blade-forms::components.grid.column',
        'form-grid' => 'blade-forms::components.grid.index',
        'form-group' => Components\Group::class,
        'form-help' => Components\Help::class,
        'form-icon' => 'blade-forms::components.icon',
        'form-input' => Components\Input::class,
        'form-label' => Components\Label::class,
        'form-radio' => Components\Radio::class,
        'form-section-title' => Components\SectionTitle::class,
        'form-section' => Components\Section::class,
        'form-sections' => 'blade-forms::components.section-group',
        'form-select-description' => Components\SelectDescription::class,
        'form-select' => Components\Select::class,
        'form-textarea' => Components\Textarea::class,
        'form-toggle' => Components\Toggle::class,
        'form' => Components\Form::class,
    ],
];
