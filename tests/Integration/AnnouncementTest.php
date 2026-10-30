<?php
/**
 * The announcement option: sanitized on every write, escaped on output, hidden when empty.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Tests\Integration;

use BlockStarter\Settings\AnnouncementSetting;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Covers AnnouncementSetting and the Announcement bindings source together.
 */
final class AnnouncementTest extends TestCase {

	protected function tearDown(): void {
		if ( defined( 'ABSPATH' ) ) {
			delete_option( AnnouncementSetting::OPTION );
		}
		parent::tearDown();
	}

	/**
	 * Raw input and what should be stored.
	 *
	 * @return array<string, array{mixed, string}>
	 */
	public static function submissions(): array {
		return array(
			'plain text kept'           => array( 'Shop closed Monday', 'Shop closed Monday' ),
			'allowed inline tags kept'  => array( '<strong>New:</strong> <a href="https://example.com/x">read</a>', '<strong>New:</strong> <a href="https://example.com/x">read</a>' ),
			'script removed'            => array( 'Hi<script>alert(1)</script>', 'Hialert(1)' ),
			'javascript: href stripped' => array( '<a href="javascript:alert(1)">x</a>', '<a href="alert(1)">x</a>' ),
			'event handler stripped'    => array( '<em onclick="steal()">x</em>', '<em>x</em>' ),
			'block tags removed'        => array( '<div><p>Hi</p></div>', 'Hi' ),
			'newlines collapsed'        => array( "  one\n\ntwo  ", 'one two' ),
			'non-string becomes empty'  => array( array( 'x' ), '' ),
		);
	}

	#[DataProvider( 'submissions' )]
	public function test_update_option_stores_the_sanitized_value( mixed $raw, string $expected ): void {
		update_option( AnnouncementSetting::OPTION, $raw );

		$this->assertSame( $expected, get_option( AnnouncementSetting::OPTION ) );
	}

	public function test_settings_field_escapes_the_stored_value(): void {
		// Bypass sanitizing on save to prove the field escapes on output regardless.
		remove_all_filters( 'sanitize_option_' . AnnouncementSetting::OPTION );
		update_option( AnnouncementSetting::OPTION, '</textarea><script>x</script>' );

		ob_start();
		AnnouncementSetting::render_field();
		$html = (string) ob_get_clean();

		$this->assertStringNotContainsString( '<script>', $html );
		$this->assertStringContainsString( '&lt;/textarea&gt;&lt;script&gt;x&lt;/script&gt;', $html );

		AnnouncementSetting::register();
	}

	public function test_header_bar_shows_the_announcement(): void {
		update_option( AnnouncementSetting::OPTION, 'Closed <em>Monday</em>' );

		$html = self::render_header();

		$this->assertMatchesRegularExpression( '#<p class="has-text-align-center( wp-block-paragraph)?">Closed <em>Monday</em></p>#', $html );
	}

	public function test_header_bar_is_removed_when_announcement_is_empty(): void {
		update_option( AnnouncementSetting::OPTION, '' );

		$this->assertStringNotContainsString( 'block-starter-announcement', self::render_header() );
	}

	public function test_announcement_is_exposed_in_the_rest_settings_endpoint(): void {
		$this->assertArrayHasKey( AnnouncementSetting::OPTION, get_registered_settings() );
		$this->assertTrue( get_registered_settings()[ AnnouncementSetting::OPTION ]['show_in_rest'] );
	}

	/**
	 * Renders parts/header.html the way a template would.
	 */
	private static function render_header(): string {
		return do_blocks( '<!-- wp:template-part {"slug":"header","theme":"block-starter"} /-->' );
	}
}
