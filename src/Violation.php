<?php
/**
 * One structured contract violation found in a REST response.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts;

/**
 * Immutable pairing of a location and a human-readable message.
 */
final class Violation {

	/**
	 * @param string $path    JSON-pointer-style location of the offending value.
	 *                        An empty string denotes the response root; "/0"
	 *                        denotes the first item of a collection response.
	 * @param string $message Human-readable description of the violation.
	 */
	public function __construct(
		public readonly string $path,
		public readonly string $message,
	) {}
}
