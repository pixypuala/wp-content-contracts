<?php
/**
 * Tests for the framework-free core of the WP-CLI response-check command.
 *
 * @package Pixypuala\ContentContracts
 */

declare( strict_types=1 );

namespace Pixypuala\ContentContracts\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Pixypuala\ContentContracts\CheckResult;
use Pixypuala\ContentContracts\Cli\ResponseCheckCommand;
use Pixypuala\ContentContracts\Contract;
use Pixypuala\ContentContracts\Field;
use Pixypuala\ContentContracts\FieldType;
use Pixypuala\ContentContracts\Violation;

final class ResponseCheckCommandTest extends TestCase {

	private function articleContract(): Contract {
		return new Contract(
			'article',
			1,
			array(
				new Field( 'id', FieldType::Integer ),
				new Field( 'title', FieldType::Str ),
			)
		);
	}

	public function test_evaluate_passes_valid_body(): void {
		$body   = (string) json_encode(
			array(
				'id'    => 1,
				'title' => 'Hi',
			)
		);
		$result = ( new ResponseCheckCommand() )->evaluate( $this->articleContract(), $body );

		$this->assertTrue( $result->passed );
	}

	public function test_evaluate_reports_violations_in_body(): void {
		$body   = (string) json_encode( array( 'id' => 'nope' ) );
		$result = ( new ResponseCheckCommand() )->evaluate( $this->articleContract(), $body );

		$this->assertFalse( $result->passed );
		$this->assertContains( 'Field "id" must be of type integer.', $result->messages() );
		$this->assertContains( 'Missing required field "title".', $result->messages() );
	}

	public function test_evaluate_rejects_non_json_body(): void {
		$this->expectException( InvalidArgumentException::class );
		( new ResponseCheckCommand() )->evaluate( $this->articleContract(), 'not json' );
	}

	public function test_report_lines_render_success(): void {
		$lines = ( new ResponseCheckCommand() )->report_lines( new CheckResult( true, array() ) );

		$this->assertSame( array( 'Response satisfies the contract.' ), $lines );
	}

	public function test_report_lines_render_violations(): void {
		$result = new CheckResult(
			false,
			array( new Violation( '/0', 'Missing required field "id".' ) )
		);

		$lines = ( new ResponseCheckCommand() )->report_lines( $result );

		$this->assertSame(
			array(
				'Response violates the contract (1 issue(s)):',
				'  - /0: Missing required field "id".',
			),
			$lines
		);
	}
}
