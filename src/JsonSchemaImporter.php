<?php
/**
 * Parse a JSON Schema (draft 2020-12) document back into a content contract.
 *
 * The importer is the exact inverse of {@see JsonSchemaExporter}: it reads the
 * `$id` for the contract name and version, walks `properties` in declaration
 * order, maps each JSON Schema fragment back to a {@see FieldType}, and marks a
 * field required when it appears in the schema's `required` array. Exporting a
 * contract and importing the result yields an equal contract — the boundary is
 * symmetric, so a schema authored elsewhere can be validated here as well.
 *
 * Malformed input fails loudly with an {@see \InvalidArgumentException} rather
 * than producing a silently wrong contract.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts;

use InvalidArgumentException;

/**
 * Converts a JSON Schema array or JSON string into a Contract.
 */
final class JsonSchemaImporter {

	/**
	 * Matches the `$id` the exporter emits, capturing name and version.
	 */
	private const ID_PATTERN = '/^urn:content-contract:(?P<name>.+):v(?P<version>\d+)$/';

	/**
	 * Import a contract from a decoded JSON Schema array.
	 *
	 * @param array<string, mixed> $schema JSON Schema document.
	 *
	 * @return Contract Reconstructed contract.
	 *
	 * @throws InvalidArgumentException When the schema cannot be mapped to a contract.
	 */
	public function from_array( array $schema ): Contract {
		[ $name, $version ] = $this->parse_id( $schema );

		$properties = $schema['properties'] ?? array();
		if ( ! is_array( $properties ) ) {
			throw new InvalidArgumentException( 'Schema "properties" must be an object.' );
		}

		$required = $schema['required'] ?? array();
		if ( ! is_array( $required ) ) {
			throw new InvalidArgumentException( 'Schema "required" must be an array.' );
		}

		$fields = array();
		foreach ( $properties as $field_name => $fragment ) {
			if ( ! is_array( $fragment ) ) {
				throw new InvalidArgumentException(
					sprintf( 'Property "%s" must be a schema object.', (string) $field_name )
				);
			}

			$fields[] = new Field(
				(string) $field_name,
				$this->field_type( (string) $field_name, $fragment ),
				in_array( $field_name, $required, true )
			);
		}

		return new Contract( $name, $version, $fields );
	}

	/**
	 * Import a contract from a JSON Schema string.
	 *
	 * @param string $json JSON Schema document.
	 *
	 * @return Contract Reconstructed contract.
	 *
	 * @throws InvalidArgumentException When the JSON is invalid or cannot be mapped.
	 */
	public function from_json( string $json ): Contract {
		$decoded = json_decode( $json, true );
		if ( ! is_array( $decoded ) ) {
			throw new InvalidArgumentException( 'Schema JSON must decode to an object.' );
		}

		return $this->from_array( $decoded );
	}

	/**
	 * Extract the contract name and version from the schema `$id`.
	 *
	 * @param array<string, mixed> $schema JSON Schema document.
	 *
	 * @return array{0: string, 1: int} Name and version.
	 *
	 * @throws InvalidArgumentException When `$id` is missing or malformed.
	 */
	private function parse_id( array $schema ): array {
		$id = $schema['$id'] ?? null;
		if ( ! is_string( $id ) || 1 !== preg_match( self::ID_PATTERN, $id, $matches ) ) {
			throw new InvalidArgumentException(
				'Schema "$id" must match "urn:content-contract:<name>:v<version>".'
			);
		}

		return array( $matches['name'], (int) $matches['version'] );
	}

	/**
	 * Map a single JSON Schema property fragment back to a field type.
	 *
	 * @param string               $field_name Field name, for error messages.
	 * @param array<string, mixed> $fragment   JSON Schema fragment for one property.
	 *
	 * @return FieldType Resolved field type.
	 *
	 * @throws InvalidArgumentException When the fragment maps to no known type.
	 */
	private function field_type( string $field_name, array $fragment ): FieldType {
		$type   = $fragment['type'] ?? null;
		$format = $fragment['format'] ?? null;

		if ( 'array' === $type ) {
			$items = $fragment['items'] ?? array();
			if ( is_array( $items ) && 'string' === ( $items['type'] ?? null ) ) {
				return FieldType::StrList;
			}
			throw new InvalidArgumentException(
				sprintf( 'Array property "%s" must declare string items.', $field_name )
			);
		}

		return match ( true ) {
			'integer' === $type                          => FieldType::Integer,
			'boolean' === $type                          => FieldType::Boolean,
			'string' === $type && 'uri' === $format       => FieldType::Url,
			'string' === $type && 'date-time' === $format => FieldType::Iso8601,
			'string' === $type && null === $format        => FieldType::Str,
			default                                       => throw new InvalidArgumentException(
				sprintf( 'Property "%s" has no supported type mapping.', $field_name )
			),
		};
	}
}
