<?php
/**
 * Tests for the JSON Schema exporter.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts\Tests;

use PHPUnit\Framework\TestCase;
use Pixypuala\ContentContracts\Contract;
use Pixypuala\ContentContracts\Field;
use Pixypuala\ContentContracts\FieldType;
use Pixypuala\ContentContracts\JsonSchemaExporter;

final class JsonSchemaExporterTest extends TestCase {

	private function articleContract(): Contract {
		return new Contract(
			'article',
			1,
			array(
				new Field( 'id', FieldType::Integer ),
				new Field( 'title', FieldType::Str ),
				new Field( 'publishedAt', FieldType::Iso8601 ),
				new Field( 'url', FieldType::Url, false ),
				new Field( 'tags', FieldType::StrList, false ),
			)
		);
	}

	public function test_document_declares_draft_2020_12_dialect(): void {
		$schema = ( new JsonSchemaExporter() )->to_array( $this->articleContract() );

		$this->assertSame( 'https://json-schema.org/draft/2020-12/schema', $schema['$schema'] );
		$this->assertSame( 'object', $schema['type'] );
		$this->assertFalse( $schema['additionalProperties'] );
	}

	public function test_id_and_title_encode_name_and_version(): void {
		$schema = ( new JsonSchemaExporter() )->to_array( $this->articleContract() );

		$this->assertSame( 'urn:content-contract:article:v1', $schema['$id'] );
		$this->assertSame( 'article', $schema['title'] );
	}

	public function test_only_required_fields_map_to_required(): void {
		$schema = ( new JsonSchemaExporter() )->to_array( $this->articleContract() );

		// id, title and publishedAt are required; url and tags are optional.
		$this->assertSame( array( 'id', 'title', 'publishedAt' ), $schema['required'] );
	}

	public function test_required_is_omitted_when_no_field_is_required(): void {
		$contract = new Contract(
			'loose',
			1,
			array( new Field( 'note', FieldType::Str, false ) )
		);

		$schema = ( new JsonSchemaExporter() )->to_array( $contract );

		$this->assertArrayNotHasKey( 'required', $schema );
	}

	public function test_field_types_map_to_json_schema_types(): void {
		$schema     = ( new JsonSchemaExporter() )->to_array( $this->articleContract() );
		$properties = $schema['properties'];

		$this->assertSame( array( 'type' => 'integer' ), $properties['id'] );
		$this->assertSame( array( 'type' => 'string' ), $properties['title'] );
		$this->assertSame(
			array(
				'type'   => 'string',
				'format' => 'date-time',
			),
			$properties['publishedAt']
		);
		$this->assertSame(
			array(
				'type'   => 'string',
				'format' => 'uri',
			),
			$properties['url']
		);
		$this->assertSame(
			array(
				'type'  => 'array',
				'items' => array( 'type' => 'string' ),
			),
			$properties['tags']
		);
	}

	public function test_boolean_field_maps_to_boolean_type(): void {
		$contract = new Contract(
			'flag',
			1,
			array( new Field( 'featured', FieldType::Boolean ) )
		);

		$schema = ( new JsonSchemaExporter() )->to_array( $contract );

		$this->assertSame( array( 'type' => 'boolean' ), $schema['properties']['featured'] );
	}

	public function test_property_order_follows_field_declaration_order(): void {
		$schema = ( new JsonSchemaExporter() )->to_array( $this->articleContract() );

		$this->assertSame(
			array( 'id', 'title', 'publishedAt', 'url', 'tags' ),
			array_keys( $schema['properties'] )
		);
	}

	public function test_json_output_is_stable_and_deterministic(): void {
		$exporter = new JsonSchemaExporter();
		$contract = $this->articleContract();

		// Same contract, two exports: byte-identical output every time.
		$this->assertSame( $exporter->to_json( $contract ), $exporter->to_json( $contract ) );

		$decoded = json_decode( $exporter->to_json( $contract ), true );
		$this->assertSame( $exporter->to_array( $contract ), $decoded );
	}
}
