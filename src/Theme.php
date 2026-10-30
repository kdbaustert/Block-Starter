<?php
/**
 * Wires the theme's services to WordPress hooks.
 *
 * @package BlockStarter
 */

namespace BlockStarter;

use BlockStarter\Bindings\Announcement;
use BlockStarter\Bindings\ReadingTime;
use BlockStarter\Settings\AnnouncementSetting;

/**
 * Entry point called from functions.php.
 */
final class Theme {

	public const SLUG = 'block-starter';

	/**
	 * Registers every hook the theme needs. Safe to call more than once.
	 */
	public static function boot(): void {
		if ( has_action( 'after_setup_theme', array( Setup::class, 'add_theme_supports' ) ) ) {
			return;
		}

		add_action( 'after_setup_theme', array( Setup::class, 'add_theme_supports' ) );
		add_action( 'wp_enqueue_scripts', array( Setup::class, 'enqueue_stylesheet' ) );
		add_action( 'init', array( Setup::class, 'register_pattern_category' ) );

		add_action( 'init', array( AnnouncementSetting::class, 'register' ) );
		add_action( 'admin_init', array( AnnouncementSetting::class, 'add_settings_field' ) );

		add_action( 'init', array( Announcement::class, 'register_source' ) );
		add_filter( 'render_block_core/group', array( Announcement::class, 'hide_empty_bar' ), 10, 2 );

		add_action( 'init', array( ReadingTime::class, 'register_source' ) );
	}

	/**
	 * Theme version from style.css, used to cache-bust the stylesheet.
	 */
	public static function version(): string {
		return (string) wp_get_theme( self::SLUG )->get( 'Version' );
	}
}
