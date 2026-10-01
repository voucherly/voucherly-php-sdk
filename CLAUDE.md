# voucherly-php-sdk

Shared Voucherly conventions live in the `claude-rules` submodule. After a fresh clone run `git submodule update --init`; to pull a newer version run `git submodule update --remote claude-rules` and commit the moved pointer.

The submodule is hosted on the private Voucherly Azure DevOps, while this repository is public: a clone without that access builds and tests without it, and the CI does not fetch it.

@claude-rules/rules/comments.md
@claude-rules/rules/git.md

The official PHP SDK of the Voucherly API, published on Packagist as `voucherly/voucherly-php-sdk` under the namespace `VoucherlyApi\`. It covers every public operation of the OpenAPI spec that the documentation publishes, and nothing more.

Its users are the `voucherly-prestashop` and `voucherly-woocommerce` plugins. The SDK is bundled inside them, next to other plugins and their own dependencies, and several of the decisions below come from that.

## Commands

```bash
composer install
composer test                          # PHPUnit 9.6
php tools/check-coverage.php           # every operation of the spec is mapped or excluded
vendor/bin/php-cs-fixer fix            # the CI runs it with --dry-run
php tools/generate.php                 # rewrites lib/Model, lib/Request and lib/Enum from the spec
VOUCHERLY_API_KEY=sk_sand_... vendor/bin/phpunit --group live   # read-only calls against the sandbox
```

`VOUCHERLY_LIVE_WRITES=1` adds the live calls that create data in the sandbox: customers, addresses, payments that are then voided, concept stores and store areas that are then deleted, and one store named `SDK test store` that is updated in place. The key only ever lives in the environment, never in a file.

The local PHP is 8.2, so only the CI proves PHP 7.4. To try 7.4 locally, resolve the dependencies with `composer config platform.php 7.4.33` in a copy of the repository and run the tests in the `php:7.4-cli` Docker image.

## Layout

- `lib/VoucherlyClient.php`: the client, its options and one property per service.
- `lib/Service/`: one service per tag of the spec, written by hand.
- `lib/Model/`, `lib/Request/`, `lib/Enum/`: **generated** by `tools/generate.php`, except `Page`, `Pagination` and `RequestParams`, which are written by hand. Never edit a generated file: change the generator and run it.
- `lib/VoucherlyObject.php`: hydration and serialization of every model and request.
- `lib/Http/`: the transport interface, the cURL transport and the requestor.
- `lib/Exception/`: the exceptions.
- `spec/openapi.yaml` and `spec/coverage.json`: the copy of the spec and the map from its operations to the methods.

## Decisions

- **The spec is the contract.** It is read from the `main` branch of the public voucherly-docs repository, `static/download/openapi.yaml`, which is what the documentation site serves. Methods, fields and the text of the docblocks come only from there: the repository is public, and a field the spec does not document stays out on purpose. The spec is written in SberemPay, `docs/dev/webapi/openapi.yaml`, and published to voucherly-docs by `npm run api:publish`: a wrong spec is fixed there, never worked around here.
- **No openapi-generator.** `tools/generate.php` writes the models, requests, parameters and enums in the style of this repository, and the services are written by hand. `tools/check-coverage.php` and `tests/SchemaCoverageTest.php` prove that nothing of the spec is missing.
- **An instance client with one service per tag**, never static state: a process can hold more than one key, and a test can inject a transport.
- **A request sends only the properties assigned to it, an explicit `null` included.** Request properties are typed with no default, so an unassigned one stays uninitialized and is skipped. Reading one throws an `Error`, as for any uninitialized typed property, and callers read it with `?? null`. A `__get` that returned `null` was rejected: it requires unsetting the properties, after which `$request->lines[] = $line` only raises a notice and drops the line. The API reads which fields are present: on `update-customer`, a missing field is left unchanged and a `null` one is cleared. A property of a class used only by requests refuses `null` when the spec says `nullable: false`; every other property is nullable, because a response can leave any of them out. The docblock of a property the spec lists in `required` says `Required.`, and a property the spec marks `deprecated` carries `@deprecated`.
- **A response accepts what it does not know.** Unknown members go to `getExtensionData()`, and enum values are plain strings with constants, so the server can add fields and values without breaking anybody.
- **Ids are opaque strings**, never parsed.
- **Exceptions**: `VoucherlyException` is the base; `ApiException` carries the status, the problem details already read and the raw body, with one subclass for each error status of the spec; `ConnectionException` is for network errors and timeouts. `getCode()` is the HTTP status, as `NotSuccessException` had in 1.x; the problem member `code` is `getErrorCode()` because `getCode()` is final.
- **Lists** return a `Page` shaped like the JSON, `items` and `pagination`. There is no auto-pagination.
- **Downloads** (`application/pdf`) return the bytes as a string.
- **No automatic retry.** A payment POST sent twice can be charged twice, and the API has no idempotency key.
- **The HTTP transport is injectable** through `TransportInterface`; the default one uses cURL with a 10 seconds connection timeout and a 30 seconds total timeout.
- **PHP 7.4 or later, without deprecations up to 8.4.** So no enums, `readonly`, constructor promotion, union types, `mixed`, `match`, nullsafe `?->`, named arguments, `str_contains`, and no dynamic properties.
- **No runtime dependency** beyond `ext-curl` and `ext-json`: a Guzzle bundled in a plugin conflicts with the one of another plugin.
- **`init.php`** is an `spl_autoload_register` on `lib/`, for the installations without Composer, so it cannot fall behind the files.
- **Every directory of `lib/` has an `index.php`**: PrestaShop rejects a module whose directories lack one. `tests/Core/PackageTest.php` checks it.
- **Response dates stay ISO 8601 strings.** `DateTimeImmutable` cannot read the seven fractional digits the API writes, and some timestamps of the API have no offset. Request dates are `\DateTimeInterface`.

## What the server does that the spec does not say

- It still accepts `isFood`, on the line and on the product, as an obsolete alias of `lineType`, and `productDescription` on the line as an alias of `product.variant`. The SDK sends only `lineType` and `variant`.
- It answers 415 to a POST without a JSON body, even on `void-payment`, which declares none: a POST or PUT without a request sends `{}`.
- It reads five telemetry headers, `x-voucherly-os`, `x-voucherly-osversion`, `x-voucherly-app`, `x-voucherly-appversion`, `x-voucherly-devicetype`. The SDK also keeps `x-voucherly-osframework` and `x-voucherly-apphouse`, as 1.x did. A header without a value is not sent.
- One host, `https://api.voucherly.it`, whose paths already hold `/v1`. Sandbox and live differ by the prefix of the key, not by host.

