# Runtime verification

The exporter, the importer, and the response checker are unit-tested without
WordPress. What that cannot prove is the part the tool exists for: fetching a
real REST response from a running site and judging it against a contract.

## The run

WordPress 7.0.2, PHP 8.2, checking a genuinely independent API — the delivery
routes of the `hybrid-wordpress-content-delivery-platform` plugin — against a
contract authored here and exported through `JsonSchemaExporter`.

```
$ wp content-contracts check-response article.contract.json \
    http://portfolio.local/wp-json/hdp/v1/articles/5 --json-path=data
Response satisfies the contract.
$ echo $?
0

$ wp content-contracts check-response article.contract.json \
    http://portfolio.local/wp-json/hdp/v1/articles --json-path=data.0
Response satisfies the contract.
$ echo $?
0

$ wp content-contracts check-response article.contract.json \
    http://portfolio.local/wp-json/hdp/v1/articles/5
Response violates the contract (10 issue(s)):
  - Missing required field "id".
  - Missing required field "slug".
  …
$ echo $?
1
```

The third run is the same response without `--json-path`, so the contract is
compared against the envelope rather than the resource inside it. It correctly
fails, and the non-zero exit is what makes the command usable as a CI gate.

## What the live run found

Three defects the unit suite could not see, all now fixed:

1. **An unreadable or non-contract schema file crashed.** The importer's
   rejection escaped as an uncaught exception and printed a stack trace under a
   WordPress critical-error page instead of an actionable message.
2. **A URL answering with HTML crashed the same way.**
3. **Enveloped APIs could not be checked at all.** A resource contract compared
   against `{ "meta": …, "data": … }` always failed on the wrapper's own keys,
   which excluded the most common real response shape. Hence `--json-path`.

And one from naming: the option was originally `--path`, which WP-CLI reserves
globally for the WordPress install directory. The collision silently retargeted
the entire command at a directory that did not exist:

```
Error: This does not seem to be a WordPress installation.
The used path is: …/wp-content-contracts/data/
```

## What is still not proven here

Authentication. Every check above is against a public, read-only endpoint;
contracts for authenticated APIs would need credentials passed through to
`wp_remote_get`, which this command does not currently support.
