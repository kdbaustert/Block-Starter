<?php
/**
 * Word counting and rounding behind the reading-time binding.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Tests\Unit;

use BlockStarter\Bindings\ReadingTime;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Pure functions on ReadingTime; no WordPress needed.
 */
final class ReadingTimeTest extends TestCase {

	/**
	 * Text and its expected word count.
	 *
	 * @return array<string, array{string, int}>
	 */
	public static function texts(): array {
		return array(
			'empty'                => array( '', 0 ),
			'whitespace only'      => array( " \n\t ", 0 ),
			'punctuation ignored'  => array( 'Hello, world — again!', 3 ),
			'contractions are one' => array( "don't it's they’re", 3 ),
			'hyphenated is one'    => array( 'well-known long-term', 2 ),
			'numbers count'        => array( 'WordPress 7 shipped in 2026', 5 ),
			'non-latin letters'    => array( 'café naïve Ελληνικά', 3 ),
		);
	}

	#[DataProvider( 'texts' )]
	public function test_count_words( string $text, int $expected ): void {
		$this->assertSame( $expected, ReadingTime::count_words( $text ) );
	}

	public function test_minutes_is_at_least_one(): void {
		$this->assertSame( 1, ReadingTime::minutes( 0 ) );
	}

	public function test_minutes_rounds_up_past_a_full_minute(): void {
		$this->assertSame( 1, ReadingTime::minutes( ReadingTime::WORDS_PER_MINUTE ) );
		$this->assertSame( 2, ReadingTime::minutes( ReadingTime::WORDS_PER_MINUTE + 1 ) );
	}
}
