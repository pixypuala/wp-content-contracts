<?php
/**
 * The structured outcome of checking a REST response against a contract.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts;

/**
 * Immutable pass/violations result.
 */
final class CheckResult {

	/**
	 * @param bool         $passed     Whether the response satisfied the contract.
	 * @param Violation[]  $violations Structured violations; empty when passed.
	 */
	public function __construct(
		public readonly bool $passed,
		public readonly array $violations,
	) {}

	/**
	 * Flatten violations into human-readable, located lines.
	 *
	 * @return string[] One line per violation, prefixed with its path when set.
	 */
	public function messages(): array {
		return array_map(
			static fn ( Violation $violation ): string => '' === $violation->path
				? $violation->message
				: sprintf( '%s: %s', $violation->path, $violation->message ),
			$this->violations
		);
	}
}
