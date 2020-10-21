<?php

namespace Gldrenthe89\NovaStringGeneratorField;

use Gldrenthe89\NovaStringGeneratorField\Traits\NovaGenerateStringTrait;
use Laravel\Nova\Fields\Password;

class NovaGeneratePassword extends Password
{
    use NovaGenerateStringTrait;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'generate-string';
}
