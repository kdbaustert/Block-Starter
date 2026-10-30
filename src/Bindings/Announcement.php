<?php
/**
 * Block bindings source that fills the header's announcement paragraph.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Bindings;

use BlockStarter\Settings\AnnouncementSetting;

/**
 * Connects the announcement option to blocks in the editor and on the front end.
 */
final class Announcement {

	public const SOURCE = 'block-starter/announcement';

	public const BAR_CLASS = 'block-starter-announcement';

	/**
	 * Registers the bindings source used in parts/header.html.
	 */
	public static function register_source(): void {
		register_block_bindings_source(
			self::SOURCE,
			array(
				'label'              => __( 'Site announcement', 'block-starter' ),
				'get_value_callback' => array( self::class, 'get_value' ),
			)
		);
	}

	/**
	 * Value for the bound paragraph, already passed through wp_kses.
	 */
	public static function get_value(): string {
		return AnnouncementSetting::get_html();
	}

	/**
	 * Drops the announcement bar's group from the page when there's nothing to announce.
	 *
	 * @param string               $block_content Rendered group markup.
	 * @param array<string, mixed> $block         Parsed block, including attrs.
	 */
	public static function hide_empty_bar( string $block_content, array $block ): string {
		$class_name = $block['attrs']['className'] ?? '';

		if ( ! is_string( $class_name ) || ! in_array( self::BAR_CLASS, explode( ' ', $class_name ), true ) ) {
			return $block_content;
		}

		return '' === AnnouncementSetting::get_html() ? '' : $block_content;
	}
}
