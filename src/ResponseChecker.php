<?php
/**
 * Check a decoded REST response against a content contract.
 *
 * A live REST endpoint may return either a single contract object or a
 * collection of them. This checker accepts the decoded body of either shape and
 * reports every violation with its location: missing required fields, wrong
 * types, and unexpected fields (the same closed semantics as
 * {@see Contract::validate()}), so a caller learns exactly where a response
 * drifted from the contract. It is framework-free — the caller supplies an
 * already-decoded array, keeping the only environment-dependent step (the HTTP
 * fetch) outside this class.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts;

/**
 * Validates a decoded REST response against a Contract.
 */
final class ResponseChecker {

	/**
	 * Check a decoded response against a contract.
	 *
	 * A list of objects is checked item by item; any other array is treated as a
	 * single contract object.
	 *
	 * @param Contract             $contract Contract the response must satisfy.
	 * @param array<mixed, mixed>  $response Decoded JSON response body.
	 *
	 * @return CheckResult Structured pass/violations outcome.
	 */
	public function check( Contract $contract, array $response ): CheckResult {
		$violations = $this->is_collection( $response )
			? $this->check_collection( $contract, $response )
			: $this->check_item( $contract, $response, '' );

		return new CheckResult( array() === $violations, $violations );
	}

	/**
	 * Check each item of a collection response.
	 *
	 * @param Contract           $contract Contract each item must satisfy.
	 * @param array<int, mixed>  $items    List of decoded items.
	 *
	 * @return Violation[]
	 */
	private function check_collection( Contract $contract, array $items ): array {
		$violations = array();

		foreach ( $items as $index => $item ) {
			$path = sprintf( '/%d', $index );

			if ( ! is_array( $item ) ) {
				$violations[] = new Violation( $path, 'Response item must be a JSON object.' );
				continue;
			}

			$violations = array_merge( $violations, $this->check_item( $contract, $item, $path ) );
		}

		return $violations;
	}

	/**
	 * Check a single object against the contract, tagging each issue with a path.
	 *
	 * @param Contract            $contract Contract to check against.
	 * @param array<mixed, mixed> $item     Decoded object.
	 * @param string              $path     Location prefix for reported violations.
	 *
	 * @return Violation[]
	 */
	private function check_item( Contract $contract, array $item, string $path ): array {
		return array_map(
			static fn ( string $issue ): Violation => new Violation( $path, $issue ),
			$contract->validate( $item )
		);
	}

	/**
	 * Whether a response should be treated as a collection of objects.
	 *
	 * A non-empty JSON array is a collection; each of its items is checked in
	 * turn, and any item that is not an object is itself a violation. An empty
	 * array is treated as a single (empty) object so that a contract with
	 * required fields reports them as missing rather than passing vacuously.
	 *
	 * @param array<mixed, mixed> $response Decoded response.
	 *
	 * @return bool
	 */
	private function is_collection( array $response ): bool {
		return array() !== $response && array_is_list( $response );
	}
}
