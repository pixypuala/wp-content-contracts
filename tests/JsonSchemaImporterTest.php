<?php
/**
 * Tests for the JSON Schema importer and export/import symmetry.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Pixypuala\ContentContracts\Contract;
use Pixypuala\ContentContracts\Field;
use Pixypuala\ContentContracts\FieldType;
use Pixypuala\ContentContracts\JsonSchemaExporter;
use Pixypuala\ContentContracts\JsonSchemaImporter;

final class JsonSchemaImporterTest extends TestCase {

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

	public function test_import_recovers_name_and_version_from_id(): void {
		$contract = ( new JsonSchemaImporter() )->from_array(
			( new JsonSchemaExporter() )->to_array( $this->articleContract() )
		);

		$this->assertSame( 'article', $contract->name );
		$this->assertSame( 1, $contract->version );
	}

	public function test_import_recovers_all_field_types(): void {
		$contract = ( new JsonSchemaImporter() )->from_array(
			( new JsonSchemaExporter() )->to_array( $this->articleContract() )
		);

		$types = array_map(
			static fn ( Field $field ): FieldType => $field->type,
			$contract->fields
		);

		$this->assertSame(
			array(
				FieldType::Integer,
				FieldType::Str,
				FieldType::Iso8601,
				FieldType::Url,
				FieldType::StrList,
			),
			$types
		);
	}

	public function test_import_recovers_requiredness(): void {
		$contract = ( new JsonSchemaImporter() )->from_array(
			( new JsonSchemaExporter() )->to_array( $this->articleContract() )
		);

		$required = array();
		foreach ( $contract->fields as $field ) {
			$required[ $field->name ] = $field->required;
		}

		$this->assertSame(
			array(
				'id'          => true,
				'title'       => true,
				'publishedAt' => true,
				'url'         => false,
				'tags'        => false,
			),
			$required
		);
	}

	public function test_array_round_trip_equals_original(): void {
		$original = $this->articleContract();
		$exporter = new JsonSchemaExporter();
		$importer = new JsonSchemaImporter();

		$restored = $importer->from_array( $exporter->to_array( $original ) );

		$this->assertTrue( $original->equals( $restored ) );
	}

	public function test_json_round_trip_equals_original(): void {
		$original = $this->articleContract();
		$exporter = new JsonSchemaExporter();
		$importer = new JsonSchemaImporter();

		$restored = $importer->from_json( $exporter->to_json( $original ) );

		$this->assertTrue( $original->equals( $restored ) );
		// A second export of the restored contract is byte-identical to the first.
		$this->assertSame( $exporter->to_json( $original ), $exporter->to_json( $restored ) );
	}

	public function test_contract_with_no_required_fields_round_trips(): void {
		$original = new Contract(
			'loose',
			3,
			array( new Field( 'note', FieldType::Str, false ) )
		);
		$exporter = new JsonSchemaExporter();

		$restored = ( new JsonSchemaImporter() )->from_array( $exporter->to_array( $original ) );

		$this->assertTrue( $original->equals( $restored ) );
	}

	public function test_missing_id_is_rejected(): void {
		$this->expectException( InvalidArgumentException::class );
		( new JsonSchemaImporter() )->from_array(
			array(
				'type'       => 'object',
				'properties' => array(),
			)
		);
	}

	public function test_unknown_type_fragment_is_rejected(): void {
		$this->expectException( InvalidArgumentException::class );
		( new JsonSchemaImporter() )->from_array(
			array(
				'$id'        => 'urn:content-contract:weird:v1',
				'properties' => array( 'amount' => array( 'type' => 'number' ) ),
			)
		);
	}

	public function test_array_property_without_string_items_is_rejected(): void {
		$this->expectException( InvalidArgumentException::class );
		( new JsonSchemaImporter() )->from_array(
			array(
				'$id'        => 'urn:content-contract:weird:v1',
				'properties' => array(
					'nums' => array(
						'type'  => 'array',
						'items' => array( 'type' => 'integer' ),
					),
				),
			)
		);
	}

	public function test_invalid_json_is_rejected(): void {
		$this->expectException( InvalidArgumentException::class );
		( new JsonSchemaImporter() )->from_json( '{ not json' );
	}
}
