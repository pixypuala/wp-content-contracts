<?php
/**
 * Export a content contract as a JSON Schema (draft 2020-12) document.
 *
 * The exporter is the read side of the documented JSON-Schema boundary: it turns
 * an in-memory {@see Contract} into a standard, portable schema so a consumer can
 * validate the same payloads WordPress emits without importing this library. The
 * mapping mirrors {@see Contract::validate()} exactly — required fields become
 * `required`, each field type maps to its JSON Schema counterpart, and the object
 * is closed (`additionalProperties: false`) because contracts are not subsets.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts;

/**
 * Converts a Contract into a JSON Schema draft 2020-12 array or JSON string.
 */
final class JsonSchemaExporter {

	/**
	 * The JSON Schema dialect this exporter targets.
	 */
	private const DIALECT = 'https://json-schema.org/draft/2020-12/schema';

	/**
	 * Export a contract as a JSON Schema array.
	 *
	 * Property and required ordering follow field declaration order, so the same
	 * contract always yields byte-identical output.
	 *
	 * @param Contract $contract Contract to export.
	 *
	 * @return array<string, mixed> JSON Schema document.
	 */
	public function to_array( Contract $contract ): array {
		$properties = array();
		$required   = array();

		foreach ( $contract->fields as $field ) {
			$properties[ $field->name ] = $this->field_schema( $field->type );

			if ( $field->required ) {
				$required[] = $field->name;
			}
		}

		$schema = array(
			'$schema'              => self::DIALECT,
			'$id'                  => sprintf( 'urn:content-contract:%s:v%d', $contract->name, $contract->version ),
			'title'                => $contract->name,
			'type'                 => 'object',
			'properties'           => $properties,
			'additionalProperties' => false,
		);

		// Only emit "required" when at least one field demands it; an empty
		// "required" array is legal but noise, and draft 2020-12 omits it.
		if ( array() !== $required ) {
			$schema['required'] = $required;
		}

		return $schema;
	}

	/**
	 * Export a contract as a deterministic JSON string.
	 *
	 * @param Contract $contract Contract to export.
	 *
	 * @return string Pretty-printed JSON Schema.
	 */
	public function to_json( Contract $contract ): string {
		return (string) json_encode(
			$this->to_array( $contract ),
			JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
		);
	}

	/**
	 * Map a single field type to its JSON Schema fragment.
	 *
	 * @param FieldType $type Field type to map.
	 *
	 * @return array<string, mixed> JSON Schema fragment for one property.
	 */
	private function field_schema( FieldType $type ): array {
		return match ( $type ) {
			FieldType::Str     => array( 'type' => 'string' ),
			FieldType::Integer => array( 'type' => 'integer' ),
			FieldType::Boolean => array( 'type' => 'boolean' ),
			FieldType::Url     => array(
				'type'   => 'string',
				'format' => 'uri',
			),
			FieldType::Iso8601 => array(
				'type'   => 'string',
				'format' => 'date-time',
			),
			FieldType::StrList => array(
				'type'  => 'array',
				'items' => array( 'type' => 'string' ),
			),
		};
	}
}
