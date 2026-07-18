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
	 *
	 * @return CheckResult Structured pass/violations outcome.
	 *
	 * @throws InvalidArgumentException When the body is not a JSON array/object.
	 */
	public function evaluate( Contract $contract, string $body ): CheckResult {
		$decoded = json_decode( $body, true );
		if ( ! is_array( $decoded ) ) {
			throw new InvalidArgumentException( 'Response body must be a JSON object or array.' );
		}

		return $this->checker->check( $contract, $decoded );
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
		 * @param array<string, string> $assoc_args Reserved for future options.
		 *
		 * @return void
		 */
		static function ( array $args, array $assoc_args ): void {
			unset( $assoc_args );

			[ $schema_file, $url ] = array( $args[0] ?? '', $args[1] ?? '' );
			if ( '' === $schema_file || '' === $url ) {
				\WP_CLI::error( 'Usage: wp content-contracts check-response <schema-file> <url>' );
			}

			$schema_json = (string) file_get_contents( $schema_file );
			$contract    = ( new \Pixypuala\ContentContracts\JsonSchemaImporter() )->from_json( $schema_json );

			$response = wp_remote_get( $url );
			if ( is_wp_error( $response ) ) {
				\WP_CLI::error( sprintf( 'Request failed: %s', $response->get_error_message() ) );
			}

			$command = new ResponseCheckCommand();
			$result  = $command->evaluate( $contract, (string) wp_remote_retrieve_body( $response ) );

			foreach ( $command->report_lines( $result ) as $line ) {
				\WP_CLI::log( $line );
			}

			if ( ! $result->passed ) {
				\WP_CLI::halt( 1 );
			}
		}
	);
}
