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

	/**
	 * Structural equality: same name, type, and requiredness.
	 *
	 * @param Field $other Field to compare against.
	 *
	 * @return bool
	 */
	public function equals( Field $other ): bool {
		return $this->name === $other->name
			&& $this->type === $other->type
			&& $this->required === $other->required;
	}
}
