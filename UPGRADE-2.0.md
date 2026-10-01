# Upgrading from 1.x to 2.0

2.0 is a rewrite: the static API of 1.x is gone, and every call goes through an instance of `VoucherlyClient`. This page translates every 1.x call.

## Configuration

1.x kept the key and the headers in static properties:

```php
\VoucherlyApi\Api::setApiKey($apiKey);
\VoucherlyApi\Api::setOsNameHeader('PrestaShop');
\VoucherlyApi\Api::setOsVersionHeader(_PS_VERSION_);
\VoucherlyApi\Api::setAppNameHeader('voucherly-prestashop');
\VoucherlyApi\Api::setAppVersionHeader($this->version);
```

2.0 takes them as options of the client, which you create once and reuse:

```php
$voucherly = new \VoucherlyApi\VoucherlyClient([
    'apiKey' => $apiKey,
    'os' => 'PrestaShop',
    'osVersion' => _PS_VERSION_,
    'app' => 'voucherly-prestashop',
    'appVersion' => $this->version,
]);
```

| 1.x setter | 2.0 option |
|---|---|
| `Api::setApiKey()` | `apiKey` |
| `Api::setOsNameHeader()` | `os` |
| `Api::setOsVersionHeader()` | `osVersion` |
| `Api::setOsFrameworkHeader()` | `osFramework` |
| `Api::setAppNameHeader()` | `app` |
| `Api::setAppVersionHeader()` | `appVersion` |
| `Api::setAppHouseHeader()` | `appHouse` |
| `Api::setDeviceTypeHeader()` | `deviceType` |

The `*_on_demand` calls, which used a key other than the static one, are no longer needed: create a second client with the other key.

1.x accepted an empty key and let the API answer 401, while 2.0 throws an `InvalidArgumentException` when the client is created without one. A plugin whose key may not be configured yet should create the client at its first use, not in its constructor, and treat an empty key as an invalid one.

## Calls

| 1.x | 2.0 |
|---|---|
| `Payment::create($request)` | `$voucherly->payments->create($request)` |
| `Payment::get($id)` | `$voucherly->payments->retrieve($id)` |
| `Payment::capture($id)` | `$voucherly->payments->confirm($id)` |
| `Payment::refund($id)` | `$voucherly->payments->refund($id)` |
| `PaymentGateway::list()` | `$voucherly->paymentGateways->list()` |
| `Customer::paymentMethods($customerId)->items` | `$voucherly->paymentMethods->list($customerId)->items` |
| `PaymentHelper::isPaidOrCaptured($payment)` | `in_array($payment->status, [PaymentStatus::PAID, PaymentStatus::CONFIRMED], true)` |
| `Api::testAuthentication($apiKey)` | call `$voucherly->paymentGateways->list()` and catch an `ApiException` whose `getStatusCode()` is 401 |

`PaymentHelper::isPaidOrCaptured()` also accepted `Captured`, a status the API never returns: `PaymentStatus` lists the statuses that exist.

`confirm()` and `refund()` accept an optional request: without it they confirm or refund the whole Payment, as 1.x did.

The API pages the saved PaymentMethods like every other list: `paymentMethods->list()` returns the 10 most recent, and `ListCustomerPaymentMethodParams::$length` asks for up to 100.

## Payment requests

`CreatePaymentRequest` moved to `VoucherlyApi\Request\CreatePaymentRequest`. Its fields keep their names, but a field you do not assign is no longer sent: 1.x sent the customer fields and the redirect URLs as empty strings, and `mode` as `Payment`. The API rejects a request without `mode`, so assign it: `$request->mode = PaymentMode::PAYMENT`.

A field you do not assign has no value at all, not even `null`, so reading it throws an `Error`, which a `catch (\Exception $e)` does not catch. Where 1.x code reads a field back from a request it built, as in `$request->customerId != $payment->customerId`, read it with `$request->customerId ?? null` or check it with `isset()`.

`CreatePaymentRequestLine` becomes `VoucherlyApi\Request\PaymentLineRequest`, and the product fields move to a nested `PaymentLineRequestProduct`:

| 1.x `CreatePaymentRequestLine` | 2.0 |
|---|---|
| `quantity`, `unitAmount`, `unitDiscountAmount`, `discountAmount`, `productId` | same name on `PaymentLineRequest` |
| `productName` | `product->name` |
| `productImage` | `product->image` |
| `taxRate` | `product->taxRate` |
| `isFood = true` | `product->lineType = LineType::FOOD` |
| `productDescription` | `product->variant` |
| `priceId` | removed: the API does not have it |

```php
$product = new PaymentLineRequestProduct();
$product->name = $name;
$product->image = $imageUrl;
$product->taxRate = 22.0;
$product->lineType = $isFood ? LineType::FOOD : LineType::NON_FOOD;

$line = new PaymentLineRequest();
$line->quantity = $quantity;
$line->unitAmount = $unitAmountInCents;
$line->product = $product;
```

`CreatePaymentRequestDiscount` becomes `VoucherlyApi\Model\PaymentDiscount`, with the same `discountName`, `discountDescription` and `amount`.

## Responses

1.x returned the decoded JSON as `stdClass`. 2.0 returns typed objects of `VoucherlyApi\Model` with the same property names, so `$payment->status`, `$payment->checkoutUrl` and `$page->items` keep working.

A JSON object without a fixed shape is an array, not an object: `$payment->metadata->orderId` becomes `$payment->metadata['orderId']`, and the same holds for `Customer::$metadata` and `TransactionNextAction::$params`.

Lists keep their `items`. `paymentGateways->list()` returns a `PaymentGatewayList`, and the other lists a `Page`, which also has `pagination`.

## Errors

`NotSuccessException` becomes `VoucherlyApi\Exception\ApiException`. Its `getCode()` is still the HTTP status, and it now carries the problem details and the raw body. Network errors, which 1.x threw as a generic `\Exception`, are now `ConnectionException`. Both extend `VoucherlyException`.

```php
try {
    $payment = $voucherly->payments->create($request);
} catch (\VoucherlyApi\Exception\ApiException $exception) {
    $this->log($exception->getStatusCode() . ' ' . $exception->getRawBody());
}
```
