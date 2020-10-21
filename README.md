# Nova String Generation Field
A Nova field which can genarate a string upon creating or updating, this adds a string generator button and copy button to a Text or Password field.

## Installation:

You can install the package in to a Laravel app that uses Nova via composer:

```bash
composer require gldrenthe89/nova-string-generator-field
```

```php
use Gldrenthe89\NovaStringGeneratorField\NovaGenerateString;
use Gldrenthe89\NovaStringGeneratorField\NovaGeneratePassword;

// Field which extends the default Nova Text field
NovaGenerateString::make('String')
    ->length(12) // specify the generated string length (default = 10)
    ->excludeRules(['symbols']), // exclude characters types (symbols, numbers. uppercase, lowercase)

// Field which extends the default Nova Password field
NovaGeneratePassword::make('password'), // possible options are the same as above
```

## License:
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
