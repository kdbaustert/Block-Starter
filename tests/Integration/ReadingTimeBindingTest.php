<?php
/**
 * The reading-time bindings source as used in parts/post-meta.html.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Tests\Integration;

use BlockStarter\Bindings\ReadingTime;
use WP_Block;

/**
 * Renders a bound paragraph against a real post.
 */
final class ReadingTimeBindingTest extends TestCase {

	/**
	 * Post created by the current test, deleted in tearDown().
	 *
	 * @var int
	 */
	private int $post_id = 0;

	protected function tearDown(): void {
		if ( $this->post_id ) {
			wp_delete_post( $this->post_id, true );
		}
		parent::tearDown();
	}

	public function test_bound_paragraph_shows_minutes_for_the_post_in_context(): void {
		$words         = ReadingTime::WORDS_PER_MINUTE * 2 + 1;
		$this->post_id = self::create_post( '<!-- wp:paragraph --><p>' . str_repeat( 'word ', $words ) . '</p><!-- /wp:paragraph -->' );

		$this->assertMatchesRegularExpression( self::paragraph( '3 min read' ), self::render_bound_paragraph( $this->post_id ) );
	}

	public function test_markup_in_post_content_is_not_counted_or_echoed(): void {
		$this->post_id = self::create_post( '<p data-x="<script>">short</p>[gallery ids="1,2"]' );

		$this->assertMatchesRegularExpression( self::paragraph( '1 min read' ), self::render_bound_paragraph( $this->post_id ) );
	}

	/**
	 * Inserts a published post and returns its ID.
	 *
	 * @param string $content Post content.
	 */
	private static function create_post( string $content ): int {
		return wp_insert_post(
			array(
				'post_title'   => 'Reading time test',
				'post_content' => $content,
				'post_status'  => 'publish',
			),
			true
		);
	}

	/**
	 * Renders a paragraph bound to the reading-time source with postId in context.
	 *
	 * @param int $post_id Post to measure.
	 */
	private static function render_bound_paragraph( int $post_id ): string {
		$parsed = parse_blocks( '<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"block-starter/reading-time"}}}} --><p></p><!-- /wp:paragraph -->' )[0];

		return trim( ( new WP_Block( $parsed, array( 'postId' => $post_id ) ) )->render() );
	}

	/**
	 * Pattern for a rendered paragraph holding exactly $text. Newer WordPress
	 * adds the wp-block-paragraph class; 6.7 doesn't.
	 *
	 * @param string $text Expected paragraph text.
	 */
	private static function paragraph( string $text ): string {
		return '#^<p( class="wp-block-paragraph")?>' . preg_quote( $text, '#' ) . '</p>$#';
	}
}
