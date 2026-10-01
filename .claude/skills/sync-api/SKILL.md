---
name: sync-api
description: Bring the SDK in line with the published Voucherly OpenAPI spec. Downloads the spec, classifies the changes as patch, minor or major, regenerates models, requests and enums, updates services, coverage map and tests, then bumps version and changelog. Use when the spec changed, when asked to sync or update the API, or from the sync-api workflow.
---

# Sync the SDK with the API spec

`spec/openapi.yaml` is the contract: the SDK exposes what it documents and nothing else. Every update starts from the difference between the committed copy and the new one, never from a rebuild. Read `CLAUDE.md` first: it holds the naming rule and the decisions this procedure applies.

## 1. Download the spec

The spec lives in the public repository voucherly/voucherly-docs, at `static/download/openapi.yaml`. No token is needed.

- By hand: `curl -fsSL https://raw.githubusercontent.com/voucherly/voucherly-docs/main/static/download/openapi.yaml -o spec/openapi.yaml`
- From a pull request of the sync-api workflow: the workflow has already downloaded the spec at the docs commit that triggered it and run the generator, on a `sync-api/<date>` branch. Check out that branch, skip this step and the generator, and read the diff against `main` in step 2: `git diff main -- spec/openapi.yaml`.
- A spec not merged yet: copy `static/download/openapi.yaml` from a local voucherly-docs checkout, only when Francesco asks for it.

Write down the date of the download: `info.version` is always `v1`, so the changelog dates the spec instead.

## 2. Read the diff and classify it

Run `git diff --stat spec/openapi.yaml` and `git diff spec/openapi.yaml`. If nothing changed, stop and say so.

Summarize the changes to Francesco, in Italian, one line each, grouped by operation and schema, and say which release they make:

- **major**: an operation, a field or an enum value removed or renamed; a path or HTTP method changed; a type changed; an optional parameter or body field that becomes required; any change to the signature of an existing method.
- **minor**: a new operation, schema, optional field, optional parameter or enum value.
- **patch**: descriptions and examples only.

The server may add response fields and enum values at any time without breaking the SDK, so a new response field is minor, not major.

## 3. Implement

1. Run `php tools/generate.php`. It rewrites `lib/Model`, `lib/Request` and `lib/Enum` from the spec and deletes the classes the spec no longer declares. The pages (`Page`, `Pagination`) and `RequestParams` are written by hand and survive it. Never edit a generated file by hand: change the generator.
2. When the generator stops on an enum without a name, add the name to `INLINE_ENUMS` or `INCLUDE_ENUMS` in `tools/generate.php`, and the same entry in `tests/SchemaCoverageTest.php`.
3. For a new operation, add the method to the service of its tag, named with the rule in `CLAUDE.md`; for a new tag, add a service and its property on `VoucherlyClient`. Map it in `spec/coverage.json`, and add a test in `tests/Service` that checks method, path, query, headers and body and reads the sample response.
4. For a removed operation, remove the method, its entry in `spec/coverage.json` and its test.
5. For a changed operation, update the method, the test, and `UPGRADE-2.0.md` or the upgrade notes of the new major.

## 4. Verify

```bash
composer test
php tools/check-coverage.php
vendor/bin/php-cs-fixer fix
php tools/generate.php && git status --short lib
```

The last command must print no change to the generated directories after the formatter ran. With a sandbox key, run also `VOUCHERLY_API_KEY=sk_sand_... vendor/bin/phpunit --group live`: it reads every listing of the sandbox and reports the members the spec does not document. Never write the key in a file.

## 5. Version and changelog

Bump `VoucherlyClient::VERSION` by the release the changes make, and add a section to `CHANGELOG.md`: `## X.Y.Z - YYYY-MM-DD`, with the date of the spec download, and one line per change that a user of the SDK can see.

## 6. Stop

No commit, stage, tag or push unless Francesco asks for it. On a `sync-api/<date>` branch, the workflow has committed the spec and the generated code; the rest of the procedure stays in the working tree for Francesco to review.
