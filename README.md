# Voucherly PHP SDK

[![Latest Stable Version](https://poser.pugx.org/voucherly/voucherly-php-sdk/v/stable.svg)](https://packagist.org/packages/voucherly/voucherly-php-sdk)
[![Total Downloads](https://poser.pugx.org/voucherly/voucherly-php-sdk/downloads.svg)](https://packagist.org/packages/voucherly/voucherly-php-sdk)
[![License](https://poser.pugx.org/voucherly/voucherly-php-sdk/license.svg)](https://packagist.org/packages/voucherly/voucherly-php-sdk)

The official [Voucherly][voucherly] PHP library, supporting PHP 5.6.0 and later.


## Requirements

PHP 5.6.0 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require voucherly/voucherly-php-sdk
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once 'vendor/autoload.php';
```

## Manual Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/voucherly/voucherly-php-sdk/releases). Then, to use the bindings, include the `init.php` file.

```php
require_once '/path/to/voucherly-php-sdk/init.php';
```

## Dependencies

The bindings require the following extensions in order to work properly:

-   [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
-   [`json`](https://secure.php.net/manual/en/book.json.php)
-   [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.


## Documentation

For a comprehensive list of examples, check out the [API documentation][api-docs].

## Development

For any requests, bug or comments, please [open an issue][issues] or [submit a pull request][pulls].

[api-docs]: https://docs.voucherly.it
[dotnet-core-cli-tools]: https://docs.microsoft.com/en-us/dotnet/core/tools/
[issues]: https://github.com/voucherly/voucherly-dotnet/issues/new
[nuget-cli]: https://docs.microsoft.com/en-us/nuget/tools/nuget-exe-cli-reference
[package-manager-console]: https://docs.microsoft.com/en-us/nuget/tools/package-manager-console
[pulls]: https://github.com/voucherly/voucherly-dotnet/pulls
[voucherly]: https://voucherly.it