The User-Agent is `VoucherlyApiPhpSdk/<version>`, with the version in `VoucherlyClient::VERSION`.

## Naming rule

- **Service**: one per tag, a property of the client in the plural: `companies`, `customers`, `paymentMethods`, `payments`, `paymentGateways`, `receipts`, `terminals`, `stores`, `conceptStores`, `storeAreas`, `reports`.
- **Method**: the verb of the `operationId`, followed by the words that come after the name of the tag's resource, in the plural for a `list`: `create-payment` is `payments->create`, `list-customer-address` is `customers->listAddresses`, `retrieve-customer-prepaid-balance` is `customers->retrievePrepaidBalance`, `download-payment-refund-receipt` is `payments->downloadRefundReceipt`, `list-customer-payment-method` is `paymentMethods->list`, `volumes-report` is `reports->volumes`. The rule has no exception today; if one becomes necessary, write it here.
- **Arguments**: the path parameters with the names of the spec, then the request body, then the `...Params` object with the query and header parameters. A `...Params` object is always an object, even for one parameter, so that a new parameter is a minor release.
- **Classes**: a schema drops its group prefix (`Payments.`, `Customers.`, `Stores.`, `PaymentGateways.`, `Receipts.`, `Terminals.`, `Reports.`) and joins the rest: `Payments.PaymentLine.Unit` is `PaymentLineUnit`. An inline object is its parent followed by the property: `PaymentLineRequestProduct`. The renames are `CompanyAddressForExternalApi` to `CompanyAddress`, `GetPaymentGatewaysResponse` to `PaymentGatewayList`, `PaginationResponse` to `Pagination`, and the volumes response to `VolumesReport`. The inline enums are named in `INLINE_ENUMS` and `INCLUDE_ENUMS` of the generator.
- **Parameters**: `<OperationId in PascalCase>Params`, with the required parameters as constructor arguments; a header drops its `Voucherly-` prefix, so `Voucherly-Wait-Time` is `waitTime`.
- **Enum constants**: the value in UPPER_SNAKE_CASE, `NonFood` is `NON_FOOD`.
- **Classes used only by request bodies** go in `Request`, every other class in `Model`.

