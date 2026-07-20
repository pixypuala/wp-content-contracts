<?php
/**
 * WP-CLI command: check a live REST response against a content contract.
 *
 * The command is thin glue over {@see ResponseChecker}. Its logic-bearing core —
 * decoding a body and formatting the {@see CheckResult} into report lines — is
 * framework-free and unit tested. Only the HTTP fetch and WP-CLI output binding
 * require a running WordPress, and those live behind the `class_exists` guard at
 * the bottom of this file so the class loads harmlessly under PHPUnit.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts\Cli;

use InvalidArgumentException;
use Pixypuala\ContentContracts\CheckResult;
use Pixypuala\ContentContracts\Contract;
use Pixypuala\ContentContracts\ResponseChecker;

/**
 * Registers `wp content-contracts check-response`.
 */
final class ResponseCheckCommand {

	/**
	 * @param ResponseChecker $checker Contract checker used to evaluate the body.
	 */
	public function __construct(
		private readonly ResponseChecker $checker = new ResponseChecker(),
	) {}

	/**
	 * Evaluate an already-fetched response body against a contract.
	 *
	 * This is the testable core: it decodes the body and delegates to the
	 * checker, leaving the HTTP request and WP-CLI reporting to the glue.
	 *
	 * @param Contract $contract Contract the response must satisfy.
	 * @param string   $body     Raw JSON response body.
	 * @param string   $path     Dotted path to the described object, e.g. "data".
	 *                           Empty checks the whole body.
	 *
	 * @return CheckResult Structured pass/violations outcome.
	 *
	 * @throws InvalidArgumentException When the body is not a JSON array/object,
	 *                                  or $path does not lead to one.
	 */
	public function evaluate( Contract $contract, string $body, string $path = '' ): CheckResult {
		$decoded = json_decode( $body, true );
		if ( ! is_array( $decoded ) ) {
			throw new InvalidArgumentException( 'Response body must be a JSON object or array.' );
		}

		return $this->checker->check( $contract, $this->at_path( $decoded, $path ) );
	}

	/**
	 * Narrow a decoded body to the object the contract actually describes.
	 *
	 * Most real APIs wrap their payload — `{ "meta": {...}, "data": {...} }` — so
	 * checking the whole body against a resource contract would always fail on the
	 * wrapper's own keys. A dotted path selects the described object instead.
	 *
	 * @param array<string, mixed> $decoded Decoded response body.
	 * @param string               $path    Dotted path, e.g. "data" or "data.0". Empty means the whole body.
	 *
	 * @return array<string, mixed> The selected object.
	 *
	 * @throws InvalidArgumentException When the path does not lead to an object.
	 */
	private function at_path( array $decoded, string $path ): array {
		if ( '' === $path ) {
			return $decoded;
		}

		$current = $decoded;
		foreach ( explode( '.', $path ) as $segment ) {
			if ( ! is_array( $current ) || ! array_key_exists( $segment, $current ) ) {
				throw new InvalidArgumentException( sprintf( 'Path "%s" is not present in the response.', $path ) );
			}
			$current = $current[ $segment ];
		}

		if ( ! is_array( $current ) ) {
			throw new InvalidArgumentException( sprintf( 'Path "%s" is not an object or array.', $path ) );
		}

		return $current;
	}

	/**
	 * Format a check result into human-readable report lines.
	 *
	 * @param CheckResult $result Outcome to render.
	 *
	 * @return string[] Report lines; a single success line, or one line per violation.
	 */
	public function report_lines( CheckResult $result ): array {
		if ( $result->passed ) {
			return array( 'Response satisfies the contract.' );
		}

		$header = array(
			sprintf( 'Response violates the contract (%d issue(s)):', count( $result->violations ) ),
		);

		$lines = array_map(
			static fn ( string $message ): string => sprintf( '  - %s', $message ),
			$result->messages()
		);

		return array_merge( $header, $lines );
	}
}

// Thin glue: only bind to WP-CLI when running inside WordPress.
if ( class_exists( '\WP_CLI' ) ) {
	\WP_CLI::add_command(
		'content-contracts check-response',
		/**
		 * Fetch a live REST URL and check its body against a contract schema.
		 *
		 * @param string[]              $args       Positional args: <schema-file> <url>.
		 * @param array<string, string> $assoc_args Options; supports --json-path.
		 *
		 * @return void
		 */
		static function ( array $args, array $assoc_args ): void {
			// Named `json-path`, not `path`: WP-CLI reserves `--path` globally for
			// the WordPress install directory, and a collision silently retargets
			// the whole command at a directory that does not exist.
			$path = (string) ( $assoc_args['json-path'] ?? '' );

			[ $schema_file, $url ] = array( $args[0] ?? '', $args[1] ?? '' );
			if ( '' === $schema_file || '' === $url ) {
				\WP_CLI::error( 'Usage: wp content-contracts check-response <schema-file> <url> [--json-path=<dotted.path>]' );
			}

			if ( ! is_readable( $schema_file ) ) {
				\WP_CLI::error( sprintf( 'Cannot read schema file: %s', $schema_file ) );
			}

			$schema_json = (string) file_get_contents( $schema_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local file read in a CLI context.

			/*
			 * The importer rejects a schema that is not a content contract by
			 * throwing. That rejection is correct, but a CLI must report it as a
			 * usage error the operator can act on, not as an uncaught stack trace.
			 */
			try {
				$contract = ( new \Pixypuala\ContentContracts\JsonSchemaImporter() )->from_json( $schema_json );
			} catch ( \InvalidArgumentException | \JsonException $error ) {
				\WP_CLI::error( sprintf( 'Invalid contract schema in %s: %s', $schema_file, $error->getMessage() ) );
			}

			$response = wp_remote_get( $url );
			if ( is_wp_error( $response ) ) {
				\WP_CLI::error( sprintf( 'Request failed: %s', $response->get_error_message() ) );
			}

			$command = new ResponseCheckCommand();

			// A URL that answers with HTML rather than JSON is an operator
			// mistake, and must read as one instead of a stack trace.
			try {
				$result = $command->evaluate( $contract, (string) wp_remote_retrieve_body( $response ), $path );
			} catch ( \InvalidArgumentException $error ) {
				\WP_CLI::error( sprintf( 'Cannot check %s: %s', $url, $error->getMessage() ) );
			}

			foreach ( $command->report_lines( $result ) as $line ) {
				\WP_CLI::log( $line );
			}

			if ( ! $result->passed ) {
				\WP_CLI::halt( 1 );
			}
		}
	);
}
