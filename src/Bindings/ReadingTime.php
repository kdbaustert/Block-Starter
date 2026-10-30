<?php
/**
 * Block bindings source that shows an estimated reading time for the current post.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Bindings;

use WP_Block;

/**
 * Estimates reading time from a post's text.
 */
final class ReadingTime {

	public const SOURCE = 'block-starter/reading-time';

	public const WORDS_PER_MINUTE = 230;

	/**
	 * Registers the bindings source used in parts/post-meta.html.
	 */
	public static function register_source(): void {
		register_block_bindings_source(
			self::SOURCE,
			array(
				'label'              => __( 'Reading time', 'block-starter' ),
				'get_value_callback' => array( self::class, 'get_value' ),
				'uses_context'       => array( 'postId' ),
			)
		);
	}

	/**
	 * Reading time label for the post in the block's context, escaped for HTML.
	 *
	 * @param array<string, mixed> $source_args Binding args (unused).
	 * @param WP_Block             $block       The bound block instance.
	 */
	public static function get_value( array $source_args, WP_Block $block ): string {
		$post_id = isset( $block->context['postId'] ) ? absint( $block->context['postId'] ) : get_the_ID();
		$content = $post_id ? get_post_field( 'post_content', $post_id, 'raw' ) : '';

		$minutes = self::minutes( self::count_words( wp_strip_all_tags( strip_shortcodes( (string) $content ) ) ) );

		/* translators: %d: estimated minutes to read the post. */
		return esc_html( sprintf( _n( '%d min read', '%d min read', $minutes, 'block-starter' ), $minutes ) );
	}

	/**
	 * Counts words in plain text. Works for any script that separates words with spaces.
	 *
	 * @param string $text Plain text with tags already removed.
	 */
	public static function count_words( string $text ): int {
		return (int) preg_match_all( '/[\p{L}\p{N}][\p{L}\p{N}\'’-]*/u', $text );
	}

	/**
	 * Minutes to read a number of words, rounded up, never less than one.
	 *
	 * @param int $word_count Number of words.
	 */
	public static function minutes( int $word_count ): int {
		return max( 1, (int) ceil( $word_count / self::WORDS_PER_MINUTE ) );
	}
}
