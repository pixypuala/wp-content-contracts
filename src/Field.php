<?php
/**
 * One field in a content contract.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts;

/**
 * Immutable field definition.
 */
final class Field {

	/**
	 * @param string    $name     Field name (the wire key).
	 * @param FieldType $type     Expected type.
	 * @param bool      $required Whether the field must be present and non-null.
	 */
	public function __construct(
		public readonly string $name,
		public readonly FieldType $type,
		public readonly bool $required = true,
	) {}
}
