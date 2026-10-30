<?php
/**
 * The site-wide announcement shown above the header.
 *
 * @package BlockStarter
 */

namespace BlockStarter\Settings;

/**
 * Stores the announcement as an option, editable under Settings > General and over REST.
 */
final class AnnouncementSetting {

	public const OPTION = 'block_starter_announcement';

	private const FIELD_ID = 'block-starter-announcement';

	/**
	 * Inline tags an editor may use in the announcement. Links are limited to
	 * the protocols wp_kses allows, so javascript: URLs are stripped.
	 *
	 * @return array<string, array<string, bool>>
	 */
	public static function allowed_html(): array {
		return array(
			'a'      => array( 'href' => true ),
			'strong' => array(),
			'em'     => array(),
		);
	}

	/**
	 * Registers the option with a sanitize callback so every write path is covered:
	 * the settings form, the REST API and update_option() calls from code.
	 */
	public static function register(): void {
		register_setting(
			'general',
			self::OPTION,
			array(
				'type'              => 'string',
				'label'             => __( 'Announcement', 'block-starter' ),
				'description'       => __( 'A short notice shown above the site header.', 'block-starter' ),
				'default'           => '',
				'sanitize_callback' => array( self::class, 'sanitize' ),
				'show_in_rest'      => true,
			)
		);
	}

	/**
	 * Cleans a submitted announcement: limited inline HTML, one line, trimmed.
	 *
	 * @param mixed $value Raw value from the form, REST request or update_option().
	 */
	public static function sanitize( $value ): string {
		if ( ! is_string( $value ) ) {
			return '';
		}

		$single_line = preg_replace( '/\s+/', ' ', $value );

		return trim( wp_kses( $single_line, self::allowed_html() ) );
	}

	/**
	 * Adds the field to Settings > General.
	 */
	public static function add_settings_field(): void {
		add_settings_field(
			self::FIELD_ID,
			__( 'Announcement', 'block-starter' ),
			array( self::class, 'render_field' ),
			'general',
			'default',
			array( 'label_for' => self::FIELD_ID )
		);
	}

	/**
	 * Prints the textarea. The stored value was sanitized on save and is escaped again here.
	 */
	public static function render_field(): void {
		printf(
			'<textarea id="%1$s" name="%2$s" rows="2" class="large-text">%3$s</textarea><p class="description">%4$s</p>',
			esc_attr( self::FIELD_ID ),
			esc_attr( self::OPTION ),
			esc_textarea( (string) get_option( self::OPTION, '' ) ),
			esc_html__( 'Shown above the header on every page. Links, bold and italics are allowed. Leave empty to hide the bar.', 'block-starter' )
		);
	}

	/**
	 * The stored announcement, escaped for output in HTML.
	 */
	public static function get_html(): string {
		return wp_kses( (string) get_option( self::OPTION, '' ), self::allowed_html() );
	}
}
