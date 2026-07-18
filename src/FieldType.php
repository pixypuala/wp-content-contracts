<?php
/**
 * The primitive types a contract field can declare.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts;

/**
 * Supported field types with their own validation rule.
 */
enum FieldType: string {
	case Str     = 'string';
	case Integer = 'integer';
	case Boolean = 'boolean';
	case Url     = 'url';
	case Iso8601 = 'iso8601';
	case StrList = 'string[]';

	/**
	 * Whether a value satisfies this type.
	 *
	 * @param mixed $value Candidate value.
	 *
	 * @return bool
	 */
	public function accepts( mixed $value ): bool {
		return match ( $this ) {
			self::Str     => is_string( $value ),
			self::Integer => is_int( $value ),
			self::Boolean => is_bool( $value ),
			self::Url     => is_string( $value ) && false !== filter_var( $value, FILTER_VALIDATE_URL ),
			self::Iso8601 => is_string( $value ) && $this->is_iso8601( $value ),
			self::StrList => $this->is_string_list( $value ),
		};
	}

	/**
	 * @param string $value Candidate timestamp.
	 *
	 * @return bool
	 */
	private function is_iso8601( string $value ): bool {
		// Accept a strict RFC3339/ISO-8601 date-time, e.g. 2026-07-18T09:30:00+00:00.
		return 1 === preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}:\d{2})$/', $value );
	}

	/**
	 * @param mixed $value Candidate list.
	 *
	 * @return bool
	 */
	private function is_string_list( mixed $value ): bool {
		if ( ! is_array( $value ) || ( array() !== $value && ! array_is_list( $value ) ) ) {
			return false;
		}
		foreach ( $value as $item ) {
			if ( ! is_string( $item ) ) {
				return false;
			}
		}
		return true;
	}
}
