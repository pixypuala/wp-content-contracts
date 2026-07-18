<?php
/**
 * Tests for the REST response checker.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts\Tests;

use PHPUnit\Framework\TestCase;
use Pixypuala\ContentContracts\Contract;
use Pixypuala\ContentContracts\Field;
use Pixypuala\ContentContracts\FieldType;
use Pixypuala\ContentContracts\ResponseChecker;
use Pixypuala\ContentContracts\Violation;

final class ResponseCheckerTest extends TestCase {

	private function articleContract(): Contract {
		return new Contract(
			'article',
			1,
			array(
				new Field( 'id', FieldType::Integer ),
				new Field( 'title', FieldType::Str ),
				new Field( 'url', FieldType::Url, false ),
			)
		);
	}

	private function validArticle(): array {
		return array(
			'id'    => 7,
			'title' => 'Hello',
			'url'   => 'https://example.com/hello',
		);
	}

	public function test_valid_single_object_passes(): void {
		$result = ( new ResponseChecker() )->check( $this->articleContract(), $this->validArticle() );

		$this->assertTrue( $result->passed );
		$this->assertSame( array(), $result->violations );
	}

	public function test_missing_required_field_is_reported(): void {
		$data = $this->validArticle();
		unset( $data['title'] );

		$result = ( new ResponseChecker() )->check( $this->articleContract(), $data );

		$this->assertFalse( $result->passed );
		$this->assertContains( 'Missing required field "title".', $result->messages() );
	}

	public function test_wrong_type_is_reported(): void {
		$data       = $this->validArticle();
		$data['id'] = 'seven';

		$result = ( new ResponseChecker() )->check( $this->articleContract(), $data );

		$this->assertFalse( $result->passed );
		$this->assertContains( 'Field "id" must be of type integer.', $result->messages() );
	}

	public function test_unexpected_field_is_reported(): void {
		$data           = $this->validArticle();
		$data['secret'] = 'leak';

		$result = ( new ResponseChecker() )->check( $this->articleContract(), $data );

		$this->assertFalse( $result->passed );
		$this->assertContains(
			'Unexpected field "secret" is not part of the contract.',
			$result->messages()
		);
	}

	public function test_valid_collection_passes(): void {
		$response = array( $this->validArticle(), $this->validArticle() );

		$result = ( new ResponseChecker() )->check( $this->articleContract(), $response );

		$this->assertTrue( $result->passed );
	}

	public function test_collection_reports_violation_with_item_path(): void {
		$bad = $this->validArticle();
		unset( $bad['id'] );
		$response = array( $this->validArticle(), $bad );

		$result = ( new ResponseChecker() )->check( $this->articleContract(), $response );

		$this->assertFalse( $result->passed );
		$this->assertCount( 1, $result->violations );
		$violation = $result->violations[0];
		$this->assertInstanceOf( Violation::class, $violation );
		$this->assertSame( '/1', $violation->path );
		$this->assertSame( 'Missing required field "id".', $violation->message );
	}

	public function test_collection_with_non_object_item_is_reported(): void {
		$response = array( $this->validArticle(), 'not-an-object' );

		$result = ( new ResponseChecker() )->check( $this->articleContract(), $response );

		$this->assertFalse( $result->passed );
		$this->assertContains( '/1: Response item must be a JSON object.', $result->messages() );
	}

	public function test_empty_response_fails_a_contract_with_required_fields(): void {
		$result = ( new ResponseChecker() )->check( $this->articleContract(), array() );

		$this->assertFalse( $result->passed );
		$this->assertContains( 'Missing required field "id".', $result->messages() );
	}
}
