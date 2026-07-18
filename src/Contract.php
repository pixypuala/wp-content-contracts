<?php
/**
 * A versioned content contract: a named set of typed fields.
 *
 * A contract validates data at the boundary between WordPress and its consumers.
 * Because the contract is explicit, typed, and versioned, a consumer knows
 * exactly what to expect and a producer knows exactly what it must emit — the
 * two can evolve independently as long as the contract version is respected.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts;

/**
 * A validatable content contract.
 */
final class Contract {

	/**
	 * @param string  $name    Contract name, e.g. "article".
	 * @param int     $version Contract version (bump on breaking changes).
	 * @param Field[] $fields  Field definitions.
	 */
	public function __construct(
		public readonly string $name,
		public readonly int $version,
		public readonly array $fields,
	) {}

	/**
	 * Validate a data array against this contract.
	 *
	 * @param array<string, mixed> $data Candidate data.
	 *
	 * @return string[] Human-readable issues; empty means valid.
	 */
	public function validate( array $data ): array {
		$issues = array();

		foreach ( $this->fields as $field ) {
			$present = array_key_exists( $field->name, $data );

			if ( ! $present || null === $data[ $field->name ] ) {
				if ( $field->required ) {
					$issues[] = sprintf( 'Missing required field "%s".', $field->name );
				}
				continue;
			}

			if ( ! $field->type->accepts( $data[ $field->name ] ) ) {
				$issues[] = sprintf(
					'Field "%s" must be of type %s.',
					$field->name,
					$field->type->value
				);
			}
		}

		// Reject unexpected fields so the contract is closed, not merely a subset.
		$known = array_map( static fn ( Field $f ): string => $f->name, $this->fields );
		foreach ( array_keys( $data ) as $key ) {
			if ( ! in_array( $key, $known, true ) ) {
				$issues[] = sprintf( 'Unexpected field "%s" is not part of the contract.', (string) $key );
			}
		}

		return $issues;
	}

	/**
	 * Whether data satisfies the contract.
	 *
	 * @param array<string, mixed> $data Candidate data.
	 *
	 * @return bool
	 */
	public function is_satisfied_by( array $data ): bool {
		return array() === $this->validate( $data );
	}
}
