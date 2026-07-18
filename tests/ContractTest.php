<?php
/**
 * Tests for the content contract validator.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts\Tests;

use PHPUnit\Framework\TestCase;
use Pixypuala\ContentContracts\Contract;
use Pixypuala\ContentContracts\Field;
use Pixypuala\ContentContracts\FieldType;

final class ContractTest extends TestCase {

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

	private function validArticle(): array {
		return array(
			'id'          => 7,
			'title'       => 'Hello',
			'publishedAt' => '2026-07-18T09:30:00+00:00',
			'url'         => 'https://example.com/hello',
			'tags'        => array( 'news' ),
		);
	}

	public function test_valid_data_satisfies_the_contract(): void {
		$this->assertTrue( $this->articleContract()->is_satisfied_by( $this->validArticle() ) );
	}

	public function test_missing_required_field_is_reported(): void {
		$data = $this->validArticle();
		unset( $data['title'] );
		$issues = $this->articleContract()->validate( $data );
		$this->assertContains( 'Missing required field "title".', $issues );
	}

	public function test_wrong_type_is_reported(): void {
		$data       = $this->validArticle();
		$data['id'] = 'seven'; // Should be integer.
		$issues     = $this->articleContract()->validate( $data );
		$this->assertContains( 'Field "id" must be of type integer.', $issues );
	}

	public function test_bad_iso8601_is_reported(): void {
		$data                = $this->validArticle();
		$data['publishedAt'] = '18/07/2026';
		$issues              = $this->articleContract()->validate( $data );
		$this->assertNotEmpty( $issues );
	}

	public function test_invalid_url_is_reported(): void {
		$data        = $this->validArticle();
		$data['url'] = 'not a url';
		$this->assertNotEmpty( $this->articleContract()->validate( $data ) );
	}

	public function test_optional_field_may_be_omitted(): void {
		$data = $this->validArticle();
		unset( $data['url'], $data['tags'] );
		$this->assertTrue( $this->articleContract()->is_satisfied_by( $data ) );
	}

	public function test_unexpected_field_is_rejected(): void {
		$data           = $this->validArticle();
		$data['secret'] = 'leak';
		$issues         = $this->articleContract()->validate( $data );
		$this->assertContains( 'Unexpected field "secret" is not part of the contract.', $issues );
	}

	public function test_string_list_rejects_mixed_types(): void {
		$data         = $this->validArticle();
		$data['tags'] = array( 'ok', 123 );
		$this->assertNotEmpty( $this->articleContract()->validate( $data ) );
	}
}
