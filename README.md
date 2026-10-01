# Voucherly PHP SDK

[![Latest Stable Version](https://poser.pugx.org/voucherly/voucherly-php-sdk/v/stable.svg)](https://packagist.org/packages/voucherly/voucherly-php-sdk)
[![Total Downloads](https://poser.pugx.org/voucherly/voucherly-php-sdk/downloads.svg)](https://packagist.org/packages/voucherly/voucherly-php-sdk)
[![License](https://poser.pugx.org/voucherly/voucherly-php-sdk/license.svg)](https://packagist.org/packages/voucherly/voucherly-php-sdk)

The official PHP library for the [Voucherly API][api-docs]. It covers every public operation of the API, and it follows the OpenAPI spec published with the documentation.

Upgrading from 1.x? Read [UPGRADE-2.0.md](UPGRADE-2.0.md).

## Requirements

PHP 7.4 or later, with the `curl` and `json` extensions. The SDK has no other runtime dependency, so it can live inside a PrestaShop or WooCommerce plugin next to other plugins without version conflicts.

## Installation

```bash
composer require voucherly/voucherly-php-sdk
```

Without Composer, download the [latest release](https://github.com/voucherly/voucherly-php-sdk/releases) and include its autoloader:

```php
require_once '/path/to/voucherly-php-sdk/init.php';
```

## Usage

```php
use VoucherlyApi\Enum\LineType;
use VoucherlyApi\Enum\PaymentMode;
use VoucherlyApi\Request\CreatePaymentRequest;
use VoucherlyApi\Request\PaymentLineRequest;
use VoucherlyApi\Request\PaymentLineRequestProduct;
use VoucherlyApi\VoucherlyClient;

$voucherly = new VoucherlyClient(['apiKey' => 'sk_sand_...']);

$product = new PaymentLineRequestProduct();
$product->name = 'Fresh bowl';
$product->lineType = LineType::FOOD;

$line = new PaymentLineRequest();
$line->quantity = 1;
$line->unitAmount = 790;
$line->product = $product;

$request = new CreatePaymentRequest();
$request->mode = PaymentMode::PAYMENT;
$request->customerEmail = 'mario.rossi@example.com';
$request->lines = [$line];
$request->redirectOkUrl = 'https://shop.example.com/ok';
$request->redirectKoUrl = 'https://shop.example.com/ko';

$payment = $voucherly->payments->create($request);
header('Location: ' . $payment->checkoutUrl);
```

Every tag of the API is a service of the client, and every operation is a method named after its verb: `$voucherly->payments->retrieve($id)`, `$voucherly->customers->listAddresses($customerId, $params)`, `$voucherly->paymentGateways->list()`. The [API reference][api-docs] documents every field.

### Requests send only what you set

A request object sends the properties you assign and nothing else, an explicit `null` included. This matters on partial updates, where a missing field is left unchanged and a `null` one is cleared:

```php
$update = new UpdateCustomerRequest();
$update->phoneNumber = null; // clears the phone number; firstName and lastName stay as they are
$voucherly->customers->update($customerId, $update);
```

A property you have not assigned has no value, so reading it throws an `Error`: read it with `?? null` or check it with `isset()`.

A request can also be built from an array with the JSON names: `CreatePaymentRequest::fromArray([...])`.

### Query parameters and pages

Query and header parameters go in a `...Params` object, and required ones are constructor arguments:

```php
$params = new ListCustomerParams();
$params->email = 'mario.rossi@example.com';
$params->length = 50;

do {
    $page = $voucherly->customers->list($params);
    foreach ($page->items as $customer) {
        // ...
    }
    $params->start = $page->pagination->nextStart;
} while ($page->pagination->hasMore);
```

Related objects come only when `include` asks for them: a Payment read without it has no lines, discounts or transactions.

```php
$params = new RetrievePaymentParams();
$params->include = [PaymentInclude::LINES, PaymentInclude::TRANSACTIONS];
$payment = $voucherly->payments->retrieve($paymentId, $params);
```

### Enums

The values of an enum are string constants, such as `PaymentStatus::PAID`. A property holding one is a plain string, so a value added to the API later does not break the SDK.

### Unknown fields

A response keeps the fields this version of the SDK does not know in `getExtensionData()`.

### Errors

Every error extends `VoucherlyApi\Exception\VoucherlyException`:

- `ApiException` when the API answers with a status outside 2xx. It exposes the status (also as `getCode()`), the problem details (`getTitle()`, `getDetail()`, `getErrorCode()`, `getParameter()`, `getExtensions()`) and the raw body. Each status the spec declares has a subclass: `BadRequestException`, `NotFoundException`, `ConflictException` (with `getPaymentStatus()`), `UnprocessableEntityException`, `FailedDependencyException`.
- `ConnectionException` when no response arrives: DNS, TLS, refused connection, timeout.

```php
try {
    $voucherly->payments->confirm($paymentId, $request);
} catch (ConflictException $exception) {
    // $exception->getPaymentStatus() says why the Payment cannot be confirmed
} catch (FailedDependencyException $exception) {
    // $exception->getExtensions()['operations'] lists what was already done: sending the same request again settles the rest
}
```

The SDK never retries a request: a payment sent twice can be charged twice.

## Options

```php
$voucherly = new VoucherlyClient([
    'apiKey' => 'sk_live_...',
    'merchantId' => null,        // platform keys (ik_) only
    'tenant' => null,            // platform keys (ik_) only: live or sand
    'connectTimeout' => 10,      // seconds, default cURL transport only
    'timeout' => 30,             // seconds, default cURL transport only
    'os' => 'PrestaShop',        // telemetry headers, sent only when they have a value
    'osVersion' => '8.2.1',
    'osFramework' => null,
    'app' => 'voucherly-prestashop',
    'appVersion' => '2.0.0',
    'appHouse' => 'Voucherly',
    'deviceType' => null,
    'transport' => null,         // your own VoucherlyApi\Http\TransportInterface
]);
```

The HTTP transport can be replaced: implement `VoucherlyApi\Http\TransportInterface`, with its own logging or proxy, and pass it as `transport`.

## Development

```bash
composer install
composer test                 # PHPUnit
composer coverage             # every operation of the spec has a method
vendor/bin/php-cs-fixer fix
php tools/generate.php        # rewrites lib/Model, lib/Request and lib/Enum from spec/openapi.yaml
```

With a sandbox key, `VOUCHERLY_API_KEY=sk_sand_... vendor/bin/phpunit --group live` runs read-only calls against the sandbox; add `VOUCHERLY_LIVE_WRITES=1` to run also the calls that create data.

For requests, bugs or comments, [open an issue][issues] or [submit a pull request][pulls].

[api-docs]: https://docs.voucherly.it
[issues]: https://github.com/voucherly/voucherly-php-sdk/issues/new
[pulls]: https://github.com/voucherly/voucherly-php-sdk/pulls
