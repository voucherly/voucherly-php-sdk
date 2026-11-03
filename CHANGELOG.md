# Changelog

## 2.1.0 - 2026-11-03

- `$client->payments->quote()` prices the body of a Payment without creating it, and returns a `PaymentQuote`.
- `$client->companies->update()` updates a Company by ID or join code, with `UpdateCompanyRequest`: only the properties you assign are sent, and an explicit `null` removes the `$referenceId`.
- `$referenceId` on `Company`, `CreateCompanyRequest` and `ListCompanyParams`, and on `CreateCustomerRequest`, `UpdateCustomerRequest` and `ListCustomerParams`: the ID in your own system.
- `CreatePaymentRequest::$customer`, a `CreatePaymentRequestCustomer` with `$referenceId`, `$email`, `$firstName`, `$lastName`, `$phoneNumber`, `$emailVerified` and `$phoneVerified`. It replaces `$customerEmail`, `$customerFirstName`, `$customerLastName` and `$customerPhoneNumber`, which are now `@deprecated`.
- `CreatePaymentRequest::$company`, a `CreatePaymentRequestCompany` with `$referenceId`, `$name` and `$joinCode`: the Payment finds the Company by its reference or creates it, and its Customer joins it.
- On the Customer: `$language`, `$birthDate`, `$consents`, `$origin` and `$registered`; on the requests that create and update it, `$language`, `$birthDate` and `$consents`, plus `$origin` and `$registeredAt` on creation.
- `Payment::$subtotalAmount`, `PaymentLine::$subtotalDiscountAmount`, options of an option on `PaymentLineModifier::$modifiers`, and `CustomerAddress::$additionalInformation`.
- The meal-voucher amounts and counts, confirmed and refunded, on the revenues reports.
- `LineOrigin::LIVE_OFFER`.
- `Store::$reservationIsActive`, reservations used at all, and `Store::$reservationOnlineIsActive`, reservations open on the site, in place of `$ecommerceReservationIsActive`, also on `StoreRequest`.
- A discount has `$level`, `LINE` or `SUBTOTAL` from `DiscountLevel`, its `$amount` in cents and `$externalCouponCode`, in place of `$type`, `$value`, `$index` and `DiscountType`. The discounts of `CreatePaymentRequest` are `PaymentDiscountRequest`.
- `$refundMode` on `RefundPaymentRequest` and `RefundPaymentRequestTransaction`, in place of `$asCredit`: `RefundMode::CREDIT` credits the customer's wallet.

## 2.0.0 - 2026-10-01

Rewritten from the OpenAPI spec of the Voucherly API, as published on 2026-10-01. The upgrade from 1.x is described in [UPGRADE-2.0.md](https://github.com/voucherly/voucherly-php-sdk/blob/main/UPGRADE-2.0.md).

- Every public operation of the API: 43 operations over companies, customers and their addresses, wallet and prepaid balance, payment methods, payments, receipts, terminals, stores, concept stores, store areas, payment gateways and the volumes report.
- `VoucherlyClient`, created with an array of options, replaces the static `Api` configuration, so one process can use more than one key.
- Typed models, request and parameter classes, and enums as class constants. A request sends only the properties assigned to it, an explicit `null` included.
- Responses accept fields and enum values that the SDK does not know yet.
- Exceptions carry the HTTP status, the problem details and the raw body, with a subclass for each error status of the spec, and a `ConnectionException` for network errors and timeouts.
- The HTTP transport can be replaced through `TransportInterface`. The default cURL transport has a connection timeout of 10 seconds and a total timeout of 30 seconds.
- Platform keys, with the `merchantId` and `tenant` options.
- PHP 7.4 or later; `ext-mbstring` is no longer required.

## 1.x

See the [releases on GitHub](https://github.com/voucherly/voucherly-php-sdk/releases).
