<?php
/**
 * Theme supports, the stylesheet and the pattern category.
 *
 * @package BlockStarter
 */

namespace BlockStarter;

/**
 * Basic theme setup that doesn't belong in theme.json.
 */
final class Setup {

	/**
	 * Block themes get most supports automatically; these are the extras.
	 */
	public static function add_theme_supports(): void {
		load_theme_textdomain( Theme::SLUG, get_template_directory() . '/languages' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'style.css' );
	}

	/**
	 * Loads style.css for the few rules theme.json can't express.
	 */
	public static function enqueue_stylesheet(): void {
		wp_enqueue_style( Theme::SLUG, get_stylesheet_uri(), array(), Theme::version() );
	}

	/**
	 * Groups the theme's patterns together in the inserter.
	 */
	public static function register_pattern_category(): void {
		register_block_pattern_category(
			Theme::SLUG,
			array(
				'label'       => __( 'Block Starter', 'block-starter' ),
				'description' => __( 'Sections and page layouts from the Block Starter theme.', 'block-starter' ),
			)
		);
	}
}