## The spec and its updates

`spec/openapi.yaml` is committed: its `git diff` is the list of changes of the next update. `spec/coverage.json` maps every public `operationId` to its method, `service.method`, or excludes it with a reason, in the same format as the .NET SDK. The procedure of an update is the `sync-api` skill in `.claude/skills/sync-api/`.

`.github/workflows/sync-api.yml` does the mechanical part of it, without Claude and without any API key:

- it starts on a `repository_dispatch` of type `openapi-updated`, which voucherly-docs sends at every push on `main` that touches `static/download/openapi.yaml`, with `client_payload: { "sha": "<docs commit>" }`; by hand with `workflow_dispatch`, whose `sha` input defaults to `main`; and every Monday, because a dispatch whose token has expired never arrives and nobody notices;
- it downloads the spec at that commit and stops when it is the one already on `main`, or on the open `sync-api/*` pull request;
- otherwise it runs `php tools/generate.php`, the tests and the coverage check, commits the spec and the generated code on `sync-api/<date>`, and opens a pull request towards `main`, or updates the one already open, with the result of each check and the diff of the spec.

What the generator cannot do stays by hand, on that branch, with the skill: the methods, `spec/coverage.json` and the tests of new or removed operations, the kind of release, the version and the changelog. A pull request opened with the `GITHUB_TOKEN` of a workflow starts no other workflow, so the CI does not run on it: its description carries the checks the sync ran. The setting "Allow GitHub Actions to create and approve pull requests" must be on. Merge, tag and release stay by hand, so that a major never ships without Francesco reading it.

The dispatch is sent by a workflow of voucherly-docs, with a token that has Contents write permission on both SDK repositories. The contract between the repositories is only the `event_type` and the `client_payload` above.

An automatic update reads the spec from GitHub at the commit that triggered it, `https://raw.githubusercontent.com/voucherly/voucherly-docs/<sha>/static/download/openapi.yaml`, and never from the documentation site. Netlify finishes deploying after the push, so the site can still serve the previous spec, and the address with `main` can stay cached for a few minutes, while the one with the sha always returns the same file.

## Tests

- `tests/Support/FakeTransport.php` records the requests and returns queued responses; `ServiceTestCase` checks each operation against the spec, with its method, path, query, headers and body.
- The spec has no example of a 2xx response, so each service test builds the response from the schema with every property, and requires the model to read all of it and nothing else.
- The request examples of the spec, named examples included, are sent through their classes and must come out unchanged.
- `tests/SchemaCoverageTest.php` compares the request bodies, the enums and the parameters with the spec.
- `tests/Live` runs against the sandbox, only with a sandbox key in `VOUCHERLY_API_KEY`.

## Conventions

- No comments, except where the reason is not obvious. A comment sentence never breaks over two lines.
- Commits in English, in the imperative, on one line, with no `Co-Authored-By` or any other reference to AI.
- No commit, stage, tag or push unless Francesco asks for it.
- A field removed or renamed, or a method whose signature changes, is a major release.
- Packagist publishes a release when its tag is pushed. Bump `VoucherlyClient::VERSION` and `CHANGELOG.md` first: a test checks that they agree.
