# Changelog

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
